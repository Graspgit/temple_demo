<?php
namespace App\Models;

use CodeIgniter\Model;

class PCBSettingModel extends Model
{
    protected $table = 'pcb_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['category', 'income_from', 'income_to', 'tax_amount', 'tax_percentage', 'effective_year', 'status', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    public function getApplicableRate($annualIncome, $category, $year = null)
    {
        if (!$year) $year = date('Y');
        
        return $this->where('category', $category)
                    ->where('income_from <=', $annualIncome)
                    ->where('income_to >=', $annualIncome)
                    ->where('status', 1)
                    ->where('effective_year <=', $year)
                    ->orderBy('effective_year', 'DESC')
                    ->first();
    }
}