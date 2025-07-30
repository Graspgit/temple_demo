<?php
namespace App\Models;

use CodeIgniter\Model;

class EISSettingModel extends Model
{
    protected $table = 'eis_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['salary_from', 'salary_to', 'employee_percentage', 'employer_percentage', 'employee_amount', 'employer_amount', 'effective_date', 'status', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    public function getApplicableRate($salary, $date = null)
    {
        if (!$date) $date = date('Y-m-d');
        
        return $this->where('salary_from <=', $salary)
                    ->where('salary_to >=', $salary)
                    ->where('status', 1)
                    ->where('effective_date <=', $date)
                    ->orderBy('effective_date', 'DESC')
                    ->first();
    }
}