<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaveTypeModel extends Model
{
    protected $table = 'leave_types';
    protected $primaryKey = 'id';
    protected $allowedFields = ['leave_code', 'leave_name', 'days_per_year', 'carry_forward', 'max_carry_forward', 'is_paid', 'status', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    public function getActiveLeaveTypes()
    {
        return $this->where('status', 1)->findAll();
    }
}
