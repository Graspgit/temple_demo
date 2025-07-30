<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffModel extends Model
{
    protected $table = 'staff_details';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'staff_code', 'staff_type', 'first_name', 'last_name', 'email', 'phone',
        'address', 'date_of_birth', 'gender', 'marital_status', 'join_date',
        'department_id', 'designation_id', 'basic_salary', 'status'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get all active staff with related details
    public function getActiveStaff()
    {
        return $this->select('staff_details.*, departments.department_name, designations.designation_name')
            ->join('departments', 'departments.id = staff_details.department_id', 'left')
            ->join('designations', 'designations.id = staff_details.designation_id', 'left')
            ->where('staff_details.status', 'active')
            ->findAll();
    }

    // Get staff with all details including foreigner/statutory info
    public function getStaffFullDetails($staffId)
    {
        $staff = $this->find($staffId);
        
        if (!$staff) {
            return null;
        }

        // Get department and designation
        $staff['department'] = $this->db->table('departments')
            ->where('id', $staff['department_id'])
            ->get()->getRowArray();
            
        $staff['designation'] = $this->db->table('designations')
            ->where('id', $staff['designation_id'])
            ->get()->getRowArray();

        // Get foreigner details if applicable
        if ($staff['staff_type'] == 'foreigner') {
            $staff['foreigner_details'] = $this->db->table('staff_foreigner_details')
                ->where('staff_id', $staffId)
                ->get()->getRowArray();
        } else {
            // Get statutory details for local staff
            $staff['statutory_details'] = $this->db->table('staff_statutory_details')
                ->where('staff_id', $staffId)
                ->get()->getRowArray();
        }

        // Get next of kin
        $staff['next_of_kin'] = $this->db->table('staff_next_of_kin')
            ->where('staff_id', $staffId)
            ->get()->getResultArray();

        // Get documents
        $staff['documents'] = $this->db->table('staff_documents')
            ->where('staff_id', $staffId)
            ->orderBy('uploaded_date', 'DESC')
            ->get()->getResultArray();

        // Get salary components
        $staff['allowances'] = $this->db->table('staff_salary_components')
            ->select('staff_salary_components.*, allowances.allowance_name')
            ->join('allowances', 'allowances.id = staff_salary_components.component_id')
            ->where('staff_salary_components.staff_id', $staffId)
            ->where('staff_salary_components.component_type', 'allowance')
            ->where('staff_salary_components.status', 1)
            ->get()->getResultArray();

        $staff['deductions'] = $this->db->table('staff_salary_components')
            ->select('staff_salary_components.*, deductions.deduction_name')
            ->join('deductions', 'deductions.id = staff_salary_components.component_id')
            ->where('staff_salary_components.staff_id', $staffId)
            ->where('staff_salary_components.component_type', 'deduction')
            ->where('staff_salary_components.status', 1)
            ->get()->getResultArray();

        return $staff;
    }

    // Generate unique staff code
    public function generateStaffCode()
    {
        $lastStaff = $this->orderBy('id', 'DESC')->first();
        
        if ($lastStaff) {
            $lastCode = $lastStaff['staff_code'];
            $number = intval(substr($lastCode, 3)) + 1;
            return 'EMP' . str_pad($number, 4, '0', STR_PAD_LEFT);
        }
        
        return 'EMP0001';
    }

    // Get staff for payroll generation
    public function getStaffForPayroll($month)
    {
        return $this->select('staff_details.*')
            ->where('staff_details.status', 'active')
            ->where('DATE_FORMAT(staff_details.join_date, "%Y-%m") <=', $month)
            ->findAll();
    }

    // Search staff
    public function searchStaff($keyword)
    {
        return $this->like('staff_code', $keyword)
            ->orLike('first_name', $keyword)
            ->orLike('last_name', $keyword)
            ->orLike('email', $keyword)
            ->orLike('phone', $keyword)
            ->findAll();
    }

    // Get staff by department
    public function getStaffByDepartment($departmentId)
    {
        return $this->where('department_id', $departmentId)
            ->where('status', 'active')
            ->findAll();
    }

    // Check visa/passport expiry for foreigners
    public function getExpiringDocuments($days = 30)
    {
        $expiryDate = date('Y-m-d', strtotime("+{$days} days"));
        
        $foreigners = $this->db->table('staff_foreigner_details')
            ->select('staff_foreigner_details.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name')
            ->join('staff_details', 'staff_details.id = staff_foreigner_details.staff_id')
            ->where('staff_details.status', 'active')
            ->groupStart()
                ->where('passport_expiry <=', $expiryDate)
                ->orWhere('visa_expiry <=', $expiryDate)
            ->groupEnd()
            ->get()->getResultArray();

        return $foreigners;
    }
}