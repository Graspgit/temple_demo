<?php
namespace App\Models;

use CodeIgniter\Model;

class RomDocumentModel extends Model
{
    protected $table = 'rom_documents';
    protected $primaryKey = 'id';
    protected $allowedFields = ['booking_id', 'document_type', 'document_name', 'file_path', 'uploaded_by'];
    protected $useTimestamps = false;

    public function getDocumentsByBooking($bookingId)
    {
        return $this->where('booking_id', $bookingId)->findAll();
    }
}