<?php
namespace App\Models;

use CodeIgniter\Model;

class AllowanceModel extends Model
{
    protected $table = 'allowances';
    protected $primaryKey = 'id';
    protected $allowedFields = ['allowance_name', 'allowance_type', 'is_taxable', 'is_epf_eligible', 'is_socso_eligible', 'status', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    public function getActiveAllowances()
    {
        return $this->where('status', 1)->findAll();
    }
}