<?php
namespace App\Models;

use CodeIgniter\Model;

class PayrollDetailModel extends Model
{
    protected $table = 'payroll_details';
    protected $primaryKey = 'id';
    protected $allowedFields = ['payroll_id', 'component_type', 'component_name', 'amount', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    public function getPayrollBreakdown($payrollId)
    {
        return $this->where('payroll_id', $payrollId)
                    ->orderBy('component_type')
                    ->orderBy('component_name')
                    ->findAll();
    }

    public function savePayrollDetails($payrollId, $details)
    {
        // Delete existing details
        $this->where('payroll_id', $payrollId)->delete();
        
        // Insert new details
        if (!empty($details)) {
            return $this->insertBatch($details);
        }
        
        return true;
    }
}