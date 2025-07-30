<?php

namespace App\Validation;

class InventoryRules
{
    /**
     * Check if product code is unique
     */
    public function unique_product_code($str, string $fields, array $data): bool
    {
        $model = new \App\Models\Inventory\ProductModel();
        $id = $data['id'] ?? null;
        
        return $model->isUniqueCode($str, $id);
    }
    
    /**
     * Validate GST rate
     */
    public function valid_gst_rate($str): bool
    {
        $validRates = [0, 5, 12, 18, 28];
        return in_array((float)$str, $validRates);
    }
    
    /**
     * Check parent category
     */
    public function valid_parent_category($id): bool
    {
        if (empty($id)) {
            return true;
        }
        
        $model = new \App\Models\Inventory\ProductCategoryModel();
        return $model->find($id) !== null;
    }
    
    /**
     * Validate expiry date
     */
    public function future_date($str): bool
    {
        $date = strtotime($str);
        return $date > time();
    }
}