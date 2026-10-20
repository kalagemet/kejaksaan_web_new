<?php
namespace App\Models;

use CodeIgniter\Model;

class MasyarakatModel extends Model
{
    protected $table = 'dt_masyarakat';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nama', 'nik', 'no_wa', 'email', 'password', 'foto', 'pekerjaan', 'tanggal_lahir', 'alamat', 'aktif', 'email_verified_at', 'wa_verified_at', 'session_version'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $beforeInsert = ['insertUtc'];
    protected $beforeUpdate = ['updateUtc'];

    protected function insertUtc(array $event): array
    {
        $event['data']['created_at'] = gmdate('Y-m-d H:i:s');
        $event['data']['updated_at'] = $event['data']['created_at'];
        return $event;
    }
    protected function updateUtc(array $event): array
    {
        $event['data']['updated_at'] = gmdate('Y-m-d H:i:s');
        return $event;
    }
    protected $validationRules = [
        'nama' => 'required|min_length[2]|max_length[150]',
        'nik' => 'required|regex_match[/^[0-9]{16}$/]',
        'email' => 'required|valid_email|max_length[254]',
        'no_wa' => 'required|regex_match[/^[1-9][0-9]{7,14}$/]',
        'pekerjaan' => 'permit_empty|max_length[150]',
        'tanggal_lahir' => 'permit_empty|valid_date[Y-m-d]',
        'alamat' => 'permit_empty|max_length[2000]',
    ];
}
