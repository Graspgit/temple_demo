<?php
namespace App\Models;
use CodeIgniter\Model;

class EntryItemModel extends Model
{
    protected $table = 'entryitems';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'entry_id', 'ledger_id', 'amount', 'dc', 'dc_id', 'narration'
    ];
}