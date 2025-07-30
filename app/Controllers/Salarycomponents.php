<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StaffModel;
use App\Models\PermissionModel;

class Salarycomponents extends BaseController
{
    protected $staffModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->model = new PermissionModel();
        $this->staffModel = new StaffModel();
        if( ($this->session->get('login') ) == false && $this->session->get('role') != 1){
            $data['dn_msg'] = 'Please Login';
            return redirect()->to('/login');
        }
    }

    // List all staff with their salary components
    public function index()
    {
        $data['title'] = 'Staff Salary Components';
        
        // Get all active staff with their component counts
        $data['staff'] = $this->db->table('staff_details')
            ->select('staff_details.*, departments.department_name, designations.designation_name,
                    (SELECT COUNT(*) FROM staff_salary_components WHERE staff_id = staff_details.id AND component_type = "allowance" AND status = 1) as allowance_count,
                    (SELECT COUNT(*) FROM staff_salary_components WHERE staff_id = staff_details.id AND component_type = "deduction" AND status = 1) as deduction_count')
            ->join('departments', 'departments.id = staff_details.department_id', 'left')
            ->join('designations', 'designations.id = staff_details.designation_id', 'left')
            ->where('staff_details.status', 'active')
            ->get()->getResultArray();
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('hr/salarycomponents/index', $data);
        echo view('template/footer');
    }

    // Manage salary components for a specific staff
    public function manage($staffId)
    {
        $data['title'] = 'Manage Salary Components';
        $data['staff'] = $this->staffModel->find($staffId);
        
        if (!$data['staff']) {
            return redirect()->to('/salarycomponents')->with('error', 'Staff not found');
        }
        
        // Get current salary components
        $data['current_allowances'] = $this->db->table('staff_salary_components')
            ->select('staff_salary_components.*, allowances.allowance_name, allowances.allowance_type')
            ->join('allowances', 'allowances.id = staff_salary_components.component_id')
            ->where('staff_salary_components.staff_id', $staffId)
            ->where('staff_salary_components.component_type', 'allowance')
            ->where('staff_salary_components.status', 1)
            ->get()->getResultArray();
        
        $data['current_deductions'] = $this->db->table('staff_salary_components')
            ->select('staff_salary_components.*, deductions.deduction_name, deductions.deduction_type')
            ->join('deductions', 'deductions.id = staff_salary_components.component_id')
            ->where('staff_salary_components.staff_id', $staffId)
            ->where('staff_salary_components.component_type', 'deduction')
            ->where('staff_salary_components.status', 1)
            ->get()->getResultArray();
        
        // Get available allowances and deductions
        $data['allowances'] = $this->db->table('allowances')->where('status', 1)->get()->getResultArray();
        $data['deductions'] = $this->db->table('deductions')->where('status', 1)->get()->getResultArray();
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('hr/salarycomponents/manage', $data);
        echo view('template/footer');
    }

    // Add salary component
    public function add()
    {
        $staffId = $this->request->getPost('staff_id');
        $componentType = $this->request->getPost('component_type');
        $componentId = $this->request->getPost('component_id');
        
        // Check if component already exists
        $existing = $this->db->table('staff_salary_components')
            ->where('staff_id', $staffId)
            ->where('component_type', $componentType)
            ->where('component_id', $componentId)
            ->where('status', 1)
            ->get()->getRowArray();
        
        if ($existing) {
            return redirect()->back()->with('error', 'This component is already assigned to the staff');
        }
        
        $data = [
            'staff_id' => $staffId,
            'component_type' => $componentType,
            'component_id' => $componentId,
            'amount' => $this->request->getPost('amount') ?: 0,
            'percentage' => $this->request->getPost('percentage') ?: null,
            'effective_date' => $this->request->getPost('effective_date'),
            'end_date' => $this->request->getPost('end_date') ?: null,
            'status' => 1
        ];
        
        $this->db->table('staff_salary_components')->insert($data);
        
        return redirect()->back()->with('success', ucfirst($componentType) . ' added successfully');
    }

    // Update salary component
    public function update($id)
    {
        $data = [
            'amount' => $this->request->getPost('amount') ?: 0,
            'percentage' => $this->request->getPost('percentage') ?: null,
            'effective_date' => $this->request->getPost('effective_date'),
            'end_date' => $this->request->getPost('end_date') ?: null
        ];
        
        $this->db->table('staff_salary_components')->where('id', $id)->update($data);
        
        return redirect()->back()->with('success', 'Component updated successfully');
    }

    // Remove salary component
    public function remove($id)
    {
        // Soft delete by setting status to 0
        $this->db->table('staff_salary_components')->where('id', $id)->update(['status' => 0]);
        
        return redirect()->back()->with('success', 'Component removed successfully');
    }

    // Bulk assignment
    public function bulk()
    {
        $data['title'] = 'Bulk Salary Component Assignment';
        
        $data['departments'] = $this->db->table('departments')->where('status', 1)->get()->getResultArray();
        $data['designations'] = $this->db->table('designations')->where('status', 1)->get()->getResultArray();
        $data['allowances'] = $this->db->table('allowances')->where('status', 1)->get()->getResultArray();
        $data['deductions'] = $this->db->table('deductions')->where('status', 1)->get()->getResultArray();
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('hr/salarycomponents/bulk', $data);
        echo view('template/footer');
    }

    // Process bulk assignment
    public function processBulk()
    {
        $componentType = $this->request->getPost('component_type');
        $componentId = $this->request->getPost('component_id');
        $departmentId = $this->request->getPost('department_id');
        $designationId = $this->request->getPost('designation_id');
        $staffType = $this->request->getPost('staff_type');
        
        // Build query to get staff
        $query = $this->db->table('staff_details')->where('status', 'active');
        
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }
        if ($designationId) {
            $query->where('designation_id', $designationId);
        }
        if ($staffType) {
            $query->where('staff_type', $staffType);
        }
        
        $staff = $query->get()->getResultArray();
        
        if (empty($staff)) {
            return redirect()->back()->with('error', 'No staff found with the selected criteria');
        }
        
        $this->db->transStart();
        $successCount = 0;
        
        foreach ($staff as $employee) {
            // Check if component already exists
            $existing = $this->db->table('staff_salary_components')
                ->where('staff_id', $employee['id'])
                ->where('component_type', $componentType)
                ->where('component_id', $componentId)
                ->where('status', 1)
                ->get()->getRowArray();
            
            if (!$existing) {
                $data = [
                    'staff_id' => $employee['id'],
                    'component_type' => $componentType,
                    'component_id' => $componentId,
                    'amount' => $this->request->getPost('amount') ?: 0,
                    'percentage' => $this->request->getPost('percentage') ?: null,
                    'effective_date' => $this->request->getPost('effective_date'),
                    'end_date' => $this->request->getPost('end_date') ?: null,
                    'status' => 1
                ];
                
                $this->db->table('staff_salary_components')->insert($data);
                $successCount++;
            }
        }
        
        $this->db->transComplete();
        
        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Failed to assign components');
        }
        
        return redirect()->to('/salarycomponents')->with('success', "Component assigned to $successCount staff members");
    }

    // View salary breakdown for a staff
    public function breakdown($staffId)
    {
        $data['title'] = 'Salary Breakdown';
        $data['staff'] = $this->staffModel->find($staffId);
        
        if (!$data['staff']) {
            return redirect()->to('/salarycomponents')->with('error', 'Staff not found');
        }
        
        // Get allowances
        $data['allowances'] = $this->db->table('staff_salary_components')
            ->select('staff_salary_components.*, allowances.allowance_name, allowances.allowance_type, 
                    allowances.is_taxable, allowances.is_epf_eligible, allowances.is_socso_eligible')
            ->join('allowances', 'allowances.id = staff_salary_components.component_id')
            ->where('staff_salary_components.staff_id', $staffId)
            ->where('staff_salary_components.component_type', 'allowance')
            ->where('staff_salary_components.status', 1)
            ->get()->getResultArray();
        
        // Get deductions
        $data['deductions'] = $this->db->table('staff_salary_components')
            ->select('staff_salary_components.*, deductions.deduction_name, deductions.deduction_type')
            ->join('deductions', 'deductions.id = staff_salary_components.component_id')
            ->where('staff_salary_components.staff_id', $staffId)
            ->where('staff_salary_components.component_type', 'deduction')
            ->where('staff_salary_components.status', 1)
            ->get()->getResultArray();
        
        // Calculate totals
        $data['total_allowances'] = 0;
        $data['total_deductions'] = 0;
        
        foreach ($data['allowances'] as $allowance) {
            if ($allowance['percentage']) {
                $amount = ($data['staff']['basic_salary'] * $allowance['percentage']) / 100;
            } else {
                $amount = $allowance['amount'];
            }
            $data['total_allowances'] += $amount;
        }
        
        foreach ($data['deductions'] as $deduction) {
            if ($deduction['percentage']) {
                $amount = ($data['staff']['basic_salary'] * $deduction['percentage']) / 100;
            } else {
                $amount = $deduction['amount'];
            }
            $data['total_deductions'] += $amount;
        }
        
        $data['gross_salary'] = $data['staff']['basic_salary'] + $data['total_allowances'];
        $data['net_salary'] = $data['gross_salary'] - $data['total_deductions'];
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('hr/salarycomponents/breakdown', $data);
        echo view('template/footer');
    }

    // History of salary components
    public function history($staffId)
    {
        $data['title'] = 'Salary Component History';
        $data['staff'] = $this->staffModel->find($staffId);
        
        if (!$data['staff']) {
            return redirect()->to('/salarycomponents')->with('error', 'Staff not found');
        }
        
        // Get all components including inactive ones
        $data['history'] = $this->db->table('staff_salary_components')
                    ->select('staff_salary_components.*')
                    ->select('CASE 
                        WHEN component_type = "allowance" THEN allowances.allowance_name
                        ELSE deductions.deduction_name
                    END as component_name', false) // `false` prevents escaping
                    ->join('allowances', 'allowances.id = staff_salary_components.component_id AND component_type = "allowance"', 'left')
                    ->join('deductions', 'deductions.id = staff_salary_components.component_id AND component_type = "deduction"', 'left')
                    ->where('staff_salary_components.staff_id', $staffId)
                    ->orderBy('staff_salary_components.created_at', 'DESC')
                    ->get()->getResultArray();
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('hr/salarycomponents/history', $data);
        echo view('template/footer');
    }
}