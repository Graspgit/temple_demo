<?php
namespace App\Models;

use CodeIgniter\Model;

class NextOfKinModel extends Model
{
    protected $table = 'staff_next_of_kin';
    protected $primaryKey = 'id';
    protected $allowedFields = ['staff_id', 'name', 'relationship', 'phone', 'email', 'address', 'is_primary', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    public function getStaffNextOfKin($staffId)
    {
        return $this->where('staff_id', $staffId)
                    ->orderBy('is_primary', 'DESC')
                    ->findAll();
    }
}