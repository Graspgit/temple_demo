<?php
namespace App\Models;

use CodeIgniter\Model;

class StaffDocumentModel extends Model
{
    protected $table = 'staff_documents';
    protected $primaryKey = 'id';
    protected $allowedFields = ['staff_id', 'document_type', 'document_name', 'file_path', 'expiry_date', 'uploaded_date'];
    protected $useTimestamps = false;

    public function getExpiringDocuments($days = 30)
    {
        $date = date('Y-m-d', strtotime("+$days days"));
        return $this->where('expiry_date IS NOT NULL')
                    ->where('expiry_date <=', $date)
                    ->where('expiry_date >=', date('Y-m-d'))
                    ->findAll();
    }
}