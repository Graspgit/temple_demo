<?php
namespace App\Models;

use CodeIgniter\Model;

class DesignationModel extends Model
{
    protected $table = 'designations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['designation_name', 'department_id', 'min_salary', 'max_salary', 'status', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    public function getDesignationsByDepartment($departmentId)
    {
        return $this->where('department_id', $departmentId)
                    ->where('status', 1)
                    ->findAll();
    }
}