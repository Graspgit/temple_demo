<?php
namespace App\Models;

use CodeIgniter\Model;

class DeletionReasonsModel extends Model
{
    protected $table = 'deletion_reasons';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['reason', 'is_active'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = null;

    public function getActiveReasons()
    {
        return $this->where('is_active', 1)->findAll();
    }
}