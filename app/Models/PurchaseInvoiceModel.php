<?php
namespace App\Models;
use CodeIgniter\Model;

class PurchaseInvoiceModel extends Model
{
    protected $table = 'purchase_invoices';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'invoice_number', 'invoice_date', 'supplier_id', 'supplier_invoice_number',
        'supplier_invoice_date', 'grn_id', 'po_id', 'due_date', 'subtotal',
        'tax_amount', 'discount_amount', 'other_charges', 'total_amount',
        'paid_amount', 'balance_amount', 'payment_status', 'status', 'entry_id',
        'approved_by', 'approved_date', 'notes', 'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}