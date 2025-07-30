<?php
namespace App\Models;
use CodeIgniter\Model;

class PurchasePaymentAllocationModel extends Model
{
    protected $table = 'purchase_payment_allocations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['payment_id', 'invoice_id', 'allocated_amount'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}