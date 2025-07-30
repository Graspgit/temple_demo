<?php
namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $table = 'purchase_order';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'po_no',
        'invoice_type',
        'customer_supplier_id',
        'challan_no',
        'date',
        'subject', // Add this field to your table
        'remarks',
        'total',
        'discount',
        'grand_total',
        'paid_amount',
        'due_amount',
        'is_approved',
        'created_name', // New field for creator name
        'approved_date', // New field for approval date
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get purchase order with supplier/customer details
     */
    public function getPurchaseOrderWithDetails($id)
    {
        $builder = $this->db->table($this->table . ' po');
        $builder->select('po.*, 
                         CASE 
                            WHEN po.invoice_type = 2 THEN s.supplier_name 
                            ELSE c.customer_name 
                         END as entity_name,
                         CASE 
                            WHEN po.invoice_type = 2 THEN s.supplier_code 
                            ELSE c.customer_code 
                         END as entity_code');
        $builder->join('supplier s', 's.id = po.customer_supplier_id AND po.invoice_type = 2', 'left');
        $builder->join('customer c', 'c.id = po.customer_supplier_id AND po.invoice_type = 1', 'left');
        $builder->where('po.id', $id);
        
        return $builder->get()->getRowArray();
    }

    /**
     * Get all purchase orders with entity names
     */
    public function getAllWithEntityNames()
    {
        $builder = $this->db->table($this->table . ' po');
        $builder->select('po.*, 
                         CASE 
                            WHEN po.invoice_type = 2 THEN s.supplier_name 
                            ELSE c.customer_name 
                         END as entity_name,
                         CASE 
                            WHEN po.invoice_type = 2 THEN "Purchase" 
                            ELSE "Sales" 
                         END as type_name');
        $builder->join('supplier s', 's.id = po.customer_supplier_id AND po.invoice_type = 2', 'left');
        $builder->join('customer c', 'c.id = po.customer_supplier_id AND po.invoice_type = 1', 'left');
        $builder->orderBy('po.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}

// App/Models/SupplierModel.php
namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'supplier_name',
        'supplier_code',
        'contact_person',
        'mobile_no',
        'email_id',
        'remarks',
        'vat_no',
        'phone',
        'contact',
        'address1',
        'address2',
        'fax',
        'city',
        'state',
        'zipcode',
        'country',
        'phoneno',
        'ledger_id',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get formatted supplier address
     */
    public function getFormattedAddress($supplierId)
    {
        $supplier = $this->find($supplierId);
        if (!$supplier) return '';

        $address = [];
        if (!empty($supplier['address1'])) $address[] = $supplier['address1'];
        if (!empty($supplier['address2'])) $address[] = $supplier['address2'];
        if (!empty($supplier['city'])) $address[] = $supplier['city'];
        if (!empty($supplier['state'])) $address[] = $supplier['state'];
        if (!empty($supplier['zipcode'])) $address[] = $supplier['zipcode'];
        if (!empty($supplier['country'])) $address[] = $supplier['country'];

        return implode(', ', $address);
    }
}

// App/Models/CustomerModel.php
namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'customer_name',
        'customer_code',
        'mobile_no',
        'email_id',
        'vat_no',
        'phone',
        'cr_no',
        'address1',
        'address2',
        'fax',
        'city',
        'state',
        'zipcode',
        'country',
        'ledger_id',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get formatted customer address
     */
    public function getFormattedAddress($customerId)
    {
        $customer = $this->find($customerId);
        if (!$customer) return '';

        $address = [];
        if (!empty($customer['address1'])) $address[] = $customer['address1'];
        if (!empty($customer['address2'])) $address[] = $customer['address2'];
        if (!empty($customer['city'])) $address[] = $customer['city'];
        if (!empty($customer['state'])) $address[] = $customer['state'];
        if (!empty($customer['zipcode'])) $address[] = $customer['zipcode'];
        if (!empty($customer['country'])) $address[] = $customer['country'];

        return implode(', ', $address);
    }
}

// App/Models/PurchaseOrderDetailsModel.php
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