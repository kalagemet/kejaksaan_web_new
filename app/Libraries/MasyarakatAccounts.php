<?php
namespace App\Libraries;

use App\Models\MasyarakatModel;
use CodeIgniter\Database\BaseConnection;
use Config\Database;

class MasyarakatAccounts
{
    private BaseConnection $db;
    private OtpDeliveryInterface $delivery;
    private MasyarakatModel $model;

    public function __construct(?BaseConnection $db = null, ?OtpDeliveryInterface $delivery = null)
    {
        $this->db = $db ?? Database::connect();
        $this->delivery = $delivery ?? service('otpDelivery');
        $this->model = new MasyarakatModel($this->db);
    }

    public static function wa(string $value): string
    {
        $value = str_replace([' ', '-', '(', ')', '+'], '', trim($value));
        return strpos($value, '0') === 0 ? '62' . substr($value, 1) : $value;
    }

    public static function password(string $password): void
    {
        if (mb_strlen($password) < 8 || strlen($password) > 72) {
            throw new \DomainException('Password minimal 8 karakter dan maksimal 72 byte.');
        }
    }

    private function available(string $field, string $value, ?int $except = null): void
    {
        $builder = $this->db->table('dt_masyarakat')->where($field, $value);
        if ($except) { $builder->where('id !=', $except); }
        if ($builder->countAllResults()) {
            $label = ['nik' => 'NIK', 'email' => 'Email', 'no_wa' => 'Nomor WhatsApp'][$field];
            throw new \DomainException($label . ' sudah terdaftar. Gunakan data lain atau masuk ke akun yang ada.');
        }
    }

    public function register(array $data): array
    {
        self::password($data['password']);
        $data['email'] = strtolower(trim($data['email']));
        $data['no_wa'] = self::wa($data['no_wa']);
        foreach (['nik', 'email', 'no_wa'] as $field) { $this->available($field, $data[$field]); }
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['aktif'] = 0;
        $data['session_version'] = 1;
        // Verification state must never come from the registration form.
        unset($data['email_verified_at'], $data['wa_verified_at']);
        try {
            $id = $this->model->insert($data);
            if (!$id) { throw new \DomainException(implode(' ', $this->model->errors())); }
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            throw new \DomainException('NIK, email, atau nomor WhatsApp sudah digunakan.');
        }
        return $this->model->find($id);
    }

    public function authenticate(string $nik, string $password): array
    {
        $user = $this->model->where('nik', $nik)->first();
        if (!$user || !password_verify($password, $user['password'])) {
            throw new \DomainException('NIK atau password tidak sesuai.');
        }
        return $user;
    }

    public function active(int $id): array
    {
        $user = $this->model->find($id);
        if (!$user || !(int) $user['aktif']) {
            throw new \DomainException('Akun masyarakat tidak aktif.');
        }
        return $user;
    }

    public function registrationOtp(int $id): array
    {
        $user = $this->model->find($id);
        if (!$user || (int) $user['aktif']) { throw new \DomainException('Permintaan verifikasi tidak valid.'); }
        return $this->issue($user, 'register', $user['email']);
    }

    public function resetOtp(string $nik, string $email): array
    {
        $user = $this->model->where('nik', $nik)->where('email', strtolower(trim($email)))->first();
        if (!$user || !(int) $user['aktif'] || !$user['email_verified_at']) {
            return ['token' => bin2hex(random_bytes(32)), 'sent' => true];
        }
        try { return $this->issue($user, 'reset', $user['email']); }
        catch (\DomainException $e) { return ['token' => bin2hex(random_bytes(32)), 'sent' => true]; }
    }

    public function contactOtp(int $id, string $kind, string $value, string $password): array
    {
        $user = $this->active($id);
        if (!password_verify($password, $user['password'])) { throw new \DomainException('Password saat ini tidak sesuai.'); }
        if (!in_array($kind, ['email', 'no_wa'], true)) { throw new \DomainException('Jenis kontak tidak valid.'); }
        $value = $kind === 'email' ? strtolower(trim($value)) : self::wa($value);
        if (($kind === 'email' && (!filter_var($value, FILTER_VALIDATE_EMAIL) || strlen($value) > 254))
            || ($kind === 'no_wa' && !preg_match('/^[1-9][0-9]{7,14}$/', $value))) {
            throw new \DomainException('Format kontak baru tidak valid.');
        }
        if ($user[$kind] === $value) { throw new \DomainException('Kontak baru sama dengan data saat ini.'); }
        $this->available($kind, $value, $id);
        // Email changes verify the new email; WA changes are approved through the current verified email.
        return $this->issue($user, $kind === 'email' ? 'email_change' : 'wa_change', $kind === 'email' ? $value : $user['email'], ['value' => $value]);
    }

    private function issue(array $user, string $purpose, string $destination, array $payload = []): array
    {
        $token = bin2hex(random_bytes(32));
        $code = (string) random_int(100000, 999999);
        $now = gmdate('Y-m-d H:i:s');
        $this->db->transBegin();
        try {
            // Serialize issuance per account, including concurrent resend requests.
            $this->db->table('dt_masyarakat')->where('id', $user['id'])->update(['updated_at' => $now]);
            $recent = $this->db->table('dt_masyarakat_otp')->where('masyarakat_id', $user['id'])
                ->where('purpose', $purpose)->where('created_at >=', gmdate('Y-m-d H:i:s', time() - 60))->countAllResults();
            if ($recent) { throw new \DomainException('Tunggu 60 detik sebelum meminta OTP lagi.'); }
            $this->db->table('dt_masyarakat_otp')->where('masyarakat_id', $user['id'])->where('purpose', $purpose)
                ->where('used_at', null)->update(['used_at' => $now]);
            $this->db->table('dt_masyarakat_otp')->insert([
                'masyarakat_id' => $user['id'], 'token' => $token, 'purpose' => $purpose, 'channel' => 'email',
                'destination' => $destination, 'code_hash' => password_hash($code, PASSWORD_DEFAULT),
                'payload' => json_encode($payload), 'attempts' => 0, 'created_at' => $now,
                'expires_at' => gmdate('Y-m-d H:i:s', time() + 600),
            ]);
            if (!$this->db->transStatus()) { throw new \DomainException('Permintaan OTP belum dapat dibuat.'); }
            $this->db->transCommit();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            if ($e instanceof \DomainException) { throw $e; }
            throw new \DomainException('Permintaan OTP belum dapat dibuat.');
        }
        $sent = false;
        try {
            $this->delivery->send('email', $destination, $code);
            $this->db->table('dt_masyarakat_otp')->where('token', $token)->update(['delivered_at' => gmdate('Y-m-d H:i:s')]);
            $sent = true;
        } catch (\Throwable $e) {
            // Do not log the code, destination, or SMTP exception containing credentials.
            log_message('error', 'Pengiriman OTP layanan masyarakat gagal.');
        }
        return ['token' => $token, 'sent' => $sent];
    }

    public function resend(string $token, ?int $actorId = null): array
    {
        $row = $this->db->table('dt_masyarakat_otp')->where('token', $token)->where('used_at', null)->get()->getRowArray();
        if (!$row) { throw new \DomainException('Permintaan OTP tidak tersedia. Ulangi proses dari halaman sebelumnya.'); }
        if (in_array($row['purpose'], ['email_change', 'wa_change'], true) && $actorId !== (int) $row['masyarakat_id']) {
            throw new \DomainException('Permintaan OTP tidak valid.');
        }
        $user = $this->model->find($row['masyarakat_id']);
        if (!$user || ($row['purpose'] === 'register' && (int) $user['aktif'])) { throw new \DomainException('Permintaan OTP tidak valid.'); }
        return $this->issue($user, $row['purpose'], $row['destination'], json_decode($row['payload'], true) ?: []);
    }

    public function verify(string $token, string $code, ?string $password = null, ?int $actorId = null): string
    {
        if (!preg_match('/^[0-9]{6}$/', $code)) { throw new \DomainException('OTP harus terdiri dari 6 angka.'); }
        $row = $this->db->table('dt_masyarakat_otp')->where('token', $token)->get()->getRowArray();
        if (!$row) { throw new \DomainException('OTP tidak valid atau kedaluwarsa.'); }
        $user = $this->model->find($row['masyarakat_id']);
        if (!$user || ($row['purpose'] === 'register' && (int) $user['aktif'])
            || ($row['purpose'] !== 'register' && !(int) $user['aktif'])
            || (in_array($row['purpose'], ['reset','wa_change'], true) && $row['destination'] !== $user['email'])) {
            throw new \DomainException('Permintaan OTP tidak berlaku untuk kondisi akun saat ini.');
        }
        if (in_array($row['purpose'], ['email_change', 'wa_change'], true) && $actorId !== (int) $row['masyarakat_id']) {
            throw new \DomainException('Permintaan OTP tidak valid.');
        }
        if ($row['purpose'] === 'reset') { self::password((string) $password); }
        $now = gmdate('Y-m-d H:i:s');
        $this->db->table('dt_masyarakat_otp')->where('id', $row['id'])->where('used_at', null)
            ->where('delivered_at !=', null)->where('expires_at >', $now)->where('attempts <', 5)
            ->set('attempts', 'attempts + 1', false)->update();
        if ($this->db->affectedRows() !== 1) { throw new \DomainException('OTP tidak valid, kedaluwarsa, atau batas 5 percobaan telah habis.'); }
        if (!password_verify($code, $row['code_hash'])) { throw new \DomainException('Kode OTP tidak sesuai.'); }
        $this->db->transBegin();
        try {
            $this->db->table('dt_masyarakat_otp')->where('id', $row['id'])->where('used_at', null)->update(['used_at' => $now]);
            if ($this->db->affectedRows() !== 1) { throw new \DomainException('OTP sudah digunakan.'); }
            $payload = json_decode($row['payload'], true) ?: [];
            $id = (int) $row['masyarakat_id'];
            if ($row['purpose'] === 'register') {
                $changes = ['aktif' => 1, 'email_verified_at' => $now];
            } elseif ($row['purpose'] === 'reset') {
                $changes = ['password' => password_hash($password, PASSWORD_DEFAULT)];
                $this->db->table('dt_masyarakat')->where('id', $id)->set('session_version', 'session_version + 1', false)->update();
            } elseif ($row['purpose'] === 'email_change') {
                $this->available('email', $payload['value'], $id);
                $changes = ['email' => $payload['value'], 'email_verified_at' => $now];
            } elseif ($row['purpose'] === 'wa_change') {
                $this->available('no_wa', $payload['value'], $id);
                $changes = ['no_wa' => $payload['value'], 'wa_verified_at' => null];
            } else { throw new \DomainException('Tujuan OTP tidak valid.'); }
            if (!$this->model->update($id, $changes) || !$this->db->transStatus()) { throw new \DomainException('Data akun tidak dapat diperbarui.'); }
            if ($row['purpose'] === 'email_change' || $row['purpose'] === 'reset') {
                $pending = $this->db->table('dt_masyarakat_otp')->where('masyarakat_id', $id)->where('used_at', null);
                if ($row['purpose'] === 'email_change') { $pending->whereIn('purpose', ['reset', 'wa_change']); }
                $pending->update(['used_at' => $now]);
            }
            $this->db->transCommit();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            if ($e instanceof \DomainException) { throw $e; }
            throw new \DomainException('Kontak sudah digunakan atau data akun tidak dapat diperbarui.');
        }
        return $row['purpose'];
    }

    public function updateProfile(int $id, array $data): void
    {
        $safe = array_intersect_key($data, array_flip(['nama', 'pekerjaan', 'tanggal_lahir', 'alamat', 'foto']));
        if (!$this->model->update($id, $safe)) { throw new \DomainException(implode(' ', $this->model->errors())); }
    }
}
