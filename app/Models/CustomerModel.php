<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'id';
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
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'customer_name' => 'required|min_length[3]|max_length[200]',
        'customer_code' => 'required|min_length[2]|max_length[30]|is_unique[customer.customer_code,id,{id}]',
        'email_id' => 'permit_empty|valid_email|max_length[200]',
        'mobile_no' => 'permit_empty|max_length[20]',
        'phone' => 'permit_empty|max_length[20]'
    ];
    
    protected $validationMessages = [
        'customer_name' => [
            'required' => 'Customer name is required',
            'min_length' => 'Customer name must be at least 3 characters',
            'max_length' => 'Customer name cannot exceed 200 characters'
        ],
        'customer_code' => [
            'required' => 'Customer code is required',
            'is_unique' => 'Customer code already exists'
        ],
        'email_id' => [
            'valid_email' => 'Please enter a valid email address'
        ]
    ];

    public function getAllCustomers()
    {
        return $this->orderBy('customer_name', 'ASC')->findAll();
    }

    public function getCustomerById($id)
    {
        return $this->find($id);
    }

    public function getCustomerByCode($code)
    {
        return $this->where('customer_code', $code)->first();
    }

    public function searchCustomers($search)
    {
        return $this->like('customer_name', $search)
                    ->orLike('customer_code', $search)
                    ->orLike('email_id', $search)
                    ->orderBy('customer_name', 'ASC')
                    ->findAll();
    }

    public function getCustomersWithDues()
    {
        $builder = $this->db->table($this->table . ' c');
        $builder->select('c.*, COALESCE(SUM(i.due_amount), 0) as total_due');
        $builder->join('invoice i', 'i.customer_supplier_id = c.id AND i.invoice_type = 1', 'left');
        $builder->groupBy('c.id');
        $builder->orderBy('c.customer_name', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    public function getActiveCustomers()
    {
        return $this->where('ledger_id >', 0)
                    ->orderBy('customer_name', 'ASC')
                    ->findAll();
    }
}