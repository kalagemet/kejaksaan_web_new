<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTabelUser extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'created_at');
        $this->forge->dropColumn('users', 'updated_at');
    }
}
