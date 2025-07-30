<?php
namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table = 'login';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'ic_number', 'username', 'password', 'email', 'role', 
        'profile_id', 'status', 'member_comes', 'index_id', 'phone_code', 
        'phone_no', 'address1', 'address2', 'remarks', 'otp_code', 'imag'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created';
    protected $updatedField = 'modified';

    public function getActiveUsers()
    {
        return $this->select('id, name, username')
                   ->where('status', 1)
                   ->findAll();
    }

    public function getUsersForDropdown()
    {
        return $this->select('id, name, username')
                   ->where('status', 1)
                   ->where('role !=', 98) // Exclude customers if needed
                   ->findAll();
    }
}