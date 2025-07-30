<?php
namespace App\Models;
use CodeIgniter\Model;

class PurchaseInvoiceItemModel extends Model
{
    protected $table = 'purchase_invoice_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'invoice_id', 'grn_item_id', 'item_type', 'item_id', 'description',
        'uom_id', 'quantity', 'unit_price', 'tax_rate', 'tax_amount',
        'discount_rate', 'discount_amount', 'total_amount', 'ledger_id', 'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}