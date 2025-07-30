<?php
namespace App\Controllers\Inventory;

use App\Controllers\BaseController;

abstract class BaseInventoryController extends BaseController
{
    protected $model;
    protected $viewPath;
    protected $entityName;
    protected $entityNamePlural;
    
    /**
     * Common index method
     */
    public function index()
    {
        $data = [
            'title' => $this->entityNamePlural,
            'records' => $this->model->getActive()
        ];
        
        return view($this->viewPath . '/index', $data);
    }
    
    /**
     * Common datatables method
     */
    public function datatables()
    {
        if ($this->request->isAJAX()) {
            $draw = $this->request->getPost('draw');
            $start = $this->request->getPost('start');
            $length = $this->request->getPost('length');
            $search = $this->request->getPost('search')['value'];
            
            $page = ($start / $length) + 1;
            $filters = [];
            
            if (!empty($search)) {
                $searchFields = $this->getSearchFields();
                foreach ($searchFields as $field) {
                    $filters[$field] = $search;
                }
            }
            
            $result = $this->model->getPaginated($length, $page, $filters);
            
            return $this->response->setJSON([
                'draw' => $draw,
                'recordsTotal' => $result['total'],
                'recordsFiltered' => $result['total'],
                'data' => $result['data']
            ]);
        }
    }
    
    /**
     * Get search fields for datatables
     */
    abstract protected function getSearchFields();
    
    /**
     * Common create method
     */
    public function create()
    {
        if ($this->request->isAJAX()) {
            $data = $this->getFormData();
            return view($this->viewPath . '/form', $data);
        }
    }
    
    /**
     * Get form data
     */
    abstract protected function getFormData($record = null);
    
    /**
     * Common store method
     */
    public function store()
    {
        if ($this->request->isAJAX()) {
            $rules = $this->getValidationRules();
            
            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }
            
            $data = $this->prepareData();
            
            if ($this->model->insert($data)) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => $this->entityName . ' created successfully'
                ]);
            }
            
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to create ' . $this->entityName
            ]);
        }
    }
    
    /**
     * Get validation rules
     */
    abstract protected function getValidationRules($id = null);
    
    /**
     * Prepare data for insert/update
     */
    abstract protected function prepareData();
    
    /**
     * Common edit method
     */
    public function edit($id)
    {
        if ($this->request->isAJAX()) {
            $record = $this->model->find($id);
            
            if (!$record) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => $this->entityName . ' not found'
                ]);
            }
            
            $data = $this->getFormData($record);
            $data['record'] = $record;
            
            return view($this->viewPath . '/form', $data);
        }
    }
    
    /**
     * Common update method
     */
    public function update($id)
    {
        if ($this->request->isAJAX()) {
            $record = $this->model->find($id);
            
            if (!$record) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => $this->entityName . ' not found'
                ]);
            }
            
            $rules = $this->getValidationRules($id);
            
            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }
            
            $data = $this->prepareData();
            
            if ($this->model->update($id, $data)) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => $this->entityName . ' updated successfully'
                ]);
            }
            
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to update ' . $this->entityName
            ]);
        }
    }
    
    /**
     * Common delete method
     */
    public function delete($id)
    {
        if ($this->request->isAJAX()) {
            $record = $this->model->find($id);
            
            if (!$record) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => $this->entityName . ' not found'
                ]);
            }
            
            if ($this->canDelete($id)) {
                if ($this->model->delete($id)) {
                    return $this->response->setJSON([
                        'status' => true,
                        'message' => $this->entityName . ' deleted successfully'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Cannot delete this ' . $this->entityName . '. It is being used.'
                ]);
            }
            
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to delete ' . $this->entityName
            ]);
        }
    }
    
    /**
     * Check if record can be deleted
     */
    protected function canDelete($id)
    {
        return true;
    }
    
    /**
     * Common toggle status method
     */
    public function toggle($id)
    {
        if ($this->request->isAJAX()) {
            $record = $this->model->find($id);
            
            if (!$record) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => $this->entityName . ' not found'
                ]);
            }
            
            $newStatus = $record['is_active'] == 1 ? 0 : 1;
            
            if ($this->model->update($id, ['is_active' => $newStatus])) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => $this->entityName . ' status updated successfully'
                ]);
            }
            
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to update ' . $this->entityName . ' status'
            ]);
        }
    }
    
    /**
     * Export to Excel
     */
    public function export()
    {
        // Implementation for Excel export
        // This would use PHPSpreadsheet or similar library
    }
}