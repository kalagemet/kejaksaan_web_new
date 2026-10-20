<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTabelUser extends Migration
{
    public function up()
    {
        $fields = [
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];
        foreach ($fields as $name => $definition) {
            if (!$this->db->fieldExists($name, 'users')) {
                $this->forge->addColumn('users', [$name => $definition]);
            }
        }
    }

    public function down()
    {
        // Columns belong to CreateUserTable and must survive this rollback.
    }
}
