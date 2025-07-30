<?php
namespace App\Models;
use CodeIgniter\Model;

class EntryModel extends Model
{
    protected $table = 'entries';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'entrytype_id', 'entry_code', 'narration', 'entry_date', 
        'status', 'created_by', 'created_at', 'updated_at'
    ];
}