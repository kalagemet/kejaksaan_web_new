<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLayananMasyarakat extends Migration
{
    public function up()
    {
        $id = ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true];
        $ref = ['type' => 'INT', 'unsigned' => true];
        $date = ['type' => 'DATETIME', 'null' => true];
        $text = ['type' => 'TEXT', 'null' => true];
        $this->forge->addField([
            'id' => $id, 'nama' => ['type' => 'VARCHAR', 'constraint' => 150],
            'nik' => ['type' => 'VARCHAR', 'constraint' => 16],
            'no_wa' => ['type' => 'VARCHAR', 'constraint' => 20],
            'email' => ['type' => 'VARCHAR', 'constraint' => 254],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'foto' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'pekerjaan' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'tanggal_lahir' => ['type' => 'DATE', 'null' => true], 'alamat' => $text,
            'aktif' => ['type' => 'TINYINT', 'default' => 0],
            'email_verified_at' => $date, 'wa_verified_at' => $date,
            'session_version' => ['type' => 'INT', 'default' => 1],
            'created_at' => $date, 'updated_at' => $date,
        ]);
        $this->forge->addKey('id', true);
        foreach (['nik', 'email', 'no_wa'] as $key) {
            $this->forge->addUniqueKey($key);
        }
        $this->forge->createTable('dt_masyarakat');

        $this->forge->addField([
            'id' => $id, 'masyarakat_id' => $ref,
            'token' => ['type' => 'VARCHAR', 'constraint' => 64],
            'purpose' => ['type' => 'VARCHAR', 'constraint' => 32],
            'channel' => ['type' => 'VARCHAR', 'constraint' => 16, 'default' => 'email'],
            'destination' => ['type' => 'VARCHAR', 'constraint' => 254],
            'code_hash' => ['type' => 'VARCHAR', 'constraint' => 255], 'payload' => $text,
            'attempts' => ['type' => 'INT', 'default' => 0],
            'expires_at' => $date, 'delivered_at' => $date, 'used_at' => $date,
            'created_at' => $date,
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('token');
        $this->forge->addKey(['masyarakat_id', 'purpose']);
        $this->forge->addForeignKey('masyarakat_id', 'dt_masyarakat', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('dt_masyarakat_otp');

        $this->forge->addField([
            'id' => $id, 'masyarakat_id' => $ref,
            'nomor' => ['type' => 'VARCHAR', 'constraint' => 40],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 150],
            'nik' => ['type' => 'VARCHAR', 'constraint' => 16],
            'alamat' => ['type' => 'TEXT'], 'uraian' => ['type' => 'TEXT'],
            'status' => ['type' => 'VARCHAR', 'constraint' => 32, 'default' => 'diajukan'],
            'version' => ['type' => 'INT', 'default' => 1],
            'created_at' => $date, 'updated_at' => $date,
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nomor');
        $this->forge->addKey(['masyarakat_id', 'created_at']);
        $this->forge->addKey('status');
        $this->forge->addForeignKey('masyarakat_id', 'dt_masyarakat', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('dt_pengaduan');

        $this->forge->addField([
            'id' => $id, 'pengaduan_id' => $ref,
            'jenis' => ['type' => 'VARCHAR', 'constraint' => 20],
            'nama_asli' => ['type' => 'VARCHAR', 'constraint' => 255],
            'nama_file' => ['type' => 'VARCHAR', 'constraint' => 100],
            'mime' => ['type' => 'VARCHAR', 'constraint' => 100],
            'ukuran' => ['type' => 'INT', 'unsigned' => true], 'created_at' => $date,
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pengaduan_id', 'dt_pengaduan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('dt_pengaduan_berkas');

        $this->forge->addField([
            'id' => $id, 'pengaduan_id' => $ref,
            'status' => ['type' => 'VARCHAR', 'constraint' => 32],
            'tanggapan' => ['type' => 'TEXT'],
            'tujuan_pelimpahan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'admin_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'admin_nama' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'created_at' => $date,
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pengaduan_id', 'dt_pengaduan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('dt_pengaduan_riwayat');
    }

    public function down()
    {
        foreach (['dt_pengaduan_riwayat', 'dt_pengaduan_berkas', 'dt_pengaduan', 'dt_masyarakat_otp', 'dt_masyarakat'] as $table) {
            $this->forge->dropTable($table, true);
        }
    }
}
