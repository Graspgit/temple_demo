<?php

namespace App\Models\Inventory;

use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class BaseInventoryModel extends Model
{
    protected $DBGroup = 'default';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Custom fields for tracking user actions
    protected $createdByField = 'created_by';
    protected $updatedByField = 'updated_by';
    
    // Common validation messages
    protected $validationMessages = [
        'required' => '{field} is required.',
        'is_unique' => '{field} already exists.',
        'min_length' => '{field} must be at least {param} characters.',
        'max_length' => '{field} cannot exceed {param} characters.',
        'numeric' => '{field} must be numeric.',
        'decimal' => '{field} must be a valid decimal number.',
        'valid_email' => '{field} must be a valid email address.',
        'regex_match' => '{field} format is invalid.'
    ];
    
    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
        
        // Set user ID for created_by and updated_by
        if (session()->has('user_id')) {
            $this->setUserId(session()->get('user_id'));
        }
    }
    
    /**
     * Set user ID for tracking
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }
    
    /**
     * Override insert to add created_by
     */
    public function insert($data = null, bool $returnID = true)
    {
        if (!empty($this->createdByField) && !isset($data[$this->createdByField])) {
            $data[$this->createdByField] = $this->userId ?? null;
        }
        
        return parent::insert($data, $returnID);
    }
    
    /**
     * Override update to add updated_by
     */
    public function update($id = null, $data = null): bool
    {
        if (!empty($this->updatedByField) && !isset($data[$this->updatedByField])) {
            $data[$this->updatedByField] = $this->userId ?? null;
        }
        
        return parent::update($id, $data);
    }
    
    /**
     * Get active records only
     */
    public function getActive()
    {
        return $this->where('is_active', 1)->findAll();
    }
    
    /**
     * Get records with pagination
     */
    public function getPaginated($perPage = 10, $page = 1, $filters = [])
    {
        $builder = $this->builder();
        
        // Apply filters
        foreach ($filters as $field => $value) {
            if (!empty($value)) {
                if (is_array($value)) {
                    $builder->whereIn($field, $value);
                } else {
                    $builder->like($field, $value);
                }
            }
        }
        
        // Get total count
        $total = $builder->countAllResults(false);
        
        // Get paginated results
        $data = $builder->limit($perPage, ($page - 1) * $perPage)
                       ->get()
                       ->getResultArray();
        
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage)
        ];
    }
    
    /**
     * Check if code exists (for unique validation)
     */
    public function isUniqueCode($code, $id = null)
    {
        $builder = $this->builder();
        
        if (isset($this->codeField)) {
            $builder->where($this->codeField, $code);
            
            if ($id !== null) {
                $builder->where($this->primaryKey . ' !=', $id);
            }
            
            return $builder->countAllResults() === 0;
        }
        
        return true;
    }
    
    /**
     * Get dropdown options
     */
    public function getDropdownOptions($valueField = null, $textField = null, $filters = [])
    {
        $valueField = $valueField ?? $this->primaryKey;
        $textField = $textField ?? $this->nameField ?? 'name';
        
        $builder = $this->builder();
        
        // Apply filters
        foreach ($filters as $field => $value) {
            $builder->where($field, $value);
        }
        
        // Default to active records only
        if ($this->db->fieldExists('is_active', $this->table)) {
            $builder->where('is_active', 1);
        }
        
        $results = $builder->orderBy($textField, 'ASC')
                          ->get()
                          ->getResultArray();
        
        $options = [];
        foreach ($results as $row) {
            $options[$row[$valueField]] = $row[$textField];
        }
        
        return $options;
    }
    
    /**
     * Soft delete (set is_active = 0)
     */
    public function deactivate($id)
    {
        return $this->update($id, ['is_active' => 0]);
    }
    
    /**
     * Restore soft deleted record
     */
    public function activate($id)
    {
        return $this->update($id, ['is_active' => 1]);
    }
    
    /**
     * Get validation errors as string
     */
    public function getErrorString()
    {
        $errors = $this->validation->getErrors();
        return implode('<br>', $errors);
    }
    
    /**
     * Generate unique code
     */
    public function generateCode($prefix = '', $length = 6)
    {
        do {
            $code = $prefix . str_pad(rand(1, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
        } while (!$this->isUniqueCode($code));
        
        return $code;
    }
    
    /**
     * Get next sequence number for codes
     */
    public function getNextSequence($prefix = '')
    {
        $builder = $this->builder();
        
        if (isset($this->codeField)) {
            $lastCode = $builder->select($this->codeField)
                               ->like($this->codeField, $prefix, 'after')
                               ->orderBy($this->codeField, 'DESC')
                               ->limit(1)
                               ->get()
                               ->getRowArray();
            
            if ($lastCode) {
                $number = intval(substr($lastCode[$this->codeField], strlen($prefix)));
                return $number + 1;
            }
        }
        
        return 1;
    }
    
    /**
     * Bulk update records
     */
    public function bulkUpdate($ids, $data)
    {
        if (!empty($this->updatedByField)) {
            $data[$this->updatedByField] = $this->userId ?? null;
        }
        
        if ($this->useTimestamps && !empty($this->updatedField)) {
            $data[$this->updatedField] = date('Y-m-d H:i:s');
        }
        
        return $this->builder()
                    ->whereIn($this->primaryKey, $ids)
                    ->update($data);
    }
    
    /**
     * Export data to array (for Excel/CSV export)
     */
    public function exportData($filters = [], $columns = [])
    {
        $builder = $this->builder();
        
        // Select specific columns if provided
        if (!empty($columns)) {
            $builder->select($columns);
        }
        
        // Apply filters
        foreach ($filters as $field => $value) {
            if (!empty($value)) {
                $builder->where($field, $value);
            }
        }
        
        return $builder->get()->getResultArray();
    }
}