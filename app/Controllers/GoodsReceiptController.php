<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GoodsReceiptModel;
use App\Models\GoodsReceiptItemModel;
use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderItemModel;
use App\Models\SupplierModel;
use App\Models\StockLedgerModel;
use App\Models\ProductModel;
use App\Models\RawMaterialModel;

class GoodsReceiptController extends BaseController
{
    protected $grnModel;
    protected $grnItemModel;
    protected $poModel;
    protected $poItemModel;
    protected $supplierModel;
    protected $stockLedgerModel;
    protected $productModel;
    protected $rawMaterialModel;

    public function __construct()
    {
        $this->grnModel = new GoodsReceiptModel();
        $this->grnItemModel = new GoodsReceiptItemModel();
        $this->poModel = new PurchaseOrderModel();
        $this->poItemModel = new PurchaseOrderItemModel();
        $this->supplierModel = new SupplierModel();
        $this->stockLedgerModel = new StockLedgerModel();
        $this->productModel = new ProductModel();
        $this->rawMaterialModel = new RawMaterialModel();
    }

    public function index()
    {
        $data['title'] = 'Goods Receipt Notes';
        $data['grns'] = $this->grnModel
            ->select('goods_receipt_notes.*, supplier.supplier_name, purchase_orders.po_number')
            ->join('supplier', 'supplier.id = goods_receipt_notes.supplier_id')
            ->join('purchase_orders', 'purchase_orders.id = goods_receipt_notes.po_id', 'left')
            ->orderBy('goods_receipt_notes.created_at', 'DESC')
            ->findAll();
        
        return view('purchase/grn_list', $data);
    }

    public function create()
    {
        $data['title'] = 'Create Goods Receipt Note';
        $data['suppliers'] = $this->supplierModel->where('status', 1)->findAll();
        $data['purchase_orders'] = $this->poModel
            ->whereIn('status', ['approved', 'partial'])
            ->findAll();
        $data['grn_number'] = $this->generateGRNNumber();
        
        $po_id = $this->request->getGet('po_id');
        if ($po_id) {
            $data['selected_po'] = $this->poModel->find($po_id);
            $data['po_items'] = $this->getPendingPOItems($po_id);
        }
        
        return view('purchase/grn_form', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'grn_date' => 'required|valid_date',
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
                if ($item['accepted_quantity'] > 0) {
                    $line_total = $item['accepted_quantity'] * $item['unit_price'];
                    $item_tax = ($line_total * $item['tax_rate']) / 100;
                    $item_discount = ($line_total * $item['discount_rate']) / 100;
                    
                    $subtotal += $line_total;
                    $tax_amount += $item_tax;
                    $discount_amount += $item_discount;
                }
            }

            $total_amount = $subtotal + $tax_amount - $discount_amount;

            // Save GRN header
            $grnData = [
                'grn_number' => $this->request->getPost('grn_number'),
                'grn_date' => $this->request->getPost('grn_date'),
                'po_id' => $this->request->getPost('po_id') ?: null,
                'supplier_id' => $this->request->getPost('supplier_id'),
                'invoice_number' => $this->request->getPost('invoice_number'),
                'invoice_date' => $this->request->getPost('invoice_date'),
                'delivery_note_number' => $this->request->getPost('delivery_note_number'),
                'subtotal' => $subtotal,
                'tax_amount' => $tax_amount,
                'discount_amount' => $discount_amount,
                'total_amount' => $total_amount,
                'status' => 'draft',
                'notes' => $this->request->getPost('notes'),
                'created_by' => session()->get('user_id')
            ];

            $grn_id = $this->grnModel->insert($grnData);

            // Save GRN items and update stock
            foreach ($items as $item) {
                if ($item['accepted_quantity'] > 0) {
                    $line_total = $item['accepted_quantity'] * $item['unit_price'];
                    $item_tax = ($line_total * $item['tax_rate']) / 100;
                    $item_discount = ($line_total * $item['discount_rate']) / 100;
                    $item_total = $line_total + $item_tax - $item_discount;

                    $grnItemData = [
                        'grn_id' => $grn_id,
                        'po_item_id' => $item['po_item_id'] ?? null,
                        'item_type' => $item['item_type'],
                        'item_id' => $item['item_id'],
                        'description' => $item['description'],
                        'uom_id' => $item['uom_id'],
                        'ordered_quantity' => $item['ordered_quantity'] ?? $item['received_quantity'],
                        'received_quantity' => $item['received_quantity'],
                        'accepted_quantity' => $item['accepted_quantity'],
                        'rejected_quantity' => $item['rejected_quantity'] ?? 0,
                        'unit_price' => $item['unit_price'],
                        'tax_rate' => $item['tax_rate'],
                        'tax_amount' => $item_tax,
                        'discount_rate' => $item['discount_rate'],
                        'discount_amount' => $item_discount,
                        'total_amount' => $item_total,
                        'batch_number' => $item['batch_number'] ?? null,
                        'expiry_date' => $item['expiry_date'] ?? null,
                        'notes' => $item['notes'] ?? null
                    ];

                    $this->grnItemModel->insert($grnItemData);

                    // Update PO item received quantity if linked to PO
                    if (!empty($item['po_item_id'])) {
                        $poItem = $this->poItemModel->find($item['po_item_id']);
                        if ($poItem) {
                            $newReceivedQty = $poItem['received_quantity'] + $item['accepted_quantity'];
                            $this->poItemModel->update($item['po_item_id'], [
                                'received_quantity' => $newReceivedQty
                            ]);
                        }
                    }
                }
            }

            // Update PO status if all items received
            if ($this->request->getPost('po_id')) {
                $this->updatePOStatus($this->request->getPost('po_id'));
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Failed to create goods receipt note');
            }

            return redirect()->to('/goods-receipts')->with('success', 'Goods receipt note created successfully');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function approve($id)
    {
        $grn = $this->grnModel->find($id);
        
        if (!$grn || $grn['status'] !== 'draft') {
            return redirect()->back()->with('error', 'GRN cannot be approved');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update GRN status
            $this->grnModel->update($id, [
                'status' => 'approved',
                'approved_by' => session()->get('user_id'),
                'approved_date' => date('Y-m-d H:i:s')
            ]);

            // Get GRN items
            $grnItems = $this->grnItemModel->where('grn_id', $id)->findAll();

            // Update stock ledger
            foreach ($grnItems as $item) {
                if ($item['accepted_quantity'] > 0) {
                    // Get current stock balance
                    $lastEntry = $this->stockLedgerModel
                        ->where('item_type', $item['item_type'])
                        ->where('item_id', $item['item_id'])
                        ->orderBy('id', 'DESC')
                        ->first();
                    
                    $balance = ($lastEntry['balance_quantity'] ?? 0) + $item['accepted_quantity'];
                    
                    // Add stock ledger entry
                    $stockData = [
                        'transaction_date' => $grn['grn_date'],
                        'transaction_type' => 'grn',
                        'transaction_id' => $grn['id'],
                        'transaction_number' => $grn['grn_number'],
                        'item_type' => $item['item_type'],
                        'item_id' => $item['item_id'],
                        'batch_number' => $item['batch_number'],
                        'in_quantity' => $item['accepted_quantity'],
                        'out_quantity' => 0,
                        'balance_quantity' => $balance,
                        'unit_cost' => $item['unit_price'],
                        'total_cost' => $item['total_amount'],
                        'created_by' => session()->get('user_id')
                    ];
                    
                    $this->stockLedgerModel->insert($stockData);

                    // Update current stock in product/raw material table
                    if ($item['item_type'] == 'product') {
                        $product = $this->productModel->find($item['item_id']);
                        $this->productModel->update($item['item_id'], [
                            'current_stock' => ($product['current_stock'] ?? 0) + $item['accepted_quantity']
                        ]);
                    } else {
                        $material = $this->rawMaterialModel->find($item['item_id']);
                        $this->rawMaterialModel->update($item['item_id'], [
                            'current_stock' => ($material['current_stock'] ?? 0) + $item['accepted_quantity']
                        ]);
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to approve GRN');
            }

            return redirect()->back()->with('success', 'GRN approved and stock updated successfully');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function view($id)
    {
        $data['title'] = 'View Goods Receipt Note';
        $data['grn'] = $this->grnModel
            ->select('goods_receipt_notes.*, supplier.supplier_name, supplier.address1, supplier.phone, supplier.email_id, purchase_orders.po_number')
            ->join('supplier', 'supplier.id = goods_receipt_notes.supplier_id')
            ->join('purchase_orders', 'purchase_orders.id = goods_receipt_notes.po_id', 'left')
            ->where('goods_receipt_notes.id', $id)
            ->first();
        
        if (!$data['grn']) {
            return redirect()->to('/goods-receipts')->with('error', 'GRN not found');
        }

        $data['grn_items'] = $this->grnItemModel
            ->where('grn_id', $id)
            ->findAll();

        // Get item details
        foreach ($data['grn_items'] as &$item) {
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
        
        return view('purchase/grn_view', $data);
    }

    public function print($id)
    {
        $data = $this->view($id);
        return view('purchase/grn_print', $data);
    }

    private function generateGRNNumber()
    {
        $prefix = 'GRN';
        $year = date('y');
        $month = date('m');
        
        // Get last GRN number for current month
        $lastGRN = $this->grnModel
            ->where('YEAR(created_at)', date('Y'))
            ->where('MONTH(created_at)', date('m'))
            ->orderBy('id', 'DESC')
            ->first();
        
        if ($lastGRN) {
            $lastNumber = intval(substr($lastGRN['grn_number'], -5));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . $month . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    private function getPendingPOItems($po_id)
    {
        $poItems = $this->poItemModel
            ->where('po_id', $po_id)
            ->findAll();
        
        $pendingItems = [];
        foreach ($poItems as $item) {
            $pendingQty = $item['quantity'] - $item['received_quantity'];
            if ($pendingQty > 0) {
                $item['pending_quantity'] = $pendingQty;
                
                // Get item details
                if ($item['item_type'] == 'product') {
                    $product = $this->productModel->find($item['item_id']);
                    $item['item_name'] = $product['product_name'] ?? '';
                    $item['item_code'] = $product['product_code'] ?? '';
                } else {
                    $material = $this->rawMaterialModel->find($item['item_id']);
                    $item['item_name'] = $material['material_name'] ?? '';
                    $item['item_code'] = $material['material_code'] ?? '';
                }
                
                $pendingItems[] = $item;
            }
        }
        
        return $pendingItems;
    }

    private function updatePOStatus($po_id)
    {
        $poItems = $this->poItemModel->where('po_id', $po_id)->findAll();
        
        $totalOrdered = 0;
        $totalReceived = 0;
        
        foreach ($poItems as $item) {
            $totalOrdered += $item['quantity'];
            $totalReceived += $item['received_quantity'];
        }
        
        if ($totalReceived == 0) {
            $status = 'approved';
        } elseif ($totalReceived < $totalOrdered) {
            $status = 'partial';
        } else {
            $status = 'completed';
        }
        
        $this->poModel->update($po_id, ['status' => $status]);
    }

    // AJAX endpoints
    public function getPOItems()
    {
        $po_id = $this->request->getGet('po_id');
        $items = $this->getPendingPOItems($po_id);
        
        return $this->response->setJSON([
            'success' => true,
            'items' => $items
        ]);
    }
}