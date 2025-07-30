<?php
namespace App\Models;

use CodeIgniter\Model;

class StaffSalaryComponentModel extends Model
{
    protected $table = 'staff_salary_components';
    protected $primaryKey = 'id';
    protected $allowedFields = ['staff_id', 'component_type', 'component_id', 'amount', 'percentage', 'effective_date', 'end_date', 'status', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    public function getStaffAllowances($staffId, $date = null)
    {
        if (!$date) $date = date('Y-m-d');
        
        return $this->select('staff_salary_components.*, allowances.allowance_name, allowances.is_taxable, allowances.is_epf_eligible, allowances.is_socso_eligible')
                    ->join('allowances', 'allowances.id = staff_salary_components.component_id')
                    ->where('staff_id', $staffId)
                    ->where('component_type', 'allowance')
                    ->where('status', 1)
                    ->where('effective_date <=', $date)
                    ->groupStart()
                        ->where('end_date IS NULL')
                        ->orWhere('end_date >=', $date)
                    ->groupEnd()
                    ->findAll();
    }

    public function getStaffDeductions($staffId, $date = null)
    {
        if (!$date) $date = date('Y-m-d');
        
        return $this->select('staff_salary_components.*, deductions.deduction_name')
                    ->join('deductions', 'deductions.id = staff_salary_components.component_id')
                    ->where('staff_id', $staffId)
                    ->where('component_type', 'deduction')
                    ->where('status', 1)
                    ->where('effective_date <=', $date)
                    ->groupStart()
                        ->where('end_date IS NULL')
                        ->orWhere('end_date >=', $date)
                    ->groupEnd()
                    ->findAll();
    }
}