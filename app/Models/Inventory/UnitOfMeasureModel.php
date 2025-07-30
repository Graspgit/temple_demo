<?php
namespace App\Models\Inventory;

class UnitOfMeasureModel extends BaseInventoryModel
{
    protected $table = 'units_of_measure';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'uom_code', 'uom_name', 'uom_type', 'base_unit_id', 
        'conversion_factor', 'decimal_places', 'is_active', 
        'created_by', 'updated_by'
    ];
    
    protected $codeField = 'uom_code';
    protected $nameField = 'uom_name';
    
    protected $validationRules = [
        'uom_code' => [
            'label' => 'UOM Code',
            'rules' => 'required|min_length[2]|max_length[10]|regex_match[/^[A-Z0-9]+$/]',
            'errors' => [
                'regex_match' => 'UOM Code must contain only uppercase letters and numbers.'
            ]
        ],
        'uom_name' => [
            'label' => 'UOM Name',
            'rules' => 'required|min_length[2]|max_length[50]'
        ],
        'uom_type' => [
            'label' => 'UOM Type',
            'rules' => 'required|in_list[quantity,weight,volume,length]'
        ],
        'conversion_factor' => [
            'label' => 'Conversion Factor',
            'rules' => 'required|decimal|greater_than[0]'
        ],
        'decimal_places' => [
            'label' => 'Decimal Places',
            'rules' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[4]'
        ]
    ];
    
    /**
     * Get UOMs with base unit name
     */
    public function getUOMsWithBaseUnit()
    {
        return $this->select('units_of_measure.*, base.uom_name as base_unit_name')
                    ->join('units_of_measure base', 'base.id = units_of_measure.base_unit_id', 'left')
                    ->findAll();
    }
    
    /**
     * Get UOMs by type
     */
    public function getByType($type)
    {
        return $this->where('uom_type', $type)
                    ->where('is_active', 1)
                    ->orderBy('uom_name', 'ASC')
                    ->findAll();
    }
    
    /**
     * Convert quantity between UOMs
     */
    public function convertQuantity($quantity, $fromUomId, $toUomId)
    {
        if ($fromUomId == $toUomId) {
            return $quantity;
        }
        
        $fromUom = $this->find($fromUomId);
        $toUom = $this->find($toUomId);
        
        if (!$fromUom || !$toUom) {
            return false;
        }
        
        // Convert to base unit first
        $baseQuantity = $quantity * $fromUom['conversion_factor'];
        
        // Convert from base unit to target unit
        $convertedQuantity = $baseQuantity / $toUom['conversion_factor'];
        
        return round($convertedQuantity, $toUom['decimal_places']);
    }
}