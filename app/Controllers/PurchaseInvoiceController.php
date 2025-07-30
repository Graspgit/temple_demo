<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PurchaseInvoiceModel;
use App\Models\PurchaseInvoiceItemModel;
use App\Models\GoodsReceiptModel;
use App\Models\GoodsReceiptItemModel;
use App\Models\SupplierModel;
use App\Models\ProductModel;
use App\Models\RawMaterialModel;
use App\Models\EntryModel;
use App\Models\EntryItemModel;
use App\Models\LedgerModel;
use App\Models\InventorySettingsModel;

class PurchaseInvoiceController extends BaseController
{
    protected $invoiceModel;
    protected $invoiceItemModel;
    protected $grnModel;
    protected $grnItemModel;
    protected $supplierModel;
    protected $productModel;
    protected $rawMaterialModel;
    protected $entryModel;
    protected $entryItemModel;
    protected $ledgerModel;
    protected $settingsModel;

    public function __construct()
    {
        $this->invoiceModel = new PurchaseInvoiceModel();
        $this->invoiceItemModel = new PurchaseInvoiceItemModel();
        $this->grnModel = new GoodsReceiptModel();
        $this->grnItemModel = new GoodsReceiptItemModel();
        $this->supplierModel = new SupplierModel();
        $this->productModel = new ProductModel();
        $this->rawMaterialModel = new RawMaterialModel();
        $this->entryModel = new EntryModel();
        $this->entryItemModel = new EntryItemModel();
        $this->ledgerModel = new LedgerModel();
        $this->settingsModel = new InventorySettingsModel();
    }

    public function index()
    {
        $data['title'] = 'Purchase Invoices';
        $data['invoices'] = $this->invoiceModel
            ->select('purchase_invoices.*, supplier.supplier_name')
            ->join('supplier', 'supplier.id = purchase_invoices.supplier_id')
            ->orderBy('purchase_invoices.created_at', 'DESC')
            ->findAll();
        
        return view('purchase/invoice_list', $data);
    }

    public function create()
    {
        $data['title'] = 'Create Purchase Invoice';
        $data['suppliers'] = $this->supplierModel->where('status', 1)->findAll();
        $data['invoice_number'] = $this->generateInvoiceNumber();
        
        // Get approved GRNs without invoice
        $data['grns'] = $this->grnModel
            ->select('goods_receipt_notes.*, supplier.supplier_name')
            ->join('supplier', 'supplier.id = goods_receipt_notes.supplier_id')
            ->where('goods_receipt_notes.status', 'approved')
            ->whereNotIn('goods_receipt_notes.id', function($builder) {
                return $builder->select('grn_id')
                    ->from('purchase_invoices')
                    ->where('grn_id IS NOT NULL')
                    ->where('status !=', 'cancelled');
            })
            ->findAll();
        
        $grn_id = $this->request->getGet('grn_id');
        if ($grn_id) {
            $data['selected_grn'] = $this->grnModel->find($grn_id);
            $data['grn_items'] = $this->getGRNItems($grn_id);
        }
        
        return view('purchase/invoice_form', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'invoice_date' => 'required|valid_date',
            'supplier_id' => 'required|numeric',
            'supplier_invoice_number' => 'required',
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

            $other_charges = $this->request->getPost('other_charges') ?? 0;
            $total_amount = $subtotal + $tax_amount - $discount_amount + $other_charges;

            // Save Invoice header
            $invoiceData = [
                'invoice_number' => $this->request->getPost('invoice_number'),
                'invoice_date' => $this->request->getPost('invoice_date'),
                'supplier_id' => $this->request->getPost('supplier_id'),
                'supplier_invoice_number' => $this->request->getPost('supplier_invoice_number'),
                'supplier_invoice_date' => $this->request->getPost('supplier_invoice_date'),
                'grn_id' => $this->request->getPost('grn_id') ?: null,
                'po_id' => $this->request->getPost('po_id') ?: null,
                'due_date' => $this->request->getPost('due_date'),
                'subtotal' => $subtotal,
                'tax_amount' => $tax_amount,
                'discount_amount' => $discount_amount,
                'other_charges' => $other_charges,
                'total_amount' => $total_amount,
                'paid_amount' => 0,
                'balance_amount' => $total_amount,
                'payment_status' => 'unpaid',
                'status' => 'draft',
                'notes' => $this->request->getPost('notes'),
                'created_by' => session()->get('user_id')
            ];

            $invoice_id = $this->invoiceModel->insert($invoiceData);

            // Save Invoice items
            foreach ($items as $item) {
                $line_total = $item['quantity'] * $item['unit_price'];
                $item_tax = ($line_total * $item['tax_rate']) / 100;
                $item_discount = ($line_total * $item['discount_rate']) / 100;
                $item_total = $line_total + $item_tax - $item_discount;

                // Get or create ledger for the item
                $ledger_id = $this->getOrCreateItemLedger($item['item_type'], $item['item_id']);

                $itemData = [
                    'invoice_id' => $invoice_id,
                    'grn_item_id' => $item['grn_item_id'] ?? null,
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
                    'ledger_id' => $ledger_id,
                    'notes' => $item['notes'] ?? null
                ];

                $this->invoiceItemModel->insert($itemData);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Failed to create purchase invoice');
            }

            return redirect()->to('/purchase-invoices')->with('success', 'Purchase invoice created successfully');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function approve($id)
    {
        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice || $invoice['status'] !== 'draft') {
            return redirect()->back()->with('error', 'Invoice cannot be approved');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Check if accounting migration is enabled
            $settings = $this->settingsModel->first();
            $accountMigration = $settings['is_account_migration'] ?? 0;

            // Update invoice status
            $updateData = [
                'status' => 'approved',
                'approved_by' => session()->get('user_id'),
                'approved_date' => date('Y-m-d H:i:s')
            ];

            // Create accounting entry if enabled
            if ($accountMigration == 1) {
                $entry_id = $this->createAccountingEntry($invoice);
                $updateData['entry_id'] = $entry_id;
            }

            $this->invoiceModel->update($id, $updateData);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to approve invoice');
            }

            $message = 'Invoice approved successfully';
            if ($accountMigration == 1) {
                $message .= ' and accounting entry created';
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function createAccountingEntry($invoice)
    {
        // Get supplier details
        $supplier = $this->supplierModel->find($invoice['supplier_id']);
        if (!$supplier['ledger_id']) {
            throw new \Exception('Supplier ledger not found. Please create supplier ledger first.');
        }

        // Get invoice items
        $invoiceItems = $this->invoiceItemModel->where('invoice_id', $invoice['id'])->findAll();

        // Generate entry code
        $entryCode = $this->generateEntryCode(4); // 4 = Journal

        // Create main entry
        $entryData = [
            'entrytype_id' => 4, // Journal
            'entry_code' => $entryCode,
            'narration' => 'Purchase Invoice: ' . $invoice['invoice_number'] . ' - Supplier: ' . $supplier['supplier_name'],
            'entry_date' => $invoice['invoice_date'],
            'status' => 1,
            'created_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $entry_id = $this->entryModel->insert($entryData);

        // Create entry items
        $dc_id = 1; // 1 = Debit

        // Debit entries for each item
        foreach ($invoiceItems as $item) {
            if (!$item['ledger_id']) {
                throw new \Exception('Item ledger not found for item: ' . $item['item_id']);
            }

            $entryItemData = [
                'entry_id' => $entry_id,
                'ledger_id' => $item['ledger_id'],
                'amount' => $item['total_amount'],
                'dc' => 'D',
                'dc_id' => $dc_id++,
                'narration' => 'Purchase of ' . $item['description']
            ];

            $this->entryItemModel->insert($entryItemData);
        }

        // Credit entry for supplier
        $entryItemData = [
            'entry_id' => $entry_id,
            'ledger_id' => $supplier['ledger_id'],
            'amount' => $invoice['total_amount'],
            'dc' => 'C',
            'dc_id' => $dc_id++,
            'narration' => 'Purchase Invoice: ' . $invoice['invoice_number']
        ];

        $this->entryItemModel->insert($entryItemData);

        return $entry_id;
    }

    private function generateInvoiceNumber()
    {
        $prefix = 'PINV';
        $year = date('y');
        $month = date('m');
        
        // Get last invoice number for current month
        $lastInvoice = $this->invoiceModel
            ->where('YEAR(created_at)', date('Y'))
            ->where('MONTH(created_at)', date('m'))
            ->orderBy('id', 'DESC')
            ->first();
        
        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice['invoice_number'], -5));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . $month . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    private function generateEntryCode($entrytype_id)
    {
        $year = date('y');
        $month = date('m');
        
        // Get last entry for this type and month
        $lastEntry = $this->entryModel
            ->where('entrytype_id', $entrytype_id)
            ->where('YEAR(created_at)', date('Y'))
            ->where('MONTH(created_at)', date('m'))
            ->orderBy('id', 'DESC')
            ->first();
        
        if ($lastEntry) {
            // Extract serial number from last entry code
            $lastSerial = intval(substr($lastEntry['entry_code'], -5));
            $newSerial = $lastSerial + 1;
        } else {
            $newSerial = 1;
        }
        
        return $year . $month . str_pad($newSerial, 5, '0', STR_PAD_LEFT);
    }

    private function getOrCreateItemLedger($item_type, $item_id)
    {
        // Get item details
        if ($item_type == 'product') {
            $item = $this->productModel->find($item_id);
            $itemName = $item['product_name'];
            $itemCode = $item['product_code'];
            $parentGroupName = 'Products';
        } else {
            $item = $this->rawMaterialModel->find($item_id);
            $itemName = $item['material_name'];
            $itemCode = $item['material_code'];
            $parentGroupName = 'Raw Materials';
        }

        // Check if ledger already exists
        if (!empty($item['ledger_id'])) {
            return $item['ledger_id'];
        }

        // Get settings for parent ledger
        $settings = $this->settingsModel->first();
        
        // Find or create parent group under Assets -> Current Assets
        $currentAssetsGroup = $this->ledgerModel
            ->where('name', 'Current Assets')
            ->where('group_id', 2) // Assuming 2 is Assets group
            ->first();
        
        if (!$currentAssetsGroup) {
            throw new \Exception('Current Assets group not found in ledger');
        }

        // Find or create Products/Raw Materials group
        $parentGroup = $this->ledgerModel
            ->where('name', $parentGroupName)
            ->where('group_id', $currentAssetsGroup['id'])
            ->first();
        
        if (!$parentGroup) {
            // Create parent group
            $parentGroupData = [
                'name' => $parentGroupName,
                'group_id' => $currentAssetsGroup['id'],
                'type' => 1, // Group type
                'code' => strtoupper(substr($parentGroupName, 0, 3)),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $parentGroupId = $this->ledgerModel->insert($parentGroupData);
        } else {
            $parentGroupId = $parentGroup['id'];
        }

        // Create ledger for item
        $ledgerData = [
            'name' => $itemName . ' (' . $itemCode . ')',
            'group_id' => $parentGroupId,
            'type' => 0, // Ledger type
            'code' => $itemCode,
            'op_balance' => 0,
            'op_balance_dc' => 'D',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $ledger_id = $this->ledgerModel->insert($ledgerData);

        // Update item with ledger_id
        if ($item_type == 'product') {
            $this->productModel->update($item_id, ['ledger_id' => $ledger_id]);
        } else {
            $this->rawMaterialModel->update($item_id, ['ledger_id' => $ledger_id]);
        }

        return $ledger_id;
    }

    private function getGRNItems($grn_id)
    {
        $grnItems = $this->grnItemModel
            ->where('grn_id', $grn_id)
            ->findAll();
        
        foreach ($grnItems as &$item) {
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
        }
        
        return $grnItems;
    }

    public function view($id)
    {
        $data['title'] = 'View Purchase Invoice';
        $data['invoice'] = $this->invoiceModel
            ->select('purchase_invoices.*, supplier.supplier_name, supplier.address1, supplier.phone, supplier.email_id')
            ->join('supplier', 'supplier.id = purchase_invoices.supplier_id')
            ->where('purchase_invoices.id', $id)
            ->first();
        
        if (!$data['invoice']) {
            return redirect()->to('/purchase-invoices')->with('error', 'Invoice not found');
        }

        $data['invoice_items'] = $this->invoiceItemModel
            ->where('invoice_id', $id)
            ->findAll();

        // Get item details
        foreach ($data['invoice_items'] as &$item) {
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

        // Get payment details
        $data['payments'] = $this->getInvoicePayments($id);
        
        return view('purchase/invoice_view', $data);
    }

    public function print($id)
    {
        $data = $this->view($id);
        return view('purchase/invoice_print', $data);
    }

    public function cancel($id)
    {
        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice || $invoice['status'] === 'cancelled') {
            return redirect()->back()->with('error', 'Invoice cannot be cancelled');
        }

        if ($invoice['paid_amount'] > 0) {
            return redirect()->back()->with('error', 'Cannot cancel invoice with payments');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update invoice status
            $this->invoiceModel->update($id, ['status' => 'cancelled']);

            // Cancel accounting entry if exists
            if ($invoice['entry_id']) {
                $this->entryModel->update($invoice['entry_id'], ['status' => 0]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to cancel invoice');
            }

            return redirect()->back()->with('success', 'Invoice cancelled successfully');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function getInvoicePayments($invoice_id)
    {
        $sql = "SELECT pp.*, ppa.allocated_amount 
                FROM purchase_payment_allocations ppa
                JOIN purchase_payments pp ON pp.id = ppa.payment_id
                WHERE ppa.invoice_id = ? AND pp.status = 'approved'
                ORDER BY pp.payment_date DESC";
        
        $query = $this->db->query($sql, [$invoice_id]);
        return $query->getResultArray();
    }

    // AJAX endpoints
    public function getGRNDetails()
    {
        $grn_id = $this->request->getGet('grn_id');
        
        $grn = $this->grnModel->find($grn_id);
        if (!$grn) {
            return $this->response->setJSON(['success' => false]);
        }

        $items = $this->getGRNItems($grn_id);
        
        return $this->response->setJSON([
            'success' => true,
            'grn' => $grn,
            'items' => $items
        ]);
    }

    public function getSupplierInvoices()
    {
        $supplier_id = $this->request->getGet('supplier_id');
        
        $invoices = $this->invoiceModel
            ->select('id, invoice_number, invoice_date, total_amount, balance_amount')
            ->where('supplier_id', $supplier_id)
            ->where('status', 'approved')
            ->where('payment_status !=', 'paid')
            ->orderBy('invoice_date', 'ASC')
            ->findAll();
        
        return $this->response->setJSON([
            'success' => true,
            'invoices' => $invoices
        ]);
    }
}