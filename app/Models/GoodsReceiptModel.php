<?php
namespace App\Models;
use CodeIgniter\Model;

class GoodsReceiptModel extends Model
{
    protected $table = 'goods_receipt_notes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'grn_number', 'grn_date', 'po_id', 'supplier_id', 'invoice_number',
        'invoice_date', 'delivery_note_number', 'subtotal', 'tax_amount',
        'discount_amount', 'total_amount', 'status', 'approved_by', 'approved_date',
        'notes', 'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}