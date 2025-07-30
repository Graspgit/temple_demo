<?php
namespace App\Models;

use CodeIgniter\Model;

class RomSlotModel extends Model
{
    protected $table = 'rom_slot_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['slot_name', 'start_time', 'end_time', 'max_bookings', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActiveSlots()
    {
        return $this->where('is_active', 1)->orderBy('start_time', 'ASC')->findAll();
    }

    public function checkSlotAvailability($date, $slotId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('rom_slot_bookings');
        
        $slotBooking = $builder->where([
            'booking_date' => $date,
            'slot_id' => $slotId
        ])->get()->getRowArray();

        $slotInfo = $this->find($slotId);
        
        if (!$slotBooking) {
            return ['available' => true, 'remaining' => $slotInfo['max_bookings']];
        }

        $remaining = $slotInfo['max_bookings'] - $slotBooking['booking_count'];
        
        return [
            'available' => $remaining > 0,
            'remaining' => $remaining
        ];
    }

    public function updateSlotBookingCount($date, $slotId, $increment = 1)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('rom_slot_bookings');
        
        $existing = $builder->where([
            'booking_date' => $date,
            'slot_id' => $slotId
        ])->get()->getRowArray();

        if ($existing) {
            $builder->where('id', $existing['id'])
                ->update(['booking_count' => $existing['booking_count'] + $increment]);
        } else {
            $builder->insert([
                'booking_date' => $date,
                'slot_id' => $slotId,
                'booking_count' => $increment
            ]);
        }
    }
}