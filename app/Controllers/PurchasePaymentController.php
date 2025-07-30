<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PurchasePaymentModel;
use App\Models\PurchasePaymentAllocationModel;
use App\Models\PurchaseInvoiceModel;
use App\Models\SupplierModel;
use App\Models\PaymentModeModel;
use App\Models\EntryModel;
use App\Models\EntryItemModel;
use App\Models\LedgerModel;
use App\Models\InventorySettingsModel;

class PurchasePaymentController extends BaseController
{
    protected $paymentModel;
    protected $allocationModel;
    protected $invoiceModel;
    protected $supplierModel;
    protected $paymentModeModel;
    protected $entryModel;
    protected $entryItemModel;
    protected $ledgerModel;
    protected $settingsModel;

    public function __construct()
    {
        $this->paymentModel = new PurchasePaymentModel();
        $this->allocationModel = new PurchasePaymentAllocationModel();
        $this->invoiceModel = new PurchaseInvoiceModel();
        $this->supplierModel = new SupplierModel();
        $this->paymentModeModel = new PaymentModeModel();
        $this->entryModel = new EntryModel();
        $this->entryItemModel = new EntryItemModel();
        $this->ledgerModel = new LedgerModel();
        $this->settingsModel = new InventorySettingsModel();
    }

    public function index()
    {
        $data['title'] = 'Purchase Payments';
        $data['payments'] = $this->paymentModel
            ->select('purchase_payments.*, supplier.supplier_name, payment_mode.payment_mode as payment_mode_name')
            ->join('supplier', 'supplier.id = purchase_payments.supplier_id')
            ->join('payment_mode', 'payment_mode.id = purchase_payments.payment_mode_id', 'left')
            ->orderBy('purchase_payments.created_at', 'DESC')
            ->findAll();
        
        return view('purchase/payment_list', $data);
    }

    public function create()
    {
        $data['title'] = 'Create Purchase Payment';
        $data['suppliers'] = $this->supplierModel->where('status', 1)->findAll();
        $data['payment_modes'] = $this->paymentModeModel->where('status', 1)->findAll();
        $data['payment_number'] = $this->generatePaymentNumber();
        
        $supplier_id = $this->request->getGet('supplier_id');
        if ($supplier_id) {
            $data['selected_supplier'] = $this->supplierModel->find($supplier_id);
            $data['pending_invoices'] = $this->getPendingInvoices($supplier_id);
        }
        
        return view('purchase/payment_form', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'payment_date' => 'required|valid_date',
            'supplier_id' => 'required|numeric',
            'payment_mode_id' => 'required|numeric',
            'amount' => 'required|numeric|greater_than[0]',
            'allocations' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $allocations = $this->request->getPost('allocations');
            $totalAllocated = array_sum(array_column($allocations, 'amount'));
            $paymentAmount = $this->request->getPost('amount');

            if ($totalAllocated != $paymentAmount) {
                throw new \Exception('Allocated amount must equal payment amount');
            }

            // Save Payment header
            $paymentData = [
                'payment_number' => $this->request->getPost('payment_number'),
                'payment_date' => $this->request->getPost('payment_date'),
                'supplier_id' => $this->request->getPost('supplier_id'),
                'payment_mode_id' => $this->request->getPost('payment_mode_id'),
                'amount' => $paymentAmount,
                'reference_number' => $this->request->getPost('reference_number'),
                'bank_name' => $this->request->getPost('bank_name'),
                'cheque_number' => $this->request->getPost('cheque_number'),
                'cheque_date' => $this->request->getPost('cheque_date'),
                'status' => 'draft',
                'notes' => $this->request->getPost('notes'),
                'created_by' => session()->get('user_id')
            ];

            $payment_id = $this->paymentModel->insert($paymentData);

            // Save allocations
            foreach ($allocations as $allocation) {
                if ($allocation['amount'] > 0) {
                    $allocationData = [
                        'payment_id' => $payment_id,
                        'invoice_id' => $allocation['invoice_id'],
                        'allocated_amount' => $allocation['amount']
                    ];
                    $this->allocationModel->insert($allocationData);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Failed to create payment');
            }

            return redirect()->to('/purchase-payments')->with('success', 'Payment created successfully');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function approve($id)
    {
        $payment = $this->paymentModel->find($id);
        
        if (!$payment || $payment['status'] !== 'draft') {
            return redirect()->back()->with('error', 'Payment cannot be approved');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Check if accounting migration is enabled
            $settings = $this->settingsModel->first();
            $accountMigration = $settings['is_account_migration'] ?? 0;

            // Update payment status
            $updateData = [
                'status' => 'approved',
                'approved_by' => session()->get('user_id'),
                'approved_date' => date('Y-m-d H:i:s')
            ];

            // Create accounting entry if enabled
            if ($accountMigration == 1) {
                $entry_id = $this->createAccountingEntry($payment);
                $updateData['entry_id'] = $entry_id;
            }

            $this->paymentModel->update($id, $updateData);

            // Update invoice payment status
            $allocations = $this->allocationModel->where('payment_id', $id)->findAll();
            foreach ($allocations as $allocation) {
                $this->updateInvoicePaymentStatus($allocation['invoice_id']);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to approve payment');
            }

            $message = 'Payment approved successfully';
            if ($accountMigration == 1) {
                $message .= ' and accounting entry created';
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function createAccountingEntry($payment)
    {
        // Get supplier details
        $supplier = $this->supplierModel->find($payment['supplier_id']);
        if (!$supplier['ledger_id']) {
            throw new \Exception('Supplier ledger not found. Please create supplier ledger first.');
        }

        // Get payment mode details
        $paymentMode = $this->paymentModeModel->find($payment['payment_mode_id']);
        if (!$paymentMode['ledger_id']) {
            throw new \Exception('Payment mode ledger not found. Please configure payment mode ledger.');
        }

        // Generate entry code
        $entryCode = $this->generateEntryCode(2); // 2 = Payment

        // Create main entry
        $entryData = [
            'entrytype_id' => 2, // Payment
            'entry_code' => $entryCode,
            'narration' => 'Payment to Supplier: ' . $supplier['supplier_name'] . ' - Payment No: ' . $payment['payment_number'],
            'entry_date' => $payment['payment_date'],
            'status' => 1,
            'created_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $entry_id = $this->entryModel->insert($entryData);

        // Create entry items
        $dc_id = 1;

        // Debit entry for supplier
        $entryItemData = [
            'entry_id' => $entry_id,
            'ledger_id' => $supplier['ledger_id'],
            'amount' => $payment['amount'],
            'dc' => 'D',
            'dc_id' => $dc_id++,
            'narration' => 'Payment against invoices'
        ];
        $this->entryItemModel->insert($entryItemData);

        // Credit entry for cash/bank
        $entryItemData = [
            'entry_id' => $entry_id,
            'ledger_id' => $paymentMode['ledger_id'],
            'amount' => $payment['amount'],
            'dc' => 'C',
            'dc_id' => $dc_id++,
            'narration' => 'Payment to ' . $supplier['supplier_name']
        ];
        $this->entryItemModel->insert($entryItemData);

        return $entry_id;
    }

    private function updateInvoicePaymentStatus($invoice_id)
    {
        $invoice = $this->invoiceModel->find($invoice_id);
        
        // Calculate total paid
        $sql = "SELECT SUM(ppa.allocated_amount) as total_paid 
                FROM purchase_payment_allocations ppa
                JOIN purchase_payments pp ON pp.id = ppa.payment_id
                WHERE ppa.invoice_id = ? AND pp.status = 'approved'";
        
        $query = $this->db->query($sql, [$invoice_id]);
        $result = $query->getRow();
        $totalPaid = $result->total_paid ?? 0;
        
        $balance = $invoice['total_amount'] - $totalPaid;
        
        if ($balance <= 0) {
            $paymentStatus = 'paid';
        } elseif ($totalPaid > 0) {
            $paymentStatus = 'partial';
        } else {
            $paymentStatus = 'unpaid';
        }
        
        $this->invoiceModel->update($invoice_id, [
            'paid_amount' => $totalPaid,
            'balance_amount' => $balance,
            'payment_status' => $paymentStatus
        ]);
    }

    private function generatePaymentNumber()
    {
        $prefix = 'PPAY';
        $year = date('y');
        $month = date('m');
        
        // Get last payment number for current month
        $lastPayment = $this->paymentModel
            ->where('YEAR(created_at)', date('Y'))
            ->where('MONTH(created_at)', date('m'))
            ->orderBy('id', 'DESC')
            ->first();
        
        if ($lastPayment) {
            $lastNumber = intval(substr($lastPayment['payment_number'], -5));
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

    private function getPendingInvoices($supplier_id)
    {
        return $this->invoiceModel
            ->select('id, invoice_number, invoice_date, total_amount, paid_amount, balance_amount')
            ->where('supplier_id', $supplier_id)
            ->where('status', 'approved')
            ->where('payment_status !=', 'paid')
            ->orderBy('invoice_date', 'ASC')
            ->findAll();
    }

    public function view($id)
    {
        $data['title'] = 'View Purchase Payment';
        $data['payment'] = $this->paymentModel
            ->select('purchase_payments.*, supplier.supplier_name, supplier.address1, payment_mode.payment_mode as payment_mode_name')
            ->join('supplier', 'supplier.id = purchase_payments.supplier_id')
            ->join('payment_mode', 'payment_mode.id = purchase_payments.payment_mode_id', 'left')
            ->where('purchase_payments.id', $id)
            ->first();
        
        if (!$data['payment']) {
            return redirect()->to('/purchase-payments')->with('error', 'Payment not found');
        }

        // Get allocations
        $data['allocations'] = $this->allocationModel
            ->select('purchase_payment_allocations.*, purchase_invoices.invoice_number, purchase_invoices.invoice_date')
            ->join('purchase_invoices', 'purchase_invoices.id = purchase_payment_allocations.invoice_id')
            ->where('payment_id', $id)
            ->findAll();
        
        return view('purchase/payment_view', $data);
    }

    public function print($id)
    {
        $data = $this->view($id);
        return view('purchase/payment_print', $data);
    }

    public function cancel($id)
    {
        $payment = $this->paymentModel->find($id);
        
        if (!$payment || $payment['status'] === 'cancelled') {
            return redirect()->back()->with('error', 'Payment cannot be cancelled');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update payment status
            $this->paymentModel->update($id, ['status' => 'cancelled']);

            // Cancel accounting entry if exists
            if ($payment['entry_id']) {
                $this->entryModel->update($payment['entry_id'], ['status' => 0]);
            }

            // Update invoice payment status
            $allocations = $this->allocationModel->where('payment_id', $id)->findAll();
            foreach ($allocations as $allocation) {
                $this->updateInvoicePaymentStatus($allocation['invoice_id']);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to cancel payment');
            }

            return redirect()->back()->with('success', 'Payment cancelled successfully');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // AJAX endpoint
    public function getSupplierInvoices()
    {
        $supplier_id = $this->request->getGet('supplier_id');
        $invoices = $this->getPendingInvoices($supplier_id);
        
        return $this->response->setJSON([
            'success' => true,
            'invoices' => $invoices
        ]);
    }
}