<?php
namespace App\Models;

use CodeIgniter\Model;
// 9. Marriage Registration Services Model
class MarriageRegistrationServicesModel extends Model
{
    protected $table = 'marriage_registration_services';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'registration_id', 'service_id', 'quantity', 'unit_price', 'total_price'
    ];

    public function getServicesByRegistration($registration_id)
    {
        $builder = $this->db->table($this->table . ' mrs');
        $builder->select('mrs.*, mas.service_name, mas.service_name_tamil');
        $builder->join('marriage_additional_services mas', 'mrs.service_id = mas.id', 'left');
        $builder->where('mrs.registration_id', $registration_id);
        $builder->orderBy('mas.service_name', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    public function getTotalServicesAmount($registration_id)
    {
        return $this->selectSum('total_price')
                   ->where('registration_id', $registration_id)
                   ->get()
                   ->getRow()
                   ->total_price ?? 0;
    }
}
