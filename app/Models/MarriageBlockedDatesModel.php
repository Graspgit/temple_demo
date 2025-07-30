<?php
namespace App\Models;

use CodeIgniter\Model;
// 5. Marriage Blocked Dates Model
class MarriageBlockedDatesModel extends Model
{
    protected $table = 'marriage_blocked_dates';
    protected $primaryKey = 'id';
    protected $allowedFields = ['blocked_date', 'reason', 'is_full_day', 'blocked_slots', 'created_by'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';

    public function isDateBlocked($date, $slot_id = null)
    {
        $blocked = $this->where('blocked_date', $date)->first();
        
        if (!$blocked) {
            return false;
        }
        
        if ($blocked['is_full_day']) {
            return true;
        }
        
        if ($slot_id && $blocked['blocked_slots']) {
            $blocked_slots = json_decode($blocked['blocked_slots'], true);
            return in_array($slot_id, $blocked_slots);
        }
        
        return false;
    }

    public function getBlockedDates()
    {
        return $this->orderBy('blocked_date', 'ASC')->findAll();
    }

    public function getBlockedDatesForMonth($month, $year)
    {
        return $this->where('MONTH(blocked_date)', $month)
                   ->where('YEAR(blocked_date)', $year)
                   ->findAll();
    }
}
