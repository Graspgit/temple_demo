<?php
namespace App\Models;

use CodeIgniter\Model;
// 6. Marriage Additional Services Model
class MarriageAdditionalServicesModel extends Model
{
    protected $table = 'marriage_additional_services';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'service_name', 'service_name_tamil', 'service_name_chinese', 'service_name_hindi',
        'service_price', 'is_active'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';

    public function getActiveServices()
    {
        return $this->where('is_active', 1)->orderBy('service_name', 'ASC')->findAll();
    }

    public function getServicesByLanguage($language = 'english')
    {
        $services = $this->getActiveServices();
        
        foreach ($services as &$service) {
            switch ($language) {
                case 'tamil':
                    $service['display_name'] = $service['service_name_tamil'] ?: $service['service_name'];
                    break;
                case 'chinese':
                    $service['display_name'] = $service['service_name_chinese'] ?: $service['service_name'];
                    break;
                case 'hindi':
                    $service['display_name'] = $service['service_name_hindi'] ?: $service['service_name'];
                    break;
                default:
                    $service['display_name'] = $service['service_name'];
            }
        }
        
        return $services;
    }
}
