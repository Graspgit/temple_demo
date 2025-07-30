<?php

namespace App\Controllers\Inventory;

use App\Controllers\MasterController;
use App\Models\Inventory\ProductModel;

class ProductController extends MasterController
{
    public function __construct()
    {
        $this->model = new ProductModel();
        $this->viewPath = 'inventory/products';
        $this->moduleName = 'Product';
        $this->routeBase = 'inventory/products';
        $this->searchFields = ['name', 'code', 'barcode', 'description'];
    }

    protected function getValidationRules($id = null)
    {
        $rules = [
            'name' => [
                'label' => 'Product Name',
                'rules' => 'required|max_length[100]'
            ],
            'code' => [
                'label' => 'Product Code',
                'rules' => $id ? 
                    "required|max_length[50]|is_unique[inv_products.code,id,$id]" : 
                    'required|max_length[50]|is_unique[inv_products.code]'
            ],
            'barcode' => [
                'label' => 'Barcode',
                'rules' => $id ? 
                    "permit_empty|max_length[50]|is_unique[inv_products.barcode,id,$id]" : 
                    'permit_empty|max_length[50]|is_unique[inv_products.barcode]'
            ],
            'category_id' => [
                'label' => 'Category',
                'rules' => 'required|is_natural_no_zero'
            ],
            'unit_of_measure_id' => [
                'label' => 'Unit of Measure',
                'rules' => 'required|is_natural_no_zero'
            ],
            'description' => [
                'label' => 'Description',
                'rules' => 'permit_empty'
            ],
            'minimum_stock' => [
                'label' => 'Minimum Stock',
                'rules' => 'permit_empty|decimal|greater_than_equal_to[0]'
            ],
            'maximum_stock' => [
                'label' => 'Maximum Stock',
                'rules' => 'permit_empty|decimal|greater_than_equal_to[0]'
            ],
            'reorder_level' => [
                'label' => 'Reorder Level',
                'rules' => 'permit_empty|decimal|greater_than_equal_to[0]'
            ],
            'reorder_quantity' => [
                'label' => 'Reorder Quantity',
                'rules' => 'permit_empty|decimal|greater_than[0]'
            ],
            'purchase_price' => [
                'label' => 'Purchase Price',
                'rules' => 'permit_empty|decimal|greater_than_equal_to[0]'
            ],
            'selling_price' => [
                'label' => 'Selling Price',
                'rules' => 'permit_empty|decimal|greater_than_equal_to[0]'
            ],
            'is_stockable' => [
                'label' => 'Stockable',
                'rules' => 'permit_empty|in_list[0,1]'
            ],
            'is_perishable' => [
                'label' => 'Perishable',
                'rules' => 'permit_empty|in_list[0,1]'
            ],
            'expiry_days' => [
                'label' => 'Expiry Days',
                'rules' => 'permit_empty|is_natural'
            ],
            'is_donation_item' => [
                'label' => 'Donation Item',
                'rules' => 'permit_empty|in_list[0,1]'
            ],
            'is_prasadam_item' => [
                'label' => 'Prasadam Item',
                'rules' => 'permit_empty|in_list[0,1]'
            ],
            'is_pooja_item' => [
                'label' => 'Pooja Item',
                'rules' => 'permit_empty|in_list[0,1]'
            ],
            'default_supplier_id' => [
                'label' => 'Default Supplier',
                'rules' => 'permit_empty|is_natural_no_zero'
            ],
            'storage_conditions' => [
                'label' => 'Storage Conditions',
                'rules' => 'permit_empty|max_length[255]'
            ]
        ];

        return $rules;
    }

    protected function prepareData(array $data)
    {
        // Convert checkbox values
        $checkboxFields = ['is_stockable', 'is_perishable', 'is_donation_item', 
                          'is_prasadam_item', 'is_pooja_item'];
        
        foreach ($checkboxFields as $field) {
            $data[$field] = isset($data[$field]) && $data[$field] ? 1 : 0;
        }
        
        // Handle empty numeric values
        $numericFields = ['minimum_stock', 'maximum_stock', 'reorder_level', 
                         'reorder_quantity', 'purchase_price', 'selling_price', 
                         'expiry_days', 'default_supplier_id'];
        
        foreach ($numericFields as $field) {
            if (empty($data[$field])) {
                $data[$field] = null;
            }
        }
        
        // If perishable is unchecked, clear expiry days
        if ($data['is_perishable'] == 0) {
            $data['expiry_days'] = null;
        }
        
        // Generate code if not provided
        if (empty($data['code']) && !empty($data['name'])) {
            $categoryCode = 'PRD'; // Default
            if (!empty($data['category_id'])) {
                $category = $this->db->table('inv_product_categories')
                    ->where('id', $data['category_id'])
                    ->get()->getRowArray();
                if ($category && !empty($category['code'])) {
                    $categoryCode = strtoupper(substr($category['code'], 0, 3));
                }
            }
            $data['code'] = $this->generateUniqueCode($categoryCode);
        }

        return $data;
    }

    private function generateUniqueCode($prefix)
    {
        $lastProduct = $this->model
            ->where('code LIKE', $prefix . '%')
            ->orderBy('id', 'DESC')
            ->first();
        
        if ($lastProduct) {
            preg_match('/(\d+)$/', $lastProduct['code'], $matches);
            $nextNumber = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    protected function canDelete($id)
    {
        $db = \Config\Database::connect();
        
        // Check if product has stock
        $stockCount = $db->table('inv_stock')
            ->where('product_id', $id)
            ->where('quantity !=', 0)
            ->countAllResults();
        
        if ($stockCount > 0) {
            return [
                'status' => false,
                'message' => "Cannot delete this product. It has stock in $stockCount warehouse(s)."
            ];
        }
        
        // Check if product is used in transactions
        $transactionCount = $db->table('inv_stock_transaction_details')
            ->where('product_id', $id)
            ->countAllResults();
        
        if ($transactionCount > 0) {
            return [
                'status' => false,
                'message' => "Cannot delete this product. It has been used in $transactionCount transaction(s)."
            ];
        }
        
        // Check if product is linked to archanai or prasadam
        $archanaiCount = $db->table('archanai_products')
            ->where('product_id', $id)
            ->countAllResults();
        
        if ($archanaiCount > 0) {
            return [
                'status' => false,
                'message' => "Cannot delete this product. It is linked to archanai services."
            ];
        }
        
        return ['status' => true];
    }

    protected function getFormData($id = null)
    {
        $data = parent::getFormData($id);
        
        // Get categories for dropdown
        $data['categories'] = $this->db->table('inv_product_categories')
            ->where('deleted_at', null)
            ->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();
        
        // Get units of measure
        $data['units'] = $this->db->table('inv_unit_of_measure')
            ->where('deleted_at', null)
            ->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();
        
        // Get suppliers
        $data['suppliers'] = $this->db->table('supplier')
            ->where('status', 'Active')
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();
        
        return $data;
    }

    protected function modifyDatatablesQuery($builder)
    {
        // Join with related tables
        $builder->select('inv_products.*, 
                         c.name as category_name, 
                         u.abbreviation as unit_abbr,
                         s.name as supplier_name')
                ->join('inv_product_categories c', 'c.id = inv_products.category_id')
                ->join('inv_unit_of_measure u', 'u.id = inv_products.unit_of_measure_id')
                ->join('supplier s', 's.id = inv_products.default_supplier_id', 'left');
        
        return $builder;
    }

    public function checkStock($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['error' => 'Product ID required'])->setStatusCode(400);
        }
        
        $db = \Config\Database::connect();
        
        $stock = $db->table('inv_stock s')
            ->select('s.*, w.name as warehouse_name, w.type as warehouse_type')
            ->join('inv_warehouses w', 'w.id = s.warehouse_id')
            ->where('s.product_id', $id)
            ->where('w.deleted_at', null)
            ->get()
            ->getResultArray();
        
        $totalStock = array_sum(array_column($stock, 'quantity'));
        
        return $this->response->setJSON([
            'product_id' => $id,
            'total_stock' => $totalStock,
            'warehouses' => $stock
        ]);
    }

    public function importTemplate()
    {
        // Generate CSV template for bulk import
        $headers = [
            'Product Name*', 'Product Code*', 'Barcode', 'Category Code*', 
            'Unit Abbreviation*', 'Description', 'Minimum Stock', 'Maximum Stock',
            'Reorder Level', 'Reorder Quantity', 'Purchase Price', 'Selling Price',
            'Is Stockable (Y/N)', 'Is Perishable (Y/N)', 'Expiry Days',
            'Is Donation Item (Y/N)', 'Is Prasadam Item (Y/N)', 'Is Pooja Item (Y/N)',
            'Supplier Code', 'Storage Conditions'
        ];
        
        $filename = 'product_import_template_' . date('Ymd') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);
        
        // Add sample row
        $sample = [
            'Camphor', 'POOJ-0001', '1234567890', 'POOJ', 'pkt', 
            'Camphor for aarti', '10', '100', '20', '50', '25.00', '30.00',
            'Y', 'N', '', 'N', 'N', 'Y', 'SUP001', 'Store in cool, dry place'
        ];
        fputcsv($output, $sample);
        
        fclose($output);
        exit;
    }
}