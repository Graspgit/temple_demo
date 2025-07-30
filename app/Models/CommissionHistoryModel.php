<?php
namespace App\Models;

use CodeIgniter\Model;

class CommissionHistoryModel extends Model
{
    protected $table = 'commission_history';
    protected $primaryKey = 'id';
    protected $allowedFields = ['staff_id', 'commission_date', 'commission_type', 'base_amount', 'commission_percentage', 'commission_amount', 'reference_no', 'remarks', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    public function getMonthlyCommission($staffId, $month)
    {
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));
        
        return $this->where('staff_id', $staffId)
                    ->where('commission_date >=', $startDate)
                    ->where('commission_date <=', $endDate)
                    ->findAll();
    }

    public function getCommissionSummary($month)
    {
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));
        
        return $this->select('staff_id, SUM(commission_amount) as total_commission')
                    ->where('commission_date >=', $startDate)
                    ->where('commission_date <=', $endDate)
                    ->groupBy('staff_id')
                    ->findAll();
    }
}