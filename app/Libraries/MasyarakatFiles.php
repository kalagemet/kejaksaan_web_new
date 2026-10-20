<?php
namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;

class MasyarakatFiles
{
    private string $directory;

    public function __construct(?string $directory = null)
    {
        $this->directory = $directory ?? WRITEPATH . 'layanan_masyarakat';
    }

    public function save(?UploadedFile $file, string $kind): array
    {
        $max = in_array($kind, ['identitas', 'foto'], true) ? 2 : 10;
        $types = $kind === 'aduan' ? ['application/pdf' => ['pdf']] : [
            'application/pdf' => ['pdf'], 'image/jpeg' => ['jpg', 'jpeg'], 'image/png' => ['png'],
        ];
        if ($kind === 'foto') {
            unset($types['application/pdf']);
        }
        if (!$file || !$file->isValid() || $file->hasMoved() || $file->getSize() > $max * 1024 * 1024) {
            throw new \DomainException('Berkas ' . $kind . ' wajib valid dan maksimal ' . $max . ' MB.');
        }
        $mime = $file->getMimeType();
        if (!isset($types[$mime]) || !in_array(strtolower($file->getClientExtension()), $types[$mime], true)) {
            throw new \DomainException('Format berkas ' . $kind . ' tidak sesuai. Gunakan ' . ($kind === 'aduan' ? 'PDF.' : ($kind === 'foto' ? 'JPG atau PNG.' : 'PDF, JPG, atau PNG.')));
        }
        if (strpos($mime, 'image/') === 0) {
            $size = @getimagesize($file->getTempName());
            if (!$size || $size[0] * $size[1] > 20000000) {
                throw new \DomainException('Dimensi gambar terlalu besar atau tidak valid.');
            }
        }
        $name = bin2hex(random_bytes(20)) . '.' . $types[$mime][0];
        $original = preg_replace('/[\x00-\x1F\x7F]/u', '_', basename(str_replace('\\', '/', $file->getName()))) ?: 'dokumen';
        $meta = ['jenis' => $kind, 'nama_asli' => mb_substr($original, 0, 255), 'nama_file' => $name, 'mime' => $mime, 'ukuran' => $file->getSize()];
        try {
            if (!$file->move($this->directory, $name)) { throw new \RuntimeException('Upload failed.'); }
        } catch (\Throwable $e) {
            throw new \DomainException('Berkas belum dapat disimpan. Silakan coba kembali atau hubungi pengelola.');
        }
        return $meta;
    }

    public function path(string $name): string
    {
        if (!preg_match('/^[a-f0-9]{40}\.(pdf|jpg|png)$/', $name)) {
            throw new \DomainException('Berkas tidak ditemukan.');
        }
        $root = realpath($this->directory);
        $path = realpath($this->directory . DIRECTORY_SEPARATOR . $name);
        if (!$root || !$path || dirname($path) !== $root || !is_file($path)) {
            throw new \DomainException('Berkas tidak ditemukan.');
        }
        return $path;
    }

    public function remove(?string $name): void
    {
        if (!$name) {
            return;
        }
        try { unlink($this->path($name)); } catch (\DomainException $e) { }
    }
}
