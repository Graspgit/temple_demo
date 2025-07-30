<?php
namespace App\Models;

use CodeIgniter\Model;

class PaymentModeModel extends Model
{
    protected $table = 'payment_mode';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'menu_order', 'description', 'shortcode', 'paid_through',
        'ledger_id', 'pay_key', 'status', 'is_payment_gateway',
        'archanai', 'prasadam', 'annathanam', 'donation', 'hall_booking'
        , 'ubayam', 'kattalai_archanai', 'expenses', 'outdoor', 'catering', 'rom'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}