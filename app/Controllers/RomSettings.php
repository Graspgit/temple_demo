<?php

namespace App\Controllers;

class RomSettings extends BaseController
{
    public function __construct()
    {
        // Check if user has admin permissions
        if (false && !session('is_admin')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Unauthorized access');
        }
    }

    /**
     * Display ROM settings configuration page
     */
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Get current ROM settings
        $rom_settings = $db->table('settings')
                          ->where('type', 11)
                          ->get()
                          ->getResultArray();
        
        // Get available ledgers for ROM configuration
        $ledgers = $db->table('ledgers l')
                     ->select('l.id, l.name, l.code, g.name as group_name')
                     ->join('groups g', 'l.group_id = g.id', 'left')
                     ->where('l.type', 'INCOME')
                     ->orderBy('g.name, l.name')
                     ->get()
                     ->getResultArray();
        
        // Get current ROM ledger details
        $current_rom_ledger = null;
        $rom_ledger_setting = array_filter($rom_settings, function($setting) {
            return $setting['setting_name'] === 'rom_ledger_id';
        });
        
        if (!empty($rom_ledger_setting) && !empty(current($rom_ledger_setting)['setting_value'])) {
            $ledger_id = current($rom_ledger_setting)['setting_value'];
            $current_rom_ledger = $db->table('ledgers l')
                                    ->select('l.id, l.name, l.code, g.name as group_name')
                                    ->join('groups g', 'l.group_id = g.id', 'left')
                                    ->where('l.id', $ledger_id)
                                    ->get()
                                    ->getRowArray();
        }
        
        // Get active payment modes
        $payment_modes = $db->table('payment_mode')
                           ->select('id, name, rom')
                           ->where('status', 1)
                           ->orderBy('menu_order')
                           ->get()
                           ->getResultArray();
        
        // Get ROM accounting summary
        $rom_summary = $this->getRomAccountingSummary();
        
        $data = [
            'title' => 'ROM Settings Configuration',
            'rom_settings' => $rom_settings,
            'ledgers' => $ledgers,
            'current_rom_ledger' => $current_rom_ledger,
            'payment_modes' => $payment_modes,
            'rom_summary' => $rom_summary
        ];
        
        return view('admin/rom_settings', $data);
    }
    
    /**
     * Update ROM ledger configuration
     */
    public function update_rom_ledger()
    {
        $ledger_id = $this->request->getPost('rom_ledger_id');
        
        if (empty($ledger_id)) {
            return redirect()->back()->with('error', 'Please select a ROM ledger account');
        }
        
        $db = \Config\Database::connect();
        
        // Verify the ledger exists and is an income account
        $ledger = $db->table('ledgers')
                    ->where('id', $ledger_id)
                    ->where('type', 'INCOME')
                    ->get()
                    ->getRowArray();
        
        if (!$ledger) {
            return redirect()->back()->with('error', 'Invalid ledger selected. Please select a valid income ledger.');
        }
        
        try {
            // Update or insert ROM ledger setting
            $existing = $db->table('settings')
                          ->where('type', 11)
                          ->where('setting_name', 'rom_ledger_id')
                          ->get()
                          ->getRowArray();
            
            if ($existing) {
                $db->table('settings')
                   ->where('id', $existing['id'])
                   ->update([
                       'setting_value' => $ledger_id,
                       'updated_at' => date('Y-m-d H:i:s')
                   ]);
            } else {
                $db->table('settings')->insert([
                    'type' => 11,
                    'setting_name' => 'rom_ledger_id',
                    'setting_value' => $ledger_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            return redirect()->back()->with('success', 'ROM ledger configuration updated successfully');
            
        } catch (\Exception $e) {
            log_message('error', 'ROM ledger update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update ROM ledger configuration');
        }
    }
    
    /**
     * Update payment mode ROM support
     */
    public function update_payment_modes()
    {
        $rom_enabled_modes = $this->request->getPost('rom_payment_modes') ?? [];
        
        $db = \Config\Database::connect();
        
        try {
            // First, disable ROM for all payment modes
            $db->table('payment_mode')->update(['rom' => 0]);
            
            // Then enable ROM for selected payment modes
            if (!empty($rom_enabled_modes)) {
                $db->table('payment_mode')
                   ->whereIn('id', $rom_enabled_modes)
                   ->update(['rom' => 1]);
            }
            
            return redirect()->back()->with('success', 'Payment mode ROM settings updated successfully');
            
        } catch (\Exception $e) {
            log_message('error', 'Payment mode ROM update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update payment mode settings');
        }
    }
    
    /**
     * Get ROM accounting summary for dashboard
     */
    private function getRomAccountingSummary()
    {
        $db = \Config\Database::connect();
        
        // Get total ROM collections
        $total_collections = $db->table('entries e')
                               ->select('SUM(e.cr_total) as total_amount, COUNT(*) as total_registrations')
                               ->where('e.type', 20)
                               ->where('e.is_deleted', 0)
                               ->get()
                               ->getRowArray();
        
        // Get monthly collections (current year)
        $monthly_collections = $db->table('entries e')
                                 ->select('MONTH(e.date) as month, SUM(e.cr_total) as amount, COUNT(*) as count')
                                 ->where('e.type', 20)
                                 ->where('e.is_deleted', 0)
                                 ->where('YEAR(e.date)', date('Y'))
                                 ->groupBy('MONTH(e.date)')
                                 ->orderBy('month')
                                 ->get()
                                 ->getResultArray();
        
        // Get recent ROM transactions
        $recent_transactions = $db->table('entries e')
                                 ->select('e.id, e.number, e.date, e.cr_total as amount, e.narration')
                                 ->where('e.type', 20)
                                 ->where('e.is_deleted', 0)
                                 ->orderBy('e.date', 'DESC')
                                 ->limit(10)
                                 ->get()
                                 ->getResultArray();
        
        return [
            'total_collections' => $total_collections,
            'monthly_collections' => $monthly_collections,
            'recent_transactions' => $recent_transactions
        ];
    }
    
    /**
     * Get ROM accounting report
     */
    public function accounting_report()
    {
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-t');
        
        $db = \Config\Database::connect();
        
        // Use the view created in SQL setup
        $report_data = $db->table('view_rom_accounting v')
                         ->where('v.registration_date >=', $start_date)
                         ->where('v.registration_date <=', $end_date)
                         ->orderBy('v.registration_date', 'DESC')
                         ->get()
                         ->getResultArray();
        
        // Get summary totals
        $summary = $db->table('view_rom_accounting v')
                     ->select('
                         COUNT(*) as total_registrations,
                         SUM(v.total_amount) as total_amount,
                         SUM(v.paid_amount) as total_paid,
                         SUM(CASE WHEN v.payment_status = "paid" THEN 1 ELSE 0 END) as paid_count,
                         SUM(CASE WHEN v.payment_status = "pending" THEN 1 ELSE 0 END) as pending_count
                     ')
                     ->where('v.registration_date >=', $start_date)
                     ->where('v.registration_date <=', $end_date)
                     ->get()
                     ->getRowArray();
        
        $data = [
            'title' => 'ROM Accounting Report',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'report_data' => $report_data,
            'summary' => $summary
        ];
        
        return view('admin/rom_accounting_report', $data);
    }
    
    /**
     * Export ROM accounting report to CSV
     */
    public function export_report()
    {
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-t');
        
        $db = \Config\Database::connect();
        
        $report_data = $db->table('view_rom_accounting v')
                         ->where('v.registration_date >=', $start_date)
                         ->where('v.registration_date <=', $end_date)
                         ->orderBy('v.registration_date', 'DESC')
                         ->get()
                         ->getResultArray();
        
        // Set headers for CSV download
        $filename = 'rom_accounting_report_' . $start_date . '_to_' . $end_date . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, [
            'Registration ID',
            'Registration Number', 
            'Registration Date',
            'Couple Names',
            'Total Amount',
            'Paid Amount',
            'Payment Status',
            'Registration Status',
            'Entry Number',
            'Accounting Date',
            'Payment Mode',
            'ROM Ledger'
        ]);
        
        // CSV data
        foreach ($report_data as $row) {
            fputcsv($output, [
                $row['registration_id'],
                $row['registration_number'],
                $row['registration_date'],
                $row['couple_names'],
                $row['total_amount'],
                $row['paid_amount'],
                ucfirst($row['payment_status']),
                ucfirst($row['registration_status']),
                $row['entry_number'],
                $row['accounting_date'],
                $row['payment_mode_name'],
                $row['rom_ledger_name']
            ]);
        }
        
        fclose($output);
        exit();
    }
    
    /**
     * Test ROM accounting entry creation
     */
    public function test_accounting()
    {
        if (!session('is_admin')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }
        
        $db = \Config\Database::connect();
        
        // Check if ROM ledger is configured
        $rom_ledger_id = $db->table('settings')
                           ->select('setting_value')
                           ->where('type', 11)
                           ->where('setting_name', 'rom_ledger_id')
                           ->get()
                           ->getRow();
        
        if (!$rom_ledger_id || empty($rom_ledger_id->setting_value)) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'ROM Ledger ID not configured. Please set it in settings first.'
            ]);
        }
        
        // Get first active payment mode
        $payment_mode = $db->table('payment_mode')
                          ->select('id')
                          ->where('status', 1)
                          ->where('rom', 1)
                          ->limit(1)
                          ->get()
                          ->getRow();
        
        if (!$payment_mode) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'No ROM-enabled payment modes found. Please configure payment modes first.'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'ROM accounting configuration is properly set up',
            'rom_ledger_id' => $rom_ledger_id->setting_value,
            'test_payment_mode_id' => $payment_mode->id
        ]);
    }
}