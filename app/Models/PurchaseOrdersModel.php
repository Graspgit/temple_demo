<?php

namespace App\Models;
use CodeIgniter\Model;

class PurchaseOrdersModel extends Model
{
    protected $table = 'purchase_orders';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'po_number', 'po_date', 'supplier_id', 'delivery_date', 'reference_number',
        'terms_conditions', 'subtotal', 'tax_amount', 'discount_amount', 'total_amount',
        'status', 'approved_by', 'approved_date', 'notes', 'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
