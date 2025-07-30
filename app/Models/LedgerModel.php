<?php
namespace App\Models;
use CodeIgniter\Model;

class LedgerModel extends Model
{
    protected $table = 'ledgers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'code', 'group_id', 'type', 'op_balance', 'op_balance_dc', 'credit_aging',
        'reconciliation', 'pa', 'hb', 'aging', 'notes', 'left_code', 'right_code', 'iv'
        'is_migrate'
    ];
}