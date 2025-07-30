<?php 
namespace App\Models;

use CodeIgniter\Model;

// 2. Marriage Slots Model
class MarriageSlotsModel extends Model
{
    protected $table = 'marriage_slots';
    protected $primaryKey = 'id';
    protected $allowedFields = ['slot_name', 'start_time', 'end_time', 'max_bookings', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActiveSlots()
    {
        return $this->where('is_active', 1)->orderBy('start_time', 'ASC')->findAll();
    }

    public function getSlotsWithAvailability($date)
    {
        $builder = $this->db->table($this->table . ' ms');
        $builder->select('ms.*, 
                         COALESCE(booking_counts.booked, 0) as booked_count,
                         (ms.max_bookings - COALESCE(booking_counts.booked, 0)) as available_count');
        $builder->join('(SELECT slot_id, COUNT(*) as booked 
                        FROM marriage_registrations 
                        WHERE registration_date = "' . $date . '" 
                        AND registration_status != "cancelled" 
                        GROUP BY slot_id) booking_counts', 
                       'ms.id = booking_counts.slot_id', 'left');
        $builder->where('ms.is_active', 1);
        $builder->orderBy('ms.start_time', 'ASC');
        
        return $builder->get()->getResultArray();
    }
}
