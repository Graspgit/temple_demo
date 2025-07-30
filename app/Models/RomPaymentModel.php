<?php
namespace App\Models;

use CodeIgniter\Model;

class RomPaymentModel extends Model
{
    protected $table = 'rom_payments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'booking_id', 'payment_date', 'payment_mode_id', 'payment_type',
        'amount', 'transaction_id', 'payment_status', 'payment_gateway_response',
        'remarks', 'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getPaymentsByBooking($bookingId)
    {
        return $this->select('rom_payments.*, pm.name as payment_mode_name')
            ->join('payment_mode pm', 'pm.id = rom_payments.payment_mode_id')
            ->where('booking_id', $bookingId)
            ->findAll();
    }

    public function getPaymentSummary($bookingId)
    {
        return $this->selectSum('amount', 'total_paid')
            ->where('booking_id', $bookingId)
            ->where('payment_status', 'success')
            ->first();
    }
}