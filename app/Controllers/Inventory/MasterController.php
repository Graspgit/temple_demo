<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

abstract class MasterController extends BaseController
{
    /**
     * Model instance
     */
    protected $model;
    
    /**
     * View path for the module
     */
    protected $viewPath;
    
    /**
     * Module name (singular)
     */
    protected $moduleName;
    
    /**
     * Module name (plural)
     */
    protected $moduleNamePlural;
    
    /**
     * Route base path
     */
    protected $routeBase;
    
    /**
     * Primary key field name
     */
    protected $primaryKey = 'id';
    
    /**
     * Fields to search in
     */
    protected $searchFields = [];
    
    /**
     * User ID for audit trail
     */
    protected $userId;
    
    public function __construct()
    {
        // Set user ID for audit trail
        $this->userId = session()->get('user_id');
    }
    
    /**
     * Display a listing of the resource
     */
    public function index()
    {
        $data = [
            'title' => $this->moduleNamePlural,
            'module' => [
                'name' => $this->moduleName,
                'plural' => $this->moduleNamePlural,
                'route' => $this->routeBase
            ]
        ];
        
        return view($this->viewPath . '/index', $data);
    }
    
    /**
     * Return data for DataTables
     */
    public function datatables()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }
        
        $draw = $this->request->getPost('draw');
        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $search = $this->request->getPost('search')['value'] ?? '';
        $order = $this->request->getPost('order')[0] ?? null;
        
        // Build query
        $builder = $this->model->builder();
        
        // Add custom query modifications
        $this->modifyDatatablesQuery($builder);
        
        // Apply search
        if (!empty($search) && !empty($this->searchFields)) {
            $builder->groupStart();
            foreach ($this->searchFields as $field) {
                $builder->orLike($field, $search);
            }
            $builder->groupEnd();
        }
        
        // Get total count
        $totalRecords = $builder->countAllResults(false);
        
        // Apply ordering
        if ($order) {
            $columns = $this->request->getPost('columns');
            $orderColumn = $columns[$order['column']]['data'] ?? $this->primaryKey;
            $orderDir = $order['dir'] ?? 'asc';
            $builder->orderBy($orderColumn, $orderDir);
        }
        
        // Apply pagination
        $builder->limit($length, $start);
        
        // Get data
        $data = $builder->get()->getResultArray();
        
        // Process data before sending
        $data = $this->processDatatablesData($data);
        
        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }
    
    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        if ($this->request->isAJAX()) {
            $data = $this->getFormData();
            return view($this->viewPath . '/form', $data);
        }
        
        $data = [
            'title' => 'Add ' . $this->moduleName,
            'action' => base_url($this->routeBase . '/store'),
            'module' => [
                'name' => $this->moduleName,
                'plural' => $this->moduleNamePlural,
                'route' => $this->routeBase
            ]
        ];
        
        $data = array_merge($data, $this->getFormData());
        
        return view($this->viewPath . '/create', $data);
    }
    
    /**
     * Store a newly created resource
     */
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to(base_url($this->routeBase));
        }
        
        // Get validation rules
        $rules = $this->getValidationRules();
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }
        
        // Prepare data
        $data = $this->prepareData($this->request->getPost());
        
        // Add audit fields
        $data['created_by'] = $this->userId;
        $data['created_at'] = date('Y-m-d H:i:s');
        
        try {
            if ($this->model->insert($data)) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => $this->moduleName . ' created successfully'
                ]);
            }
            
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to create ' . $this->moduleName
            ]);
            
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'An error occurred while creating ' . $this->moduleName
            ]);
        }
    }
    
    /**
     * Display the specified resource
     */
    public function view($id = null)
    {
        $record = $this->model->find($id);
        
        if (!$record) {
            return redirect()->to(base_url($this->routeBase))
                           ->with('error', $this->moduleName . ' not found');
        }
        
        $data = [
            'title' => $this->moduleName . ' Details',
            'record' => $record,
            'module' => [
                'name' => $this->moduleName,
                'plural' => $this->moduleNamePlural,
                'route' => $this->routeBase
            ]
        ];
        
        // Get additional view data
        $data = array_merge($data, $this->getViewData($record));
        
        return view($this->viewPath . '/view', $data);
    }
    
    /**
     * Show the form for editing the specified resource
     */
    public function edit($id = null)
    {
        if ($this->request->isAJAX()) {
            $record = $this->model->find($id);
            
            if (!$record) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => $this->moduleName . ' not found'
                ]);
            }
            
            $data = array_merge(
                ['record' => $record],
                $this->getFormData($record)
            );
            
            return view($this->viewPath . '/form', $data);
        }
        
        return redirect()->to(base_url($this->routeBase));
    }
    
    /**
     * Update the specified resource
     */
    public function update($id = null)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to(base_url($this->routeBase));
        }
        
        $record = $this->model->find($id);
        
        if (!$record) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $this->moduleName . ' not found'
            ]);
        }
        
        // Get validation rules
        $rules = $this->getValidationRules($id);
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }
        
        // Prepare data
        $data = $this->prepareData($this->request->getPost(), $id);
        
        // Add audit fields
        $data['updated_by'] = $this->userId;
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        try {
            if ($this->model->update($id, $data)) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => $this->moduleName . ' updated successfully'
                ]);
            }
            
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to update ' . $this->moduleName
            ]);
            
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'An error occurred while updating ' . $this->moduleName
            ]);
        }
    }
    
    /**
     * Remove the specified resource
     */
    public function delete($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }
        
        $record = $this->model->find($id);
        
        if (!$record) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $this->moduleName . ' not found'
            ]);
        }
        
        // Check if record can be deleted
        if (!$this->canDelete($id)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Cannot delete this ' . $this->moduleName . '. It is being used in other transactions.'
            ]);
        }
        
        try {
            if ($this->model->delete($id)) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => $this->moduleName . ' deleted successfully'
                ]);
            }
            
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to delete ' . $this->moduleName
            ]);
            
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'An error occurred while deleting ' . $this->moduleName
            ]);
        }
    }
    
    /**
     * Toggle active status
     */
    public function toggle($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }
        
        $record = $this->model->find($id);
        
        if (!$record) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $this->moduleName . ' not found'
            ]);
        }
        
        $newStatus = $record['is_active'] == 1 ? 0 : 1;
        
        try {
            if ($this->model->update($id, ['is_active' => $newStatus])) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => $this->moduleName . ' status updated successfully',
                    'new_status' => $newStatus
                ]);
            }
            
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to update status'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'An error occurred while updating status'
            ]);
        }
    }
    
    /**
     * Export data to Excel/CSV
     */
    public function export()
    {
        $format = $this->request->getGet('format') ?? 'csv';
        $filename = strtolower(str_replace(' ', '_', $this->moduleNamePlural)) . '_' . date('Y-m-d');
        
        // Get filtered data
        $builder = $this->model->builder();
        $this->applyExportFilters($builder);
        $data = $builder->get()->getResultArray();
        
        if ($format == 'csv') {
            return $this->exportCSV($data, $filename);
        }
        
        // Future: Add Excel export using PHPSpreadsheet
        return $this->exportCSV($data, $filename);
    }
    
    /**
     * Export data as CSV
     */
    protected function exportCSV($data, $filename)
    {
        // Set headers
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        
        // Create file pointer
        $output = fopen('php://output', 'w');
        
        // Get export columns
        $columns = $this->getExportColumns();
        
        // Add headers
        fputcsv($output, array_values($columns));
        
        // Add data
        foreach ($data as $row) {
            $exportRow = [];
            foreach ($columns as $field => $label) {
                $exportRow[] = $row[$field] ?? '';
            }
            fputcsv($output, $exportRow);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Search for autocomplete
     */
    public function search()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }
        
        $term = $this->request->getGet('term');
        
        if (empty($term)) {
            return $this->response->setJSON([]);
        }
        
        $results = $this->model->search($term);
        $formatted = $this->formatSearchResults($results);
        
        return $this->response->setJSON($formatted);
    }
    
    // Abstract methods to be implemented by child classes
    
    /**
     * Get validation rules
     */
    abstract protected function getValidationRules($id = null): array;
    
    /**
     * Prepare data for insert/update
     */
    abstract protected function prepareData(array $postData, $id = null): array;
    
    // Optional methods to override
    
    /**
     * Get additional form data
     */
    protected function getFormData($record = null): array
    {
        return [];
    }
    
    /**
     * Get additional view data
     */
    protected function getViewData($record): array
    {
        return [];
    }
    
    /**
     * Check if record can be deleted
     */
    protected function canDelete($id): bool
    {
        return true;
    }
    
    /**
     * Modify datatables query
     */
    protected function modifyDatatablesQuery($builder): void
    {
        // Override to add joins, where conditions, etc.
    }
    
    /**
     * Process datatables data before sending
     */
    protected function processDatatablesData(array $data): array
    {
        return $data;
    }
    
    /**
     * Apply filters for export
     */
    protected function applyExportFilters($builder): void
    {
        // Override to add export filters
    }
    
    /**
     * Get export columns
     */
    protected function getExportColumns(): array
    {
        // Default: use allowed fields
        $fields = $this->model->allowedFields ?? [];
        $columns = [];
        
        foreach ($fields as $field) {
            $columns[$field] = ucwords(str_replace('_', ' ', $field));
        }
        
        return $columns;
    }
    
    /**
     * Format search results
     */
    protected function formatSearchResults(array $results): array
    {
        return $results;
    }
}