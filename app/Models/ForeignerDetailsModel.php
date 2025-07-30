<?php
// ForeignerDetailsModel.php
namespace App\Models;

use CodeIgniter\Model;

class ForeignerDetailsModel extends Model
{
    protected $table = 'staff_foreigner_details';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'staff_id', 'passport_number', 'passport_expiry', 'visa_number',
        'visa_type', 'visa_category', 'visa_expiry', 'visa_renewal_date',
        'country_of_origin'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getByStaffId($staffId)
    {
        return $this->where('staff_id', $staffId)->first();
    }
}