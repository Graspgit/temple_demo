<?php
namespace App\Models;

use CodeIgniter\Model;

class CommissionSettingModel extends Model
{
    protected $table = 'commission_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['staff_id', 'commission_type', 'commission_percentage', 'commission_amount', 'effective_date', 'end_date', 'status', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    public function getActiveCommissionSettings($staffId, $date = null)
    {
        if (!$date) $date = date('Y-m-d');
        
        return $this->where('staff_id', $staffId)
                    ->where('status', 1)
                    ->where('effective_date <=', $date)
                    ->groupStart()
                        ->where('end_date IS NULL')
                        ->orWhere('end_date >=', $date)
                    ->groupEnd()
                    ->findAll();
    }
}