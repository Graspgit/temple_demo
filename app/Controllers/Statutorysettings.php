<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StaffModel;
use App\Models\PermissionModel;

class Statutorysettings extends BaseController
{

    public function __construct()
    {
        parent:: __construct();
		$this->model = new PermissionModel();
        $this->staffModel = new StaffModel();
        if( ($this->session->get('login') ) == false && $this->session->get('role') != 1){
            $data['dn_msg'] = 'Please Login';
            return redirect()->to('/login');
		}
    }

    // Statutory settings main page
    public function statutory()
    {
        $data['title'] = 'Statutory Settings';
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/settings/statutory', $data);
        echo view('template/footer');
    }

    // EPF Settings
    public function epf()
    {
        $data['title'] = 'EPF Settings';
        $data['epf_settings'] = $this->db->table('epf_settings')
            ->orderBy('salary_from', 'ASC')
            ->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/settings/epf', $data);
        echo view('template/footer');
    }

    // Save EPF Setting
    public function saveEpf()
    {
        $id = $this->request->getPost('id');
        
        $data = [
            'salary_from' => $this->request->getPost('salary_from'),
            'salary_to' => $this->request->getPost('salary_to'),
            'employee_percentage' => $this->request->getPost('employee_percentage'),
            'employer_percentage' => $this->request->getPost('employer_percentage'),
            'employee_amount' => $this->request->getPost('employee_amount'),
            'employer_amount' => $this->request->getPost('employer_amount'),
            'effective_date' => $this->request->getPost('effective_date'),
            'status' => $this->request->getPost('status') ?? 1
        ];
        
        if ($id) {
            $this->db->table('epf_settings')->where('id', $id)->update($data);
            $message = 'EPF setting updated successfully';
        } else {
            $this->db->table('epf_settings')->insert($data);
            $message = 'EPF setting added successfully';
        }
        
        return redirect()->to('/statutorysettings/epf')->with('success', $message);
    }

    // SOCSO Settings
    public function socso()
    {
        $data['title'] = 'SOCSO Settings';
        $data['socso_settings'] = $this->db->table('socso_settings')
            ->orderBy('salary_from', 'ASC')
            ->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/settings/socso', $data);
        echo view('template/footer');
    }

    // Save SOCSO Setting
    public function saveSocso()
    {
        $id = $this->request->getPost('id');
        $data = [
            'salary_from' => $this->request->getPost('salary_from'),
            'salary_to' => $this->request->getPost('salary_to'),
            'employee_percentage' => $this->request->getPost('employee_percentage'),
            'employer_percentage' => $this->request->getPost('employer_percentage'),
            'employee_amount' => $this->request->getPost('employee_amount'),
            'employer_amount' => $this->request->getPost('employer_amount'),
            'category' => $this->request->getPost('category'),
            'effective_date' => $this->request->getPost('effective_date'),
            'status' => $this->request->getPost('status') ?? 1
        ];
        
        if ($id) {
            $this->db->table('socso_settings')->where('id', $id)->update($data);
            $message = 'SOCSO setting updated successfully';
        } else {
            print_r($data);
            $res = $this->db->table('socso_settings')->insert($data);
            $message = 'SOCSO setting added successfully';
        }
        
        return redirect()->to('/statutorysettings/socso')->with('success', $message);
    }

    // EIS Settings
    public function eis()
    {
        $data['title'] = 'EIS Settings';
        $data['eis_settings'] = $this->db->table('eis_settings')
            ->orderBy('salary_from', 'ASC')
            ->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/settings/eis', $data);
        echo view('template/footer');
    }

    // Save EIS Setting
    public function saveEis()
    {
        $id = $this->request->getPost('id');
        
        $data = [
            'salary_from' => $this->request->getPost('salary_from'),
            'salary_to' => $this->request->getPost('salary_to'),
            'employee_percentage' => $this->request->getPost('employee_percentage'),
            'employer_percentage' => $this->request->getPost('employer_percentage'),
            'employee_amount' => $this->request->getPost('employee_amount'),
            'employer_amount' => $this->request->getPost('employer_amount'),
            'effective_date' => $this->request->getPost('effective_date'),
            'status' => $this->request->getPost('status') ?? 1
        ];
        
        if ($id) {
            $this->db->table('eis_settings')->where('id', $id)->update($data);
            $message = 'EIS setting updated successfully';
        } else {
            $this->db->table('eis_settings')->insert($data);
            $message = 'EIS setting added successfully';
        }
        
        return redirect()->to('/statutorysettings/eis')->with('success', $message);
    }

    // PCB Settings
    public function pcb()
    {
        $data['title'] = 'PCB (Income Tax) Settings';
        $data['pcb_settings'] = $this->db->table('pcb_settings')
            ->orderBy('category', 'ASC')
            ->orderBy('income_from', 'ASC')
            ->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/settings/pcb', $data);
        echo view('template/footer');
    }

    // Save PCB Setting
    public function savePcb()
    {
        $id = $this->request->getPost('id');
        
        $data = [
            'category' => $this->request->getPost('category'),
            'income_from' => $this->request->getPost('income_from'),
            'income_to' => $this->request->getPost('income_to'),
            'tax_amount' => $this->request->getPost('tax_amount'),
            'tax_percentage' => $this->request->getPost('tax_percentage'),
            'effective_year' => $this->request->getPost('effective_year'),
            'status' => $this->request->getPost('status') ?? 1
        ];
        
        if ($id) {
            $this->db->table('pcb_settings')->where('id', $id)->update($data);
            $message = 'PCB setting updated successfully';
        } else {
            $this->db->table('pcb_settings')->insert($data);
            $message = 'PCB setting added successfully';
        }
        
        return redirect()->to('/statutorysettings/pcb')->with('success', $message);
    }

    // Allowances
    public function allowances()
    {
        $data['title'] = 'Allowance Settings';
        $data['allowances'] = $this->db->table('allowances')->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/settings/allowances', $data);
        echo view('template/footer');
    }

    // Save Allowance
    public function saveAllowance()
    {
        $id = $this->request->getPost('id');
        
        $data = [
            'allowance_name' => $this->request->getPost('allowance_name'),
            'allowance_type' => $this->request->getPost('allowance_type'),
            'is_taxable' => $this->request->getPost('is_taxable') ?? 0,
            'is_epf_eligible' => $this->request->getPost('is_epf_eligible') ?? 0,
            'is_socso_eligible' => $this->request->getPost('is_socso_eligible') ?? 0,
            'status' => $this->request->getPost('status') ?? 1
        ];
        
        if ($id) {
            $this->db->table('allowances')->where('id', $id)->update($data);
            $message = 'Allowance updated successfully';
        } else {
            $this->db->table('allowances')->insert($data);
            $message = 'Allowance added successfully';
        }
        
        return redirect()->to('/statutorysettings/allowances')->with('success', $message);
    }

    // Deductions
    public function deductions()
    {
        $data['title'] = 'Deduction Settings';
        $data['deductions'] = $this->db->table('deductions')->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/settings/deductions', $data);
        echo view('template/footer');
    }

    // Save Deduction
    public function saveDeduction()
    {
        $id = $this->request->getPost('id');
        
        $data = [
            'deduction_name' => $this->request->getPost('deduction_name'),
            'deduction_type' => $this->request->getPost('deduction_type'),
            'status' => $this->request->getPost('status') ?? 1
        ];
        
        if ($id) {
            $this->db->table('deductions')->where('id', $id)->update($data);
            $message = 'Deduction updated successfully';
        } else {
            $this->db->table('deductions')->insert($data);
            $message = 'Deduction added successfully';
        }
        
        return redirect()->to('/statutorysettings/deductions')->with('success', $message);
    }

    // Departments
    public function departments()
    {
        $data['title'] = 'Department Settings';
        $data['departments'] = $this->db->table('departments')->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/settings/departments', $data);
        echo view('template/footer');
    }

    // Save Department
    public function saveDepartment()
    {
        $id = $this->request->getPost('id');
        
        $data = [
            'department_name' => $this->request->getPost('department_name'),
            'department_code' => $this->request->getPost('department_code'),
            'status' => $this->request->getPost('status') ?? 1
        ];
        
        if ($id) {
            $this->db->table('departments')->where('id', $id)->update($data);
            $message = 'Department updated successfully';
        } else {
            $this->db->table('departments')->insert($data);
            $message = 'Department added successfully';
        }
        
        return redirect()->to('/statutorysettings/departments')->with('success', $message);
    }

    // Designations
    public function designations()
    {
        $data['title'] = 'Designation Settings';
        $data['designations'] = $this->db->table('designations')
            ->select('designations.*, departments.department_name')
            ->join('departments', 'departments.id = designations.department_id', 'left')
            ->get()->getResultArray();
        $data['departments'] = $this->db->table('departments')->where('status', 1)->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/settings/designations', $data);
        echo view('template/footer');
    }

    // Save Designation
    public function saveDesignation()
    {
        $id = $this->request->getPost('id');
        
        $data = [
            'designation_name' => $this->request->getPost('designation_name'),
            'department_id' => $this->request->getPost('department_id'),
            'min_salary' => $this->request->getPost('min_salary'),
            'max_salary' => $this->request->getPost('max_salary'),
            'status' => $this->request->getPost('status') ?? 1
        ];
        
        if ($id) {
            $this->db->table('designations')->where('id', $id)->update($data);
            $message = 'Designation updated successfully';
        } else {
            $this->db->table('designations')->insert($data);
            $message = 'Designation added successfully';
        }
        
        return redirect()->to('/statutorysettings/designations')->with('success', $message);
    }

    // Delete functions for all settings
    public function delete($table, $id)
    {
        $allowedTables = ['epf_settings', 'socso_settings', 'eis_settings', 'pcb_settings', 
                         'allowances', 'deductions', 'departments', 'designations'];
        
        if (!in_array($table, $allowedTables)) {
            return redirect()->back()->with('error', 'Invalid request');
        }
        
        try {
            $this->db->table($table)->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Record deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cannot delete record. It may be in use.');
        }
    }

    // Import EPF rates from file
    public function importEpf()
    {
        $file = $this->request->getFile('epf_file');
        
        if ($file->isValid() && !$file->hasMoved()) {
            $data = array_map('str_getcsv', file($file->getTempName()));
            $headers = array_shift($data);
            
            $this->db->transStart();
            
            foreach ($data as $row) {
                $epfData = [
                    'salary_from' => $row[0],
                    'salary_to' => $row[1],
                    'employee_percentage' => $row[2] ?? 0,
                    'employer_percentage' => $row[3] ?? 0,
                    'employee_amount' => $row[4] ?? null,
                    'employer_amount' => $row[5] ?? null,
                    'effective_date' => date('Y-m-d'),
                    'status' => 1
                ];
                
                $this->db->table('epf_settings')->insert($epfData);
            }
            
            $this->db->transComplete();
            
            if ($this->db->transStatus() === FALSE) {
                return redirect()->back()->with('error', 'Failed to import EPF rates');
            }
            
            return redirect()->to('/statutorysettings/epf')->with('success', 'EPF rates imported successfully');
        }
        
        return redirect()->back()->with('error', 'Invalid file');
    }

    public function accountSettings()
    {
        $data['title'] = 'Account Settings';
        
        // Get current settings
        $data['settings'] = $this->db->table('statutory_account_settings')
            ->get()->getRowArray();
        
        // Get all liability group IDs (2000 and all its descendants)
        $liabilityGroups = $this->getAllDescendantGroups('2000');
        
        // Get liability ledgers (for EPF, SOCSO, EIS, PCB Payable, and Deductions)
        $data['liability_ledgers'] = $this->db->table('ledgers')
            ->select('ledgers.id, CONCAT(ledgers.left_code, "/", ledgers.right_code, " - ", ledgers.name) as display_name, groups.name as group_name')
            ->join('groups', 'groups.id = ledgers.group_id', 'left')
            ->whereIn('ledgers.group_id', $liabilityGroups)
            ->orderBy('ledgers.left_code', 'ASC')
            ->orderBy('ledgers.right_code', 'ASC')
            ->get()->getResultArray();
        
        // Get all expense group IDs (5000 and 6000 and all their descendants)
        $expenseGroups = array_merge(
            $this->getAllDescendantGroups('5000'),
            $this->getAllDescendantGroups('6000')
        );
        
        // Get expense ledgers (for Salary Expense)
        $data['expense_ledgers'] = $this->db->table('ledgers')
            ->select('ledgers.id, CONCAT(ledgers.left_code, "/", ledgers.right_code, " - ", ledgers.name) as display_name, groups.name as group_name')
            ->join('groups', 'groups.id = ledgers.group_id', 'left')
            ->whereIn('ledgers.group_id', $expenseGroups)
            ->orderBy('ledgers.left_code', 'ASC')
            ->orderBy('ledgers.right_code', 'ASC')
            ->get()->getResultArray();
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('hr/settings/account_settings', $data);
        echo view('template/footer');
    }

    // Helper function to get all descendant groups
    private function getAllDescendantGroups($groupCode)
    {
        $groupIds = [];
        
        // Get the main group
        $mainGroup = $this->db->table('groups')
            ->where('code', $groupCode)
            ->get()->getRowArray();
        
        if ($mainGroup) {
            $groupIds[] = $mainGroup['id'];
            
            // Recursively get all child groups
            $this->getChildGroups($mainGroup['id'], $groupIds);
        }
        
        return $groupIds;
    }

    // Recursive function to get child groups
    private function getChildGroups($parentId, &$groupIds)
    {
        $children = $this->db->table('groups')
            ->where('parent_id', $parentId)
            ->get()->getResultArray();
        
        foreach ($children as $child) {
            $groupIds[] = $child['id'];
            // Recursively get children of this child
            $this->getChildGroups($child['id'], $groupIds);
        }
    }

    // Save Account Settings
    public function saveAccountSettings()
    {
        $id = $this->request->getPost('id');
        
        $data = [
            'epf_ledger_id' => $this->request->getPost('epf_ledger_id'),
            'socso_ledger_id' => $this->request->getPost('socso_ledger_id'),
            'eis_ledger_id' => $this->request->getPost('eis_ledger_id'),
            'pcb_ledger_id' => $this->request->getPost('pcb_ledger_id'),
            'deduction_ledger_id' => $this->request->getPost('deduction_ledger_id'),
            'salary_expense_ledger_id' => $this->request->getPost('salary_expense_ledger_id'),
            'is_account_migrate' => $this->request->getPost('is_account_migrate') ?? 0
        ];
        
        if ($id) {
            $this->db->table('statutory_account_settings')->where('id', $id)->update($data);
            $message = 'Account settings updated successfully';
        } else {
            $this->db->table('statutory_account_settings')->insert($data);
            $message = 'Account settings saved successfully';
        }
        
        return redirect()->to('/statutorysettings/accountSettings')->with('success', $message);
    }
}