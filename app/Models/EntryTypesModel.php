<?php
namespace App\Models;

use CodeIgniter\Model;

class EntryTypesModel extends Model
{
    protected $table = 'entry_types';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['name', 'code', 'description', 'status'];

    protected $useTimestamps = false;

    public function getActiveTypes()
    {
        return $this->where('status', 1)->findAll();
    }
}