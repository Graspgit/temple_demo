<?php
namespace App\Models;

use CodeIgniter\Model;

class StatutoryDetailsModel extends Model
{
    protected $table = 'staff_statutory_details';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'staff_id', 'epf_number', 'socso_number', 'eis_number',
        'income_tax_number', 'pcb_code', 'ea_form_submitted'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getByStaffId($staffId)
    {
        return $this->where('staff_id', $staffId)->first();
    }
}