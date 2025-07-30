<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'id';
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
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'supplier_name' => 'required|min_length[3]|max_length[255]',
        'supplier_code' => 'permit_empty|max_length[255]|is_unique[supplier.supplier_code,id,{id}]',
        'email_id' => 'permit_empty|valid_email|max_length[100]',
        'mobile_no' => 'permit_empty|max_length[20]',
        'phone' => 'permit_empty|max_length[20]'
    ];
    
    protected $validationMessages = [
        'supplier_name' => [
            'required' => 'Supplier name is required',
            'min_length' => 'Supplier name must be at least 3 characters',
            'max_length' => 'Supplier name cannot exceed 255 characters'
        ],
        'supplier_code' => [
            'is_unique' => 'Supplier code already exists'
        ],
        'email_id' => [
            'valid_email' => 'Please enter a valid email address'
        ]
    ];

    public function getAllSuppliers()
    {
        return $this->orderBy('supplier_name', 'ASC')->findAll();
    }

    public function getSupplierById($id)
    {
        return $this->find($id);
    }

    public function getSupplierByCode($code)
    {
        return $this->where('supplier_code', $code)->first();
    }

    public function searchSuppliers($search)
    {
        return $this->like('supplier_name', $search)
                    ->orLike('supplier_code', $search)
                    ->orLike('email_id', $search)
                    ->orLike('contact_person', $search)
                    ->orderBy('supplier_name', 'ASC')
                    ->findAll();
    }

    public function getSuppliersWithDues()
    {
        $builder = $this->db->table($this->table . ' s');
        $builder->select('s.*, COALESCE(SUM(i.due_amount), 0) as total_due');
        $builder->join('invoice i', 'i.customer_supplier_id = s.id AND i.invoice_type = 2', 'left');
        $builder->groupBy('s.id');
        $builder->orderBy('s.supplier_name', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    public function getActiveSuppliers()
    {
        return $this->where('ledger_id >', 0)
                    ->orderBy('supplier_name', 'ASC')
                    ->findAll();
    }
}