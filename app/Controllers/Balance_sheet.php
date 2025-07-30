<?php
namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;

class Balance_sheet extends BaseController
{

    function __construct()
    {
        parent::__construct();
        helper(['url', 'common']);
        
        if (!$this->session->get('login') || $this->session->get('role') != 1) {
            $_SESSION['fail'] = 'Please Login';
            return redirect()->to('/login');
        }
    }
    
   public function balancesheet_full()
    {
        $tdate = $this->request->getPost('tdate') ?: date("Y-m-d");
        $active_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
        $sdate = $active_year['from_year_month'] . "-01";
        
        $data = [
            'sdate' => $sdate,
            'tdate' => $tdate,
            'list' => $this->generateBalanceSheetData($sdate, $tdate),
            'check_financial_year' => $active_year,
            'funds' => $this->db->table("funds")->get()->getResultArray()
        ];
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('account_report/balancesheet_full', $data);
        echo view('template/footer');
    }
    
    /**
     * New print view method - opens in new window
     */
    public function print_view()
    {
        $tdate = $this->request->getGet('tdate') ?: date("Y-m-d");
        $active_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
        $sdate = $active_year['from_year_month'] . "-01";
        
        // Get admin profile for heading
        $admin_profile = $this->db->table("admin_profile")->where('id', 1)->get()->getRowArray();
        $heading = $admin_profile['name'] ?? 'SREE SELVA VINAYAGAR TEMPLE';
        
        $data = [
            'list' => $this->generateBalanceSheetData($sdate, $tdate, false), // false to exclude links
            'tdate' => $tdate,
            'heading' => $heading
        ];
        
        echo view('account_report/print_view_balancesheet', $data);
    }
    
    /**
     * Generate balance sheet data with improved performance
     */
    private function generateBalanceSheetData($sdate, $tdate, $includeLinks = true)
    {
        $datas = [];
        $groups = $this->db->table('groups')
            ->whereIn('code', ['1000', '2000', '3000'])
            ->orderBy('code', 'ASC')
            ->get()
            ->getResult();
        
        $currentPL = $this->calculateCurrentProfitLoss($sdate, $tdate);
        $history = history_of_balancing();
        
        foreach ($groups as $group) {
            $groupData = $this->processGroup($group, $sdate, $tdate, $currentPL, $history, $includeLinks);
            $datas = array_merge($datas, $groupData['html']);
        }
        
        // Add total liabilities & equity
        $liabilitiesEquityTotal = $this->calculateTotalLiabilitiesEquity($groups, $sdate, $tdate, $currentPL, $history, $includeLinks);
        $datas[] = $this->formatTotalRow('Total Liabilities & Equity', $liabilitiesEquityTotal['current'], $liabilitiesEquityTotal['previous']);
        $datas[] = '</table>';
        
        return $datas;
    }
    
    /**
     * Process a single group and its children
     */
    private function processGroup($group, $sdate, $tdate, $currentPL, $history, $includeLinks = true)
    {
        $html = [];
        $groupTotal = 0;
        $groupTotalPrev = 0;
        
        // Add group header
        $html[] = $this->formatGroupHeader($group);
        
        // Process sub-groups
        $subGroups = $this->db->table('groups')
            ->where('parent_id', $group->id)
            ->orderBy('code', 'ASC')
            ->get()
            ->getResult();
        
        foreach ($subGroups as $subGroup) {
            $subGroupData = $this->processSubGroup($subGroup, $sdate, $tdate, $group->name, $currentPL, $history, $includeLinks);
            if ($subGroupData['total'] != 0 || $subGroupData['totalPrev'] != 0) {
                $html = array_merge($html, $subGroupData['html']);
                $groupTotal += $subGroupData['total'];
                $groupTotalPrev += $subGroupData['totalPrev'];
            }
        }
        
        // Process direct ledgers
        $directLedgers = $this->db->table('ledgers')
            ->where('group_id', $group->id)
            ->get()
            ->getResult();
        
        foreach ($directLedgers as $ledger) {
            $ledgerData = $this->processLedger($ledger, $sdate, $tdate, $group->name, $currentPL, $history, $includeLinks);
            if ($ledgerData['amount'] != 0 || $ledgerData['amountPrev'] != 0) {
                $html[] = $ledgerData['html'];
                $groupTotal += $ledgerData['amount'];
                $groupTotalPrev += $ledgerData['amountPrev'];
            }
        }
        
        // Add group total
        if ($groupTotal != 0 || $groupTotalPrev != 0) {
            $html[] = $this->formatGroupTotal($group->name, $groupTotal, $groupTotalPrev);
        }
        
        return [
            'html' => $html,
            'total' => $groupTotal,
            'totalPrev' => $groupTotalPrev
        ];
    }
    
    /**
     * Process sub-groups
     */
    private function processSubGroup($subGroup, $sdate, $tdate, $parentGroupName, $currentPL, $history, $includeLinks = true)
    {
        $html = [];
        $subGroupTotal = 0;
        $subGroupTotalPrev = 0;
        
        // Add sub-group header
        $html[] = $this->formatSubGroupHeader($subGroup);
        
        // Process ledgers in this sub-group
        $ledgers = $this->db->table('ledgers')
            ->where('group_id', $subGroup->id)
            ->get()
            ->getResult();
        
        foreach ($ledgers as $ledger) {
            $ledgerData = $this->processLedger($ledger, $sdate, $tdate, $parentGroupName, $currentPL, $history, $includeLinks);
            if ($ledgerData['amount'] != 0 || $ledgerData['amountPrev'] != 0) {
                $html[] = $ledgerData['html'];
                $subGroupTotal += $ledgerData['amount'];
                $subGroupTotalPrev += $ledgerData['amountPrev'];
            }
        }
        
        // Process nested sub-groups
        $nestedGroups = $this->db->table('groups')
            ->where('parent_id', $subGroup->id)
            ->orderBy('code', 'ASC')
            ->get()
            ->getResult();
        
        foreach ($nestedGroups as $nestedGroup) {
            $nestedData = $this->processSubGroup($nestedGroup, $sdate, $tdate, $parentGroupName, $currentPL, $history, $includeLinks);
            if ($nestedData['total'] != 0 || $nestedData['totalPrev'] != 0) {
                $html = array_merge($html, $nestedData['html']);
                $subGroupTotal += $nestedData['total'];
                $subGroupTotalPrev += $nestedData['totalPrev'];
            }
        }
        
        // Add sub-group total if needed
        if ($subGroupTotal != 0 || $subGroupTotalPrev != 0) {
            $html[] = $this->formatSubGroupTotal($subGroup->name, $subGroupTotal, $subGroupTotalPrev);
        }
        
        return [
            'html' => $html,
            'total' => $subGroupTotal,
            'totalPrev' => $subGroupTotalPrev
        ];
    }
    
    /**
     * Process individual ledger
     */
    private function processLedger($ledger, $sdate, $tdate, $parentGroupName, $currentPL, $history, $includeLinks = true)
    {
        $amount = get_ledger_amt_new_rightcode_triplezero_single($ledger->id, $sdate, $tdate);
        $amountPrev = get_ledger_amt_new_rightcode_triplezero_previousyear($ledger->id, $sdate, $tdate);
        
        // Adjust sign for non-asset accounts
        if (strtolower($parentGroupName) != 'assets') {
            $amount = -1 * $amount;
            $amountPrev = -1 * $amountPrev;
        }
        
        $html = '';
        if ($amount != 0 || $amountPrev != 0) {
            $html = $this->formatLedgerRow($ledger, $amount, $amountPrev, $sdate, $tdate, $includeLinks);
            
            // Add P&L or Historical Balancing if applicable
            if (!empty($ledger->pa)) {
                $html .= $this->formatProfitLossRow($currentPL);
                $amount += $currentPL;
            }
            
            if (!empty($ledger->hb) && $history['dr_total'] != $history['cr_total']) {
                $hb = $history['dr_total'] - $history['cr_total'];
                $html .= $this->formatHistoricalBalancingRow($hb);
                $amount += $hb;
            }
        }
        
        return [
            'html' => $html,
            'amount' => $amount,
            'amountPrev' => $amountPrev
        ];
    }
    
    /**
     * Calculate current profit/loss
     */
    private function calculateCurrentProfitLoss($sdate, $tdate)
    {
        $incomes = $this->db->query("
            SELECT COALESCE(sum(if(dc = 'C', amount, 0)), 0) as cr_total, 
                   COALESCE(sum(if(dc = 'D', amount, 0)), 0) as dr_total 
            FROM entries e 
            LEFT JOIN entryitems ei ON e.id = ei.entry_id 
            WHERE e.date >= ? AND e.date <= ? 
            AND ei.ledger_id IN (
                SELECT id FROM ledgers WHERE group_id IN (
                    SELECT id FROM `groups` WHERE code IN (4000, 8000) 
                    OR parent_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000))
                    OR parent_id IN (SELECT id FROM `groups` WHERE parent_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000)))
                )
            )", [$sdate, $tdate])->getRowArray();
        
        $expenses = $this->db->query("
            SELECT COALESCE(sum(if(dc = 'C', amount, 0)), 0) as cr_total, 
                   COALESCE(sum(if(dc = 'D', amount, 0)), 0) as dr_total 
            FROM entries e 
            LEFT JOIN entryitems ei ON e.id = ei.entry_id 
            WHERE e.date >= ? AND e.date <= ? 
            AND ei.ledger_id IN (
                SELECT id FROM ledgers WHERE group_id IN (
                    SELECT id FROM `groups` WHERE code IN (5000, 6000, 9000) 
                    OR parent_id IN (SELECT id FROM `groups` WHERE code IN (5000, 6000, 9000))
                    OR parent_id IN (SELECT id FROM `groups` WHERE parent_id IN (SELECT id FROM `groups` WHERE code IN (5000, 6000, 9000)))
                )
            )", [$sdate, $tdate])->getRowArray();
        
        $currentIncomes = $incomes['cr_total'] - $incomes['dr_total'];
        $currentExpenses = $expenses['dr_total'] - $expenses['cr_total'];
        
        return $currentIncomes - $currentExpenses;
    }
    
    /**
     * Calculate total liabilities & equity
     */
    private function calculateTotalLiabilitiesEquity($groups, $sdate, $tdate, $currentPL, $history)
    {
        $liabilitiesTotal = 0;
        $capitalTotal = 0;
        $liabilitiesTotalPrev = 0;
        $capitalTotalPrev = 0;
        
        foreach ($groups as $group) {
            $groupData = $this->processGroup($group, $sdate, $tdate, $currentPL, $history);
            
            if ($group->name == 'Liabilities') {
                $liabilitiesTotal = $groupData['total'];
                $liabilitiesTotalPrev = $groupData['totalPrev'];
            } elseif ($group->name == 'Equity' || $group->name == 'Capital') {
                $capitalTotal = $groupData['total'];
                $capitalTotalPrev = $groupData['totalPrev'];
            }
        }
        
        return [
            'current' => $liabilitiesTotal + $capitalTotal,
            'previous' => $liabilitiesTotalPrev + $capitalTotalPrev
        ];
    }
    
    // HTML Formatting Methods
    private function formatGroupHeader($group)
    {
        return '<tr class="group-header">
            <td style="width: 40%;text-transform: uppercase; padding:10px !important;">
                <b>(' . $group->code . ') ' . $group->name . '</b>
            </td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
        </tr>';
    }
    
    private function formatSubGroupHeader($subGroup)
    {
        return '<tr class="subgroup-header">
            <td style="width: 40%; padding:8px !important;">
                <span style="margin-left: 2%; font-weight:bold; color: black;text-transform: uppercase;">
                    (' . $subGroup->code . ') ' . $subGroup->name . '
                </span>
            </td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
        </tr>';
    }
    
    private function formatLedgerRow($ledger, $amount, $amountPrev, $sdate, $tdate, $includeLinks = true)
    {
        $formattedAmount = $this->formatAmount($amount);
        $formattedAmountPrev = $this->formatAmount($amountPrev);
        $ledgerText = '(' . $ledger->left_code . '/' . $ledger->right_code . ') ' . $ledger->name;
        
        if ($includeLinks) {
            return '<tr class="ledger-row">
                <td style="width: 40%;">
                    <a style="margin-left: 6%;cursor: pointer;" 
                       href="' . base_url() . '/accountreport/ledger_report/?ledger=' . $ledger->id . '&fdate=' . $sdate . '&tdate=' . $tdate . '" 
                       target="_blank">
                        ' . $ledgerText . '
                    </a>
                </td>
                <td style="text-align: right;">' . $formattedAmount . '</td>
                <td style="text-align: right;">' . $formattedAmountPrev . '</td>
            </tr>';
        } else {
            return '<tr class="ledger-row">
                <td style="width: 40%;">
                    <span style="margin-left: 6%;">' . $ledgerText . '</span>
                </td>
                <td style="text-align: right;">' . $formattedAmount . '</td>
                <td style="text-align: right;">' . $formattedAmountPrev . '</td>
            </tr>';
        }
    }
    
    private function formatGroupTotal($groupName, $total, $totalPrev)
    {
        return '<tr class="group-total">
            <td style="width: 60%;text-transform: uppercase; text-align:center;">
                <b>Total ' . $groupName . '</b>
            </td>
            <td style="text-align: right;"><b>' . $this->formatAmount($total) . '</b></td>
            <td style="text-align: right;"><b>' . $this->formatAmount($totalPrev) . '</b></td>
        </tr></table><table class="table1" border="1" style="width:100%;">';
    }
    
    private function formatSubGroupTotal($subGroupName, $total, $totalPrev)
    {
        return '<tr class="subgroup-total">
            <td style="width: 60%;text-transform: uppercase; text-align:center;">
                <b>Total ' . $subGroupName . '</b>
            </td>
            <td style="text-align: right;"><b>' . $this->formatAmount($total) . '</b></td>
            <td style="text-align: right;"><b>' . $this->formatAmount($totalPrev) . '</b></td>
        </tr></table><table class="table1" border="1" style="width:100%;">';
    }
    
    private function formatTotalRow($label, $amount, $amountPrev)
    {
        return '<tr style="color: black;">
            <td style="width: 60%;text-transform: uppercase; text-align:center;">
                <b>' . $label . '</b>
            </td>
            <td style="text-align: right;"><b>' . $this->formatAmount($amount) . '</b></td>
            <td style="text-align: right;"><b>' . $this->formatAmount($amountPrev) . '</b></td>
        </tr>';
    }
    
    private function formatProfitLossRow($currentPL)
    {
        return '<tr style="color: black;">
            <td style="width: 40%;"><span style="margin-left: 6%;">Current Profit & Loss</span></td>
            <td style="text-align: right;">' . $this->formatAmount($currentPL) . '</td>
            <td style="text-align: right;">-</td>
        </tr>';
    }
    
    private function formatHistoricalBalancingRow($hb)
    {
        return '<tr style="color: black;">
            <td style="width: 40%;"><span style="margin-left: 6%;">Historical Balancing</span></td>
            <td style="text-align: right;">' . $this->formatAmount($hb) . '</td>
            <td style="text-align: right;">-</td>
        </tr>';
    }
    
    private function formatAmount($amount)
    {
        if ($amount < 0) {
            return "( " . number_format(abs($amount), 2) . " )";
        }
        return number_format($amount, 2);
    }
    
    /**
     * Print balance sheet
     */
    public function print_balancesheet_full()
    {
        $tdate = $this->request->getPost('tpdate') ?: date("Y-m-d");
        $active_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
        $sdate = $active_year['from_year_month'] . "-01";
        
        // Get admin profile for heading
        $admin_profile = $this->db->table("admin_profile")->where('id', 1)->get()->getRowArray();
        $heading = $admin_profile['name'] ?? 'SREE SELVA VINAYAGAR TEMPLE';
        
        $data = [
            'list' => $this->generateBalanceSheetData($sdate, $tdate, false), // false to exclude links
            'tdate' => $tdate,
            'heading' => $heading
        ];
        
        echo view('account_report/print_balancesheet_full', $data);
    }
    
    /**
     * Generate PDF
     */
    public function pdf_balancesheet_full()
    {
        $tdate = $this->request->getGet('tpdate') ?: date("Y-m-d");
        $active_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
        $sdate = $active_year['from_year_month'] . "-01";
        
        // Get admin profile for heading
        $admin_profile = $this->db->table("admin_profile")->where('id', 1)->get()->getRowArray();
        $heading = $admin_profile['name'] ?? 'SREE SELVA VINAYAGAR TEMPLE';
        
        $data = [
            'list' => $this->generateBalanceSheetData($sdate, $tdate, false), // false to exclude links
            'tdate' => $tdate,
            'heading' => $heading
        ];
        
        $html = view('account_report/print_balancesheet_full', $data);
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('isPhpEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("balancesheet.pdf", array("Attachment" => true));
    }
    
    /**
     * Generate Excel
     */
    public function excel_balancesheet_full()
    {
        $tdate = $this->request->getPost('tpdate') ?: date("Y-m-d");
        $active_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
        $sdate = $active_year['from_year_month'] . "-01";
        
        $data = $this->generateExcelData($sdate, $tdate);
        
        // Get admin profile for heading
        $admin_profile = $this->db->table("admin_profile")->where('id', 1)->get()->getRowArray();
        $heading = $admin_profile['name'] ?? 'SREE SELVA VINAYAGAR TEMPLE - BALANCE SHEET';
        
        $fileName = "balancesheet_" . $sdate . "_to_" . $tdate;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $sheet->getStyle('A1')->getFont()->setBold(true)->setName('Arial')->setSize(10);
        $sheet->getStyle('A2:C2')->getFont()->setBold(true);
        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', $heading);
        $sheet->setCellValue('A2', 'Account Name');
        $sheet->setCellValue('B2', 'Current Year');
        $sheet->setCellValue('C2', 'Previous Year');
        
        // Add data
        $row = 3;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item['accountname']);
            $sheet->setCellValue('B' . $row, $item['amount']);
            $sheet->setCellValue('C' . $row, $item['previousyear_amount']);
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('uploads/excel/' . $fileName . '.xlsx');
        
        return $this->response->download('uploads/excel/' . $fileName . '.xlsx', null)
            ->setFileName($fileName . '.xlsx');
    }
    
    /**
     * Generate data for Excel export
     */
    private function generateExcelData($sdate, $tdate)
    {
        $excelData = [];
        $groups = $this->db->table('groups')
            ->whereIn('code', ['1000', '2000', '3000'])
            ->orderBy('code', 'ASC')
            ->get()
            ->getResult();
        
        $currentPL = $this->calculateCurrentProfitLoss($sdate, $tdate);
        $history = history_of_balancing();
        
        foreach ($groups as $group) {
            $this->addExcelGroupData($excelData, $group, $sdate, $tdate, $currentPL, $history);
        }
        
        // Add Total Liabilities & Equity row
        $liabilitiesEquityTotal = $this->calculateTotalLiabilitiesEquity($groups, $sdate, $tdate, $currentPL, $history);
        $excelData[] = [
            'accountname' => 'Total Liabilities & Equity',
            'amount' => $this->formatAmount($liabilitiesEquityTotal['current']),
            'previousyear_amount' => $this->formatAmount($liabilitiesEquityTotal['previous'])
        ];
        
        return $excelData;
    }
    
    /**
     * Add group data to Excel array
     */
    private function addExcelGroupData(&$excelData, $group, $sdate, $tdate, $currentPL, $history)
    {
        $groupTotal = 0;
        $groupTotalPrev = 0;
        
        // Add group header
        $excelData[] = [
            'accountname' => '(' . $group->code . ') ' . $group->name,
            'amount' => '',
            'previousyear_amount' => ''
        ];
        
        // Process subgroups
        $subGroups = $this->db->table('groups')
            ->where('parent_id', $group->id)
            ->orderBy('code', 'ASC')
            ->get()
            ->getResult();
        
        foreach ($subGroups as $subGroup) {
            $subGroupData = $this->processExcelSubGroup($subGroup, $sdate, $tdate, $group->name, $currentPL, $history);
            if ($subGroupData['total'] != 0 || $subGroupData['totalPrev'] != 0) {
                foreach ($subGroupData['data'] as $row) {
                    $excelData[] = $row;
                }
                $groupTotal += $subGroupData['total'];
                $groupTotalPrev += $subGroupData['totalPrev'];
            }
        }
        
        // Process direct ledgers
        $directLedgers = $this->db->table('ledgers')
            ->where('group_id', $group->id)
            ->get()
            ->getResult();
        
        foreach ($directLedgers as $ledger) {
            $amount = get_ledger_amt_new_rightcode_triplezero_single($ledger->id, $sdate, $tdate);
            $amountPrev = get_ledger_amt_new_rightcode_triplezero_previousyear($ledger->id, $sdate, $tdate);
            
            if (strtolower($group->name) != 'assets') {
                $amount = -1 * $amount;
                $amountPrev = -1 * $amountPrev;
            }
            
            if ($amount != 0 || $amountPrev != 0) {
                $excelData[] = [
                    'accountname' => '    (' . $ledger->left_code . '/' . $ledger->right_code . ') ' . $ledger->name,
                    'amount' => $this->formatAmount($amount),
                    'previousyear_amount' => $this->formatAmount($amountPrev)
                ];
                
                // Add P&L if applicable
                if (!empty($ledger->pa)) {
                    $excelData[] = [
                        'accountname' => '    Current Profit & Loss',
                        'amount' => $this->formatAmount($currentPL),
                        'previousyear_amount' => '-'
                    ];
                    $amount += $currentPL;
                }
                
                // Add Historical Balancing if applicable
                if (!empty($ledger->hb) && $history['dr_total'] != $history['cr_total']) {
                    $hb = $history['dr_total'] - $history['cr_total'];
                    $excelData[] = [
                        'accountname' => '    Historical Balancing',
                        'amount' => $this->formatAmount($hb),
                        'previousyear_amount' => '-'
                    ];
                    $amount += $hb;
                }
                
                $groupTotal += $amount;
                $groupTotalPrev += $amountPrev;
            }
        }
        
        // Add group total
        if ($groupTotal != 0 || $groupTotalPrev != 0) {
            $excelData[] = [
                'accountname' => 'Total ' . $group->name,
                'amount' => $this->formatAmount($groupTotal),
                'previousyear_amount' => $this->formatAmount($groupTotalPrev)
            ];
        }
    }
    
    /**
     * Process subgroup for Excel
     */
    private function processExcelSubGroup($subGroup, $sdate, $tdate, $parentGroupName, $currentPL, $history)
    {
        $data = [];
        $subGroupTotal = 0;
        $subGroupTotalPrev = 0;
        
        // Add subgroup header
        $data[] = [
            'accountname' => '  (' . $subGroup->code . ') ' . $subGroup->name,
            'amount' => '',
            'previousyear_amount' => ''
        ];
        
        // Process ledgers
        $ledgers = $this->db->table('ledgers')
            ->where('group_id', $subGroup->id)
            ->get()
            ->getResult();
        
        foreach ($ledgers as $ledger) {
            $amount = get_ledger_amt_new_rightcode_triplezero_single($ledger->id, $sdate, $tdate);
            $amountPrev = get_ledger_amt_new_rightcode_triplezero_previousyear($ledger->id, $sdate, $tdate);
            
            if (strtolower($parentGroupName) != 'assets') {
                $amount = -1 * $amount;
                $amountPrev = -1 * $amountPrev;
            }
            
            if ($amount != 0 || $amountPrev != 0) {
                $data[] = [
                    'accountname' => '    (' . $ledger->left_code . '/' . $ledger->right_code . ') ' . $ledger->name,
                    'amount' => $this->formatAmount($amount),
                    'previousyear_amount' => $this->formatAmount($amountPrev)
                ];
                
                // Add P&L if applicable
                if (!empty($ledger->pa)) {
                    $data[] = [
                        'accountname' => '    Current Profit & Loss',
                        'amount' => $this->formatAmount($currentPL),
                        'previousyear_amount' => '-'
                    ];
                    $amount += $currentPL;
                }
                
                // Add Historical Balancing if applicable
                if (!empty($ledger->hb) && $history['dr_total'] != $history['cr_total']) {
                    $hb = $history['dr_total'] - $history['cr_total'];
                    $data[] = [
                        'accountname' => '    Historical Balancing',
                        'amount' => $this->formatAmount($hb),
                        'previousyear_amount' => '-'
                    ];
                    $amount += $hb;
                }
                
                $subGroupTotal += $amount;
                $subGroupTotalPrev += $amountPrev;
            }
        }
        
        // Process nested subgroups
        $nestedGroups = $this->db->table('groups')
            ->where('parent_id', $subGroup->id)
            ->orderBy('code', 'ASC')
            ->get()
            ->getResult();
        
        foreach ($nestedGroups as $nestedGroup) {
            $nestedData = $this->processExcelSubGroup($nestedGroup, $sdate, $tdate, $parentGroupName, $currentPL, $history);
            if ($nestedData['total'] != 0 || $nestedData['totalPrev'] != 0) {
                foreach ($nestedData['data'] as $row) {
                    $data[] = $row;
                }
                $subGroupTotal += $nestedData['total'];
                $subGroupTotalPrev += $nestedData['totalPrev'];
            }
        }
        
        // Add subgroup total
        if ($subGroupTotal != 0 || $subGroupTotalPrev != 0) {
            $data[] = [
                'accountname' => '  Total ' . $subGroup->name,
                'amount' => $this->formatAmount($subGroupTotal),
                'previousyear_amount' => $this->formatAmount($subGroupTotalPrev)
            ];
        }
        
        return [
            'data' => $data,
            'total' => $subGroupTotal,
            'totalPrev' => $subGroupTotalPrev
        ];
    }
}