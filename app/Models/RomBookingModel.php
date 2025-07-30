<?php
// app/Models/RomBookingModel.php
namespace App\Models;

use CodeIgniter\Model;

class RomBookingModel extends Model
{
    protected $table = 'rom_bookings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'booking_no', 'booking_date', 'slot_id', 'venue_id', 'user_id',
        'total_amount', 'paid_amount', 'security_deposit', 'discount_amount',
        'extra_charges', 'tax_amount', 'payment_status', 'booking_status', 'remarks'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function generateBookingNo()
    {
        $prefix = 'ROM';
        $year = date('Y');
        $month = date('m');
        
        $lastBooking = $this->selectMax('id')->first();
        $nextId = ($lastBooking['id'] ?? 0) + 1;
        
        return sprintf('%s%s%s%04d', $prefix, $year, $month, $nextId);
    }

    public function getBookingWithDetails($bookingId)
    {
        return $this->select('rom_bookings.*, rs.slot_name, rs.start_time, rs.end_time, rv.venue_name, rv.venue_type')
            ->join('rom_slot_settings rs', 'rs.id = rom_bookings.slot_id')
            ->join('rom_venues rv', 'rv.id = rom_bookings.venue_id')
            ->where('rom_bookings.id', $bookingId)
            ->first();
    }

    public function getDueBookings()
    {
        return $this->where('payment_status !=', 'paid')
            ->where('booking_status !=', 'cancelled')
            ->findAll();
    }
}