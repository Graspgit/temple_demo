<?php
namespace App\Models;
use CodeIgniter\Model;

class PurchaseOrderItemModel extends Model
{
    protected $table = 'purchase_order_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'po_id', 'item_type', 'item_id', 'description', 'uom_id', 'quantity',
        'received_quantity', 'unit_price', 'tax_rate', 'tax_amount', 'discount_rate',
        'discount_amount', 'total_amount', 'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}