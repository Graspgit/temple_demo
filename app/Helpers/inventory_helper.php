<?php

if (!function_exists('format_currency')) {
    /**
     * Format number as currency
     */
    function format_currency($amount, $symbol = '₹')
    {
        return $symbol . ' ' . number_format($amount, 2);
    }
}

if (!function_exists('format_quantity')) {
    /**
     * Format quantity with appropriate decimal places
     */
    function format_quantity($quantity, $decimal_places = 3)
    {
        return number_format($quantity, $decimal_places);
    }
}

if (!function_exists('generate_product_code')) {
    /**
     * Generate product code based on category
     */
    function generate_product_code($categoryCode, $sequence)
    {
        return $categoryCode . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('calculate_gst')) {
    /**
     * Calculate GST amount
     */
    function calculate_gst($amount, $gst_rate)
    {
        return ($amount * $gst_rate) / 100;
    }
}

if (!function_exists('get_temple_item_types')) {
    /**
     * Get temple specific item types
     */
    function get_temple_item_types()
    {
        return [
            'donation' => 'Donation Item',
            'prasadam' => 'Prasadam Item',
            'pooja' => 'Pooja Item'
        ];
    }
}

if (!function_exists('get_warehouse_types')) {
    /**
     * Get warehouse types
     */
    function get_warehouse_types()
    {
        return [
            'main' => 'Main Store',
            'kitchen' => 'Kitchen Store',
            'pooja' => 'Pooja Store',
            'prasadam' => 'Prasadam Store',
            'general' => 'General Store'
        ];
    }
}

if (!function_exists('validate_barcode')) {
    /**
     * Validate barcode format
     */
    function validate_barcode($barcode)
    {
        // EAN-13 validation
        if (strlen($barcode) != 13) {
            return false;
        }
        
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int)$barcode[$i] * ($i % 2 === 0 ? 1 : 3);
        }
        
        $checkDigit = (10 - ($sum % 10)) % 10;
        
        return $checkDigit == (int)$barcode[12];
    }
}