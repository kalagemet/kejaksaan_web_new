<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlignUserSchema extends Migration
{
    public function up()
    {
        $fields = [
            'user_id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 255],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'nama_lengkap' => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
            'role' => ['type' => 'VARCHAR', 'constraint' => 32, 'default' => 'user'],
            'status' => ['type' => 'VARCHAR', 'constraint' => 1, 'default' => '0'],
            'google_id' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'token' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'instansi' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'nik' => ['type' => 'VARCHAR', 'constraint' => 16, 'null' => true],
            'no_hp' => ['type' => 'VARCHAR', 'constraint' => 32, 'null' => true],
            'alamat' => ['type' => 'TEXT', 'null' => true],
            'last_login' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];
        if ($this->db->tableExists('user')) {
            if (!$this->db->fieldExists('user_id', 'user')) {
                throw new \RuntimeException('Tabel user harus memiliki primary key user_id.');
            }
            foreach ($fields as $name => $definition) {
                if (!$this->db->fieldExists($name, 'user')) {
                    $this->forge->addColumn('user', [$name => $definition]);
                }
            }
            foreach ($this->db->getFieldData('user') as $column) {
                if ($column->name === 'token' && isset($column->max_length) && $column->max_length < 64) {
                    $this->forge->modifyColumn('user', ['token' => $fields['token']]);
                }
            }
            return;
        }
        $this->forge->addField($fields);
        $this->forge->addKey('user_id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('google_id');
        $this->forge->createTable('user');
        if ($this->db->tableExists('users')) {
            foreach ($this->db->table('users')->get()->getResultArray() as $legacy) {
                $this->db->table('user')->insert([
                    'user_id' => $legacy['id'], 'email' => $legacy['email'],
                    'password' => $legacy['password'], 'nama_lengkap' => $legacy['username'],
                    'created_at' => $legacy['created_at'], 'updated_at' => $legacy['updated_at'],
                    'role' => 'user', 'status' => '0',
                ]);
            }
        }
    }

    public function down()
    {
        throw new \RuntimeException('Migrasi akun bersifat aditif. Restore backup untuk rollback tanpa kehilangan akun.');
    }
}
