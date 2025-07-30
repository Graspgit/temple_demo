<?php

namespace App\Controllers\Inventory;

use App\Controllers\MasterController;
use App\Models\Inventory\UnitOfMeasureModel;

class UnitOfMeasureController extends MasterController
{
    public function __construct()
    {
        $this->model = new UnitOfMeasureModel();
        $this->viewPath = 'inventory/uom';
        $this->moduleName = 'Unit of Measure';
        $this->routeBase = 'inventory/uom';
        $this->searchFields = ['name', 'abbreviation', 'description'];
    }

    protected function getValidationRules($id = null)
    {
        $rules = [
            'name' => [
                'label' => 'Unit Name',
                'rules' => $id ? 
                    "required|max_length[100]|is_unique[inv_unit_of_measure.name,id,$id]" : 
                    'required|max_length[100]|is_unique[inv_unit_of_measure.name]'
            ],
            'abbreviation' => [
                'label' => 'Abbreviation',
                'rules' => $id ? 
                    "required|max_length[20]|is_unique[inv_unit_of_measure.abbreviation,id,$id]" : 
                    'required|max_length[20]|is_unique[inv_unit_of_measure.abbreviation]'
            ],
            'description' => [
                'label' => 'Description',
                'rules' => 'permit_empty|max_length[255]'
            ],
            'conversion_factor' => [
                'label' => 'Conversion Factor',
                'rules' => 'permit_empty|decimal|greater_than[0]'
            ],
            'base_unit_id' => [
                'label' => 'Base Unit',
                'rules' => 'permit_empty|is_natural_no_zero'
            ],
            'is_fractional' => [
                'label' => 'Allow Fractional',
                'rules' => 'permit_empty|in_list[0,1]'
            ],
            'category' => [
                'label' => 'Category',
                'rules' => 'permit_empty|in_list[weight,volume,count,length,area,other]'
            ],
            'decimal_places' => [
                'label' => 'Decimal Places',
                'rules' => 'permit_empty|is_natural|less_than_equal_to[4]'
            ]
        ];

        return $rules;
    }

    protected function prepareData(array $data)
    {
        // Convert checkbox values
        $data['is_fractional'] = isset($data['is_fractional']) && $data['is_fractional'] ? 1 : 0;
        
        // Handle empty values
        if (empty($data['conversion_factor'])) {
            $data['conversion_factor'] = null;
        }
        
        if (empty($data['base_unit_id'])) {
            $data['base_unit_id'] = null;
        }
        
        if (empty($data['decimal_places'])) {
            $data['decimal_places'] = 0;
        }

        return $data;
    }

    protected function canDelete($id)
    {
        // Check if unit is used in products
        $db = \Config\Database::connect();
        
        $productCount = $db->table('inv_products')
            ->where('unit_of_measure_id', $id)
            ->countAllResults();
        
        if ($productCount > 0) {
            return [
                'status' => false,
                'message' => "Cannot delete this unit. It is used by $productCount product(s)."
            ];
        }
        
        // Check if unit is used as base unit for other units
        $baseUnitCount = $db->table('inv_unit_of_measure')
            ->where('base_unit_id', $id)
            ->where('deleted_at', null)
            ->countAllResults();
        
        if ($baseUnitCount > 0) {
            return [
                'status' => false,
                'message' => "Cannot delete this unit. It is used as base unit for $baseUnitCount other unit(s)."
            ];
        }
        
        return ['status' => true];
    }

    protected function getFormData($id = null)
    {
        $data = parent::getFormData($id);
        
        // Get all active units for base unit dropdown (excluding current unit if editing)
        $baseUnits = $this->model
            ->where('deleted_at', null)
            ->where('is_active', 1);
        
        if ($id) {
            $baseUnits->where('id !=', $id);
        }
        
        $data['baseUnits'] = $baseUnits->findAll();
        
        // Unit categories for dropdown
        $data['categories'] = [
            'weight' => 'Weight (kg, g, etc.)',
            'volume' => 'Volume (L, ml, etc.)',
            'count' => 'Count (pcs, nos, etc.)',
            'length' => 'Length (m, cm, etc.)',
            'area' => 'Area (sq.m, sq.ft, etc.)',
            'other' => 'Other'
        ];
        
        return $data;
    }

    protected function modifyDatatablesQuery($builder)
    {
        // Join with base unit to show base unit name
        $builder->select('inv_unit_of_measure.*, base.name as base_unit_name, base.abbreviation as base_unit_abbr')
                ->join('inv_unit_of_measure base', 'base.id = inv_unit_of_measure.base_unit_id', 'left');
        
        return $builder;
    }
}