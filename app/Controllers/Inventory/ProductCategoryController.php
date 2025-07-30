<?php
namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use App\Models\Inventory\ProductCategoryModel;

class ProductCategoryController extends BaseController
{
    protected $categoryModel;
    
    public function __construct()
    {
        $this->categoryModel = new ProductCategoryModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Product Categories',
            'categories' => $this->categoryModel->getCategoriesWithParent()
        ];
        
        return view('inventory/categories/index', $data);
    }
    
    public function datatables()
    {
        if ($this->request->isAJAX()) {
            $draw = $this->request->getPost('draw');
            $start = $this->request->getPost('start');
            $length = $this->request->getPost('length');
            $search = $this->request->getPost('search')['value'];
            
            $builder = $this->categoryModel->builder();
            $builder->select('product_categories.*, parent.category_name as parent_name')
                    ->join('product_categories parent', 'parent.id = product_categories.parent_category_id', 'left');
            
            // Search
            if (!empty($search)) {
                $builder->groupStart()
                        ->like('product_categories.category_code', $search)
                        ->orLike('product_categories.category_name', $search)
                        ->groupEnd();
            }
            
            // Count total
            $total = $builder->countAllResults(false);
            
            // Get data
            $data = $builder->limit($length, $start)
                           ->orderBy('product_categories.created_at', 'DESC')
                           ->get()
                           ->getResultArray();
            
            return $this->response->setJSON([
                'draw' => $draw,
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data
            ]);
        }
    }
    
    public function create()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'categories' => $this->categoryModel->getDropdownOptions()
            ];
            return view('inventory/categories/form', $data);
        }
    }
    
    public function store()
    {
        if ($this->request->isAJAX()) {
            $rules = $this->categoryModel->getValidationRules();
            $rules['category_code']['rules'] .= '|is_unique[product_categories.category_code]';
            
            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }
            
            $data = [
                'category_code' => strtoupper($this->request->getPost('category_code')),
                'category_name' => $this->request->getPost('category_name'),
                'parent_category_id' => $this->request->getPost('parent_category_id') ?: null,
                'description' => $this->request->getPost('description'),
                'is_active' => $this->request->getPost('is_active') ?? 1
            ];
            
            if ($this->categoryModel->insert($data)) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Category created successfully'
                ]);
            }
            
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to create category'
            ]);
        }
    }
    
    public function edit($id)
    {
        if ($this->request->isAJAX()) {
            $category = $this->categoryModel->find($id);
            
            if (!$category) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Category not found'
                ]);
            }
            
            $data = [
                'category' => $category,
                'categories' => $this->categoryModel->getDropdownOptions()
            ];
            
            return view('inventory/categories/form', $data);
        }
    }
    
    public function update($id)
    {
        if ($this->request->isAJAX()) {
            $category = $this->categoryModel->find($id);
            
            if (!$category) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Category not found'
                ]);
            }
            
            $rules = $this->categoryModel->getValidationRules();
            $rules['category_code']['rules'] .= '|is_unique[product_categories.category_code,id,'.$id.']';
            
            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }
            
            $data = [
                'category_code' => strtoupper($this->request->getPost('category_code')),
                'category_name' => $this->request->getPost('category_name'),
                'parent_category_id' => $this->request->getPost('parent_category_id') ?: null,
                'description' => $this->request->getPost('description'),
                'is_active' => $this->request->getPost('is_active') ?? 1
            ];
            
            if ($this->categoryModel->update($id, $data)) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Category updated successfully'
                ]);
            }
            
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to update category'
            ]);
        }
    }
    
    public function delete($id)
    {
        if ($this->request->isAJAX()) {
            $category = $this->categoryModel->find($id);
            
            if (!$category) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Category not found'
                ]);
            }
            
            // Check if category has products
            if ($this->categoryModel->hasProducts($id)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Cannot delete category with products'
                ]);
            }
            
            // Check if category has subcategories
            $subcategories = $this->categoryModel->getSubcategories($id);
            if (!empty($subcategories)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Cannot delete category with subcategories'
                ]);
            }
            
            if ($this->categoryModel->delete($id)) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Category deleted successfully'
                ]);
            }
            
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to delete category'
            ]);
        }
    }
    
    public function toggle($id)
    {
        if ($this->request->isAJAX()) {
            $category = $this->categoryModel->find($id);
            
            if (!$category) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Category not found'
                ]);
            }
            
            $newStatus = $category['is_active'] == 1 ? 0 : 1;
            
            if ($this->categoryModel->update($id, ['is_active' => $newStatus])) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Category status updated successfully'
                ]);
            }
            
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to update category status'
            ]);
        }
    }
    
    public function getSubcategories($parentId)
    {
        if ($this->request->isAJAX()) {
            $subcategories = $this->categoryModel->getSubcategories($parentId);
            
            return $this->response->setJSON([
                'status' => true,
                'data' => $subcategories
            ]);
        }
    }
}