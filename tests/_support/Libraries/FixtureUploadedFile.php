<?php
namespace Tests\Support\Libraries;

// Emulate PHP's HTTP upload boundary in CLI; validation/storage code remains real.
class FixtureUploadedFile extends \CodeIgniter\HTTP\Files\UploadedFile
{
    public function isValid(): bool { return $this->getError() === UPLOAD_ERR_OK; }
    public function move(string $targetPath, ?string $name = null, bool $overwrite = false)
    {
        if (!is_dir($targetPath)) { mkdir($targetPath, 0700, true); }
        $target = $targetPath . DIRECTORY_SEPARATOR . $name;
        if (file_exists($target) && !$overwrite) { throw new \RuntimeException('Duplicate fixture file.'); }
        rename($this->getTempName(), $target);
        $this->path = $target;
        $this->hasMoved = true;
        return true;
    }
}
