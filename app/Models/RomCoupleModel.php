<?php
namespace App\Models;

use CodeIgniter\Model;

class RomCoupleModel extends Model
{
    protected $table = 'rom_couple_details';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'booking_id', 'person_type', 'name', 'dob', 'age', 'nationality',
        'ic_passport_no', 'phone', 'email', 'address', 'occupation',
        'father_name', 'mother_name', 'photo'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getCoupleByBooking($bookingId)
    {
        return $this->where('booking_id', $bookingId)->findAll();
    }
}