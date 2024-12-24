<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKamarTable extends Migration
{
    public function up()
    {
        // Disable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        
        // Drop if exists
        $this->forge->dropTable('kamar', true);

        // Create table
        $this->forge->addField([
            'id_kamar' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'nomor_kamar' => [
                'type'       => 'VARCHAR',
                'constraint' => '10'
            ],
            'tipe_kamar' => [
                'type'       => 'VARCHAR',
                'constraint' => '50'
            ],
            'harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2'
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['tersedia', 'terisi', 'maintenance'],
                'default'    => 'tersedia'
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addKey('id_kamar', true);
        $this->forge->createTable('kamar', true);

        // Enable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down()
    {
        $this->forge->dropTable('kamar', true);
    }
}
