<?php
namespace App\Models;

use CodeIgniter\Model;

// 3. Marriage Venues Model
class MarriageVenuesModel extends Model
{
    protected $table = 'marriage_venues';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'venue_name', 'venue_name_tamil', 'venue_name_chinese', 'venue_name_hindi',
        'venue_description', 'venue_capacity', 'base_price', 'additional_charges', 'is_active'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActiveVenues()
    {
        return $this->where('is_active', 1)->orderBy('venue_name', 'ASC')->findAll();
    }

    public function getVenueByLanguage($language = 'english')
    {
        $venues = $this->getActiveVenues();
        
        foreach ($venues as &$venue) {
            switch ($language) {
                case 'tamil':
                    $venue['display_name'] = $venue['venue_name_tamil'] ?: $venue['venue_name'];
                    break;
                case 'chinese':
                    $venue['display_name'] = $venue['venue_name_chinese'] ?: $venue['venue_name'];
                    break;
                case 'hindi':
                    $venue['display_name'] = $venue['venue_name_hindi'] ?: $venue['venue_name'];
                    break;
                default:
                    $venue['display_name'] = $venue['venue_name'];
            }
        }
        
        return $venues;
    }
}
