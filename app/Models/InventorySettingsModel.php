<?php

namespace App\Models;

use CodeIgniter\Model;

class InventorySettingsModel extends Model
{
    protected $table = 'inventory_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'setting_key',
        'setting_value',
        'setting_type',
        'description',
        'is_system'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'setting_key' => 'required|is_unique[inventory_settings.setting_key,id,{id}]',
        'setting_type' => 'required|in_list[boolean,integer,decimal,string,json]'
    ];
    
    protected $validationMessages = [
        'setting_key' => [
            'required' => 'Setting key is required',
            'is_unique' => 'This setting key already exists'
        ],
        'setting_type' => [
            'required' => 'Setting type is required',
            'in_list' => 'Invalid setting type'
        ]
    ];
    
    /**
     * Get a specific setting by key
     *
     * @param string $key
     * @return mixed
     */
    public function getSetting($key)
    {
        $setting = $this->where('setting_key', $key)->first();
        
        if (!$setting) {
            return null;
        }
        
        return $this->castValue($setting['setting_value'], $setting['setting_type']);
    }
    
    /**
     * Set a specific setting
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string $description
     * @return bool
     */
    public function setSetting($key, $value, $type = 'string', $description = '')
    {
        $existing = $this->where('setting_key', $key)->first();
        
        $data = [
            'setting_key' => $key,
            'setting_value' => $this->prepareValue($value, $type),
            'setting_type' => $type,
            'description' => $description
        ];
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->insert($data);
        }
    }
    
    /**
     * Get multiple settings by keys
     *
     * @param array $keys
     * @return array
     */
    public function getSettings(array $keys)
    {
        $settings = $this->whereIn('setting_key', $keys)->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $this->castValue($setting['setting_value'], $setting['setting_type']);
        }
        
        return $result;
    }
    
    /**
     * Get all settings as key-value pairs
     *
     * @return array
     */
    public function getAllSettings()
    {
        $settings = $this->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $this->castValue($setting['setting_value'], $setting['setting_type']);
        }
        
        return $result;
    }
    
    /**
     * Get settings by type
     *
     * @param string $type
     * @return array
     */
    public function getSettingsByType($type)
    {
        $settings = $this->where('setting_type', $type)->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $this->castValue($setting['setting_value'], $setting['setting_type']);
        }
        
        return $result;
    }
    
    /**
     * Delete a setting by key
     *
     * @param string $key
     * @return bool
     */
    public function deleteSetting($key)
    {
        $setting = $this->where('setting_key', $key)->first();
        
        if ($setting && $setting['is_system'] != 1) {
            return $this->delete($setting['id']);
        }
        
        return false;
    }
    
    /**
     * Cast value based on type
     *
     * @param string $value
     * @param string $type
     * @return mixed
     */
    private function castValue($value, $type)
    {
        if ($value === null) {
            return null;
        }
        
        switch ($type) {
            case 'boolean':
                return $value == '1' || $value == 'true';
                
            case 'integer':
                return intval($value);
                
            case 'decimal':
                return floatval($value);
                
            case 'json':
                return json_decode($value, true);
                
            default:
                return $value;
        }
    }
    
    /**
     * Prepare value for storage
     *
     * @param mixed $value
     * @param string $type
     * @return string
     */
    private function prepareValue($value, $type)
    {
        if ($value === null) {
            return null;
        }
        
        switch ($type) {
            case 'boolean':
                return $value ? '1' : '0';
                
            case 'integer':
                return strval(intval($value));
                
            case 'decimal':
                return strval(floatval($value));
                
            case 'json':
                return json_encode($value);
                
            default:
                return strval($value);
        }
    }
    
    /**
     * Initialize default inventory settings
     *
     * @return void
     */
    public function initializeDefaults()
    {
        $defaults = [
            [
                'setting_key' => 'enable_batch_tracking',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Enable batch/lot tracking for products',
                'is_system' => 1
            ],
            [
                'setting_key' => 'enable_expiry_tracking',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Enable expiry date tracking',
                'is_system' => 1
            ],
            [
                'setting_key' => 'enable_multi_warehouse',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Enable multiple warehouse management',
                'is_system' => 1
            ],
            [
                'setting_key' => 'enable_barcode',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Enable barcode scanning',
                'is_system' => 1
            ],
            [
                'setting_key' => 'low_stock_alert_days',
                'setting_value' => '7',
                'setting_type' => 'integer',
                'description' => 'Days before to alert for low stock',
                'is_system' => 1
            ],
            [
                'setting_key' => 'expiry_alert_days',
                'setting_value' => '30',
                'setting_type' => 'integer',
                'description' => 'Days before expiry to send alerts',
                'is_system' => 1
            ],
            [
                'setting_key' => 'default_gst_rate',
                'setting_value' => '18.00',
                'setting_type' => 'decimal',
                'description' => 'Default GST rate for products',
                'is_system' => 1
            ],
            [
                'setting_key' => 'fiscal_year_start',
                'setting_value' => '04-01',
                'setting_type' => 'string',
                'description' => 'Fiscal year start month-day',
                'is_system' => 1
            ],
            [
                'setting_key' => 'is_account_migration',
                'setting_value' => '0',
                'setting_type' => 'boolean',
                'description' => 'Enable accounting integration for inventory transactions',
                'is_system' => 0
            ],
            [
                'setting_key' => 'purchase_ledger_group_id',
                'setting_value' => null,
                'setting_type' => 'integer',
                'description' => 'Parent ledger group for purchase accounts',
                'is_system' => 0
            ],
            [
                'setting_key' => 'sales_ledger_group_id',
                'setting_value' => null,
                'setting_type' => 'integer',
                'description' => 'Parent ledger group for sales accounts',
                'is_system' => 0
            ],
            [
                'setting_key' => 'product_ledger_parent_id',
                'setting_value' => null,
                'setting_type' => 'integer',
                'description' => 'Parent ledger for product accounts',
                'is_system' => 0
            ],
            [
                'setting_key' => 'raw_material_ledger_parent_id',
                'setting_value' => null,
                'setting_type' => 'integer',
                'description' => 'Parent ledger for raw material accounts',
                'is_system' => 0
            ]
        ];
        
        foreach ($defaults as $default) {
            $existing = $this->where('setting_key', $default['setting_key'])->first();
            if (!$existing) {
                $this->insert($default);
            }
        }
    }
    
    /**
     * Get inventory related settings for quick access
     *
     * @return array
     */
    public function getInventorySettings()
    {
        $keys = [
            'is_account_migration',
            'purchase_ledger_group_id',
            'sales_ledger_group_id',
            'product_ledger_parent_id',
            'raw_material_ledger_parent_id',
            'enable_batch_tracking',
            'enable_expiry_tracking',
            'enable_multi_warehouse',
            'enable_barcode',
            'default_gst_rate'
        ];
        
        return $this->getSettings($keys);
    }
    
    /**
     * Check if accounting integration is enabled
     *
     * @return bool
     */
    public function isAccountingEnabled()
    {
        $setting = $this->getSetting('is_account_migration');
        return $setting === true || $setting === 1;
    }
}