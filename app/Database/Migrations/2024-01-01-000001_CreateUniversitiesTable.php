<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUniversitiesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'university_code' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'unique' => true,
            ],
            'university_name' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
            ],
            'short_name' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'state' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => 'India',
            ],
            'pincode' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => true,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'website' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
            ],
            'contact_person' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'contact_person_phone' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
            ],
            'contact_person_email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'established_year' => [
                'type' => 'YEAR',
                'null' => true,
            ],
            'university_type' => [
                'type' => 'ENUM',
                'constraint' => ['government', 'private', 'deemed', 'autonomous'],
                'default' => 'private',
            ],
            'affiliation_number' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('university_code');
        $this->forge->addKey('is_active');
        $this->forge->createTable('universities');
    }
    
    public function down()
    {
        $this->forge->dropTable('universities');
    }
}