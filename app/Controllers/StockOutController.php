<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StockOutModel;
use App\Models\StockOutItemsModel;
use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\RawMaterialModel;
use App\Models\StockLedgerModel;
use App\Models\InventorySettingsModel;
use App\Models\LedgerModel;
use App\Models\EntryModel;
use App\Models\EntryItemModel;
use App\Models\SalesInvoiceModel;

class StockOutController extends BaseController
{
    protected $stockOutModel;
    protected $stockOutItemsModel;
    protected $customerModel;
    protected $productModel;
    protected $rawMaterialModel;
    protected $stockLedgerModel;
    protected $inventorySettingsModel;
    protected $ledgerModel;
    protected $entryModel;
    protected $entryItemModel;
    protected $salesInvoiceModel;

    public function __construct()
    {
        $this->stockOutModel = new StockOutModel();
        $this->stockOutItemsModel = new StockOutItemsModel();
        $this->customerModel = new CustomerModel();
        $this->productModel = new ProductModel();
        $this->rawMaterialModel = new RawMaterialModel();
        $this->stockLedgerModel = new StockLedgerModel();
        $this->inventorySettingsModel = new InventorySettingsModel();
        $this->ledgerModel = new LedgerModel();
        $this->entryModel = new EntryModel();
        $this->entryItemModel = new EntryItemModel();
        $this->salesInvoiceModel = new SalesInvoiceModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Stock Out Management',
            'stockOuts' => $this->stockOutModel->getStockOutsWithDetails()
        ];
        return view('stock_out/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Stock Out',
            'products' => $this->productModel->findAll(),
            'rawMaterials' => $this->rawMaterialModel->findAll(),
            'paymentModes' => $this->getPaymentModes(),
            'stockOutTypes' => [
                'sale' => 'Sale',
                'kitchen' => 'Kitchen Usage',
                'pooja' => 'Pooja Usage',
                'wastage' => 'Wastage',
                'defective' => 'Defective',
                'internal' => 'Internal Purpose'
            ]
        ];
        return view('stock_out/create', $data);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Get inventory settings
            $settings = $this->inventorySettingsModel->first();
            
            // Validate input
            $rules = [
                'stock_out_date' => 'required|valid_date',
                'stock_out_type' => 'required|in_list[sale,kitchen,pooja,wastage,defective,internal]',
                'items' => 'required'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $stockOutType = $this->request->getPost('stock_out_type');
            
            // Handle customer for sales
            $customerId = null;
            $customerName = null;
            $customerMobile = null;
            $customerType = 'guest';

            if ($stockOutType === 'sale') {
                $customerMobile = $this->request->getPost('customer_mobile');
                
                if ($customerMobile) {
                    // Check if customer exists
                    $existingCustomer = $this->customerModel->where('mobile_no', $customerMobile)->first();
                    
                    if ($existingCustomer) {
                        $customerId = $existingCustomer['id'];
                        $customerType = 'registered';
                    } else {
                        // Guest customer
                        $customerName = $this->request->getPost('customer_name');
                    }
                } else {
                    $customerName = $this->request->getPost('customer_name') ?: 'Walk-in Customer';
                }
            }

            // Generate stock out number
            $stockOutNo = $this->generateStockOutNumber();

            // Calculate totals and prepare stock out data
            $items = json_decode($this->request->getPost('items'), true);
            $subtotal = 0;
            $totalCost = 0;
            $discountAmount = 0;
            $taxAmount = 0;

            foreach ($items as $item) {
                // Get average cost for the item
                $avgCost = $this->getAverageCost($item['item_type'], $item['item_id']);
                $itemTotalCost = $avgCost * $item['quantity'];
                $totalCost += $itemTotalCost;
                
                if ($stockOutType === 'sale') {
                    $itemTotal = $item['quantity'] * $item['unit_price'];
                    $itemDiscount = ($item['discount_percent'] / 100) * $itemTotal;
                    $itemAfterDiscount = $itemTotal - $itemDiscount;
                    $itemTax = ($item['tax_percent'] / 100) * $itemAfterDiscount;
                    
                    $subtotal += $itemTotal;
                    $discountAmount += $itemDiscount;
                    $taxAmount += $itemTax;
                }
            }

            $totalAmount = $subtotal - $discountAmount + $taxAmount;

            // Create stock out record
            $stockOutData = [
                'stock_out_no' => $stockOutNo,
                'stock_out_date' => $this->request->getPost('stock_out_date'),
                'stock_out_type' => $stockOutType,
                'customer_id' => $customerId,
                'customer_name' => $customerName,
                'customer_mobile' => $customerMobile,
                'customer_type' => $customerType,
                'narration' => $this->request->getPost('narration'),
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'total_cost' => $totalCost,
                'payment_status' => ($stockOutType === 'sale') ? 'pending' : 'paid',
                'created_by' => session()->get('user_id'),
                'fund_id' => $this->request->getPost('fund_id') ?: 1
            ];

            $this->stockOutModel->insert($stockOutData);
            $stockOutId = $this->stockOutModel->insertID();

            // Process items
            foreach ($items as $item) {
                // Get current stock
                $currentStock = $this->getCurrentStock($item['item_type'], $item['item_id']);
                
                if ($currentStock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for item");
                }

                // Get average cost
                $avgCost = $this->getAverageCost($item['item_type'], $item['item_id']);
                
                // Calculate item totals
                $itemTotal = ($stockOutType === 'sale') ? $item['quantity'] * $item['unit_price'] : 0;
                $itemDiscount = ($stockOutType === 'sale') ? ($item['discount_percent'] / 100) * $itemTotal : 0;
                $itemAfterDiscount = $itemTotal - $itemDiscount;
                $itemTax = ($stockOutType === 'sale') ? ($item['tax_percent'] / 100) * $itemAfterDiscount : 0;
                $itemFinalTotal = $itemAfterDiscount + $itemTax;
                $itemTotalCost = $avgCost * $item['quantity'];

                // Insert stock out item
                $stockOutItemData = [
                    'stock_out_id' => $stockOutId,
                    'item_type' => $item['item_type'],
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => ($stockOutType === 'sale') ? $item['unit_price'] : 0,
                    'unit_cost' => $avgCost,
                    'discount_percent' => ($stockOutType === 'sale') ? $item['discount_percent'] : 0,
                    'discount_amount' => $itemDiscount,
                    'tax_percent' => ($stockOutType === 'sale') ? $item['tax_percent'] : 0,
                    'tax_amount' => $itemTax,
                    'total_amount' => $itemFinalTotal,
                    'total_cost' => $itemTotalCost,
                    'narration' => $item['narration'] ?? null
                ];

                $this->stockOutItemsModel->insert($stockOutItemData);

                // Update stock ledger
                $this->updateStockLedger($item['item_type'], $item['item_id'], $item['quantity'], 
                    $avgCost, $stockOutId, 'stock_out', $stockOutType);
            }

            // Create accounting entries if enabled
            if ($settings && $settings['is_account_migration'] == 1) {
                $entryId = $this->createAccountingEntries($stockOutId, $stockOutType, $totalCost, 
                    $totalAmount, $settings);
                
                // Update stock out with entry ID
                $this->stockOutModel->update($stockOutId, ['entry_id' => $entryId]);
            }

            // Create sales invoice for sale type
            if ($stockOutType === 'sale') {
                $this->createSalesInvoice($stockOutId, $stockOutData);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception("Transaction failed");
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Stock out created successfully',
                'stock_out_id' => $stockOutId
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Check customer by mobile
    public function checkCustomer()
    {
        $mobile = $this->request->getGet('mobile');
        
        if (!$mobile) {
            return $this->response->setJSON([
                'found' => false
            ]);
        }

        $customer = $this->customerModel->where('mobile_no', $mobile)->first();
        
        if ($customer) {
            return $this->response->setJSON([
                'found' => true,
                'customer' => [
                    'id' => $customer['id'],
                    'name' => $customer['customer_name'],
                    'mobile' => $customer['mobile_no'],
                    'address' => $customer['address']
                ]
            ]);
        }

        return $this->response->setJSON([
            'found' => false
        ]);
    }

    // Get item details with stock and average cost
    public function getItemDetails()
    {
        $itemType = $this->request->getGet('item_type');
        $itemId = $this->request->getGet('item_id');

        if (!$itemType || !$itemId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid parameters'
            ]);
        }

        // Get item details
        if ($itemType === 'product') {
            $item = $this->productModel->find($itemId);
        } else {
            $item = $this->rawMaterialModel->find($itemId);
        }

        if (!$item) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Item not found'
            ]);
        }

        // Get current stock
        $currentStock = $this->getCurrentStock($itemType, $itemId);
        
        // Get average cost
        $avgCost = $this->getAverageCost($itemType, $itemId);

        return $this->response->setJSON([
            'success' => true,
            'item' => $item,
            'current_stock' => $currentStock,
            'average_cost' => $avgCost
        ]);
    }

    // Private helper methods
    private function generateStockOutNumber()
    {
        $prefix = 'SO';
        $year = date('y');
        $month = date('m');
        
        // Get last number for this month
        $lastStockOut = $this->stockOutModel
            ->where('YEAR(stock_out_date)', date('Y'))
            ->where('MONTH(stock_out_date)', date('m'))
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastStockOut && preg_match('/SO(\d{2})(\d{2})(\d+)/', $lastStockOut['stock_out_no'], $matches)) {
            $serial = intval($matches[3]) + 1;
        } else {
            $serial = 1;
        }

        return $prefix . $year . $month . str_pad($serial, 5, '0', STR_PAD_LEFT);
    }

    private function getCurrentStock($itemType, $itemId)
    {
        $stockIn = $this->stockLedgerModel
            ->selectSum('quantity')
            ->where('item_type', $itemType)
            ->where('item_id', $itemId)
            ->where('transaction_type', 'in')
            ->first();

        $stockOut = $this->stockLedgerModel
            ->selectSum('quantity')
            ->where('item_type', $itemType)
            ->where('item_id', $itemId)
            ->where('transaction_type', 'out')
            ->first();

        return ($stockIn['quantity'] ?? 0) - ($stockOut['quantity'] ?? 0);
    }

    private function getAverageCost($itemType, $itemId)
    {
        // Get all stock in transactions
        $stockIns = $this->stockLedgerModel
            ->where('item_type', $itemType)
            ->where('item_id', $itemId)
            ->where('transaction_type', 'in')
            ->findAll();

        $totalQuantity = 0;
        $totalCost = 0;

        foreach ($stockIns as $stockIn) {
            $totalQuantity += $stockIn['quantity'];
            $totalCost += ($stockIn['quantity'] * $stockIn['unit_cost']);
        }

        return $totalQuantity > 0 ? round($totalCost / $totalQuantity, 2) : 0;
    }

    private function updateStockLedger($itemType, $itemId, $quantity, $unitCost, 
        $referenceId, $referenceType, $stockOutType)
    {
        // Get item details for name
        if ($itemType === 'product') {
            $item = $this->productModel->find($itemId);
            $itemName = $item['product_name'];
        } else {
            $item = $this->rawMaterialModel->find($itemId);
            $itemName = $item['material_name'];
        }

        $stockLedgerData = [
            'transaction_date' => date('Y-m-d'),
            'item_type' => $itemType,
            'item_id' => $itemId,
            'item_name' => $itemName,
            'transaction_type' => 'out',
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'quantity' => $quantity,
            'unit_cost' => $unitCost,
            'total_cost' => $quantity * $unitCost,
            'balance_quantity' => $this->getCurrentStock($itemType, $itemId) - $quantity,
            'narration' => "Stock out - " . ucfirst($stockOutType),
            'created_by' => session()->get('user_id')
        ];

        $this->stockLedgerModel->insert($stockLedgerData);
    }

    private function createAccountingEntries($stockOutId, $stockOutType, $totalCost, 
        $totalAmount, $settings)
    {
        $stockOut = $this->stockOutModel->find($stockOutId);
        
        // Generate entry code
        $entryCode = date('ym') . str_pad($this->getNextEntrySerial(), 5, '0', STR_PAD_LEFT);
        
        // Determine entry type based on stock out type
        $entryTypeId = ($stockOutType === 'sale') ? 4 : 1; // 4 for sales, 1 for journal
        
        $entryData = [
            'entrytype_id' => $entryTypeId,
            'number' => $this->getNextEntryNumber($entryTypeId),
            'date' => $stockOut['stock_out_date'],
            'dr_total' => 0,
            'cr_total' => 0,
            'narration' => "Stock Out - " . $stockOut['stock_out_no'] . " - " . ucfirst($stockOutType),
            'fund_id' => $stockOut['fund_id'],
            'entry_code' => $entryCode,
            'status' => 'posted',
            'entry_by' => session()->get('user_id')
        ];

        // For sales type
        if ($stockOutType === 'sale') {
            $entryData['dr_total'] = $totalCost + $totalAmount;
            $entryData['cr_total'] = $totalCost + $totalAmount;
            
            // Customer/Cash ledger ID (debit)
            $cashLedgerId = $this->getCashLedgerId();
            
            // Stock out cost ledger (debit) - Direct Cost
            $costLedgerId = $settings['stock_out_cost_ledger_id'];
            
            // Stock out sale ledger (credit) - Revenue
            $saleLedgerId = $settings['stock_out_sale_ledger_id'];
            
            // Stock ledger (credit) - for inventory reduction
            $stockLedgerId = $this->getStockLedgerId();
        } else {
            // For non-sale stock outs (only cost entries)
            $entryData['dr_total'] = $totalCost;
            $entryData['cr_total'] = $totalCost;
            
            // Stock out cost ledger (debit) - Direct Cost
            $costLedgerId = $settings['stock_out_cost_ledger_id'];
            
            // Stock ledger (credit) - for inventory reduction
            $stockLedgerId = $this->getStockLedgerId();
        }

        $this->entryModel->insert($entryData);
        $entryId = $this->entryModel->insertID();

        // Create entry items
        if ($stockOutType === 'sale') {
            // Debit: Cash/Customer
            $this->entryItemModel->insert([
                'entry_id' => $entryId,
                'ledger_id' => $cashLedgerId,
                'amount' => $totalAmount,
                'dc' => 'D',
                'narration' => "Sales collection"
            ]);

            // Debit: Direct Cost
            $this->entryItemModel->insert([
                'entry_id' => $entryId,
                'ledger_id' => $costLedgerId,
                'amount' => $totalCost,
                'dc' => 'D',
                'narration' => "Cost of goods sold"
            ]);

            // Credit: Revenue
            $this->entryItemModel->insert([
                'entry_id' => $entryId,
                'ledger_id' => $saleLedgerId,
                'amount' => $totalAmount,
                'dc' => 'C',
                'narration' => "Sales revenue"
            ]);

            // Credit: Stock
            $this->entryItemModel->insert([
                'entry_id' => $entryId,
                'ledger_id' => $stockLedgerId,
                'amount' => $totalCost,
                'dc' => 'C',
                'narration' => "Stock reduction"
            ]);
        } else {
            // Debit: Direct Cost
            $this->entryItemModel->insert([
                'entry_id' => $entryId,
                'ledger_id' => $costLedgerId,
                'amount' => $totalCost,
                'dc' => 'D',
                'narration' => ucfirst($stockOutType) . " usage"
            ]);

            // Credit: Stock
            $this->entryItemModel->insert([
                'entry_id' => $entryId,
                'ledger_id' => $stockLedgerId,
                'amount' => $totalCost,
                'dc' => 'C',
                'narration' => "Stock reduction - " . ucfirst($stockOutType)
            ]);
        }

        return $entryId;
    }

    private function createSalesInvoice($stockOutId, $stockOutData)
    {
        $invoiceNo = $this->generateInvoiceNumber();
        
        $invoiceData = [
            'invoice_no' => $invoiceNo,
            'invoice_date' => $stockOutData['stock_out_date'],
            'stock_out_id' => $stockOutId,
            'customer_id' => $stockOutData['customer_id'],
            'customer_name' => $stockOutData['customer_name'] ?: 
                ($stockOutData['customer_id'] ? $this->customerModel->find($stockOutData['customer_id'])['customer_name'] : 'Walk-in Customer'),
            'customer_mobile' => $stockOutData['customer_mobile'],
            'subtotal' => $stockOutData['subtotal'],
            'discount_amount' => $stockOutData['discount_amount'],
            'tax_amount' => $stockOutData['tax_amount'],
            'total_amount' => $stockOutData['total_amount'],
            'paid_amount' => 0,
            'balance_amount' => $stockOutData['total_amount'],
            'payment_status' => 'pending',
            'created_by' => session()->get('user_id'),
            'fund_id' => $stockOutData['fund_id']
        ];

        $this->salesInvoiceModel->insert($invoiceData);
        return $this->salesInvoiceModel->insertID();
    }

    private function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $year = date('y');
        $month = date('m');
        
        $lastInvoice = $this->salesInvoiceModel
            ->where('YEAR(invoice_date)', date('Y'))
            ->where('MONTH(invoice_date)', date('m'))
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastInvoice && preg_match('/INV(\d{2})(\d{2})(\d+)/', $lastInvoice['invoice_no'], $matches)) {
            $serial = intval($matches[3]) + 1;
        } else {
            $serial = 1;
        }

        return $prefix . $year . $month . str_pad($serial, 5, '0', STR_PAD_LEFT);
    }

    private function getNextEntrySerial()
    {
        $year = date('Y');
        $month = date('m');
        
        $lastEntry = $this->entryModel
            ->where('YEAR(date)', $year)
            ->where('MONTH(date)', $month)
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastEntry && $lastEntry['entry_code']) {
            return intval(substr($lastEntry['entry_code'], -5)) + 1;
        }
        
        return 1;
    }

    private function getNextEntryNumber($entryTypeId)
    {
        $lastEntry = $this->entryModel
            ->where('entrytype_id', $entryTypeId)
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastEntry && is_numeric($lastEntry['number'])) {
            return intval($lastEntry['number']) + 1;
        }
        
        return 1;
    }

    private function getCashLedgerId()
    {
        // Get or create cash ledger
        $cashLedger = $this->ledgerModel
            ->where('name', 'Cash in Hand')
            ->orWhere('code', '1210')
            ->first();

        if (!$cashLedger) {
            // Create cash ledger under Current Assets
            $currentAssetsGroup = $this->db->table('groups')
                ->where('code', '1200')
                ->get()
                ->getRowArray();

            $this->ledgerModel->insert([
                'group_id' => $currentAssetsGroup['id'],
                'name' => 'Cash in Hand',
                'code' => '1210',
                'op_balance' => 0,
                'op_balance_dc' => 'D',
                'type' => 0,
                'reconciliation' => 0
            ]);

            return $this->ledgerModel->insertID();
        }

        return $cashLedger['id'];
    }

    private function getStockLedgerId()
    {
        // Get or create stock ledger
        $stockLedger = $this->ledgerModel
            ->where('name', 'Stock')
            ->orWhere('code', '1220')
            ->first();

        if (!$stockLedger) {
            // Create stock ledger under Current Assets
            $currentAssetsGroup = $this->db->table('groups')
                ->where('code', '1200')
                ->get()
                ->getRowArray();

            $this->ledgerModel->insert([
                'group_id' => $currentAssetsGroup['id'],
                'name' => 'Stock',
                'code' => '1220',
                'op_balance' => 0,
                'op_balance_dc' => 'D',
                'type' => 0,
                'reconciliation' => 0
            ]);

            return $this->ledgerModel->insertID();
        }

        return $stockLedger['id'];
    }

    private function getPaymentModes()
    {
        return $this->db->table('payment_mode')->get()->getResultArray();
    }
}