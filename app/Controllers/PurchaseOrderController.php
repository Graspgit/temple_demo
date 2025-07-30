<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderItemModel;
use App\Models\SupplierModel;
use App\Models\ProductModel;
use App\Models\RawMaterialModel;

class PurchaseOrderController extends BaseController
{
    protected $poModel;
    protected $poItemModel;
    protected $supplierModel;
    protected $productModel;
    protected $rawMaterialModel;

    public function __construct()
    {
        $this->poModel = new PurchaseOrderModel();
        $this->poItemModel = new PurchaseOrderItemModel();
        $this->supplierModel = new SupplierModel();
        $this->productModel = new ProductModel();
        $this->rawMaterialModel = new RawMaterialModel();
    }

    public function index()
    {
        $data['title'] = 'Purchase Orders';
        $data['purchase_orders'] = $this->poModel
            ->select('purchase_orders.*, supplier.supplier_name')
            ->join('supplier', 'supplier.id = purchase_orders.supplier_id')
            ->orderBy('purchase_orders.created_at', 'DESC')
            ->findAll();
        
        return view('purchase/purchase_order_list', $data);
    }

    public function create()
    {
        $data['title'] = 'Create Purchase Order';
        $data['suppliers'] = $this->supplierModel->where('status', 1)->findAll();
        $data['products'] = $this->productModel->findAll();
        $data['raw_materials'] = $this->rawMaterialModel->findAll();
        $data['po_number'] = $this->generatePONumber();
        
        return view('purchase/purchase_order_form', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'po_date' => 'required|valid_date',
            'supplier_id' => 'required|numeric',
            'items' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Calculate totals
            $subtotal = 0;
            $tax_amount = 0;
            $discount_amount = 0;
            $items = $this->request->getPost('items');

            foreach ($items as $item) {
                $line_total = $item['quantity'] * $item['unit_price'];
                $item_tax = ($line_total * $item['tax_rate']) / 100;
                $item_discount = ($line_total * $item['discount_rate']) / 100;
                
                $subtotal += $line_total;
                $tax_amount += $item_tax;
                $discount_amount += $item_discount;
            }

            $total_amount = $subtotal + $tax_amount - $discount_amount;

            // Save PO header
            $poData = [
                'po_number' => $this->request->getPost('po_number'),
                'po_date' => $this->request->getPost('po_date'),
                'supplier_id' => $this->request->getPost('supplier_id'),
                'delivery_date' => $this->request->getPost('delivery_date'),
                'reference_number' => $this->request->getPost('reference_number'),
                'terms_conditions' => $this->request->getPost('terms_conditions'),
                'subtotal' => $subtotal,
                'tax_amount' => $tax_amount,
                'discount_amount' => $discount_amount,
                'total_amount' => $total_amount,
                'status' => 'draft',
                'notes' => $this->request->getPost('notes'),
                'created_by' => session()->get('user_id')
            ];

            $po_id = $this->poModel->insert($poData);

            // Save PO items
            foreach ($items as $item) {
                $line_total = $item['quantity'] * $item['unit_price'];
                $item_tax = ($line_total * $item['tax_rate']) / 100;
                $item_discount = ($line_total * $item['discount_rate']) / 100;
                $item_total = $line_total + $item_tax - $item_discount;

                $itemData = [
                    'po_id' => $po_id,
                    'item_type' => $item['item_type'],
                    'item_id' => $item['item_id'],
                    'description' => $item['description'],
                    'uom_id' => $item['uom_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $item['tax_rate'],
                    'tax_amount' => $item_tax,
                    'discount_rate' => $item['discount_rate'],
                    'discount_amount' => $item_discount,
                    'total_amount' => $item_total,
                    'notes' => $item['notes'] ?? null
                ];

                $this->poItemModel->insert($itemData);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Failed to create purchase order');
            }

            return redirect()->to('/purchase-orders')->with('success', 'Purchase order created successfully');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Purchase Order';
        $data['po'] = $this->poModel->find($id);
        
        if (!$data['po']) {
            return redirect()->to('/purchase-orders')->with('error', 'Purchase order not found');
        }

        $data['po_items'] = $this->poItemModel->where('po_id', $id)->findAll();
        $data['suppliers'] = $this->supplierModel->where('status', 1)->findAll();
        $data['products'] = $this->productModel->findAll();
        $data['raw_materials'] = $this->rawMaterialModel->findAll();
        
        return view('purchase/purchase_order_form', $data);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'po_date' => 'required|valid_date',
            'supplier_id' => 'required|numeric',
            'items' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Check if PO can be edited
            $po = $this->poModel->find($id);
            if (!$po || !in_array($po['status'], ['draft', 'approved'])) {
                return redirect()->back()->with('error', 'Purchase order cannot be edited');
            }

            // Calculate totals
            $subtotal = 0;
            $tax_amount = 0;
            $discount_amount = 0;
            $items = $this->request->getPost('items');

            foreach ($items as $item) {
                $line_total = $item['quantity'] * $item['unit_price'];
                $item_tax = ($line_total * $item['tax_rate']) / 100;
                $item_discount = ($line_total * $item['discount_rate']) / 100;
                
                $subtotal += $line_total;
                $tax_amount += $item_tax;
                $discount_amount += $item_discount;
            }

            $total_amount = $subtotal + $tax_amount - $discount_amount;

            // Update PO header
            $poData = [
                'po_date' => $this->request->getPost('po_date'),
                'supplier_id' => $this->request->getPost('supplier_id'),
                'delivery_date' => $this->request->getPost('delivery_date'),
                'reference_number' => $this->request->getPost('reference_number'),
                'terms_conditions' => $this->request->getPost('terms_conditions'),
                'subtotal' => $subtotal,
                'tax_amount' => $tax_amount,
                'discount_amount' => $discount_amount,
                'total_amount' => $total_amount,
                'notes' => $this->request->getPost('notes')
            ];

            $this->poModel->update($id, $poData);

            // Delete existing items
            $this->poItemModel->where('po_id', $id)->delete();

            // Save new items
            foreach ($items as $item) {
                $line_total = $item['quantity'] * $item['unit_price'];
                $item_tax = ($line_total * $item['tax_rate']) / 100;
                $item_discount = ($line_total * $item['discount_rate']) / 100;
                $item_total = $line_total + $item_tax - $item_discount;

                $itemData = [
                    'po_id' => $id,
                    'item_type' => $item['item_type'],
                    'item_id' => $item['item_id'],
                    'description' => $item['description'],
                    'uom_id' => $item['uom_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $item['tax_rate'],
                    'tax_amount' => $item_tax,
                    'discount_rate' => $item['discount_rate'],
                    'discount_amount' => $item_discount,
                    'total_amount' => $item_total,
                    'notes' => $item['notes'] ?? null
                ];

                $this->poItemModel->insert($itemData);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Failed to update purchase order');
            }

            return redirect()->to('/purchase-orders')->with('success', 'Purchase order updated successfully');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function approve($id)
    {
        $po = $this->poModel->find($id);
        
        if (!$po || $po['status'] !== 'draft') {
            return redirect()->back()->with('error', 'Purchase order cannot be approved');
        }

        $data = [
            'status' => 'approved',
            'approved_by' => session()->get('user_id'),
            'approved_date' => date('Y-m-d H:i:s')
        ];

        if ($this->poModel->update($id, $data)) {
            return redirect()->back()->with('success', 'Purchase order approved successfully');
        }

        return redirect()->back()->with('error', 'Failed to approve purchase order');
    }

    public function cancel($id)
    {
        $po = $this->poModel->find($id);
        
        if (!$po || in_array($po['status'], ['completed', 'cancelled'])) {
            return redirect()->back()->with('error', 'Purchase order cannot be cancelled');
        }

        if ($this->poModel->update($id, ['status' => 'cancelled'])) {
            return redirect()->back()->with('success', 'Purchase order cancelled successfully');
        }

        return redirect()->back()->with('error', 'Failed to cancel purchase order');
    }

    public function view($id)
    {
        $data['title'] = 'View Purchase Order';
        $data['po'] = $this->poModel
            ->select('purchase_orders.*, supplier.supplier_name, supplier.address1, supplier.phone, supplier.email_id')
            ->join('supplier', 'supplier.id = purchase_orders.supplier_id')
            ->where('purchase_orders.id', $id)
            ->first();
        
        if (!$data['po']) {
            return redirect()->to('/purchase-orders')->with('error', 'Purchase order not found');
        }

        $data['po_items'] = $this->poItemModel
            ->select('purchase_order_items.*')
            ->where('po_id', $id)
            ->findAll();

        // Get item details
        foreach ($data['po_items'] as &$item) {
            if ($item['item_type'] == 'product') {
                $product = $this->productModel->find($item['item_id']);
                $item['item_name'] = $product['product_name'] ?? '';
                $item['item_code'] = $product['product_code'] ?? '';
            } else {
                $material = $this->rawMaterialModel->find($item['item_id']);
                $item['item_name'] = $material['material_name'] ?? '';
                $item['item_code'] = $material['material_code'] ?? '';
            }
        }
        
        return view('purchase/purchase_order_view', $data);
    }

    public function print($id)
    {
        $data = $this->view($id);
        return view('purchase/purchase_order_print', $data);
    }

    private function generatePONumber()
    {
        $prefix = 'PO';
        $year = date('y');
        $month = date('m');
        
        // Get last PO number for current month
        $lastPO = $this->poModel
            ->where('YEAR(created_at)', date('Y'))
            ->where('MONTH(created_at)', date('m'))
            ->orderBy('id', 'DESC')
            ->first();
        
        if ($lastPO) {
            $lastNumber = intval(substr($lastPO['po_number'], -5));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . $month . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    // AJAX endpoints
    public function getItemDetails()
    {
        $itemType = $this->request->getGet('item_type');
        $itemId = $this->request->getGet('item_id');
        
        if ($itemType == 'product') {
            $item = $this->productModel->find($itemId);
            if ($item) {
                return $this->response->setJSON([
                    'success' => true,
                    'item' => [
                        'name' => $item['product_name'],
                        'code' => $item['product_code'],
                        'description' => $item['description'] ?? '',
                        'uom_id' => $item['uom_id'] ?? null,
                        'price' => $item['purchase_price'] ?? 0
                    ]
                ]);
            }
        } else {
            $item = $this->rawMaterialModel->find($itemId);
            if ($item) {
                return $this->response->setJSON([
                    'success' => true,
                    'item' => [
                        'name' => $item['material_name'],
                        'code' => $item['material_code'],
                        'description' => $item['description'] ?? '',
                        'uom_id' => $item['uom_id'] ?? null,
                        'price' => $item['purchase_price'] ?? 0
                    ]
                ]);
            }
        }
        
        return $this->response->setJSON(['success' => false]);
    }
}