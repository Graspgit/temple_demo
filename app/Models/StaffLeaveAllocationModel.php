<?php
namespace App\Models;

use CodeIgniter\Model;

class StaffLeaveAllocationModel extends Model
{
    protected $table = 'staff_leave_allocation';
    protected $primaryKey = 'id';
    protected $allowedFields = ['staff_id', 'leave_type_id', 'year', 'entitled_days', 'carried_forward', 'total_days', 'used_days', 'balance_days', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    public function getStaffLeaveBalance($staffId, $year = null)
    {
        if (!$year) $year = date('Y');
        
        return $this->select('staff_leave_allocation.*, leave_types.leave_name, leave_types.leave_code')
                    ->join('leave_types', 'leave_types.id = staff_leave_allocation.leave_type_id')
                    ->where('staff_id', $staffId)
                    ->where('year', $year)
                    ->findAll();
    }

    public function updateLeaveBalance($staffId, $leaveTypeId, $year, $daysUsed)
    {
        $allocation = $this->where('staff_id', $staffId)
                          ->where('leave_type_id', $leaveTypeId)
                          ->where('year', $year)
                          ->first();
        
        if ($allocation) {
            $newUsedDays = $allocation['used_days'] + $daysUsed;
            $newBalance = $allocation['total_days'] - $newUsedDays;
            
            return $this->update($allocation['id'], [
                'used_days' => $newUsedDays,
                'balance_days' => $newBalance
            ]);
        }
        
        return false;
    }
}