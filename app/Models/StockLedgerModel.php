<?php
namespace App\Models;
use CodeIgniter\Model;

class StockLedgerModel extends Model
{
    protected $table = 'stock_ledger';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'transaction_date', 'transaction_type', 'transaction_id', 'transaction_number',
        'item_type', 'item_id', 'batch_number', 'in_quantity', 'out_quantity',
        'balance_quantity', 'unit_cost', 'total_cost', 'notes', 'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}