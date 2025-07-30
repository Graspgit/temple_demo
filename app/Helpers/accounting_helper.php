<?php
// Create this file as app/Helpers/accounting_helper.php

if (!function_exists('get_ledger_name_only')) {
    function get_ledger_name_only($ledger_id) {
        $db = \Config\Database::connect();
        $ledger = $db->table('ledgers')->where('id', $ledger_id)->get()->getRow();
        return $ledger ? $ledger->name : '';
    }
}

if (!function_exists('get_ledger_code_only')) {
    function get_ledger_code_only($ledger_id) {
        $db = \Config\Database::connect();
        $ledger = $db->table('ledgers')->where('id', $ledger_id)->get()->getRow();
        return $ledger ? $ledger->code : '';
    }
}

if (!function_exists('format_accounting_number')) {
    function format_accounting_number($number, $currency_symbol = '') {
        $formatted = number_format(abs($number), 2, '.', ',');
        
        if ($number < 0) {
            return $currency_symbol . '(' . $formatted . ')';
        }
        
        return $currency_symbol . $formatted;
    }
}

if (!function_exists('calculate_closing_balance')) {
    function calculate_closing_balance($opening_balance, $debit, $credit) {
        return $opening_balance + $debit - $credit;
    }
}

if (!function_exists('get_group_hierarchy')) {
    function get_group_hierarchy($parent_id = 0) {
        $db = \Config\Database::connect();
        $groups = [];
        
        $query = $db->table('groups')
                    ->where('parent_id', $parent_id)
                    ->orderBy('code', 'ASC')
                    ->get();
        
        foreach ($query->getResult() as $group) {
            $groups[] = [
                'id' => $group->id,
                'name' => $group->name,
                'code' => $group->code,
                'children' => get_group_hierarchy($group->id)
            ];
        }
        
        return $groups;
    }
}

if (!function_exists('booking_calendar_range_year')) {
    function booking_calendar_range_year($range_year = null) {
        // This function should return the maximum date allowed for booking
        // Adjust according to your business logic
        if ($range_year) {
            return date('Y-m-d', strtotime('+' . $range_year . ' years'));
        }
        return date('Y-m-d', strtotime('+1 year'));
    }
}

// Load this helper in your BaseController or specific controller
// helper('accounting');