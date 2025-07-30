<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Leave extends BaseController
{

    public function __construct()
    {
        parent:: __construct();
		$this->model = new PermissionModel();
        if( ($this->session->get('login') ) == false && $this->session->get('role') != 1){
            $data['dn_msg'] = 'Please Login';
            return redirect()->to('/login');
		}
    }

    // Leave management dashboard
    public function index()
    {
        $data['title'] = 'Leave Management';
        
        // Get leave summary
        $data['pending_leaves'] = $this->db->table('leave_applications')
            ->where('status', 'pending')
            ->countAllResults();
            
        $data['approved_leaves'] = $this->db->table('leave_applications')
            ->where('status', 'approved')
            ->where('from_date >=', date('Y-m-01'))
            ->countAllResults();
        
        // Get recent leave applications
        $data['recent_applications'] = $this->db->table('leave_applications')
            ->select('leave_applications.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name, leave_types.leave_name')
            ->join('staff_details', 'staff_details.id = leave_applications.staff_id')
            ->join('leave_types', 'leave_types.id = leave_applications.leave_type_id')
            ->orderBy('leave_applications.created_at', 'DESC')
            ->limit(10)
            ->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/leave/index', $data);
        echo view('template/footer');
    }

    // Leave types management
    public function types()
    {
        $data['title'] = 'Leave Types';
        $data['leave_types'] = $this->db->table('leave_types')->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/leave/types', $data);
        echo view('template/footer');
    }

    // Add/Edit leave type
    public function saveType()
    {
        $id = $this->request->getPost('id');
        
        $data = [
            'leave_code' => $this->request->getPost('leave_code'),
            'leave_name' => $this->request->getPost('leave_name'),
            'days_per_year' => $this->request->getPost('days_per_year'),
            'carry_forward' => $this->request->getPost('carry_forward') ?? 0,
            'max_carry_forward' => $this->request->getPost('max_carry_forward') ?? 0,
            'is_paid' => $this->request->getPost('is_paid') ?? 1,
            'status' => $this->request->getPost('status') ?? 1
        ];
        
        if ($id) {
            $this->db->table('leave_types')->where('id', $id)->update($data);
            $message = 'Leave type updated successfully';
        } else {
            $this->db->table('leave_types')->insert($data);
            $message = 'Leave type added successfully';
        }
        
        return redirect()->to('/leave/types')->with('success', $message);
    }

    // Leave allocation
    public function allocation()
    {
        $data['title'] = 'Leave Allocation';
        $year = $this->request->getGet('year') ?? date('Y');
        $data['year'] = $year;
        
        // Get all staff with their leave allocations
        $data['staff_allocations'] = $this->db->table('staff_details')
            ->select('staff_details.id, staff_details.staff_code, staff_details.first_name, staff_details.last_name')
            ->where('status', 'active')
            ->get()->getResultArray();
        
        // Get leave types
        $data['leave_types'] = $this->db->table('leave_types')
            ->where('status', 1)
            ->get()->getResultArray();
        
        // Get existing allocations
        foreach ($data['staff_allocations'] as &$staff) {
            $staff['allocations'] = $this->db->table('staff_leave_allocation')
                ->where('staff_id', $staff['id'])
                ->where('year', $year)
                ->get()->getResultArray();
        }
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/leave/allocation', $data);
        echo view('template/footer');
    }

    // Save leave allocation
    public function saveAllocation()
    {
        $staffId = $this->request->getPost('staff_id');
        $year = $this->request->getPost('year');
        $leaveTypeId = $this->request->getPost('leave_type_id');
        $entitledDays = $this->request->getPost('entitled_days');
        
        // Check if allocation exists
        $existing = $this->db->table('staff_leave_allocation')
            ->where('staff_id', $staffId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('year', $year)
            ->get()->getRowArray();
        
        if ($existing) {
            // Update
            $this->db->table('staff_leave_allocation')
                ->where('id', $existing['id'])
                ->update([
                    'entitled_days' => $entitledDays,
                    'total_days' => $entitledDays + $existing['carried_forward'],
                    'balance_days' => $entitledDays + $existing['carried_forward'] - $existing['used_days']
                ]);
        } else {
            // Insert
            $this->db->table('staff_leave_allocation')->insert([
                'staff_id' => $staffId,
                'leave_type_id' => $leaveTypeId,
                'year' => $year,
                'entitled_days' => $entitledDays,
                'carried_forward' => 0,
                'total_days' => $entitledDays,
                'used_days' => 0,
                'balance_days' => $entitledDays
            ]);
        }
        
        return $this->response->setJSON(['status' => 'success']);
    }

    // Bulk allocation
    public function bulk_allocation()
    {
        $year = $this->request->getPost('year');
        $leaveTypeId = $this->request->getPost('leave_type_id');
        
        // Get leave type details
        $leaveType = $this->db->table('leave_types')->where('id', $leaveTypeId)->get()->getRowArray();
        
        if (!$leaveType) {
            return redirect()->back()->with('error', 'Leave type not found');
        }
        
        // Get all active staff
        $staff = $this->db->table('staff_details')->where('status', 'active')->get()->getResultArray();
        
        $this->db->transStart();
        
        foreach ($staff as $employee) {
            // Check if allocation exists
            $existing = $this->db->table('staff_leave_allocation')
                ->where('staff_id', $employee['id'])
                ->where('leave_type_id', $leaveTypeId)
                ->where('year', $year)
                ->get()->getRowArray();
            
            if (!$existing) {
                // Calculate carry forward from previous year if applicable
                $carryForward = 0;
                if ($leaveType['carry_forward']) {
                    $previousYear = $year - 1;
                    $previousAllocation = $this->db->table('staff_leave_allocation')
                        ->where('staff_id', $employee['id'])
                        ->where('leave_type_id', $leaveTypeId)
                        ->where('year', $previousYear)
                        ->get()->getRowArray();
                    
                    if ($previousAllocation) {
                        $carryForward = min($previousAllocation['balance_days'], $leaveType['max_carry_forward']);
                    }
                }
                
                $this->db->table('staff_leave_allocation')->insert([
                    'staff_id' => $employee['id'],
                    'leave_type_id' => $leaveTypeId,
                    'year' => $year,
                    'entitled_days' => $leaveType['days_per_year'],
                    'carried_forward' => $carryForward,
                    'total_days' => $leaveType['days_per_year'] + $carryForward,
                    'used_days' => 0,
                    'balance_days' => $leaveType['days_per_year'] + $carryForward
                ]);
            }
        }
        
        $this->db->transComplete();
        
        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Failed to allocate leaves');
        }
        
        return redirect()->to('/leave/allocation')->with('success', 'Leave allocated successfully');
    }

    // Leave applications
    public function applications()
    {
        $data['title'] = 'Leave Applications';
        
        $status = $this->request->getGet('status') ?? 'all';
        $data['status'] = $status;
        
        $query = $this->db->table('leave_applications')
            ->select('leave_applications.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name, leave_types.leave_name')
            ->join('staff_details', 'staff_details.id = leave_applications.staff_id')
            ->join('leave_types', 'leave_types.id = leave_applications.leave_type_id');
        
        if ($status != 'all') {
            $query->where('leave_applications.status', $status);
        }
        
        $data['applications'] = $query->orderBy('leave_applications.created_at', 'DESC')->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/leave/applications', $data);
        echo view('template/footer');
    }

    // Apply leave
    public function apply()
    {
        $data['title'] = 'Apply Leave';
        $data['staff'] = $this->db->table('staff_details')->where('status', 'active')->get()->getResultArray();
        $data['leave_types'] = $this->db->table('leave_types')->where('status', 1)->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/leave/apply', $data);
        echo view('template/footer');
    }

    // Save leave application
    public function save_application()
    {
        $staffId = $this->request->getPost('staff_id');
        $leaveTypeId = $this->request->getPost('leave_type_id');
        $fromDate = $this->request->getPost('from_date');
        $toDate = $this->request->getPost('to_date');
        
        // Calculate days
        $from = new \DateTime($fromDate);
        $to = new \DateTime($toDate);
        $days = $to->diff($from)->days + 1;
        
        // Check leave balance
        $year = date('Y', strtotime($fromDate));
        $balance = $this->db->table('staff_leave_allocation')
            ->where('staff_id', $staffId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('year', $year)
            ->get()->getRowArray();
        
        if (!$balance || $balance['balance_days'] < $days) {
            return redirect()->back()->with('error', 'Insufficient leave balance');
        }
        
        // Save application
        $this->db->table('leave_applications')->insert([
            'staff_id' => $staffId,
            'leave_type_id' => $leaveTypeId,
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'days' => $days,
            'reason' => $this->request->getPost('reason'),
            'status' => 'pending'
        ]);
        
        return redirect()->to('/leave/applications')->with('success', 'Leave application submitted successfully');
    }

    // Approve/Reject leave
    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        $remarks = $this->request->getPost('remarks');
        
        // Get application details
        $application = $this->db->table('leave_applications')->where('id', $id)->get()->getRowArray();
        
        if (!$application) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Application not found']);
        }
        
        $this->db->transStart();
        
        // Update application status
        $this->db->table('leave_applications')
            ->where('id', $id)
            ->update([
                'status' => $status,
                'approved_by' => session()->get('user_id'),
                'approved_date' => date('Y-m-d H:i:s'),
                'remarks' => $remarks
            ]);
        
        // Update leave balance if approved
        if ($status == 'approved') {
            $year = date('Y', strtotime($application['from_date']));
            
            $allocation = $this->db->table('staff_leave_allocation')
                ->where('staff_id', $application['staff_id'])
                ->where('leave_type_id', $application['leave_type_id'])
                ->where('year', $year)
                ->get()->getRowArray();
            
            if ($allocation) {
                $this->db->table('staff_leave_allocation')
                    ->where('id', $allocation['id'])
                    ->update([
                        'used_days' => $allocation['used_days'] + $application['days'],
                        'balance_days' => $allocation['balance_days'] - $application['days']
                    ]);
            }
        }
        
        $this->db->transComplete();
        
        if ($this->db->transStatus() === FALSE) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update status']);
        }
        
        return $this->response->setJSON(['status' => 'success', 'message' => 'Leave ' . $status . ' successfully']);
    }

    // Leave balance report
    public function balance_report()
    {
        $data['title'] = 'Leave Balance Report';
        $year = $this->request->getGet('year') ?? date('Y');
        $data['year'] = $year;
        
        // Get all staff with leave balances
        $data['staff_balances'] = $this->db->table('staff_details')
            ->select('staff_details.*, departments.department_name')
            ->join('departments', 'departments.id = staff_details.department_id', 'left')
            ->where('staff_details.status', 'active')
            ->get()->getResultArray();
        
        // Get leave types
        $leaveTypes = $this->db->table('leave_types')->where('status', 1)->get()->getResultArray();
        $data['leave_types'] = $leaveTypes;
        
        // Get balances for each staff
        foreach ($data['staff_balances'] as &$staff) {
            $staff['balances'] = [];
            foreach ($leaveTypes as $leaveType) {
                $balance = $this->db->table('staff_leave_allocation')
                    ->where('staff_id', $staff['id'])
                    ->where('leave_type_id', $leaveType['id'])
                    ->where('year', $year)
                    ->get()->getRowArray();
                
                $staff['balances'][$leaveType['id']] = $balance ?? [
                    'entitled_days' => 0,
                    'used_days' => 0,
                    'balance_days' => 0
                ];
            }
        }
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/leave/balance_report', $data);
        echo view('template/footer');
    }

    // Leave calendar view
    public function calendar()
    {
        $data['title'] = 'Leave Calendar';
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/leave/calendar', $data);
        echo view('template/footer');
    }

    // Get calendar events
    public function getCalendarEvents()
    {
        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');
        
        $leaves = $this->db->table('leave_applications')
            ->select('leave_applications.*, staff_details.first_name, staff_details.last_name, leave_types.leave_name')
            ->join('staff_details', 'staff_details.id = leave_applications.staff_id')
            ->join('leave_types', 'leave_types.id = leave_applications.leave_type_id')
            ->where('leave_applications.status', 'approved')
            ->where('from_date >=', $start)
            ->where('to_date <=', $end)
            ->get()->getResultArray();
        
        $events = [];
        foreach ($leaves as $leave) {
            $events[] = [
                'title' => $leave['first_name'] . ' ' . $leave['last_name'] . ' - ' . $leave['leave_name'],
                'start' => $leave['from_date'],
                'end' => date('Y-m-d', strtotime($leave['to_date'] . ' +1 day')),
                'color' => '#4CAF50'
            ];
        }
        
        return $this->response->setJSON($events);
    }
    public function getBalance()
    {
        $staffId = $this->request->getPost('staff_id');
        $leaveTypeId = $this->request->getPost('leave_type_id');
        $year = $this->request->getPost('year') ?? date('Y');
        
        if (!$staffId || !$leaveTypeId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing parameters']);
        }
        
        // Get leave allocation for the staff
        $allocation = $this->db->table('staff_leave_allocation')
            ->where('staff_id', $staffId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('year', $year)
            ->get()->getRowArray();
        
        if ($allocation) {
            return $this->response->setJSON([
                'status' => 'success',
                'balance' => $allocation['balance_days'],
                'entitled' => $allocation['entitled_days'],
                'used' => $allocation['used_days'],
                'carried_forward' => $allocation['carried_forward']
            ]);
        } else {
            // No allocation found, check if leave type exists and return default
            $leaveType = $this->db->table('leave_types')
                ->where('id', $leaveTypeId)
                ->get()->getRowArray();
            
            if ($leaveType) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'balance' => 0,
                    'entitled' => 0,
                    'used' => 0,
                    'carried_forward' => 0,
                    'message' => 'No allocation found for this year'
                ]);
            }
            
            return $this->response->setJSON(['status' => 'error', 'message' => 'Leave type not found']);
        }
    }
}