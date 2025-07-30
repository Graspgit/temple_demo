<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StaffModel;
use App\Models\ForeignerDetailsModel;
use App\Models\StatutoryDetailsModel;
use App\Models\PermissionModel;

class Staff extends BaseController
{
    protected $staffModel;
    protected $foreignerModel;
    protected $statutoryModel;
    function __construct(){
        parent:: __construct();
        $this->staffModel = new StaffModel();
        $this->foreignerModel = new ForeignerDetailsModel();
        $this->statutoryModel = new StatutoryDetailsModel();
		$this->model = new PermissionModel();
        if( ($this->session->get('login') ) == false && $this->session->get('role') != 1){
            $data['dn_msg'] = 'Please Login';
            return redirect()->to('/login');
		}
    }

    // List all staff
    public function index()
    {
        $data['title'] = 'Staff Management';
        $data['staff'] = $this->staffModel->getActiveStaff();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/staff/index', $data);
        echo view('template/footer');
    }

    // Add new staff form
    public function create()
    {
        $data['title'] = 'Add New Staff';
        $data['staff_code'] = $this->staffModel->generateStaffCode();
        $data['departments'] = $this->db->table('departments')->where('status', 1)->get()->getResultArray();
        $data['designations'] = $this->db->table('designations')->where('status', 1)->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/staff/create', $data);
        echo view('template/footer');
    }

    // Store new staff
    public function store()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'staff_code' => 'required|is_unique[staff_details.staff_code]',
            'staff_type' => 'required|in_list[local,foreigner]',
            'first_name' => 'required|min_length[2]',
            'last_name' => 'required|min_length[2]',
            'email' => 'permit_empty|valid_email',
            'phone' => 'permit_empty|min_length[10]',
            'join_date' => 'required|valid_date',
            'department_id' => 'required|numeric',
            'designation_id' => 'required|numeric',
            'basic_salary' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $this->db->transStart();

        try {
            // Insert staff basic details
            $staffData = [
                'staff_code' => $this->request->getPost('staff_code'),
                'staff_type' => $this->request->getPost('staff_type'),
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'address' => $this->request->getPost('address'),
                'date_of_birth' => $this->request->getPost('date_of_birth'),
                'gender' => $this->request->getPost('gender'),
                'marital_status' => $this->request->getPost('marital_status'),
                'join_date' => $this->request->getPost('join_date'),
                'department_id' => $this->request->getPost('department_id'),
                'designation_id' => $this->request->getPost('designation_id'),
                'basic_salary' => $this->request->getPost('basic_salary'),
                'status' => 'active'
            ];

            $staffId = $this->staffModel->insert($staffData);
            $staffName = $staffData['first_name'] . ' ' . $staffData['last_name'];
            $ledgerId = $this->createStaffLedger($staffId, $staffName, $staffData['staff_code']);

            if (!$ledgerId) {
                log_message('warning', 'Failed to create ledger for staff ID: ' . $staffId);
            }

            // Handle staff type specific details
            if ($this->request->getPost('staff_type') == 'foreigner') {
                $foreignerData = [
                    'staff_id' => $staffId,
                    'passport_number' => $this->request->getPost('passport_number'),
                    'passport_expiry' => $this->request->getPost('passport_expiry'),
                    'visa_number' => $this->request->getPost('visa_number'),
                    'visa_type' => $this->request->getPost('visa_type'),
                    'visa_category' => $this->request->getPost('visa_category'),
                    'visa_expiry' => $this->request->getPost('visa_expiry'),
                    'visa_renewal_date' => $this->request->getPost('visa_renewal_date'),
                    'country_of_origin' => $this->request->getPost('country_of_origin')
                ];
                $this->foreignerModel->insert($foreignerData);
            } else {
                $statutoryData = [
                    'staff_id' => $staffId,
                    'epf_number' => $this->request->getPost('epf_number'),
                    'socso_number' => $this->request->getPost('socso_number'),
                    'eis_number' => $this->request->getPost('eis_number'),
                    'income_tax_number' => $this->request->getPost('income_tax_number'),
                    'pcb_code' => $this->request->getPost('pcb_code'),
                    'ea_form_submitted' => $this->request->getPost('ea_form_submitted') ?? 0
                ];
                $this->statutoryModel->insert($statutoryData);
            }

            // Handle Next of Kin
            $nokNames = $this->request->getPost('nok_name');
            if ($nokNames && is_array($nokNames)) {
                foreach ($nokNames as $key => $name) {
                    if (!empty($name)) {
                        $nokData = [
                            'staff_id' => $staffId,
                            'name' => $name,
                            'relationship' => $this->request->getPost('nok_relationship')[$key] ?? '',
                            'phone' => $this->request->getPost('nok_phone')[$key] ?? '',
                            'email' => $this->request->getPost('nok_email')[$key] ?? '',
                            'address' => $this->request->getPost('nok_address')[$key] ?? '',
                            'is_primary' => $key == 0 ? 1 : 0
                        ];
                        $this->db->table('staff_next_of_kin')->insert($nokData);
                    }
                }
            }

            // Handle document uploads
            $documents = $this->request->getFiles();
            if (isset($documents['documents'])) {
                foreach ($documents['documents'] as $doc) {
                    if ($doc->isValid() && !$doc->hasMoved()) {
                        $newName = $doc->getRandomName();
                        $doc->move('uploads/staff_documents', $newName);
                        
                        $docData = [
                            'staff_id' => $staffId,
                            'document_type' => $this->request->getPost('document_type') ?? 'General',
                            'document_name' => $doc->getClientName(),
                            'file_path' => 'uploads/staff_documents/' . $newName
                        ];
                        $this->db->table('staff_documents')->insert($docData);
                    }
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                return redirect()->back()->withInput()->with('error', 'Failed to save staff details');
            }

            return redirect()->to('/staff')->with('success', 'Staff added successfully');

        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Edit staff form
    public function edit($id)
    {
        $data['title'] = 'Edit Staff';
        $data['staff'] = $this->staffModel->getStaffFullDetails($id);
        
        if (!$data['staff']) {
            return redirect()->to('/staff')->with('error', 'Staff not found');
        }

        $data['departments'] = $this->db->table('departments')->where('status', 1)->get()->getResultArray();
        $data['designations'] = $this->db->table('designations')->where('status', 1)->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/staff/edit', $data);
        echo view('template/footer');
    }

    // Update staff
    public function update($id)
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'first_name' => 'required|min_length[2]',
            'last_name' => 'required|min_length[2]',
            'email' => 'permit_empty|valid_email',
            'phone' => 'permit_empty|min_length[10]',
            'department_id' => 'required|numeric',
            'designation_id' => 'required|numeric',
            'basic_salary' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $this->db->transStart();

        try {
            // Update staff basic details
            $staffData = [
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'address' => $this->request->getPost('address'),
                'date_of_birth' => $this->request->getPost('date_of_birth'),
                'gender' => $this->request->getPost('gender'),
                'marital_status' => $this->request->getPost('marital_status'),
                'department_id' => $this->request->getPost('department_id'),
                'designation_id' => $this->request->getPost('designation_id'),
                'basic_salary' => $this->request->getPost('basic_salary'),
                'status' => $this->request->getPost('status')
            ];

            $this->staffModel->update($id, $staffData);

            // Get staff type
            $staff = $this->staffModel->find($id);
            if ($staff && $staff['ledger_id']) {
                $newName = $staffData['first_name'] . ' ' . $staffData['last_name'] . ' - ' . $staff['staff_code'];
                $this->db->table('ledgers')
                    ->where('id', $staff['ledger_id'])
                    ->update(['name' => $newName]);
            } elseif ($staff && !$staff['ledger_id']) {
                // Create ledger if it doesn't exist
                $staffName = $staffData['first_name'] . ' ' . $staffData['last_name'];
                $this->createStaffLedger($id, $staffName, $staff['staff_code']);
            }
            // Update type-specific details
            if ($staff['staff_type'] == 'foreigner') {
                $foreignerData = [
                    'passport_number' => $this->request->getPost('passport_number'),
                    'passport_expiry' => $this->request->getPost('passport_expiry'),
                    'visa_number' => $this->request->getPost('visa_number'),
                    'visa_type' => $this->request->getPost('visa_type'),
                    'visa_category' => $this->request->getPost('visa_category'),
                    'visa_expiry' => $this->request->getPost('visa_expiry'),
                    'visa_renewal_date' => $this->request->getPost('visa_renewal_date'),
                    'country_of_origin' => $this->request->getPost('country_of_origin')
                ];
                
                $existing = $this->foreignerModel->where('staff_id', $id)->first();
                if ($existing) {
                    $this->foreignerModel->update($existing['id'], $foreignerData);
                } else {
                    $foreignerData['staff_id'] = $id;
                    $this->foreignerModel->insert($foreignerData);
                }
            } else {
                $statutoryData = [
                    'epf_number' => $this->request->getPost('epf_number'),
                    'socso_number' => $this->request->getPost('socso_number'),
                    'eis_number' => $this->request->getPost('eis_number'),
                    'income_tax_number' => $this->request->getPost('income_tax_number'),
                    'pcb_code' => $this->request->getPost('pcb_code'),
                    'ea_form_submitted' => $this->request->getPost('ea_form_submitted') ?? 0
                ];
                
                $existing = $this->statutoryModel->where('staff_id', $id)->first();
                if ($existing) {
                    $this->statutoryModel->update($existing['id'], $statutoryData);
                } else {
                    $statutoryData['staff_id'] = $id;
                    $this->statutoryModel->insert($statutoryData);
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                return redirect()->back()->withInput()->with('error', 'Failed to update staff details');
            }

            return redirect()->to('/staff/view/'.$id)->with('success', 'Staff updated successfully');

        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // View staff details
    public function view($id)
    {
        $data['title'] = 'Staff Details';
        $data['staff'] = $this->staffModel->getStaffFullDetails($id);
        
        if (!$data['staff']) {
            return redirect()->to('/staff')->with('error', 'Staff not found');
        }

        // Get leave balance
        $data['leave_balance'] = $this->db->table('staff_leave_allocation')
            ->select('staff_leave_allocation.*, leave_types.leave_name')
            ->join('leave_types', 'leave_types.id = staff_leave_allocation.leave_type_id')
            ->where('staff_id', $id)
            ->where('year', date('Y'))
            ->get()->getResultArray();

        // Get recent payslips
        $data['recent_payslips'] = $this->db->table('payroll')
            ->where('staff_id', $id)
            ->orderBy('payroll_month', 'DESC')
            ->limit(6)
            ->get()->getResultArray();

        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/staff/view', $data);
        echo view('template/footer');
    }

    // Delete staff (soft delete)
    public function delete($id)
    {
        try {
            $staff = $this->staffModel->find($id);
            
            // Update staff status
            $this->staffModel->update($id, ['status' => 'inactive']);
            
            // Also update ledger name to indicate inactive
            if ($staff && $staff['ledger_id']) {
                $ledger = $this->db->table('ledgers')->where('id', $staff['ledger_id'])->get()->getRowArray();
                if ($ledger) {
                    // Update ledger name to show inactive status
                    $updatedName = $ledger['name'];
                    if (strpos($updatedName, '(INACTIVE)') === false) {
                        $updatedName .= ' (INACTIVE)';
                    }
                    
                    $this->db->table('ledgers')
                        ->where('id', $staff['ledger_id'])
                        ->update([
                            'name' => $updatedName,
                            'notes' => 'Staff Payable Account - INACTIVE'
                        ]);
                }
            }
            
            return redirect()->to('/staff')->with('success', 'Staff deactivated successfully');
        } catch (\Exception $e) {
            return redirect()->to('/staff')->with('error', 'Failed to deactivate staff: ' . $e->getMessage());
        }
    }

    // Upload documents
    public function uploadDocument($staffId)
    {
        $file = $this->request->getFile('document');
        
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/staff_documents', $newName);
            
            $data = [
                'staff_id' => $staffId,
                'document_type' => $this->request->getPost('document_type'),
                'document_name' => $file->getClientName(),
                'file_path' => 'uploads/staff_documents/' . $newName,
                'expiry_date' => $this->request->getPost('expiry_date')
            ];
            
            $this->db->table('staff_documents')->insert($data);
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Document uploaded successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to upload document'
        ]);
    }

    // Export staff list
    public function export()
    {
        $staff = $this->staffModel->getActiveStaff();
        
        // Generate CSV
        $filename = 'staff_list_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Headers
        fputcsv($output, ['Staff Code', 'Name', 'Type', 'Department', 'Designation', 'Email', 'Phone', 'Join Date', 'Status']);
        
        // Data
        foreach ($staff as $row) {
            fputcsv($output, [
                $row['staff_code'],
                $row['first_name'] . ' ' . $row['last_name'],
                $row['staff_type'],
                $row['department_name'] ?? '',
                $row['designation_name'] ?? '',
                $row['email'],
                $row['phone'],
                $row['join_date'],
                $row['status']
            ]);
        }
        
        fclose($output);
        exit;
    }

    // Document expiry report
    public function expiry_report()
    {
        $data['title'] = 'Document Expiry Report';
        $data['expiring_documents'] = $this->staffModel->getExpiringDocuments(30);
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/staff/expiry_report', $data);
        echo view('template/footer');
    }
    private function createStaffLedger($staffId, $staffName, $staffCode)
    {
        try {
            // Find the accrual group (ac = 1)
            $accrualGroup = $this->db->table('groups')
                ->where('ac', 1)
                ->get()->getRowArray();
            
            if (!$accrualGroup) {
                // If no accrual group found, use the ACCRUAL group by code
                $accrualGroup = $this->db->table('groups')
                    ->where('code', '2200') // ACCRUAL group code from your data
                    ->get()->getRowArray();
            }
            
            if (!$accrualGroup) {
                log_message('error', 'No accrual group found for staff ledger creation');
                return null;
            }
            
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
                'name' => $staffName . ' - ' . $staffCode,
                'left_code' => $accrualGroup['code'],
                'right_code' => $nextCode,
                'op_balance' => 0,
                'op_balance_dc' => 'C',
                'type' => '0',
                'reconciliation' => '0',
                'notes' => 'Staff Payable Account'
            ];
            
            $this->db->table('ledgers')->insert($ledgerData);
            $ledgerId = $this->db->insertID();
            
            // Update staff with ledger_id using proper CI4 syntax
            $this->db->table('staff_details')
                ->where('id', $staffId)
                ->update(['ledger_id' => $ledgerId]);
            
            return $ledgerId;
        } catch (\Exception $e) {
            log_message('error', 'Failed to create ledger for staff ID ' . $staffId . ': ' . $e->getMessage());
            return null;
        }
    }

    public function reactivate($id)
    {
        try {
            $staff = $this->staffModel->find($id);
            
            // Update staff status
            $this->staffModel->update($id, ['status' => 'active']);
            
            // Update ledger name to remove inactive status
            if ($staff && $staff['ledger_id']) {
                $ledger = $this->db->table('ledgers')->where('id', $staff['ledger_id'])->get()->getRowArray();
                if ($ledger) {
                    // Remove (INACTIVE) from ledger name
                    $updatedName = str_replace(' (INACTIVE)', '', $ledger['name']);
                    
                    $this->db->table('ledgers')
                        ->where('id', $staff['ledger_id'])
                        ->update([
                            'name' => $updatedName,
                            'notes' => 'Staff Payable Account'
                        ]);
                }
            }
            
            return redirect()->to('/staff')->with('success', 'Staff reactivated successfully');
        } catch (\Exception $e) {
            return redirect()->to('/staff')->with('error', 'Failed to reactivate staff: ' . $e->getMessage());
        }
    }

    public function syncStaffLedgers()
    {
        set_time_limit(0); // This might take a while for many staff
        
        $staffWithoutLedger = $this->db->table('staff_details')
            ->where('ledger_id IS NULL')
            ->where('status', 'active')
            ->get()->getResultArray();
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($staffWithoutLedger as $staff) {
            $staffName = $staff['first_name'] . ' ' . $staff['last_name'];
            $ledgerId = $this->createStaffLedger($staff['id'], $staffName, $staff['staff_code']);
            
            if ($ledgerId) {
                $successCount++;
            } else {
                $errorCount++;
                log_message('error', 'Failed to create ledger for staff: ' . $staff['staff_code']);
            }
        }
        
        $message = "Ledger sync completed. Success: $successCount, Errors: $errorCount";
        return redirect()->to('/staff')->with('success', $message);
    }
}