<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\InventorySettingsModel;
use App\Models\LedgerModel;

class InventorySettingsController extends BaseController
{
    protected $settingsModel;
    protected $ledgerModel;

    public function __construct()
    {
        $this->settingsModel = new InventorySettingsModel();
        $this->ledgerModel = new LedgerModel();
    }

    public function index()
    {
        $data['title'] = 'Inventory Settings';
        $data['settings'] = $this->settingsModel->first();
        
        // Get ledger groups for dropdowns
        $data['ledger_groups'] = $this->ledgerModel
            ->where('type', 1) // Groups only
            ->orderBy('name', 'ASC')
            ->findAll();
        
        return view('inventory/settings', $data);
    }

    public function update()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'is_account_migration' => 'required|in_list[0,1]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $settings = $this->settingsModel->first();
        $data = [
            'is_account_migration' => $this->request->getPost('is_account_migration'),
            'purchase_ledger_group_id' => $this->request->getPost('purchase_ledger_group_id') ?: null,
            'sales_ledger_group_id' => $this->request->getPost('sales_ledger_group_id') ?: null,
            'product_ledger_parent_id' => $this->request->getPost('product_ledger_parent_id') ?: null,
            'raw_material_ledger_parent_id' => $this->request->getPost('raw_material_ledger_parent_id') ?: null
        ];

        if ($settings) {
            $this->settingsModel->update($settings['id'], $data);
        } else {
            $this->settingsModel->insert($data);
        }

        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}