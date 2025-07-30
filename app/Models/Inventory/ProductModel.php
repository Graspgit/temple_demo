<?php
namespace App\Models\Inventory;

class ProductModel extends BaseInventoryModel
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'product_code', 'product_name', 'category_id', 'uom_id',
        'product_type', 'is_perishable', 'shelf_life_days',
        'min_stock_qty', 'max_stock_qty', 'reorder_level', 'reorder_qty',
        'barcode', 'hsn_code', 'gst_rate', 'purchase_price', 'selling_price',
        'is_donation_item', 'is_prasadam_item', 'is_pooja_item',
        'is_active', 'created_by', 'updated_by'
    ];
    
    protected $codeField = 'product_code';
    protected $nameField = 'product_name';
    
    protected $validationRules = [
        'product_code' => [
            'label' => 'Product Code',
            'rules' => 'required|min_length[3]|max_length[50]'
        ],
        'product_name' => [
            'label' => 'Product Name',
            'rules' => 'required|min_length[3]|max_length[200]'
        ],
        'category_id' => [
            'label' => 'Category',
            'rules' => 'required|numeric|is_not_unique[product_categories.id]'
        ],
        'uom_id' => [
            'label' => 'Unit of Measure',
            'rules' => 'required|numeric|is_not_unique[units_of_measure.id]'
        ],
        'product_type' => [
            'label' => 'Product Type',
            'rules' => 'required|in_list[consumable,non_consumable,service]'
        ],
        'shelf_life_days' => [
            'label' => 'Shelf Life',
            'rules' => 'permit_empty|integer|greater_than[0]'
        ],
        'min_stock_qty' => [
            'label' => 'Minimum Stock',
            'rules' => 'required|decimal|greater_than_equal_to[0]'
        ],
        'max_stock_qty' => [
            'label' => 'Maximum Stock',
            'rules' => 'permit_empty|decimal|greater_than[{min_stock_qty}]'
        ],
        'reorder_level' => [
            'label' => 'Reorder Level',
            'rules' => 'required|decimal|greater_than_equal_to[0]'
        ],
        'gst_rate' => [
            'label' => 'GST Rate',
            'rules' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[100]'
        ],
        'purchase_price' => [
            'label' => 'Purchase Price',
            'rules' => 'required|decimal|greater_than_equal_to[0]'
        ],
        'selling_price' => [
            'label' => 'Selling Price',
            'rules' => 'required|decimal|greater_than_equal_to[0]'
        ]
    ];
    
    /**
     * Get products with related data
     */
    public function getProductsWithDetails()
    {
        return $this->select('products.*, pc.category_name, uom.uom_name, uom.uom_code')
                    ->join('product_categories pc', 'pc.id = products.category_id')
                    ->join('units_of_measure uom', 'uom.id = products.uom_id')
                    ->findAll();
    }
    
    /**
     * Get products by category
     */
    public function getByCategory($categoryId)
    {
        return $this->where('category_id', $categoryId)
                    ->where('is_active', 1)
                    ->findAll();
    }
    
    /**
     * Get temple specific products
     */
    public function getTempleProducts($type = 'all')
    {
        $builder = $this->builder();
        
        switch($type) {
            case 'donation':
                $builder->where('is_donation_item', 1);
                break;
            case 'prasadam':
                $builder->where('is_prasadam_item', 1);
                break;
            case 'pooja':
                $builder->where('is_pooja_item', 1);
                break;
        }
        
        return $builder->where('is_active', 1)->get()->getResultArray();
    }
    
    /**
     * Get perishable products
     */
    public function getPerishableProducts()
    {
        return $this->where('is_perishable', 1)
                    ->where('is_active', 1)
                    ->findAll();
    }
    
    /**
     * Search products
     */
    public function searchProducts($term)
    {
        return $this->groupStart()
                        ->like('product_code', $term)
                        ->orLike('product_name', $term)
                        ->orLike('barcode', $term)
                    ->groupEnd()
                    ->where('is_active', 1)
                    ->limit(20)
                    ->findAll();
    }
    
    /**
     * Get low stock products
     */
    public function getLowStockProducts($warehouseId = null)
    {
        $sql = "SELECT p.*, pc.category_name, uom.uom_name, 
                COALESCE(s.quantity, 0) as current_stock,
                CASE WHEN pw.reorder_level IS NOT NULL 
                     THEN pw.reorder_level 
                     ELSE p.reorder_level 
                END as effective_reorder_level
                FROM products p
                JOIN product_categories pc ON pc.id = p.category_id
                JOIN units_of_measure uom ON uom.id = p.uom_id
                LEFT JOIN inventory_stock s ON s.product_id = p.id";
        
        if ($warehouseId) {
            $sql .= " AND s.warehouse_id = " . intval($warehouseId);
        }
        
        $sql .= " LEFT JOIN product_warehouse_settings pw ON pw.product_id = p.id";
        
        if ($warehouseId) {
            $sql .= " AND pw.warehouse_id = " . intval($warehouseId);
        }
        
        $sql .= " WHERE p.is_active = 1 
                  AND COALESCE(s.quantity, 0) <= CASE WHEN pw.reorder_level IS NOT NULL 
                                                      THEN pw.reorder_level 
                                                      ELSE p.reorder_level 
                                                  END";
        
        return $this->db->query($sql)->getResultArray();
    }
}