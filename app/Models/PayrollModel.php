<?php
namespace App\Models;

use CodeIgniter\Model;

class PayrollModel extends Model
{
    protected $table = 'payroll';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'payroll_month', 'staff_id', 'basic_salary', 'total_allowances',
        'total_deductions', 'gross_salary', 'epf_employee', 'epf_employer',
        'socso_employee', 'socso_employer', 'eis_employee', 'eis_employer', 'approved_date', 'approved_by', 'payment_mode_id', 'is_payroll_migration',
        'pcb', 'total_commission', 'net_salary', 'payment_status',
        'payment_date', 'payment_method', 'is_payment_migration'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get payroll summary
    public function getPayrollSummary($month)
    {
        $result = $this->db->table('payroll')
            ->select('COUNT(*) as total_staff,
                    SUM(gross_salary) as total_gross,
                    SUM(net_salary) as total_net,
                    SUM(epf_employee) as total_epf_employee,
                    SUM(epf_employer) as total_epf_employer,
                    SUM(socso_employee) as total_socso_employee,
                    SUM(socso_employer) as total_socso_employer,
                    SUM(eis_employee) as total_eis_employee,
                    SUM(eis_employer) as total_eis_employer,
                    SUM(pcb) as total_pcb,
                    SUM(CASE WHEN payment_status = "pending" THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN payment_status = "approved" THEN 1 ELSE 0 END) as approved_count,
                    SUM(CASE WHEN payment_status = "paid" THEN 1 ELSE 0 END) as paid_count,
                    SUM(CASE WHEN payment_status = "cancelled" THEN 1 ELSE 0 END) as cancelled_count,
                    SUM(CASE WHEN payment_status = "pending" THEN net_salary ELSE 0 END) as pending_amount,
                    SUM(CASE WHEN payment_status = "approved" THEN net_salary ELSE 0 END) as approved_amount,
                    SUM(CASE WHEN payment_status = "paid" THEN net_salary ELSE 0 END) as paid_amount')
            ->where('payroll_month', $month)
            ->get()
            ->getRowArray();
            
        return $result;
    }

    // Check if payroll exists for month
    public function payrollExists($month, $staffId = null)
    {
        $query = $this->where('payroll_month', $month);
        
        if ($staffId) {
            $query->where('staff_id', $staffId);
        }
        
        return $query->countAllResults() > 0;
    }

    // Generate payslip
    public function getPayslip($payrollId)
    {
        $payroll = $this->select('payroll.*, staff_details.*, departments.department_name, designations.designation_name')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->join('departments', 'departments.id = staff_details.department_id', 'left')
            ->join('designations', 'designations.id = staff_details.designation_id', 'left')
            ->where('payroll.id', $payrollId)
            ->first();

        if ($payroll) {
            // Get payroll details
            $payroll['details'] = $this->db->table('payroll_details')
                ->where('payroll_id', $payrollId)
                ->get()->getResultArray();

            // Get statutory details
            if ($payroll['staff_type'] == 'local') {
                $payroll['statutory'] = $this->db->table('staff_statutory_details')
                    ->where('staff_id', $payroll['staff_id'])
                    ->get()->getRowArray();
            }
        }

        return $payroll;
    }

    public function getPayrollSummaryWithStatus($month)
    {
        $result = $this->db->table('payroll')
            ->select('COUNT(*) as total_staff,
                    SUM(gross_salary) as total_gross,
                    SUM(net_salary) as total_net,
                    SUM(epf_employee) as total_epf_employee,
                    SUM(epf_employer) as total_epf_employer,
                    SUM(socso_employee) as total_socso_employee,
                    SUM(socso_employer) as total_socso_employer,
                    SUM(eis_employee) as total_eis_employee,
                    SUM(eis_employer) as total_eis_employer,
                    SUM(pcb) as total_pcb,
                    SUM(CASE WHEN payment_status = "pending" THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN payment_status = "paid" THEN 1 ELSE 0 END) as paid_count,
                    SUM(CASE WHEN payment_status = "cancelled" THEN 1 ELSE 0 END) as cancelled_count,
                    SUM(CASE WHEN payment_status = "pending" THEN net_salary ELSE 0 END) as pending_amount,
                    SUM(CASE WHEN payment_status = "paid" THEN net_salary ELSE 0 END) as paid_amount')
            ->where('payroll_month', $month)
            ->get()
            ->getRowArray();
            
        return $result;
    }
    
    // Get payroll with additional status filters
    public function getMonthlyPayrollByStatus($month, $status = null)
    {
        $query = $this->db->table('payroll')
            ->select('payroll.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name,
                    staff_details.email, staff_details.phone, departments.department_name')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->join('departments', 'departments.id = staff_details.department_id', 'left')
            ->where('payroll_month', $month);
            
        if ($status) {
            $query->where('payment_status', $status);
        }
        
        return $query->orderBy('staff_details.staff_code', 'ASC')->get()->getResultArray();
    }
    
    // Get pending payrolls for approval
    public function getPendingPayrolls($month = null)
    {
        $query = $this->db->table('payroll')
            ->select('payroll.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->where('payment_status', 'pending');
            
        if ($month) {
            $query->where('payroll_month', $month);
        }
        
        return $query->get()->getResultArray();
    }
    
    // Get payment history for a staff member
    public function getPaymentHistory($staffId)
    {
        return $this->db->table('payroll')
            ->where('staff_id', $staffId)
            ->where('payment_status', 'paid')
            ->orderBy('payment_date', 'DESC')
            ->get()->getResultArray();
    }

    

    public function getMonthlyPayroll($month)
    {
        return $this->db->table('payroll')
            ->select('payroll.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name,
                    staff_details.email, staff_details.phone, departments.department_name, 
                    designations.designation_name')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->join('departments', 'departments.id = staff_details.department_id', 'left')
            ->join('designations', 'designations.id = staff_details.designation_id', 'left')
            ->where('payroll_month', $month)
            ->orderBy('staff_details.staff_code', 'ASC')
            ->get()->getResultArray();
    }
    
    // Get payroll by status
    public function getPayrollByStatus($month, $status)
    {
        return $this->db->table('payroll')
            ->select('payroll.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name,
                    staff_bank_details.bank_name, staff_bank_details.account_number')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->join('staff_bank_details', 'staff_bank_details.staff_id = payroll.staff_id AND staff_bank_details.is_primary = 1', 'left')
            ->where('payroll_month', $month)
            ->where('payment_status', $status)
            ->orderBy('staff_details.staff_code', 'ASC')
            ->get()->getResultArray();
    }
    
    // Get approval history
    public function getApprovalHistory($payrollId)
    {
        return $this->db->table('payroll_approval_log')
            ->where('payroll_id', $payrollId)
            ->orderBy('action_date', 'DESC')
            ->get()->getResultArray();
    }
    
    // Get payment statistics by month
    public function getPaymentStats($month)
    {
        $stats = $this->db->table('payroll')
            ->select('payment_status, COUNT(*) as count, SUM(net_salary) as total,
                    MIN(approved_date) as earliest_approval,
                    MAX(approved_date) as latest_approval')
            ->where('payroll_month', $month)
            ->groupBy('payment_status')
            ->get()->getResultArray();
        
        $result = [
            'pending' => ['count' => 0, 'total' => 0],
            'approved' => ['count' => 0, 'total' => 0],
            'paid' => ['count' => 0, 'total' => 0],
            'cancelled' => ['count' => 0, 'total' => 0]
        ];
        
        foreach ($stats as $stat) {
            $result[$stat['payment_status']] = $stat;
        }
        
        return $result;
    }
    
    // Get payrolls pending payment for more than X days
    public function getDelayedPayments($days = 3)
    {
        $dateThreshold = date('Y-m-d', strtotime("-$days days"));
        
        return $this->db->table('payroll')
            ->select('payroll.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name,
                    DATEDIFF(NOW(), approved_date) as days_pending')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->where('payment_status', 'approved')
            ->where('approved_date <=', $dateThreshold)
            ->orderBy('approved_date', 'ASC')
            ->get()->getResultArray();
    }
}