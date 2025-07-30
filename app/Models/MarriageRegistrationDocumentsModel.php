<?php
namespace App\Models;

use CodeIgniter\Model;
// 7. Marriage Registration Documents Model
class MarriageRegistrationDocumentsModel extends Model
{
    protected $table = 'marriage_registration_documents';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'registration_id', 'document_type', 'document_name', 'file_path',
        'file_size', 'mime_type'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'uploaded_at';

    public function getDocumentsByRegistration($registration_id)
    {
        return $this->where('registration_id', $registration_id)
                   ->orderBy('uploaded_at', 'DESC')
                   ->findAll();
    }

    public function getDocumentsByType($registration_id, $document_type)
    {
        return $this->where('registration_id', $registration_id)
                   ->where('document_type', $document_type)
                   ->findAll();
    }
}
