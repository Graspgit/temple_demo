<?php
namespace App\Models\Inventory;

class WarehouseModel extends BaseInventoryModel
{
    protected $table = 'warehouses';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'warehouse_code', 'warehouse_name', 'warehouse_type', 
        'address', 'contact_person', 'contact_phone', 
        'is_donation_store', 'is_active', 'created_by', 'updated_by'
    ];
    
    protected $codeField = 'warehouse_code';
    protected $nameField = 'warehouse_name';
    
    protected $validationRules = [
        'warehouse_code' => [
            'label' => 'Warehouse Code',
            'rules' => 'required|min_length[3]|max_length[20]|regex_match[/^[A-Z0-9_-]+$/]',
            'errors' => [
                'regex_match' => 'Warehouse Code must contain only uppercase letters, numbers, underscore and hyphen.'
            ]
        ],
        'warehouse_name' => [
            'label' => 'Warehouse Name',
            'rules' => 'required|min_length[3]|max_length[100]'
        ],
        'warehouse_type' => [
            'label' => 'Warehouse Type',
            'rules' => 'required|in_list[main,kitchen,pooja,prasadam,general]'
        ],
        'contact_phone' => [
            'label' => 'Contact Phone',
            'rules' => 'permit_empty|regex_match[/^[0-9+\-\s()]+$/]|max_length[20]'
        ]
    ];
    
    /**
     * Get warehouses by type
     */
    public function getByType($type)
    {
        return $this->where('warehouse_type', $type)
                    ->where('is_active', 1)
                    ->findAll();
    }
    
    /**
     * Get donation warehouses
     */
    public function getDonationWarehouses()
    {
        return $this->where('is_donation_store', 1)
                    ->where('is_active', 1)
                    ->findAll();
    }
    
    /**
     * Check if warehouse has stock
     */
    public function hasStock($warehouseId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory_stock');
        return $builder->where('warehouse_id', $warehouseId)
                      ->where('quantity >', 0)
                      ->countAllResults() > 0;
    }
}