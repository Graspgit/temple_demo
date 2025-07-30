<?php
namespace App\Models;

use CodeIgniter\Model;

class RomCalendarModel extends Model
{
    protected $table = 'rom_calendar_availability';
    protected $primaryKey = 'id';
    protected $allowedFields = ['date', 'is_available', 'reason'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function checkDateAvailability($date)
    {
        $record = $this->where('date', $date)->first();
        
        if (!$record) {
            return ['available' => true, 'reason' => null];
        }
        
        return [
            'available' => $record['is_available'] == 1,
            'reason' => $record['reason']
        ];
    }

    public function getUnavailableDates($startDate, $endDate)
    {
        return $this->where('date >=', $startDate)
            ->where('date <=', $endDate)
            ->where('is_available', 0)
            ->findAll();
    }
}