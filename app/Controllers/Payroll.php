<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PayrollModel;
use App\Models\StaffModel;
use App\Models\PermissionModel;

class Payroll extends BaseController
{
    protected $payrollModel;
    protected $staffModel;
    function __construct(){
        parent:: __construct();
        $this->payrollModel = new PayrollModel();
        $this->staffModel = new StaffModel();
		$this->model = new PermissionModel();
        if( ($this->session->get('login') ) == false && $this->session->get('role') != 1){
            $data['dn_msg'] = 'Please Login';
            return redirect()->to('/login');
		}
    }

    // Payroll dashboard
    public function index()
    {
        $data['title'] = 'Payroll Management';
        
        // Get last 6 months payroll
        $months = [];
        for ($i = 0; $i < 6; $i++) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[$month] = $this->payrollModel->getPayrollSummary($month);
        }
        $data['monthly_summary'] = $months;
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/payroll/index', $data);
        echo view('template/footer');
    }

    // Generate payroll form
    public function generate()
    {
        $data['title'] = 'Generate Payroll';
        $data['current_month'] = date('Y-m');
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/payroll/generate', $data);
        echo view('template/footer');
    }

    // Process payroll generation
    public function process_generation()
    {
        $month = $this->request->getPost('payroll_month');
        
        // Check if payroll already exists
        if ($this->payrollModel->payrollExists($month)) {
            return redirect()->back()->with('error', 'Payroll already generated for this month');
        }

        // Get all active staff
        $staff = $this->staffModel->getStaffForPayroll($month);
        
        $this->db->transStart();
        $successCount = 0;

        try {
            foreach ($staff as $employee) {
                $payrollData = $this->calculatePayroll($employee, $month);
                
                // Save payroll
                $payrollId = $this->payrollModel->insert($payrollData);
                
                // Save payroll details
                $this->savePayrollDetails($payrollId, $payrollData);
                
                $successCount++;
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                return redirect()->back()->with('error', 'Failed to generate payroll');
            }

            return redirect()->to('/payroll/view/' . $month)->with('success', "Payroll generated successfully for $successCount employees");

        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Calculate payroll for an employee
    private function calculatePayroll($employee, $month)
    {
        $basicSalary = $employee['basic_salary'];
        $staffId = $employee['id'];
        
        // Get allowances
        $allowances = $this->db->table('staff_salary_components')
            ->select('staff_salary_components.*, allowances.allowance_name, allowances.is_taxable, allowances.is_epf_eligible, allowances.is_socso_eligible')
            ->join('allowances', 'allowances.id = staff_salary_components.component_id')
            ->where('staff_salary_components.staff_id', $staffId)
            ->where('staff_salary_components.component_type', 'allowance')
            ->where('staff_salary_components.status', 1)
            ->get()->getResultArray();

        $totalAllowances = 0;
        $epfEligibleSalary = $basicSalary;
        $socsoEligibleSalary = $basicSalary;
        
        foreach ($allowances as $allowance) {
            $amount = $allowance['amount'];
            if ($allowance['percentage']) {
                $amount = ($basicSalary * $allowance['percentage']) / 100;
            }
            
            $totalAllowances += $amount;
            
            if ($allowance['is_epf_eligible']) {
                $epfEligibleSalary += $amount;
            }
            if ($allowance['is_socso_eligible']) {
                $socsoEligibleSalary += $amount;
            }
        }

        // Get deductions
        $deductions = $this->db->table('staff_salary_components')
            ->select('staff_salary_components.*, deductions.deduction_name')
            ->join('deductions', 'deductions.id = staff_salary_components.component_id')
            ->where('staff_salary_components.staff_id', $staffId)
            ->where('staff_salary_components.component_type', 'deduction')
            ->where('staff_salary_components.status', 1)
            ->get()->getResultArray();

        $totalDeductions = 0;
        foreach ($deductions as $deduction) {
            $amount = $deduction['amount'];
            if ($deduction['percentage']) {
                $amount = ($basicSalary * $deduction['percentage']) / 100;
            }
            $totalDeductions += $amount;
        }

        // Calculate statutory deductions for local staff
        $epfEmployee = 0;
        $epfEmployer = 0;
        $socsoEmployee = 0;
        $socsoEmployer = 0;
        $eisEmployee = 0;
        $eisEmployer = 0;
        $pcb = 0;

        if ($employee['staff_type'] == 'local') {
            // EPF Calculation
            $epfRates = $this->getEPFRates($epfEligibleSalary);
            $epfEmployee = $epfRates['employee'];
            $epfEmployer = $epfRates['employer'];

            // SOCSO Calculation
            $socsoRates = $this->getSOCSORate($socsoEligibleSalary);
            $socsoEmployee = $socsoRates['employee'];
            $socsoEmployer = $socsoRates['employer'];

            // EIS Calculation
            $eisRates = $this->getEISRate($socsoEligibleSalary);
            $eisEmployee = $eisRates['employee'];
            $eisEmployer = $eisRates['employer'];

            // PCB Calculation
            $statutoryDetails = $this->db->table('staff_statutory_details')
                ->where('staff_id', $staffId)
                ->get()->getRowArray();
            
            if ($statutoryDetails && $statutoryDetails['pcb_code']) {
                $pcb = $this->calculatePCB($epfEligibleSalary, $statutoryDetails['pcb_code']);
            }
        }

        // Get commission
        $commission = $this->getMonthlyCommission($staffId, $month);

        // Calculate totals
        $grossSalary = $basicSalary + $totalAllowances + $commission;
        $totalStatutoryDeductions = $epfEmployee + $socsoEmployee + $eisEmployee + $pcb;
        $netSalary = $grossSalary - $totalDeductions - $totalStatutoryDeductions;

        return [
            'payroll_month' => $month,
            'staff_id' => $staffId,
            'basic_salary' => $basicSalary,
            'total_allowances' => $totalAllowances,
            'total_deductions' => $totalDeductions,
            'gross_salary' => $grossSalary,
            'epf_employee' => $epfEmployee,
            'epf_employer' => $epfEmployer,
            'socso_employee' => $socsoEmployee,
            'socso_employer' => $socsoEmployer,
            'eis_employee' => $eisEmployee,
            'eis_employer' => $eisEmployer,
            'pcb' => $pcb,
            'total_commission' => $commission,
            'net_salary' => $netSalary,
            'payment_status' => 'pending'
        ];
    }

    // Get EPF rates
    private function getEPFRates($salary)
    {
        $epfSetting = $this->db->table('epf_settings')
            ->where('salary_from <=', $salary)
            ->where('salary_to >=', $salary)
            ->where('status', 1)
            ->orderBy('effective_date', 'DESC')
            ->get()->getRowArray();

        if ($epfSetting) {
            if ($epfSetting['employee_amount']) {
                return [
                    'employee' => $epfSetting['employee_amount'],
                    'employer' => $epfSetting['employer_amount']
                ];
            } else {
                return [
                    'employee' => ($salary * $epfSetting['employee_percentage']) / 100,
                    'employer' => ($salary * $epfSetting['employer_percentage']) / 100
                ];
            }
        }

        // Default rates if not found
        return [
            'employee' => ($salary * 11) / 100,
            'employer' => ($salary * 13) / 100
        ];
    }

    // Get SOCSO rates
    private function getSOCSORate($salary)
    {
        $socsoSetting = $this->db->table('socso_settings')
            ->where('salary_from <=', $salary)
            ->where('salary_to >=', $salary)
            ->where('status', 1)
            ->orderBy('effective_date', 'DESC')
            ->get()->getRowArray();

        if ($socsoSetting) {
            if ($socsoSetting['employee_amount']) {
                return [
                    'employee' => $socsoSetting['employee_amount'],
                    'employer' => $socsoSetting['employer_amount']
                ];
            } else {
                return [
                    'employee' => ($salary * $socsoSetting['employee_percentage']) / 100,
                    'employer' => ($salary * $socsoSetting['employer_percentage']) / 100
                ];
            }
        }

        return ['employee' => 0, 'employer' => 0];
    }

    // Get EIS rates
    private function getEISRate($salary)
    {
        $eisSetting = $this->db->table('eis_settings')
            ->where('salary_from <=', $salary)
            ->where('salary_to >=', $salary)
            ->where('status', 1)
            ->orderBy('effective_date', 'DESC')
            ->get()->getRowArray();

        if ($eisSetting) {
            if ($eisSetting['employee_amount']) {
                return [
                    'employee' => $eisSetting['employee_amount'],
                    'employer' => $eisSetting['employer_amount']
                ];
            } else {
                return [
                    'employee' => ($salary * $eisSetting['employee_percentage']) / 100,
                    'employer' => ($salary * $eisSetting['employer_percentage']) / 100
                ];
            }
        }

        return ['employee' => 0, 'employer' => 0];
    }

    // Calculate PCB
    private function calculatePCB($salary, $pcbCode)
    {
        $annualSalary = $salary * 12;
        
        $pcbSetting = $this->db->table('pcb_settings')
            ->where('category', $pcbCode)
            ->where('income_from <=', $annualSalary)
            ->where('income_to >=', $annualSalary)
            ->where('status', 1)
            ->orderBy('effective_year', 'DESC')
            ->get()->getRowArray();

        if ($pcbSetting) {
            if ($pcbSetting['tax_percentage']) {
                return ($salary * $pcbSetting['tax_percentage']) / 100;
            } else {
                return $pcbSetting['tax_amount'] / 12; // Monthly PCB
            }
        }

        return 0;
    }

    // Get monthly commission
    private function getMonthlyCommission($staffId, $month)
    {
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));
        
        $commission = $this->db->table('commission_history')
            ->selectSum('commission_amount')
            ->where('staff_id', $staffId)
            ->where('commission_date >=', $startDate)
            ->where('commission_date <=', $endDate)
            ->get()->getRowArray();

        return $commission['commission_amount'] ?? 0;
    }

    // Save payroll details
    private function savePayrollDetails($payrollId, $payrollData)
    {
        $details = [];
        
        // Basic salary
        $details[] = [
            'payroll_id' => $payrollId,
            'component_type' => 'earning',
            'component_name' => 'Basic Salary',
            'amount' => $payrollData['basic_salary']
        ];

        // Statutory deductions
        if ($payrollData['epf_employee'] > 0) {
            $details[] = [
                'payroll_id' => $payrollId,
                'component_type' => 'statutory',
                'component_name' => 'EPF Employee',
                'amount' => $payrollData['epf_employee']
            ];
        }

        if ($payrollData['socso_employee'] > 0) {
            $details[] = [
                'payroll_id' => $payrollId,
                'component_type' => 'statutory',
                'component_name' => 'SOCSO Employee',
                'amount' => $payrollData['socso_employee']
            ];
        }

        if ($payrollData['eis_employee'] > 0) {
            $details[] = [
                'payroll_id' => $payrollId,
                'component_type' => 'statutory',
                'component_name' => 'EIS Employee',
                'amount' => $payrollData['eis_employee']
            ];
        }

        if ($payrollData['pcb'] > 0) {
            $details[] = [
                'payroll_id' => $payrollId,
                'component_type' => 'statutory',
                'component_name' => 'PCB',
                'amount' => $payrollData['pcb']
            ];
        }

        if (!empty($details)) {
            $this->db->table('payroll_details')->insertBatch($details);
        }
    }

    // Generate payslip
    public function payslip($payrollId)
    {
        $data['payslip'] = $this->payrollModel->getPayslip($payrollId);
        
        if (!$data['payslip']) {
            return redirect()->back()->with('error', 'Payslip not found');
        }

        $data['title'] = 'Payslip - ' . $data['payslip']['staff_code'];
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/payroll/payslip', $data);
        echo view('template/footer');
    }

    // Monthly reports
    public function reports()
    {
        $data['title'] = 'Payroll Reports';
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/payroll/reports', $data);
        echo view('template/footer');
    }

    // EPF report
    public function epf_report()
    {
        $month = $this->request->getGet('month') ?? date('Y-m');
        
        $data['title'] = 'EPF Report - ' . date('F Y', strtotime($month . '-01'));
        $data['month'] = $month;
        
        $data['epf_data'] = $this->db->table('payroll')
            ->select('payroll.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name, staff_statutory_details.epf_number')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->join('staff_statutory_details', 'staff_statutory_details.staff_id = payroll.staff_id', 'left')
            ->where('payroll_month', $month)
            ->where('epf_employee >', 0)
            ->get()->getResultArray();

        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/payroll/epf_report', $data);
        echo view('template/footer');
    }

    // Export payroll
    public function export($month)
    {
        $payroll = $this->payrollModel->getMonthlyPayroll($month);
        
        $filename = 'payroll_' . $month . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Headers
        fputcsv($output, [
            'Staff Code', 'Name', 'Basic Salary', 'Allowances', 'Deductions',
            'Gross Salary', 'EPF Employee', 'EPF Employer', 'SOCSO Employee',
            'SOCSO Employer', 'EIS Employee', 'EIS Employer', 'PCB',
            'Commission', 'Net Salary', 'Payment Status'
        ]);
        
        // Data
        foreach ($payroll as $row) {
            fputcsv($output, [
                $row['staff_code'],
                $row['first_name'] . ' ' . $row['last_name'],
                $row['basic_salary'],
                $row['total_allowances'],
                $row['total_deductions'],
                $row['gross_salary'],
                $row['epf_employee'],
                $row['epf_employer'],
                $row['socso_employee'],
                $row['socso_employer'],
                $row['eis_employee'],
                $row['eis_employer'],
                $row['pcb'],
                $row['total_commission'],
                $row['net_salary'],
                $row['payment_status']
            ]);
        }
        
        fclose($output);
        exit;
    }
    public function monthlyReport($month)
    {
        $format = $this->request->getGet('format') ?? 'pdf';
        
        $data['title'] = 'Monthly Payroll Report - ' . date('F Y', strtotime($month . '-01'));
        $data['month'] = $month;
        $data['payroll'] = $this->payrollModel->getMonthlyPayroll($month);
        $data['summary'] = $this->payrollModel->getPayrollSummary($month);
        
        // Group by department
        $data['department_summary'] = $this->db->table('payroll')
            ->select('departments.department_name, COUNT(payroll.id) as staff_count, 
                    SUM(payroll.gross_salary) as total_gross, SUM(payroll.net_salary) as total_net')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->join('departments', 'departments.id = staff_details.department_id', 'left')
            ->where('payroll_month', $month)
            ->groupBy('departments.id')
            ->get()->getResultArray();
        
        if ($format == 'pdf') {
            // Generate PDF view
            echo view('hr/payroll/reports/monthly_report_pdf', $data);
        } elseif ($format == 'excel' || $format == 'csv') {
            $this->exportMonthlyReport($data, $format);
        }
    }

    // Statutory Reports (EPF, SOCSO, EIS, PCB)
    public function statutoryReport($month)
    {
        $type = $this->request->getGet('type') ?? 'all';
        $format = $this->request->getGet('format') ?? 'pdf';
        
        $data['title'] = 'Statutory Report - ' . date('F Y', strtotime($month . '-01'));
        $data['month'] = $month;
        $data['type'] = $type;
        
        // Get payroll data with statutory details
        $query = $this->db->table('payroll')
            ->select('payroll.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name, 
                    staff_statutory_details.epf_number, staff_statutory_details.socso_number, 
                    staff_statutory_details.income_tax_number')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->join('staff_statutory_details', 'staff_statutory_details.staff_id = payroll.staff_id', 'left')
            ->where('payroll_month', $month);
        
        if ($type != 'all') {
            switch($type) {
                case 'epf':
                    $query->where('epf_employee >', 0);
                    break;
                case 'socso':
                    $query->where('socso_employee >', 0);
                    break;
                case 'eis':
                    $query->where('eis_employee >', 0);
                    break;
                case 'pcb':
                    $query->where('pcb >', 0);
                    break;
            }
        }
        
        $data['statutory_data'] = $query->get()->getResultArray();
        
        // Calculate totals
        $data['totals'] = [
            'epf_employee' => array_sum(array_column($data['statutory_data'], 'epf_employee')),
            'epf_employer' => array_sum(array_column($data['statutory_data'], 'epf_employer')),
            'socso_employee' => array_sum(array_column($data['statutory_data'], 'socso_employee')),
            'socso_employer' => array_sum(array_column($data['statutory_data'], 'socso_employer')),
            'eis_employee' => array_sum(array_column($data['statutory_data'], 'eis_employee')),
            'eis_employer' => array_sum(array_column($data['statutory_data'], 'eis_employer')),
            'pcb' => array_sum(array_column($data['statutory_data'], 'pcb'))
        ];
        
        if ($format == 'pdf') {
            echo view('template/header');
            echo view('template/sidebar');
            echo view('hr/payroll/reports/statutory_report', $data);
            echo view('template/footer');
        } else {
            $this->exportStatutoryReport($data, $format);
        }
    }

    // Bank Transfer Report
    public function bankReport($month)
    {
        $data['title'] = 'Bank Transfer Report - ' . date('F Y', strtotime($month . '-01'));
        $data['month'] = $month;
        
        // Get paid payroll records with bank details
        $data['bank_transfers'] = $this->db->table('payroll')
            ->select('payroll.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name,
                    staff_bank_details.bank_name, staff_bank_details.account_number, staff_bank_details.account_holder_name')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->join('staff_bank_details', 'staff_bank_details.staff_id = payroll.staff_id', 'left')
            ->where('payroll_month', $month)
            ->where('payment_status', 'pending')
            ->get()->getResultArray();
        
        $data['total_amount'] = array_sum(array_column($data['bank_transfers'], 'net_salary'));
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('hr/payroll/reports/bank_report', $data);
        echo view('template/footer');
    }

    // Yearly Summary Report
    public function yearlyReport($year)
    {
        $data['title'] = 'Yearly Payroll Summary - ' . $year;
        $data['year'] = $year;
        
        // Get monthly summaries for the year
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = $year . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $monthlyData[$month] = $this->payrollModel->getPayrollSummary($month);
        }
        $data['monthly_data'] = $monthlyData;
        
        // Get yearly totals by staff
        $data['staff_yearly'] = $this->db->table('payroll')
            ->select('staff_details.staff_code, staff_details.first_name, staff_details.last_name,
                    SUM(payroll.basic_salary) as total_basic,
                    SUM(payroll.gross_salary) as total_gross,
                    SUM(payroll.epf_employee) as total_epf,
                    SUM(payroll.socso_employee) as total_socso,
                    SUM(payroll.pcb) as total_pcb,
                    SUM(payroll.net_salary) as total_net')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
             ->where("payroll_month LIKE '{$year}%'")
            ->groupBy('payroll.staff_id')
            ->get()->getResultArray();
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('hr/payroll/reports/yearly_report', $data);
        echo view('template/footer');
    }

    // Department Report
    public function departmentReport()
    {
        $month = $this->request->getGet('month') ?? date('Y-m');
        
        $data['title'] = 'Department Payroll Report';
        $data['month'] = $month;
        
        // Get department-wise payroll summary
        $data['departments'] = $this->db->table('departments')
            ->select('departments.*, 
                    COUNT(DISTINCT staff_details.id) as total_staff,
                    COALESCE(SUM(payroll.gross_salary), 0) as total_gross,
                    COALESCE(SUM(payroll.net_salary), 0) as total_net')
            ->join('staff_details', 'staff_details.department_id = departments.id', 'left')
            ->join('payroll', 'payroll.staff_id = staff_details.id AND payroll.payroll_month = "' . $month . '"', 'left')
            ->groupBy('departments.id')
            ->get()->getResultArray();
        
        // Get detailed staff list by department
        foreach ($data['departments'] as &$dept) {
            $dept['staff'] = $this->db->table('payroll')
                ->select('payroll.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name')
                ->join('staff_details', 'staff_details.id = payroll.staff_id')
                ->where('staff_details.department_id', $dept['id'])
                ->where('payroll_month', $month)
                ->get()->getResultArray();
        }
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('hr/payroll/reports/department_report', $data);
        echo view('template/footer');
    }

    // Custom Report Builder
    public function customReport()
    {
        $data['title'] = 'Custom Payroll Report';
        
        if ($this->request->getMethod() == 'post') {
            // Process custom report request
            $criteria = $this->request->getPost();
            $data['report_data'] = $this->generateCustomReport($criteria);
        }
        
        // Get available fields for selection
        $data['departments'] = $this->db->table('departments')->where('status', 1)->get()->getResultArray();
        $data['designations'] = $this->db->table('designations')->where('status', 1)->get()->getResultArray();
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('hr/payroll/reports/custom_report', $data);
        echo view('template/footer');
    }

    // SOCSO Report
    public function socsoReport()
    {
        $month = $this->request->getGet('month') ?? date('Y-m');
        $format = $this->request->getGet('format') ?? 'pdf';
        
        $data['title'] = 'SOCSO Report - ' . date('F Y', strtotime($month . '-01'));
        $data['month'] = $month;
        
        $data['socso_data'] = $this->db->table('payroll')
            ->select('payroll.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name, 
                    staff_statutory_details.socso_number')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->join('staff_statutory_details', 'staff_statutory_details.staff_id = payroll.staff_id', 'left')
            ->where('payroll_month', $month)
            ->where('socso_employee >', 0)
            ->get()->getResultArray();
        
        $data['totals'] = [
            'employee' => array_sum(array_column($data['socso_data'], 'socso_employee')),
            'employer' => array_sum(array_column($data['socso_data'], 'socso_employer'))
        ];
        
        if ($format == 'pdf') {
            echo view('template/header');
            echo view('template/sidebar');
            echo view('hr/payroll/reports/socso_report', $data);
            echo view('template/footer');
        } else {
            $this->exportSOCSOReport($data);
        }
    }

    // EIS Report
    public function eisReport()
    {
        $month = $this->request->getGet('month') ?? date('Y-m');
        $format = $this->request->getGet('format') ?? 'pdf';
        
        $data['title'] = 'EIS Report - ' . date('F Y', strtotime($month . '-01'));
        $data['month'] = $month;
        
        $data['eis_data'] = $this->db->table('payroll')
            ->select('payroll.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name,
                    staff_statutory_details.eis_number')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->join('staff_statutory_details', 'staff_statutory_details.staff_id = payroll.staff_id', 'left')
            ->where('payroll_month', $month)
            ->where('eis_employee >', 0)
            ->get()->getResultArray();
        
        $data['totals'] = [
            'employee' => array_sum(array_column($data['eis_data'], 'eis_employee')),
            'employer' => array_sum(array_column($data['eis_data'], 'eis_employer'))
        ];
        
        if ($format == 'pdf') {
            echo view('template/header');
            echo view('template/sidebar');
            echo view('hr/payroll/reports/eis_report', $data);
            echo view('template/footer');
        } else {
            $this->exportEISReport($data);
        }
    }

    // PCB Report
    public function pcbReport()
    {
        $month = $this->request->getGet('month') ?? date('Y-m');
        $format = $this->request->getGet('format') ?? 'pdf';
        
        $data['title'] = 'PCB Report - ' . date('F Y', strtotime($month . '-01'));
        $data['month'] = $month;
        
        $data['pcb_data'] = $this->db->table('payroll')
            ->select('payroll.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name,
                    staff_statutory_details.income_tax_number, staff_statutory_details.pcb_code')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->join('staff_statutory_details', 'staff_statutory_details.staff_id = payroll.staff_id', 'left')
            ->where('payroll_month', $month)
            ->where('pcb >', 0)
            ->get()->getResultArray();
        
        $data['total_pcb'] = array_sum(array_column($data['pcb_data'], 'pcb'));
        
        if ($format == 'pdf') {
            echo view('template/header');
            echo view('template/sidebar');
            echo view('hr/payroll/reports/pcb_report', $data);
            echo view('template/footer');
        } else {
            $this->exportPCBReport($data);
        }
    }

    // EA Form Report
    public function eaReport()
    {
        $year = $this->request->getGet('year') ?? date('Y', strtotime('-1 year'));
        $staffId = $this->request->getGet('staff_id') ?? null;
        
        $data['title'] = 'EA Form - Year ' . $year;
        $data['year'] = $year;
        
        if ($staffId) {
            // Generate EA for specific staff
            $data['ea_data'] = $this->generateEAForm($staffId, $year);
            echo view('hr/payroll/reports/ea_form', $data);
        } else {
            // Show list of staff to generate EA
            $data['staff_list'] = $this->db->table('payroll')
                ->distinct()
                ->select('staff_details.id, staff_details.staff_code, staff_details.first_name, staff_details.last_name')
                ->join('staff_details', 'staff_details.id = payroll.staff_id')
                ->where("payroll_month LIKE '{$year}%'")
                ->get()->getResultArray();
            
            echo view('template/header');
            echo view('template/sidebar');
            echo view('hr/payroll/reports/ea_list', $data);
            echo view('template/footer');
        }
    }

    // Generate EA Form data for a staff
    private function generateEAForm($staffId, $year)
    {
        // Get staff details with additional info
        $staff = $this->db->table('staff_details')
            ->select('staff_details.*, staff_statutory_details.income_tax_number, staff_statutory_details.epf_number')
            ->join('staff_statutory_details', 'staff_statutory_details.staff_id = staff_details.id', 'left')
            ->where('staff_details.id', $staffId)
            ->get()->getRowArray();
        
        // Get yearly payroll summary
        $payrollSummary = $this->db->table('payroll')
            ->select('SUM(basic_salary) as total_basic,
                    SUM(total_allowances) as total_allowances,
                    SUM(gross_salary) as total_gross,
                    SUM(epf_employee) as total_epf,
                    SUM(socso_employee) as total_socso,
                    SUM(pcb) as total_pcb,
                    SUM(total_commission) as total_commission,
                    SUM(net_salary) as total_net')
            ->where('staff_id', $staffId)
            ->where("payroll_month LIKE '{$year}%'")
            ->get()->getRowArray();
        
        // Get monthly breakdown
        $monthlyBreakdown = $this->db->table('payroll')
            ->where('staff_id', $staffId)
            ->where("payroll_month LIKE '{$year}%'")
            ->orderBy('payroll_month')
            ->get()->getResultArray();
        
        // Calculate taxable income
        $taxableIncome = $payrollSummary['total_gross'] - $payrollSummary['total_epf'];
        
        // Get allowances breakdown
        $allowances = $this->db->table('payroll_details')
            ->select('component_name, SUM(amount) as total_amount')
            ->join('payroll', 'payroll.id = payroll_details.payroll_id')
            ->where('payroll.staff_id', $staffId)
            ->where("payroll.payroll_month LIKE '{$year}%'")
            ->where('payroll_details.component_type', 'earning')
            ->where('payroll_details.component_name !=', 'Basic Salary')
            ->groupBy('component_name')
            ->get()->getResultArray();
        
        return [
            'staff' => $staff,
            'summary' => $payrollSummary,
            'monthly' => $monthlyBreakdown,
            'taxable_income' => $taxableIncome,
            'allowances' => $allowances,
            'year' => $year
        ];
    }

    // Export functions for CSV format
    private function exportMonthlyReport($data, $format)
    {
        $filename = 'monthly_payroll_' . $data['month'] . '.' . ($format == 'excel' ? 'xlsx' : 'csv');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Summary section
        fputcsv($output, ['MONTHLY PAYROLL REPORT - ' . date('F Y', strtotime($data['month'] . '-01'))]);
        fputcsv($output, []);
        fputcsv($output, ['Total Staff:', count($data['payroll'])]);
        fputcsv($output, ['Total Gross Salary:', number_format($data['summary']['total_gross'], 2)]);
        fputcsv($output, ['Total Net Salary:', number_format($data['summary']['total_net'], 2)]);
        fputcsv($output, []);
        
        // Department Summary
        fputcsv($output, ['DEPARTMENT SUMMARY']);
        fputcsv($output, ['Department', 'Staff Count', 'Total Gross', 'Total Net']);
        foreach ($data['department_summary'] as $dept) {
            fputcsv($output, [
                $dept['department_name'] ?? 'No Department',
                $dept['staff_count'],
                number_format($dept['total_gross'], 2),
                number_format($dept['total_net'], 2)
            ]);
        }
        fputcsv($output, []);
        
        // Detailed payroll
        fputcsv($output, ['DETAILED PAYROLL']);
        fputcsv($output, [
            'Staff Code', 'Name', 'Basic Salary', 'Allowances', 'Deductions',
            'Gross Salary', 'EPF', 'SOCSO', 'EIS', 'PCB', 'Net Salary'
        ]);
        
        foreach ($data['payroll'] as $row) {
            fputcsv($output, [
                $row['staff_code'],
                $row['first_name'] . ' ' . $row['last_name'],
                number_format($row['basic_salary'], 2),
                number_format($row['total_allowances'], 2),
                number_format($row['total_deductions'], 2),
                number_format($row['gross_salary'], 2),
                number_format($row['epf_employee'], 2),
                number_format($row['socso_employee'], 2),
                number_format($row['eis_employee'], 2),
                number_format($row['pcb'], 2),
                number_format($row['net_salary'], 2)
            ]);
        }
        
        fclose($output);
        exit;
    }

    private function exportStatutoryReport($data, $format)
    {
        $filename = 'statutory_report_' . $data['month'] . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Headers based on report type
        if ($data['type'] == 'all' || $data['type'] == 'epf') {
            fputcsv($output, ['EPF CONTRIBUTION REPORT - ' . date('F Y', strtotime($data['month'] . '-01'))]);
            fputcsv($output, ['Staff Code', 'Name', 'EPF No', 'Wages', 'Employee', 'Employer', 'Total']);
            
            foreach ($data['statutory_data'] as $row) {
                if ($row['epf_employee'] > 0) {
                    fputcsv($output, [
                        $row['staff_code'],
                        $row['first_name'] . ' ' . $row['last_name'],
                        $row['epf_number'] ?? '',
                        number_format($row['gross_salary'], 2),
                        number_format($row['epf_employee'], 2),
                        number_format($row['epf_employer'], 2),
                        number_format($row['epf_employee'] + $row['epf_employer'], 2)
                    ]);
                }
            }
            
            fputcsv($output, []);
            fputcsv($output, ['TOTAL', '', '', '', 
                number_format($data['totals']['epf_employee'], 2),
                number_format($data['totals']['epf_employer'], 2),
                number_format($data['totals']['epf_employee'] + $data['totals']['epf_employer'], 2)
            ]);
        }
        
        fclose($output);
        exit;
    }

    // Generate Custom Report based on criteria
    private function generateCustomReport($criteria)
    {
        $query = $this->db->table('payroll')
            ->select('payroll.*, staff_details.*, departments.department_name, designations.designation_name')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->join('departments', 'departments.id = staff_details.department_id', 'left')
            ->join('designations', 'designations.id = staff_details.designation_id', 'left');
        
        // Apply filters
        if (!empty($criteria['month_from'])) {
            $query->where('payroll_month >=', $criteria['month_from']);
        }
        if (!empty($criteria['month_to'])) {
            $query->where('payroll_month <=', $criteria['month_to']);
        }
        if (!empty($criteria['department_id'])) {
            $query->where('staff_details.department_id', $criteria['department_id']);
        }
        if (!empty($criteria['designation_id'])) {
            $query->where('staff_details.designation_id', $criteria['designation_id']);
        }
        if (!empty($criteria['staff_type'])) {
            $query->where('staff_details.staff_type', $criteria['staff_type']);
        }
        
        return $query->get()->getResultArray();
    }
    private function exportSOCSOReport($data)
    {
        $filename = 'socso_report_' . $data['month'] . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header
        fputcsv($output, ['SOCSO CONTRIBUTION REPORT - ' . date('F Y', strtotime($data['month'] . '-01'))]);
        fputcsv($output, []);
        fputcsv($output, ['No', 'Staff Code', 'Name', 'SOCSO No', 'Wages', 'Employee', 'Employer', 'Total']);
        
        $no = 1;
        foreach ($data['socso_data'] as $row) {
            fputcsv($output, [
                $no++,
                $row['staff_code'],
                $row['first_name'] . ' ' . $row['last_name'],
                $row['socso_number'] ?? '',
                number_format($row['gross_salary'], 2),
                number_format($row['socso_employee'], 2),
                number_format($row['socso_employer'], 2),
                number_format($row['socso_employee'] + $row['socso_employer'], 2)
            ]);
        }
        
        // Totals
        fputcsv($output, []);
        fputcsv($output, ['', '', '', 'TOTAL', '', 
            number_format($data['totals']['employee'], 2),
            number_format($data['totals']['employer'], 2),
            number_format($data['totals']['employee'] + $data['totals']['employer'], 2)
        ]);
        
        fclose($output);
        exit;
    }

    // Export EIS Report
    private function exportEISReport($data)
    {
        $filename = 'eis_report_' . $data['month'] . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header
        fputcsv($output, ['EIS CONTRIBUTION REPORT - ' . date('F Y', strtotime($data['month'] . '-01'))]);
        fputcsv($output, []);
        fputcsv($output, ['No', 'Staff Code', 'Name', 'EIS No', 'Wages', 'Employee', 'Employer', 'Total']);
        
        $no = 1;
        foreach ($data['eis_data'] as $row) {
            fputcsv($output, [
                $no++,
                $row['staff_code'],
                $row['first_name'] . ' ' . $row['last_name'],
                $row['eis_number'] ?? '',
                number_format($row['gross_salary'], 2),
                number_format($row['eis_employee'], 2),
                number_format($row['eis_employer'], 2),
                number_format($row['eis_employee'] + $row['eis_employer'], 2)
            ]);
        }
        
        // Totals
        fputcsv($output, []);
        fputcsv($output, ['', '', '', 'TOTAL', '', 
            number_format($data['totals']['employee'], 2),
            number_format($data['totals']['employer'], 2),
            number_format($data['totals']['employee'] + $data['totals']['employer'], 2)
        ]);
        
        fclose($output);
        exit;
    }

    // Export PCB Report
    private function exportPCBReport($data)
    {
        $filename = 'pcb_report_' . $data['month'] . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header
        fputcsv($output, ['PCB DEDUCTION REPORT - ' . date('F Y', strtotime($data['month'] . '-01'))]);
        fputcsv($output, []);
        fputcsv($output, ['No', 'Staff Code', 'Name', 'Income Tax No', 'PCB Code', 'Gross Salary', 'PCB Amount']);
        
        $no = 1;
        foreach ($data['pcb_data'] as $row) {
            fputcsv($output, [
                $no++,
                $row['staff_code'],
                $row['first_name'] . ' ' . $row['last_name'],
                $row['income_tax_number'] ?? '',
                $row['pcb_code'] ?? '',
                number_format($row['gross_salary'], 2),
                number_format($row['pcb'], 2)
            ]);
        }
        
        // Total
        fputcsv($output, []);
        fputcsv($output, ['', '', '', '', 'TOTAL', '', number_format($data['total_pcb'], 2)]);
        
        fclose($output);
        exit;
    }

    // Export EPF Report (update the existing epf_report method to handle CSV)
    private function exportEPFReport($data)
    {
        $filename = 'epf_report_' . $data['month'] . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header
        fputcsv($output, ['EPF CONTRIBUTION REPORT - ' . date('F Y', strtotime($data['month'] . '-01'))]);
        fputcsv($output, []);
        fputcsv($output, ['No', 'Staff Code', 'Name', 'EPF No', 'Wages', 'Employee', 'Employer', 'Total']);
        
        $no = 1;
        $totalEmployee = 0;
        $totalEmployer = 0;
        
        foreach ($data['epf_data'] as $row) {
            $totalEmployee += $row['epf_employee'];
            $totalEmployer += $row['epf_employer'];
            
            fputcsv($output, [
                $no++,
                $row['staff_code'],
                $row['first_name'] . ' ' . $row['last_name'],
                $row['epf_number'] ?? '',
                number_format($row['gross_salary'], 2),
                number_format($row['epf_employee'], 2),
                number_format($row['epf_employer'], 2),
                number_format($row['epf_employee'] + $row['epf_employer'], 2)
            ]);
        }
        
        // Totals
        fputcsv($output, []);
        fputcsv($output, ['', '', '', 'TOTAL', '', 
            number_format($totalEmployee, 2),
            number_format($totalEmployer, 2),
            number_format($totalEmployee + $totalEmployer, 2)
        ]);
        
        fclose($output);
        exit;
    }

    // Update the existing epf_report method to handle CSV export
    public function epfReport()
    {
        $month = $this->request->getGet('month') ?? date('Y-m');
        $format = $this->request->getGet('format') ?? 'pdf';
        
        $data['title'] = 'EPF Report - ' . date('F Y', strtotime($month . '-01'));
        $data['month'] = $month;
        
        $data['epf_data'] = $this->db->table('payroll')
            ->select('payroll.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name, staff_statutory_details.epf_number')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->join('staff_statutory_details', 'staff_statutory_details.staff_id = payroll.staff_id', 'left')
            ->where('payroll_month', $month)
            ->where('epf_employee >', 0)
            ->get()->getResultArray();

        if ($format == 'csv') {
            $this->exportEPFReport($data);
        } else {
            echo view('template/header');
            echo view('template/sidebar');
            echo view('hr/payroll/reports/epf_report', $data);
            echo view('template/footer');
        }
    }

    public function generateAllEa($year)
    {
        // Get all staff who have payroll records for the year
        $staffList = $this->db->table('payroll')
            ->distinct()
            ->select('staff_details.id')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->where("payroll_month LIKE '{$year}%'")
            ->get()->getResultArray();
        
        $data['year'] = $year;
        $data['ea_forms'] = [];
        
        foreach ($staffList as $staff) {
            $data['ea_forms'][] = $this->generateEAForm($staff['id'], $year);
        }
        
        // Generate batch EA forms view
        echo view('hr/payroll/reports/ea_forms_batch', $data);
    }
    
    
    // Approve all pending payslips for a month
    public function approveMonth($month)
    {
        $paymentMethod = $this->request->getPost('payment_method') ?? 'bank_transfer';
        $paymentDate = $this->request->getPost('payment_date') ?? date('Y-m-d');
        
        // Get all pending payslips for the month
        $pendingPayslips = $this->db->table('payroll')
            ->where('payroll_month', $month)
            ->where('payment_status', 'pending')
            ->get()->getResultArray();
        
        if (empty($pendingPayslips)) {
            return redirect()->back()->with('error', 'No pending payslips found for this month');
        }
        
        $this->db->transStart();
        
        $data = [
            'payment_status' => 'paid',
            'payment_date' => $paymentDate,
            'payment_method' => $paymentMethod
        ];
        
        // Update all pending payslips
        $this->db->table('payroll')
            ->where('payroll_month', $month)
            ->where('payment_status', 'pending')
            ->update($data);
        
        $this->db->transComplete();
        
        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Failed to approve payslips');
        }
        
        $count = count($pendingPayslips);
        return redirect()->back()->with('success', "$count payslips approved successfully");
    }
    
    // Reject payslip
    public function reject($payrollId)
    {
        $payroll = $this->payrollModel->find($payrollId);
        
        if (!$payroll) {
            return redirect()->back()->with('error', 'Payroll record not found');
        }
        
        if ($payroll['payment_status'] == 'paid') {
            return redirect()->back()->with('error', 'Cannot reject an approved payslip');
        }
        
        $data = [
            'payment_status' => 'cancelled'
        ];
        
        if ($this->payrollModel->update($payrollId, $data)) {
            return redirect()->back()->with('success', 'Payslip cancelled successfully');
        }
        
        return redirect()->back()->with('error', 'Failed to cancel payslip');
    }
    
    
    // Get payment summary for dashboard
    public function getPaymentSummary($month)
    {
        $summary = $this->db->table('payroll')
            ->select('payment_status, COUNT(*) as count, SUM(net_salary) as total')
            ->where('payroll_month', $month)
            ->groupBy('payment_status')
            ->get()->getResultArray();
        
        $result = [
            'pending' => ['count' => 0, 'total' => 0],
            'paid' => ['count' => 0, 'total' => 0],
            'cancelled' => ['count' => 0, 'total' => 0]
        ];
        
        foreach ($summary as $row) {
            $result[$row['payment_status']] = [
                'count' => $row['count'],
                'total' => $row['total']
            ];
        }
        
        return $result;
    }

// Replace your current approve method (line 1299) with this:

    public function approve()
    {
        // Check if it's a POST request
        if ($this->request->getMethod() != 'post') {
            return redirect()->back()->with('error', 'Invalid request method');
        }
        
        // Get data from POST
        $payrollId = $this->request->getPost('payroll_id');
        $approvalDate = $this->request->getPost('approval_date');
        
        if (!$payrollId || !$approvalDate) {
            return redirect()->back()->with('error', 'Missing required data');
        }
        
        $payroll = $this->payrollModel->find($payrollId);
        
        if (!$payroll) {
            return redirect()->back()->with('error', 'Payroll record not found');
        }
        
        if ($payroll['payment_status'] != 'pending') {
            return redirect()->back()->with('error', 'Only pending payslips can be approved');
        }
        
        $data = [
            'payment_status' => 'approved',
            'approved_date' => $approvalDate . ' 00:00:00',
            'approved_by' => $this->session->get('log_id')
        ];
        
        $this->db->transStart();
        
        // Update payroll
        $this->payrollModel->update($payrollId, $data);
        
        // Log approval
        $this->db->table('payroll_approval_log')->insert([
            'payroll_id' => $payrollId,
            'action' => 'approved',
            'action_date' => $approvalDate . ' 00:00:00',
            'action_by' => $this->session->get('log_id')
        ]);
        
        // Migrate to accounting if enabled
        $migrationResult = $this->migrateToAccounting($payrollId);
        $migrationMessage = $migrationResult ? ' and migrated to accounting' : '';

        $this->db->transComplete();
        
        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Failed to approve payslip');
        }
        
        return redirect()->back()->with('success', 'Payslip approved successfully' . $migrationMessage);
    }
    
    // Bulk approve payslips (Step 1)
    public function bulkApprove()
    {
        $payrollIds = $this->request->getPost('payroll_ids');
        
        if (empty($payrollIds)) {
            return redirect()->back()->with('error', 'No payslips selected');
        }
        
        $this->db->transStart();
        
        $data = [
            'payment_status' => 'approved',
            'approved_date' => date('Y-m-d H:i:s'),
            'approved_by' => $this->session->get('log_id')
        ];
        
        $successCount = 0;
        $migratedCount = 0;
        
        foreach ($payrollIds as $id) {
            // Check if pending
            $payroll = $this->payrollModel->find($id);
            if ($payroll && $payroll['payment_status'] == 'pending') {
                $this->payrollModel->update($id, $data);
                
                // Log approval
                $this->db->table('payroll_approval_log')->insert([
                    'payroll_id' => $id,
                    'action' => 'approved',
                    'action_date' => date('Y-m-d H:i:s'),
                    'action_by' => $this->session->get('log_id')
                ]);
                
                $successCount++;
                
                // Migrate to accounting if enabled
                if ($this->migrateToAccounting($id)) {
                    $migratedCount++;
                }
            }
        }
        
        $this->db->transComplete();
        
        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Failed to approve payslips');
        }
        
        $message = "$successCount payslips approved successfully";
        if ($migratedCount > 0) {
            $message .= " and $migratedCount migrated to accounting";
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    // Payment section - show approved payrolls awaiting payment
    public function payment($month = null)
    {
        $data['title'] = 'Process Payroll Payments';
        $data['month'] = $month ?? date('Y-m');
        
        // Get approved but unpaid payrolls
        $data['payrolls'] = $this->db->table('payroll')
            ->select('payroll.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name,
                    staff_bank_details.bank_name, staff_bank_details.account_number')
            ->join('staff_details', 'staff_details.id = payroll.staff_id')
            ->join('staff_bank_details', 'staff_bank_details.staff_id = payroll.staff_id AND staff_bank_details.is_primary = 1', 'left')
            ->where('payroll.payment_status', 'approved')
            ->where('payroll.payroll_month', $data['month'])
            ->get()->getResultArray();
        
        // Get active payment modes
        $data['payment_modes'] = $this->db->table('payment_mode')
            ->where('status', 1)
            ->orderBy('menu_order', 'ASC')
            ->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/payroll/payment', $data);
        echo view('template/footer');
    }
    
    // Process payment (Step 2)
    public function processPayment()
    {
        $payrollIds = $this->request->getPost('payroll_ids');
        $paymentDate = $this->request->getPost('payment_date');
        $paymentModeId = $this->request->getPost('payment_mode_id');
        
        if (empty($payrollIds)) {
            return redirect()->back()->with('error', 'No payslips selected for payment');
        }
        
        // Get payment mode details
        $paymentMode = $this->db->table('payment_mode')
            ->where('id', $paymentModeId)
            ->get()->getRowArray();
        
        if (!$paymentMode) {
            return redirect()->back()->with('error', 'Invalid payment mode');
        }
        
        $this->db->transStart();
        
        $data = [
            'payment_status' => 'paid',
            'payment_date' => $paymentDate,
            'payment_method' => $paymentMode['name'],
            'payment_mode_id' => $paymentModeId
        ];
        
        $successCount = 0;
        $totalAmount = 0;
        $migratedCount = 0;
        
        foreach ($payrollIds as $id) {
            // Check if approved
            $payroll = $this->payrollModel->find($id);
            if ($payroll && $payroll['payment_status'] == 'approved') {
                $this->payrollModel->update($id, $data);
                
                // Log payment
                $this->db->table('payroll_approval_log')->insert([
                    'payroll_id' => $id,
                    'action' => 'paid',
                    'action_date' => $paymentDate . ' ' . date('H:i:s'),
                    'action_by' => $this->session->get('log_id'),
                    'remarks' => 'Payment Mode: ' . $paymentMode['name']
                ]);
                
                $successCount++;
                $totalAmount += $payroll['net_salary'];
                
                // Migrate payment to accounting if enabled
                if ($this->migratePaymentToAccounting($id, $paymentModeId)) {
                    $migratedCount++;
                }
            }
        }
        
        $this->db->transComplete();
        
        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Failed to process payments');
        }
        
        $message = "$successCount payments processed successfully. Total Amount: RM " . number_format($totalAmount, 2);
        if ($migratedCount > 0) {
            $message .= " and $migratedCount migrated to accounting";
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    // Updated view method to show all statuses
    public function view($month)
    {
        $data['title'] = 'Payroll - ' . date('F Y', strtotime($month . '-01'));
        $data['month'] = $month;
        $data['payroll'] = $this->payrollModel->getMonthlyPayroll($month);
        
        // Get summary by status
        $statusSummary = $this->db->table('payroll')
            ->select('payment_status, COUNT(*) as count, SUM(net_salary) as total')
            ->where('payroll_month', $month)
            ->groupBy('payment_status')
            ->get()->getResultArray();
        
        $data['status_summary'] = [
            'pending' => ['count' => 0, 'total' => 0],
            'approved' => ['count' => 0, 'total' => 0],
            'paid' => ['count' => 0, 'total' => 0],
            'cancelled' => ['count' => 0, 'total' => 0]
        ];
        
        foreach ($statusSummary as $status) {
            $data['status_summary'][$status['payment_status']] = [
                'count' => $status['count'],
                'total' => $status['total']
            ];
        }
        
        $data['summary'] = $this->payrollModel->getPayrollSummary($month);
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/payroll/view', $data);
        echo view('template/footer');
    }
    
    // Get payment modes for AJAX
    public function getPaymentModes()
    {
        $paymentModes = $this->db->table('payment_mode')
            ->where('status', 1)
            ->orderBy('menu_order', 'ASC')
            ->get()->getResultArray();
        
        return $this->response->setJSON($paymentModes);
    }

    public function approveAllPending()
    {
        $month = $this->request->getPost('month');
        $approvalDate = $this->request->getPost('approval_date');
        
        if (empty($month) || empty($approvalDate)) {
            return redirect()->back()->with('error', 'Month and approval date are required');
        }
        
        // Get all pending payslips for the month
        $pendingPayslips = $this->db->table('payroll')
            ->where('payroll_month', $month)
            ->where('payment_status', 'pending')
            ->get()->getResultArray();
        
        if (empty($pendingPayslips)) {
            return redirect()->back()->with('error', 'No pending payslips found for this month');
        }
        
        $this->db->transStart();
        
        // Update data with proper format for approved_date and approved_by
        $data = [
            'payment_status' => 'approved',
            'approved_date' => $approvalDate . ' 00:00:00',
            'approved_by' => $this->session->get('log_id')
        ];
        
        $successCount = 0;
        $migratedCount = 0;
        
        foreach ($pendingPayslips as $payslip) {
            // Update payroll
            $this->payrollModel->update($payslip['id'], $data);
            
            // Log approval
            $this->db->table('payroll_approval_log')->insert([
                'payroll_id' => $payslip['id'],
                'action' => 'approved',
                'action_date' => $approvalDate . ' ' . date('H:i:s'),
                'action_by' => $this->session->get('log_id')
            ]);
            
            $successCount++;
            
            // Migrate to accounting if enabled
            if ($this->migrateToAccounting($payslip['id'])) {
                $migratedCount++;
            }
        }
        
        $this->db->transComplete();
        
        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Failed to approve payslips');
        }
        
        $message = "$successCount payslips approved successfully";
        if ($migratedCount > 0) {
            $message .= " and $migratedCount migrated to accounting";
        }
        
        return redirect()->back()->with('success', $message);
    }

    private function migrateToAccounting($payrollId)
    {
        // Check if account migration is enabled
        $accountSettings = $this->db->table('statutory_account_settings')
            ->where('is_account_migrate', 1)
            ->get()->getRowArray();
        
        if (!$accountSettings) {
            return false;
        }
        
        // Get payroll details
        $payroll = $this->payrollModel->find($payrollId);
        if (!$payroll || $payroll['is_payroll_migration'] == 1) {
            return false; // Already migrated or not found
        }
        
        // Get staff details
        $staff = $this->db->table('staff_details')
            ->where('id', $payroll['staff_id'])
            ->get()->getRowArray();
        
        // Check if staff has ledger_id, if not create one
        if (!$staff['ledger_id']) {
            // Find the accrual group (ac = 1)
            $accrualGroup = $this->db->table('groups')
                ->where('ac', 1)
                ->get()->getRowArray();
            
            if (!$accrualGroup) {
                // If no accrual group found, use the ACCRUAL group
                $accrualGroup = $this->db->table('groups')
                    ->where('code', '2200') // ACCRUAL group code from your data
                    ->get()->getRowArray();
            }
            
            if ($accrualGroup) {
                // Generate ledger code
                $lastLedger = $this->db->table('ledgers')
                    ->where('group_id', $accrualGroup['id'])
                    ->orderBy('right_code', 'DESC')
                    ->get()->getRowArray();
                
                if ($lastLedger && $lastLedger['right_code']) {
                    $nextCode = str_pad((int)$lastLedger['right_code'] + 1, 4, '0', STR_PAD_LEFT);
                } else {
                    $nextCode = '0001';
                }
                
                // Create ledger
                $ledgerData = [
                    'group_id' => $accrualGroup['id'],
                    'name' => $staff['first_name'] . ' ' . $staff['last_name'] . ' - ' . $staff['staff_code'],
                    'left_code' => $accrualGroup['code'],
                    'right_code' => $nextCode,
                    'op_balance' => 0,
                    'op_balance_dc' => 'C',
                    'type' => '0',
                    'reconciliation' => '0'
                ];
                
                $this->db->table('ledgers')->insert($ledgerData);
                $ledgerId = $this->db->insertID();
                
                // Update staff with ledger_id
                $this->db->table('staff_details')
                    ->where('id', $staff['id'])
                    ->update(['ledger_id' => $ledgerId]);
                
                $staff['ledger_id'] = $ledgerId;
            } else {
                log_message('error', 'No accrual group found for staff ledger creation');
                return false;
            }
        }
        
        $this->db->transStart();
        
        try {
            // Calculate gross salary (Basic + Allowances + Commission - Deductions)
            $grossSalary = $payroll['basic_salary'] + $payroll['total_allowances'] + $payroll['total_commission'];
            
            // Calculate total credit amount (should match gross salary)
            $totalCredits = $payroll['epf_employee'] + $payroll['socso_employee'] + 
                           $payroll['eis_employee'] + $payroll['pcb'] + 
                           $payroll['total_deductions'] + $payroll['net_salary'];
            
            // Generate entry code
            $yearMonth = date('y', strtotime($payroll['payroll_month'] . '-01')) . 
                        date('m', strtotime($payroll['payroll_month'] . '-01'));
            
            // Get the last entry for this month and entry type
            $lastEntry = $this->db->table('entries')
                ->where('entrytype_id', 4) // Journal Entry
                ->where("entry_code LIKE 'JOR{$yearMonth}%'")
                ->orderBy('entry_code', 'DESC')
                ->get()->getRowArray();
            
            if ($lastEntry && strlen($lastEntry['entry_code']) >= 12) {
                // Extract the serial number from the last entry code
                $lastSerial = (int)substr($lastEntry['entry_code'], -5);
                $newSerial = $lastSerial + 1;
            } else {
                $newSerial = 1;
            }
            
            $entryCode = 'JOR' . $yearMonth . str_pad($newSerial, 5, '0', STR_PAD_LEFT);
            
            // Get next entry number for the entry type
            $lastEntryNumber = $this->db->table('entries')
                ->where('entrytype_id', 4) // Journal Entry
                ->orderBy('number', 'DESC')
                ->get()->getRowArray();
            
            $entryNumber = $lastEntryNumber ? ((int)$lastEntryNumber['number'] + 1) : 1;
            
            // Create main entry
            $entryData = [
                'entrytype_id' => 4, // Journal Entry (JOR)
                'number' => $entryNumber,
                'date' => date('Y-m-d', strtotime($payroll['approved_date'])),
                'dr_total' => $grossSalary,
                'cr_total' => $grossSalary,
                'narration' => 'Payroll for ' . $staff['first_name'] . ' ' . $staff['last_name'] . ' - ' . date('F Y', strtotime($payroll['payroll_month'] . '-01')),
                'inv_id' => $payrollId,
                'type' => 20, // Payroll type
                'fund_id' => 1,
                'payment' => 'Payroll Migration',
                'entry_code' => $entryCode,
                'status' => 'POSTED',
                'entry_by' => $this->session->get('log_id')
            ];
            
            $this->db->table('entries')->insert($entryData);
            $entryId = $this->db->insertID();
            
            // Create entry items
            $entryItems = [];
            
            // Credit entries (Liabilities)
            if ($payroll['epf_employee'] > 0 && $accountSettings['epf_ledger_id']) {
                $entryItems[] = [
                    'entry_id' => $entryId,
                    'ledger_id' => $accountSettings['epf_ledger_id'],
                    'amount' => $payroll['epf_employee'],
                    'dc' => 'C',
                    'narration' => 'EPF Employee Contribution'
                ];
            }
            
            if ($payroll['socso_employee'] > 0 && $accountSettings['socso_ledger_id']) {
                $entryItems[] = [
                    'entry_id' => $entryId,
                    'ledger_id' => $accountSettings['socso_ledger_id'],
                    'amount' => $payroll['socso_employee'],
                    'dc' => 'C',
                    'narration' => 'SOCSO Employee Contribution'
                ];
            }
            
            if ($payroll['eis_employee'] > 0 && $accountSettings['eis_ledger_id']) {
                $entryItems[] = [
                    'entry_id' => $entryId,
                    'ledger_id' => $accountSettings['eis_ledger_id'],
                    'amount' => $payroll['eis_employee'],
                    'dc' => 'C',
                    'narration' => 'EIS Employee Contribution'
                ];
            }
            
            if ($payroll['pcb'] > 0 && $accountSettings['pcb_ledger_id']) {
                $entryItems[] = [
                    'entry_id' => $entryId,
                    'ledger_id' => $accountSettings['pcb_ledger_id'],
                    'amount' => $payroll['pcb'],
                    'dc' => 'C',
                    'narration' => 'PCB/Income Tax'
                ];
            }
            
            // Add deductions if any
            if ($payroll['total_deductions'] > 0 && $accountSettings['deduction_ledger_id']) {
                $entryItems[] = [
                    'entry_id' => $entryId,
                    'ledger_id' => $accountSettings['deduction_ledger_id'],
                    'amount' => $payroll['total_deductions'],
                    'dc' => 'C',
                    'narration' => 'Other Deductions'
                ];
            }
            
            // Net Salary Payable - Use staff's ledger_id
            if ($staff['ledger_id']) {
                $entryItems[] = [
                    'entry_id' => $entryId,
                    'ledger_id' => $staff['ledger_id'],
                    'amount' => $payroll['net_salary'],
                    'dc' => 'C',
                    'narration' => 'Net Salary Payable'
                ];
            }
            
            // Debit entry (Expense)
            if ($accountSettings['salary_expense_ledger_id']) {
                $entryItems[] = [
                    'entry_id' => $entryId,
                    'ledger_id' => $accountSettings['salary_expense_ledger_id'],
                    'amount' => $grossSalary,
                    'dc' => 'D',
                    'narration' => 'Salary Expense'
                ];
            }
            
            // Insert all entry items
            if (!empty($entryItems)) {
                $this->db->table('entryitems')->insertBatch($entryItems);
            }
            
            // Update payroll as migrated
            $this->payrollModel->update($payrollId, ['is_payroll_migration' => 1]);
            
            $this->db->transComplete();
            
            return $this->db->transStatus();
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Payroll migration failed: ' . $e->getMessage());
            return false;
        }
    }

    private function migratePaymentToAccounting($payrollId, $paymentModeId)
    {
        // Check if account migration is enabled
        $accountSettings = $this->db->table('statutory_account_settings')
            ->where('is_account_migrate', 1)
            ->get()->getRowArray();
        
        if (!$accountSettings) {
            return false;
        }
        
        // Get payroll details
        $payroll = $this->payrollModel->find($payrollId);
        if (!$payroll || $payroll['is_payment_migration'] == 1) {
            return false; // Already migrated or not found
        }
        
        // Get staff details
        $staff = $this->db->table('staff_details')
            ->where('id', $payroll['staff_id'])
            ->get()->getRowArray();
        
        if (!$staff['ledger_id']) {
            log_message('error', 'Staff ledger not found for payment migration');
            return false;
        }
        
        // Get payment mode details
        $paymentMode = $this->db->table('payment_mode')
            ->where('id', $paymentModeId)
            ->get()->getRowArray();
        
        if (!$paymentMode || !$paymentMode['ledger_id']) {
            log_message('error', 'Payment mode or its ledger not found');
            return false;
        }
        
        $this->db->transStart();
        
        try {
            // Generate entry code for Payment
            $yearMonth = date('ym');
            
            // Get the last entry for this month and entry type
            $lastEntry = $this->db->table('entries')
                ->where('entrytype_id', 2) // Payment Entry
                ->where("entry_code LIKE 'PAY{$yearMonth}%'")
                ->orderBy('entry_code', 'DESC')
                ->get()->getRowArray();
            
            if ($lastEntry && strlen($lastEntry['entry_code']) >= 12) {
                // Extract the serial number from the last entry code
                $lastSerial = (int)substr($lastEntry['entry_code'], -5);
                $newSerial = $lastSerial + 1;
            } else {
                $newSerial = 1;
            }
            
            $entryCode = 'PAY' . $yearMonth . str_pad($newSerial, 5, '0', STR_PAD_LEFT);
            
            // Get next entry number for the entry type
            $lastEntryNumber = $this->db->table('entries')
                ->where('entrytype_id', 2) // Payment Entry
                ->orderBy('number', 'DESC')
                ->get()->getRowArray();
            
            $entryNumber = $lastEntryNumber ? ((int)$lastEntryNumber['number'] + 1) : 1;
            
            // Create main entry
            $entryData = [
                'entrytype_id' => 2, // Payment Entry (PAY)
                'number' => $entryNumber,
                'date' => $payroll['payment_date'],
                'dr_total' => $payroll['net_salary'],
                'cr_total' => $payroll['net_salary'],
                'narration' => 'Salary Payment to ' . $staff['first_name'] . ' ' . $staff['last_name'] . ' - ' . date('F Y', strtotime($payroll['payroll_month'] . '-01')),
                'inv_id' => $payrollId,
                'type' => 20, // Payroll type
                'fund_id' => 1,
                'payment' => $paymentMode['name'],
                'paid_to' => $staff['first_name'] . ' ' . $staff['last_name'],
                'entry_code' => $entryCode,
                'status' => 'POSTED',
                'entry_by' => $this->session->get('log_id')
            ];
            
            $this->db->table('entries')->insert($entryData);
            $entryId = $this->db->insertID();
            
            // Create entry items
            $entryItems = [];
            
            // Debit - Staff ledger (clearing the payable)
            $entryItems[] = [
                'entry_id' => $entryId,
                'ledger_id' => $staff['ledger_id'],
                'amount' => $payroll['net_salary'],
                'dc' => 'D',
                'narration' => 'Salary Payment'
            ];
            
            // Credit - Payment mode ledger (cash/bank)
            $entryItems[] = [
                'entry_id' => $entryId,
                'ledger_id' => $paymentMode['ledger_id'],
                'amount' => $payroll['net_salary'],
                'dc' => 'C',
                'narration' => 'Salary Payment via ' . $paymentMode['name']
            ];
            
            // Insert all entry items
            if (!empty($entryItems)) {
                $this->db->table('entryitems')->insertBatch($entryItems);
            }
            
            // Update payroll as payment migrated
            $this->payrollModel->update($payrollId, ['is_payment_migration' => 1]);
            
            $this->db->transComplete();
            
            return $this->db->transStatus();
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Payment migration failed: ' . $e->getMessage());
            return false;
        }
    }

}