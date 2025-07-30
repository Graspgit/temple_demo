<?php
namespace App\Models\Inventory;

class ProductCategoryModel extends BaseInventoryModel
{
    protected $table = 'product_categories';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'category_code', 'category_name', 'parent_category_id', 
        'description', 'is_active', 'created_by', 'updated_by'
    ];
    
    protected $codeField = 'category_code';
    protected $nameField = 'category_name';
    
    protected $validationRules = [
        'category_code' => [
            'label' => 'Category Code',
            'rules' => 'required|min_length[2]|max_length[20]|regex_match[/^[A-Z0-9_-]+$/]',
            'errors' => [
                'regex_match' => 'Category Code must contain only uppercase letters, numbers, underscore and hyphen.'
            ]
        ],
        'category_name' => [
            'label' => 'Category Name',
            'rules' => 'required|min_length[3]|max_length[100]'
        ],
        'parent_category_id' => [
            'label' => 'Parent Category',
            'rules' => 'permit_empty|numeric|is_not_unique[product_categories.id]'
        ]
    ];
    
    /**
     * Get categories with parent name
     */
    public function getCategoriesWithParent()
    {
        return $this->select('product_categories.*, parent.category_name as parent_name')
                    ->join('product_categories parent', 'parent.id = product_categories.parent_category_id', 'left')
                    ->findAll();
    }
    
    /**
     * Get category tree structure
     */
    public function getCategoryTree($parentId = null)
    {
        $categories = $this->where('parent_category_id', $parentId)
                          ->where('is_active', 1)
                          ->orderBy('category_name', 'ASC')
                          ->findAll();
        
        foreach ($categories as &$category) {
            $category['children'] = $this->getCategoryTree($category['id']);
        }
        
        return $categories;
    }
    
    /**
     * Check if category has products
     */
    public function hasProducts($categoryId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('products');
        return $builder->where('category_id', $categoryId)->countAllResults() > 0;
    }
    
    /**
     * Get subcategories
     */
    public function getSubcategories($parentId)
    {
        return $this->where('parent_category_id', $parentId)
                    ->where('is_active', 1)
                    ->findAll();
    }
}