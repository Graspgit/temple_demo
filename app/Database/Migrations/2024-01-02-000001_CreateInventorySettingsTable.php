<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventorySettingsTable extends Migration
{
    public function up()
    {
        // Create inventory_settings table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'setting_key' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'setting_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'setting_type' => [
                'type' => 'ENUM',
                'constraint' => ['boolean', 'integer', 'decimal', 'string', 'json'],
                'default' => 'string',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_system' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('setting_key');
        $this->forge->createTable('inventory_settings');
        
        // Insert default settings
        $defaultSettings = [
            [
                'setting_key' => 'enable_batch_tracking',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Enable batch/lot tracking for products',
                'is_system' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'enable_expiry_tracking',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Enable expiry date tracking',
                'is_system' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'enable_multi_warehouse',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Enable multiple warehouse management',
                'is_system' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'enable_barcode',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Enable barcode scanning',
                'is_system' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'low_stock_alert_days',
                'setting_value' => '7',
                'setting_type' => 'integer',
                'description' => 'Days before to alert for low stock',
                'is_system' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'expiry_alert_days',
                'setting_value' => '30',
                'setting_type' => 'integer',
                'description' => 'Days before expiry to send alerts',
                'is_system' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'default_gst_rate',
                'setting_value' => '18.00',
                'setting_type' => 'decimal',
                'description' => 'Default GST rate for products',
                'is_system' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'fiscal_year_start',
                'setting_value' => '04-01',
                'setting_type' => 'string',
                'description' => 'Fiscal year start month-day',
                'is_system' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'is_account_migration',
                'setting_value' => '0',
                'setting_type' => 'boolean',
                'description' => 'Enable accounting integration for inventory transactions',
                'is_system' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'purchase_ledger_group_id',
                'setting_value' => null,
                'setting_type' => 'integer',
                'description' => 'Parent ledger group for purchase accounts',
                'is_system' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'sales_ledger_group_id',
                'setting_value' => null,
                'setting_type' => 'integer',
                'description' => 'Parent ledger group for sales accounts',
                'is_system' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'product_ledger_parent_id',
                'setting_value' => null,
                'setting_type' => 'integer',
                'description' => 'Parent ledger for product accounts',
                'is_system' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'raw_material_ledger_parent_id',
                'setting_value' => null,
                'setting_type' => 'integer',
                'description' => 'Parent ledger for raw material accounts',
                'is_system' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        $this->db->table('inventory_settings')->insertBatch($defaultSettings);
    }

    public function down()
    {
        $this->forge->dropTable('inventory_settings');
    }
}