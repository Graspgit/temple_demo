<?php
// File: app/Models/DeletedEntriesModel.php

namespace App\Models;

use CodeIgniter\Model;

class DeletedEntriesModel extends Model
{
    protected $table = 'deleted_entries';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'original_entry_id', 'entry_data', 'entry_items_data', 'entry_type_id',
        'entry_code', 'entry_date', 'dr_total', 'cr_total', 'narration',
        'payment_mode', 'deleted_by', 'deleted_reason', 'ip_address',
        'user_agent', 'session_id', 'status', 'restored_by', 'restored_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'original_entry_id' => 'required|integer',
        'entry_data' => 'required',
        'entry_items_data' => 'required',
        'deleted_by' => 'required|integer',
        'deleted_reason' => 'permit_empty|string|max_length[500]'
    ];

    protected $validationMessages = [
        'original_entry_id' => [
            'required' => 'Original entry ID is required'
        ],
        'deleted_by' => [
            'required' => 'Deleted by user ID is required'
        ]
    ];

    public function getDeletedEntriesWithDetails($filters = [])
    {
        $builder = $this->db->table('deleted_entries de');
        $builder->select('de.*, et.name as entry_type_name, l.name as deleted_by_name, l.username as deleted_by_username');
        $builder->join('entry_types et', 'et.id = de.entry_type_id', 'left');
        $builder->join('login l', 'l.id = de.deleted_by', 'left'); // Changed from users to login

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $builder->where('DATE(de.deleted_at) >=', $filters['start_date']);
            $builder->where('DATE(de.deleted_at) <=', $filters['end_date']);
        }

        if (!empty($filters['entry_type'])) {
            $builder->where('de.entry_type_id', $filters['entry_type']);
        }

        if (!empty($filters['deleted_by'])) {
            $builder->where('de.deleted_by', $filters['deleted_by']);
        }

        $builder->orderBy('de.deleted_at', 'DESC');

        return $builder->get()->getResultArray();
    }
}