<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;
use InvalidArgumentException;

class UploadStorage
{
    public static function validate(?UploadedFile $file, bool $pdf = false): void
    {
        if (!$file || !$file->isValid() || $file->hasMoved() || $file->getSize() > 10 * 1024 * 1024) {
            throw new InvalidArgumentException('File wajib valid dan maksimal 10 MB.');
        }
        $types = $pdf ? ['application/pdf' => ['pdf']] : [
            'image/jpeg' => ['jpg', 'jpeg'], 'image/png' => ['png'], 'image/gif' => ['gif'],
        ];
        $mime = $file->getMimeType();
        if (!isset($types[$mime]) || !in_array(strtolower($file->getClientExtension()), $types[$mime], true)) {
            throw new InvalidArgumentException($pdf ? 'Hanya PDF yang diperbolehkan.' : 'Hanya gambar JPG, PNG, atau GIF yang diperbolehkan.');
        }
        if (!$pdf) {
            $size = @getimagesize($file->getTempName());
            if (!$size || $size[0] * $size[1] > 40000000) {
                throw new InvalidArgumentException('Dimensi gambar tidak valid atau terlalu besar.');
            }
        }
    }

    public static function store(UploadedFile $file, string $directory): string
    {
        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'application/pdf' => 'pdf'];
        $name = bin2hex(random_bytes(20)) . '.' . $extensions[$file->getMimeType()];
        $file->move($directory, $name);
        return $name;
    }

    public static function remove(?string $name, string $directory): void
    {
        if (!$name || basename($name) !== $name || strpbrk($name, '/\\') !== false) {
            return;
        }
        $root = realpath($directory);
        $path = realpath($directory . DIRECTORY_SEPARATOR . $name);
        if ($root && $path && dirname($path) === $root && is_file($path)) {
            unlink($path);
        }
    }
}
