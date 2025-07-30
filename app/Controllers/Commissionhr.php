<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StaffModel;
use App\Models\PermissionModel;

class Commissionhr extends BaseController
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

    // Commission dashboard
    public function index()
    {
        $data['title'] = 'Commission Management';
        
        // Get commission summary
        $currentMonth = date('Y-m');
        $data['current_month_total'] = $this->db->table('commission_history')
            ->selectSum('commission_amount')
            ->where('DATE_FORMAT(commission_date, "%Y-%m")', $currentMonth)
            ->get()->getRowArray()['commission_amount'] ?? 0;
        $data['staff'] = $this->staffModel->getActiveStaff();
        
        // Get recent commissions
        $data['recent_commissions'] = $this->db->table('commission_history')
            ->select('commission_history.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name')
            ->join('staff_details', 'staff_details.id = commission_history.staff_id')
            ->orderBy('commission_date', 'DESC')
            ->limit(20)
            ->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/commission/index', $data);
        echo view('template/footer');
    }

    // Commission settings
    public function settings()
    {
        $data['title'] = 'Commission Settings';
        
        // Get staff with commission settings
        $data['commission_settings'] = $this->db->table('commission_settings')
            ->select('commission_settings.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name')
            ->join('staff_details', 'staff_details.id = commission_settings.staff_id')
            ->where('commission_settings.status', 1)
            ->get()->getResultArray();
        
        $data['staff'] = $this->db->table('staff_details')
            ->where('status', 'active')
            ->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/commission/settings', $data);
        echo view('template/footer');
    }

    // Save commission setting
    public function saveSetting()
    {
        $id = $this->request->getPost('id');
        
        $data = [
            'staff_id' => $this->request->getPost('staff_id'),
            'commission_type' => $this->request->getPost('commission_type'),
            'commission_percentage' => $this->request->getPost('commission_percentage'),
            'commission_amount' => $this->request->getPost('commission_amount'),
            'effective_date' => $this->request->getPost('effective_date'),
            'end_date' => $this->request->getPost('end_date'),
            'status' => $this->request->getPost('status') ?? 1
        ];
        
        if ($id) {
            $this->db->table('commission_settings')->where('id', $id)->update($data);
            $message = 'Commission setting updated successfully';
        } else {
            // Check if active commission exists for staff
            $existing = $this->db->table('commission_settings')
                ->where('staff_id', $data['staff_id'])
                ->where('commission_type', $data['commission_type'])
                ->where('status', 1)
                ->where('(end_date IS NULL OR end_date >= CURDATE())')
                ->get()->getRowArray();
            
            if ($existing) {
                return redirect()->back()->with('error', 'Active commission already exists for this staff and type');
            }
            
            $this->db->table('commission_settings')->insert($data);
            $message = 'Commission setting added successfully';
        }
        
        return redirect()->to('/commissionhr/settings')->with('success', $message);
    }

    // Add commission entry
    public function add()
    {
        $data['title'] = 'Add Commission';
        
        // Get staff with active commission settings
        $data['staff'] = $this->db->table('staff_details')
            ->select('staff_details.*')
            ->join('commission_settings', 'commission_settings.staff_id = staff_details.id')
            ->where('staff_details.status', 'active')
            ->where('commission_settings.status', 1)
            ->groupBy('staff_details.id')
            ->get()->getResultArray();
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/commission/add', $data);
        echo view('template/footer');
    }

    // Get commission settings for staff
    public function getStaffCommissionSettings($staffId)
    {
        $settings = $this->db->table('commission_settings')
            ->where('staff_id', $staffId)
            ->where('status', 1)
            ->where('effective_date <=', date('Y-m-d'))
            ->where('(end_date IS NULL OR end_date >= CURDATE())')
            ->get()->getResultArray();
        
        return $this->response->setJSON($settings);
    }

    // Save commission entry
    public function save()
    {
        $staffId = $this->request->getPost('staff_id');
        $commissionType = $this->request->getPost('commission_type');
        $baseAmount = $this->request->getPost('base_amount');
        
        // Get commission setting
        $setting = $this->db->table('commission_settings')
            ->where('staff_id', $staffId)
            ->where('commission_type', $commissionType)
            ->where('status', 1)
            ->where('effective_date <=', date('Y-m-d'))
            ->where('(end_date IS NULL OR end_date >= CURDATE())')
            ->get()->getRowArray();
        
        if (!$setting) {
            return redirect()->back()->with('error', 'No active commission setting found');
        }
        
        // Calculate commission
        $commissionAmount = 0;
        if ($setting['commission_percentage']) {
            $commissionAmount = ($baseAmount * $setting['commission_percentage']) / 100;
        } else if ($setting['commission_amount']) {
            $commissionAmount = $setting['commission_amount'];
        }
        
        // Save commission history
        $data = [
            'staff_id' => $staffId,
            'commission_date' => $this->request->getPost('commission_date'),
            'commission_type' => $commissionType,
            'base_amount' => $baseAmount,
            'commission_percentage' => $setting['commission_percentage'],
            'commission_amount' => $commissionAmount,
            'reference_no' => $this->request->getPost('reference_no'),
            'remarks' => $this->request->getPost('remarks')
        ];
        
        $this->db->table('commission_history')->insert($data);
        
        return redirect()->to('/commissionhr')->with('success', 'Commission added successfully');
    }

    // Bulk commission entry
    public function bulk()
    {
        $data['title'] = 'Bulk Commission Entry';
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/commission/bulk', $data);
        echo view('template/footer');
    }

    // Process bulk commission
    public function processBulk()
    {
        $file = $this->request->getFile('commission_file');
        
        if ($file->isValid() && !$file->hasMoved()) {
            $data = array_map('str_getcsv', file($file->getTempName()));
            $headers = array_shift($data);
            
            $this->db->transStart();
            $successCount = 0;
            
            foreach ($data as $row) {
                // Find staff by code
                $staff = $this->db->table('staff_details')
                    ->where('staff_code', $row[0])
                    ->get()->getRowArray();
                
                if (!$staff) continue;
                
                // Get commission setting
                $setting = $this->db->table('commission_settings')
                    ->where('staff_id', $staff['id'])
                    ->where('commission_type', $row[1])
                    ->where('status', 1)
                    ->get()->getRowArray();
                
                if (!$setting) continue;
                
                // Calculate commission
                $baseAmount = floatval($row[2]);
                $commissionAmount = 0;
                
                if ($setting['commission_percentage']) {
                    $commissionAmount = ($baseAmount * $setting['commission_percentage']) / 100;
                } else if ($setting['commission_amount']) {
                    $commissionAmount = $setting['commission_amount'];
                }
                
                // Save commission
                $commissionData = [
                    'staff_id' => $staff['id'],
                    'commission_date' => $row[3] ?? date('Y-m-d'),
                    'commission_type' => $row[1],
                    'base_amount' => $baseAmount,
                    'commission_percentage' => $setting['commission_percentage'],
                    'commission_amount' => $commissionAmount,
                    'reference_no' => $row[4] ?? null,
                    'remarks' => $row[5] ?? null
                ];
                
                $this->db->table('commission_history')->insert($commissionData);
                $successCount++;
            }
            
            $this->db->transComplete();
            
            if ($this->db->transStatus() === FALSE) {
                return redirect()->back()->with('error', 'Failed to process bulk commission');
            }
            
            return redirect()->to('/commissionhr')->with('success', "$successCount commission entries added successfully");
        }
        
        return redirect()->back()->with('error', 'Invalid file');
    }

    // Commission history
    public function history()
    {
        $data['title'] = 'Commission History';
        
        $staffId = $this->request->getGet('staff_id');
        $month = $this->request->getGet('month') ?? date('Y-m');
        
        $query = $this->db->table('commission_history')
            ->select('commission_history.*, staff_details.staff_code, staff_details.first_name, staff_details.last_name')
            ->join('staff_details', 'staff_details.id = commission_history.staff_id');
        
        if ($staffId) {
            $query->where('commission_history.staff_id', $staffId);
        }
        
        if ($month) {
            $query->where('DATE_FORMAT(commission_date, "%Y-%m")', $month);
        }
        
        $data['commissions'] = $query->orderBy('commission_date', 'DESC')->get()->getResultArray();
        $data['staff'] = $this->db->table('staff_details')->where('status', 'active')->get()->getResultArray();
        $data['selected_staff'] = $staffId;
        $data['selected_month'] = $month;
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/commission/history', $data);
        echo view('template/footer');
    }

    // Edit commission entry
    public function edit($id)
    {
        $data['title'] = 'Edit Commission';
        $data['commission'] = $this->db->table('commission_history')
            ->where('id', $id)
            ->get()->getRowArray();
        
        if (!$data['commission']) {
            return redirect()->to('/commissionhr')->with('error', 'Commission not found');
        }
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/commission/edit', $data);
        echo view('template/footer');
    }

    // Update commission entry
    public function update($id)
    {
        $data = [
            'commission_date' => $this->request->getPost('commission_date'),
            'base_amount' => $this->request->getPost('base_amount'),
            'commission_amount' => $this->request->getPost('commission_amount'),
            'reference_no' => $this->request->getPost('reference_no'),
            'remarks' => $this->request->getPost('remarks')
        ];
        
        $this->db->table('commission_history')->where('id', $id)->update($data);
        
        return redirect()->to('/commissionhr/history')->with('success', 'Commission updated successfully');
    }

    // Delete commission entry
    public function delete($id)
    {
        // Check if commission is already included in payroll
        $commission = $this->db->table('commission_history')->where('id', $id)->get()->getRowArray();
        
        if (!$commission) {
            return redirect()->back()->with('error', 'Commission not found');
        }
        
        $payrollMonth = date('Y-m', strtotime($commission['commission_date']));
        $payrollExists = $this->db->table('payroll')
            ->where('staff_id', $commission['staff_id'])
            ->where('payroll_month', $payrollMonth)
            ->countAllResults();
        
        if ($payrollExists > 0) {
            return redirect()->back()->with('error', 'Cannot delete commission already included in payroll');
        }
        
        $this->db->table('commission_history')->where('id', $id)->delete();
        
        return redirect()->back()->with('success', 'Commission deleted successfully');
    }

    // Commission report
    public function report()
    {
        $data['title'] = 'Commission Report';
        
        $fromDate = $this->request->getGet('from_date') ?? date('Y-m-01');
        $toDate = $this->request->getGet('to_date') ?? date('Y-m-t');
        
        $data['report'] = $this->db->table('commission_history')
            ->select('
                staff_details.staff_code,
                staff_details.first_name,
                staff_details.last_name,
                commission_type,
                COUNT(*) as transaction_count,
                SUM(base_amount) as total_base,
                SUM(commission_amount) as total_commission
            ')
            ->join('staff_details', 'staff_details.id = commission_history.staff_id')
            ->where('commission_date >=', $fromDate)
            ->where('commission_date <=', $toDate)
            ->groupBy('staff_id, commission_type')
            ->get()->getResultArray();
        
        $data['from_date'] = $fromDate;
        $data['to_date'] = $toDate;
        $data['total_commission'] = array_sum(array_column($data['report'], 'total_commission'));
        
        echo view('template/header');
		echo view('template/sidebar');
        echo view('hr/commission/report', $data);
        echo view('template/footer');
    }

    // Export commission report
    public function export()
    {
        $fromDate = $this->request->getGet('from_date') ?? date('Y-m-01');
        $toDate = $this->request->getGet('to_date') ?? date('Y-m-t');
        
        $report = $this->db->table('commission_history')
            ->select('
                commission_date,
                staff_details.staff_code,
                staff_details.first_name,
                staff_details.last_name,
                commission_type,
                base_amount,
                commission_percentage,
                commission_amount,
                reference_no,
                remarks
            ')
            ->join('staff_details', 'staff_details.id = commission_history.staff_id')
            ->where('commission_date >=', $fromDate)
            ->where('commission_date <=', $toDate)
            ->orderBy('commission_date', 'DESC')
            ->get()->getResultArray();
        
        $filename = 'commission_report_' . $fromDate . '_to_' . $toDate . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Headers
        fputcsv($output, [
            'Date', 'Staff Code', 'First Name', 'Last Name', 'Commission Type',
            'Base Amount', 'Commission %', 'Commission Amount', 'Reference No', 'Remarks'
        ]);
        
        // Data
        foreach ($report as $row) {
            fputcsv($output, [
                $row['commission_date'],
                $row['staff_code'],
                $row['first_name'],
                $row['last_name'],
                $row['commission_type'],
                $row['base_amount'],
                $row['commission_percentage'],
                $row['commission_amount'],
                $row['reference_no'],
                $row['remarks']
            ]);
        }
        
        fclose($output);
        exit;
    }
}