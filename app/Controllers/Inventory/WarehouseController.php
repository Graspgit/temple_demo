<?php

namespace App\Controllers\Inventory;

use App\Controllers\MasterController;
use App\Models\Inventory\WarehouseModel;

class WarehouseController extends MasterController
{
    public function __construct()
    {
        $this->model = new WarehouseModel();
        $this->viewPath = 'inventory/warehouses';
        $this->moduleName = 'Warehouse';
        $this->routeBase = 'inventory/warehouses';
        $this->searchFields = ['name', 'code', 'type', 'address', 'contact_person'];
    }

    protected function getValidationRules($id = null)
    {
        $rules = [
            'name' => [
                'label' => 'Warehouse Name',
                'rules' => 'required|max_length[100]'
            ],
            'code' => [
                'label' => 'Warehouse Code',
                'rules' => $id ? 
                    "required|max_length[20]|is_unique[inv_warehouses.code,id,$id]" : 
                    'required|max_length[20]|is_unique[inv_warehouses.code]'
            ],
            'type' => [
                'label' => 'Warehouse Type',
                'rules' => 'required|in_list[main,kitchen,pooja,prasadam,general]'
            ],
            'location' => [
                'label' => 'Location',
                'rules' => 'permit_empty|max_length[100]'
            ],
            'address' => [
                'label' => 'Address',
                'rules' => 'permit_empty|max_length[255]'
            ],
            'contact_person' => [
                'label' => 'Contact Person',
                'rules' => 'permit_empty|max_length[100]'
            ],
            'contact_number' => [
                'label' => 'Contact Number',
                'rules' => 'permit_empty|max_length[20]'
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'permit_empty|valid_email|max_length[100]'
            ],
            'capacity_info' => [
                'label' => 'Capacity Information',
                'rules' => 'permit_empty'
            ],
            'is_default' => [
                'label' => 'Default Warehouse',
                'rules' => 'permit_empty|in_list[0,1]'
            ],
            'allow_negative_stock' => [
                'label' => 'Allow Negative Stock',
                'rules' => 'permit_empty|in_list[0,1]'
            ],
            'temperature_controlled' => [
                'label' => 'Temperature Controlled',
                'rules' => 'permit_empty|in_list[0,1]'
            ],
            'security_level' => [
                'label' => 'Security Level',
                'rules' => 'permit_empty|in_list[low,medium,high]'
            ]
        ];

        return $rules;
    }

    protected function prepareData(array $data)
    {
        // Convert checkbox values
        $data['is_default'] = isset($data['is_default']) && $data['is_default'] ? 1 : 0;
        $data['allow_negative_stock'] = isset($data['allow_negative_stock']) && $data['allow_negative_stock'] ? 1 : 0;
        $data['temperature_controlled'] = isset($data['temperature_controlled']) && $data['temperature_controlled'] ? 1 : 0;
        
        // Generate code if not provided
        if (empty($data['code']) && !empty($data['name'])) {
            $prefix = strtoupper(substr($data['type'], 0, 3));
            $data['code'] = $prefix . '-' . strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $data['name']), 0, 5));
        }
        
        // If setting as default, unset other defaults
        if ($data['is_default'] == 1) {
            $this->model->where('is_default', 1)->set(['is_default' => 0])->update();
        }

        return $data;
    }

    protected function canDelete($id)
    {
        $db = \Config\Database::connect();
        
        // Check if warehouse has stock
        $stockCount = $db->table('inv_stock')
            ->where('warehouse_id', $id)
            ->where('quantity !=', 0)
            ->countAllResults();
        
        if ($stockCount > 0) {
            return [
                'status' => false,
                'message' => "Cannot delete this warehouse. It contains stock for $stockCount product(s)."
            ];
        }
        
        // Check if warehouse is used in transactions
        $transactionCount = $db->table('inv_stock_transactions')
            ->groupStart()
                ->where('from_warehouse_id', $id)
                ->orWhere('to_warehouse_id', $id)
            ->groupEnd()
            ->countAllResults();
        
        if ($transactionCount > 0) {
            return [
                'status' => false,
                'message' => "Cannot delete this warehouse. It has $transactionCount transaction(s) associated with it."
            ];
        }
        
        // Check if it's the default warehouse
        $warehouse = $this->model->find($id);
        if ($warehouse && $warehouse['is_default'] == 1) {
            return [
                'status' => false,
                'message' => "Cannot delete the default warehouse. Please set another warehouse as default first."
            ];
        }
        
        return ['status' => true];
    }

    protected function getFormData($id = null)
    {
        $data = parent::getFormData($id);
        
        // Warehouse types specific to temple
        $data['warehouseTypes'] = [
            'main' => 'Main Store',
            'kitchen' => 'Kitchen Store',
            'pooja' => 'Pooja Items Store',
            'prasadam' => 'Prasadam Store',
            'general' => 'General Store'
        ];
        
        // Security levels
        $data['securityLevels'] = [
            'low' => 'Low Security',
            'medium' => 'Medium Security',
            'high' => 'High Security (Valuables)'
        ];
        
        return $data;
    }

    public function getByType($type = null)
    {
        if (!$type || !in_array($type, ['main', 'kitchen', 'pooja', 'prasadam', 'general'])) {
            return $this->response->setJSON(['error' => 'Invalid warehouse type'])->setStatusCode(400);
        }
        
        $warehouses = $this->model
            ->where('type', $type)
            ->where('is_active', 1)
            ->where('deleted_at', null)
            ->findAll();
        
        return $this->response->setJSON(['warehouses' => $warehouses]);
    }

    public function checkStock($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['error' => 'Warehouse ID required'])->setStatusCode(400);
        }
        
        $db = \Config\Database::connect();
        
        $stock = $db->table('inv_stock s')
            ->select('s.*, p.name as product_name, p.code as product_code, u.abbreviation as unit')
            ->join('inv_products p', 'p.id = s.product_id')
            ->join('inv_unit_of_measure u', 'u.id = p.unit_of_measure_id')
            ->where('s.warehouse_id', $id)
            ->where('s.quantity !=', 0)
            ->get()
            ->getResultArray();
        
        return $this->response->setJSON([
            'warehouse_id' => $id,
            'stock_count' => count($stock),
            'stock_items' => $stock
        ]);
    }
}