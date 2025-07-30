<?php
namespace App\Models;

use CodeIgniter\Model;

class RomVenueModel extends Model
{
    protected $table = 'rom_venues';
    protected $primaryKey = 'id';
    protected $allowedFields = ['venue_name', 'venue_type', 'capacity', 'description', 'price', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActiveVenues()
    {
        return $this->where('is_active', 1)->findAll();
    }
}