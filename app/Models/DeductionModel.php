<?php
namespace App\Models;

use CodeIgniter\Model;

class DeductionModel extends Model
{
    protected $table = 'deductions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['deduction_name', 'deduction_type', 'status', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    public function getActiveDeductions()
    {
        return $this->where('status', 1)->findAll();
    }
}