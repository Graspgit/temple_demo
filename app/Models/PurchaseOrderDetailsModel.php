<?php
namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderDetailsModel extends Model
{
    protected $table = 'purchase_order_details';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'invoice_master_id',
        'description',
        'ledger_id',
        'type',
        'rate',
        'qty',
        'tax',
        'amount',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get order details with type names
     */
    public function getOrderDetailsWithTypes($orderId)
    {
        $details = $this->where('invoice_master_id', $orderId)->findAll();
        
        foreach ($details as &$detail) {
            $detail['type_name'] = ($detail['type'] == 1) ? 'Service' : 'Product';
        }
        
        return $details;
    }

    /**
     * Calculate totals for an order
     */
    public function calculateOrderTotals($orderId)
    {
        $details = $this->where('invoice_master_id', $orderId)->findAll();
        
        $subtotal = 0;
        $totalTax = 0;
        
        foreach ($details as $detail) {
            $subtotal += $detail['amount'];
            $totalTax += ($detail['rate'] * $detail['qty'] * $detail['tax'] / 100);
        }
        
        return [
            'subtotal' => $subtotal,
            'total_tax' => $totalTax,
            'grand_total' => $subtotal + $totalTax
        ];
    }
}