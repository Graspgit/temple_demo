<?php
/**
 * Balance Sheet Helper Functions
 * Place this file in app/Helpers/balancesheet_helper.php
 */

if (!function_exists('get_ledger_amt_new_rightcode_triplezero_single')) {
    /**
     * Get ledger amount for a single ledger
     */
    function get_ledger_amt_new_rightcode_triplezero_single($ledger_id, $sdate, $tdate) {
        $db = \Config\Database::connect();
        
        // Get opening balance from ac_year_ledger_balance
        $ac_year = $db->table('ac_year')->where('status', 1)->get()->getRowArray();
        $op_balance_data = $db->table('ac_year_ledger_balance')
            ->where('ledger_id', $ledger_id)
            ->where('ac_year_id', $ac_year['id'])
            ->get()
            ->getRowArray();
        
        $op_balance = 0;
        if ($op_balance_data) {
            $op_balance = ($op_balance_data['dr_amount'] != 0) ? 
                $op_balance_data['dr_amount'] : -$op_balance_data['cr_amount'];
        }
        
        // Get transactions within date range
        $transactions = $db->query("
            SELECT 
                SUM(CASE WHEN ei.dc = 'D' THEN ei.amount ELSE 0 END) as dr_total,
                SUM(CASE WHEN ei.dc = 'C' THEN ei.amount ELSE 0 END) as cr_total
            FROM entries e
            JOIN entryitems ei ON e.id = ei.entry_id
            WHERE ei.ledger_id = ? 
            AND e.date >= ? 
            AND e.date <= ?
        ", [$ledger_id, $sdate, $tdate])->getRowArray();
        
        $dr_total = $transactions['dr_total'] ?? 0;
        $cr_total = $transactions['cr_total'] ?? 0;
        
        return $op_balance + $dr_total - $cr_total;
    }
}

if (!function_exists('get_ledger_amt_new_rightcode_triplezero_previousyear')) {
    /**
     * Get ledger amount for previous year
     */
    function get_ledger_amt_new_rightcode_triplezero_previousyear($ledger_id, $sdate, $tdate) {
        $db = \Config\Database::connect();
        
        // Calculate previous year dates
        $prev_sdate = date('Y-m-d', strtotime($sdate . ' -1 year'));
        $prev_tdate = date('Y-m-d', strtotime($tdate . ' -1 year'));
        
        // Get previous year's ac_year record
        $prev_ac_year = $db->table('ac_year')
            ->where('from_year_month <=', date('Y-m', strtotime($prev_sdate)))
            ->where('to_year_month >=', date('Y-m', strtotime($prev_tdate)))
            ->get()
            ->getRowArray();
        
        if (!$prev_ac_year) {
            return 0;
        }
        
        // Get opening balance for previous year
        $op_balance_data = $db->table('ac_year_ledger_balance')
            ->where('ledger_id', $ledger_id)
            ->where('ac_year_id', $prev_ac_year['id'])
            ->get()
            ->getRowArray();
        
        $op_balance = 0;
        if ($op_balance_data) {
            $op_balance = ($op_balance_data['dr_amount'] != 0) ? 
                $op_balance_data['dr_amount'] : -$op_balance_data['cr_amount'];
        }
        
        // Get transactions for previous year
        $transactions = $db->query("
            SELECT 
                SUM(CASE WHEN ei.dc = 'D' THEN ei.amount ELSE 0 END) as dr_total,
                SUM(CASE WHEN ei.dc = 'C' THEN ei.amount ELSE 0 END) as cr_total
            FROM entries e
            JOIN entryitems ei ON e.id = ei.entry_id
            WHERE ei.ledger_id = ? 
            AND e.date >= ? 
            AND e.date <= ?
        ", [$ledger_id, $prev_sdate, $prev_tdate])->getRowArray();
        
        $dr_total = $transactions['dr_total'] ?? 0;
        $cr_total = $transactions['cr_total'] ?? 0;
        
        return $op_balance + $dr_total - $cr_total;
    }
}

if (!function_exists('get_ledger_amt_new_rightcode_triplezero')) {
    /**
     * Get consolidated ledger amount for ledgers with same left_code
     */
    function get_ledger_amt_new_rightcode_triplezero($ledger_id, $sdate, $tdate, $fund_id = null) {
        $db = \Config\Database::connect();
        
        // Get the ledger details
        $ledger = $db->table('ledgers')->where('id', $ledger_id)->get()->getRowArray();
        if (!$ledger || empty($ledger['left_code'])) {
            return get_ledger_amt_new_rightcode_triplezero_single($ledger_id, $sdate, $tdate);
        }
        
        // Get all ledgers with same left_code
        $related_ledgers = $db->table('ledgers')
            ->where('left_code', $ledger['left_code'])
            ->orderBy('right_code', 'ASC')
            ->get()
            ->getResultArray();
        
        $total = 0;
        foreach ($related_ledgers as $rel_ledger) {
            $total += get_ledger_amt_new_rightcode_triplezero_single($rel_ledger['id'], $sdate, $tdate);
        }
        
        return $total;
    }
}

if (!function_exists('get_ledger_amt_new_rightcode_triplezero_subtotal')) {
    /**
     * Get subtotal for ledger amount (used for checking if zero)
     */
    function get_ledger_amt_new_rightcode_triplezero_subtotal($ledger_id, $sdate, $tdate, $fund_id = null) {
        return abs(get_ledger_amt_new_rightcode_triplezero($ledger_id, $sdate, $tdate, $fund_id));
    }
}

if (!function_exists('get_group_amt_new_rightcode_triplezero')) {
    /**
     * Get total amount for a group
     */
    function get_group_amt_new_rightcode_triplezero($group_id, $sdate, $tdate, $fund_id = null) {
        $db = \Config\Database::connect();
        
        $total = 0;
        
        // Get all ledgers in this group
        $ledgers = $db->table('ledgers')->where('group_id', $group_id)->get()->getResultArray();
        foreach ($ledgers as $ledger) {
            $total += get_ledger_amt_new_rightcode_triplezero_single($ledger['id'], $sdate, $tdate);
        }
        
        // Get all sub-groups
        $subgroups = $db->table('groups')->where('parent_id', $group_id)->get()->getResultArray();
        foreach ($subgroups as $subgroup) {
            $total += get_group_amt_new_rightcode_triplezero($subgroup['id'], $sdate, $tdate, $fund_id);
        }
        
        return $total;
    }
}

if (!function_exists('get_group_amt_new_rightcode_triplezero_subtotal')) {
    /**
     * Get subtotal for group amount (used for checking if zero)
     */
    function get_group_amt_new_rightcode_triplezero_subtotal($group_id, $sdate, $tdate, $fund_id = null) {
        return abs(get_group_amt_new_rightcode_triplezero($group_id, $sdate, $tdate, $fund_id));
    }
}

if (!function_exists('get_group_amt_new_rightcode_triplezero_previousyear')) {
    /**
     * Get group amount for previous year
     */
    function get_group_amt_new_rightcode_triplezero_previousyear($group_id, $sdate, $tdate, $fund_id = null) {
        $db = \Config\Database::connect();
        
        $total = 0;
        
        // Get all ledgers in this group
        $ledgers = $db->table('ledgers')->where('group_id', $group_id)->get()->getResultArray();
        foreach ($ledgers as $ledger) {
            $total += get_ledger_amt_new_rightcode_triplezero_previousyear($ledger['id'], $sdate, $tdate);
        }
        
        // Get all sub-groups
        $subgroups = $db->table('groups')->where('parent_id', $group_id)->get()->getResultArray();
        foreach ($subgroups as $subgroup) {
            $total += get_group_amt_new_rightcode_triplezero_previousyear($subgroup['id'], $sdate, $tdate, $fund_id);
        }
        
        return $total;
    }
}

if (!function_exists('total_group_amt_new_rightcode_triplezero')) {
    /**
     * Get total amount for a top-level group
     */
    function total_group_amt_new_rightcode_triplezero($group_id, $sdate, $tdate, $fund_id = null) {
        return get_group_amt_new_rightcode_triplezero($group_id, $sdate, $tdate, $fund_id);
    }
}

if (!function_exists('total_group_amt_new_rightcode_triplezero_previousyear')) {
    /**
     * Get total amount for a top-level group for previous year
     */
    function total_group_amt_new_rightcode_triplezero_previousyear($group_id, $sdate, $tdate, $fund_id = null) {
        return get_group_amt_new_rightcode_triplezero_previousyear($group_id, $sdate, $tdate, $fund_id);
    }
}

if (!function_exists('get_ledger_name')) {
    /**
     * Get ledger name with code
     */
    function get_ledger_name($ledger_id) {
        $db = \Config\Database::connect();
        $ledger = $db->table('ledgers')->where('id', $ledger_id)->get()->getRowArray();
        if ($ledger) {
            return "({$ledger['left_code']}/{$ledger['right_code']}) {$ledger['name']}";
        }
        return '';
    }
}

if (!function_exists('get_ledger_name_only')) {
    /**
     * Get ledger name without code
     */
    function get_ledger_name_only($ledger_id) {
        $db = \Config\Database::connect();
        $ledger = $db->table('ledgers')->where('id', $ledger_id)->get()->getRowArray();
        return $ledger['name'] ?? '';
    }
}

if (!function_exists('history_of_balancing')) {
    /**
     * Get historical balancing data
     */
    function history_of_balancing() {
        // This would need to be implemented based on your specific business logic
        // For now, returning a default structure
        return [
            'dr_total' => 0,
            'cr_total' => 0
        ];
    }
}

if (!function_exists('booking_calendar_range_year')) {
    /**
     * Get booking calendar range year
     */
    function booking_calendar_range_year($year = null) {
        if ($year) {
            return $year . '-12-31';
        }
        return date('Y-m-d');
    }
}