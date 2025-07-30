<?php
namespace App\Models;
use CodeIgniter\Model;

class PurchasePaymentModel extends Model
{
    protected $table = 'purchase_payments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'payment_number', 'payment_date', 'supplier_id', 'payment_mode_id',
        'amount', 'reference_number', 'bank_name', 'cheque_number', 'cheque_date',
        'entry_id', 'status', 'approved_by', 'approved_date', 'notes', 'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}