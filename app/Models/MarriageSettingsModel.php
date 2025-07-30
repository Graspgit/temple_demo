<?php
namespace App\Models;

use CodeIgniter\Model;
// 10. Marriage Settings Model
class MarriageSettingsModel extends Model
{
    protected $table = 'marriage_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['setting_key', 'setting_value', 'setting_description', 'updated_by'];
    protected $useTimestamps = true;
    protected $updatedField = 'updated_at';

    public function getSetting($key, $default = 0)
    {
        $setting = $this->where('setting_key', $key)->first();
        
        return (isset($setting['setting_value']) ? $setting['setting_value'] : intval($default));
        
        
       
    }

    public function setSetting($key, $value, $description = null)
    {
        $existing = $this->where('setting_key', $key)->first();
        
        $data = [
            'setting_key' => $key,
            'setting_value' => $value,
            'updated_by' => session('user_id')
        ];
        
        if ($description) {
            $data['setting_description'] = $description;
        }
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->insert($data);
        }
    }

    public function getAllSettings()
    {
        $settings = $this->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
        
        return $result;
    }
}