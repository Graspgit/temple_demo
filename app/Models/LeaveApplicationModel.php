<?php
namespace App\Models;

use CodeIgniter\Model;

class LeaveApplicationModel extends Model
{
    protected $table = 'leave_applications';
    protected $primaryKey = 'id';
    protected $allowedFields = ['staff_id', 'leave_type_id', 'from_date', 'to_date', 'days', 'reason', 'status', 'approved_by', 'approved_date', 'remarks', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    public function getPendingApplications()
    {
        return $this->select('leave_applications.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name, leave_types.leave_name')
                    ->join('staff_details', 'staff_details.id = leave_applications.staff_id')
                    ->join('leave_types', 'leave_types.id = leave_applications.leave_type_id')
                    ->where('leave_applications.status', 'pending')
                    ->orderBy('leave_applications.created_at', 'DESC')
                    ->findAll();
    }

    public function getStaffLeaveHistory($staffId, $year = null)
    {
        $query = $this->select('leave_applications.*, leave_types.leave_name')
                      ->join('leave_types', 'leave_types.id = leave_applications.leave_type_id')
                      ->where('staff_id', $staffId);
        
        if ($year) {
            $query->where('YEAR(from_date)', $year);
        }
        
        return $query->orderBy('from_date', 'DESC')->findAll();
    }
}