<?php

namespace App\Libraries;

use Illuminate\Http\UploadedFile as LaravelUploadedFile;
use Illuminate\Support\Str;

class CI4UploadedFile
{
    /** @var LaravelUploadedFile|null */
    protected $file;
    protected $savedName = null;
    protected $hasMoved = false;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function isValid(): bool
    {
        return $this->file ? $this->file->isValid() : false;
    }

    public function hasMoved(): bool
    {
        return $this->hasMoved;
    }

    public function getRandomName(): string
    {
        $ext = $this->getClientExtension() ?: 'jpg';
        return time() . '_' . Str::random(16) . '.' . $ext;
    }

    public function getName(): string
    {
        return $this->savedName ?: ($this->file ? $this->file->getClientOriginalName() : '');
    }

    public function getClientName(): string
    {
        return $this->file ? $this->file->getClientOriginalName() : '';
    }

    public function getClientExtension(): string
    {
        return $this->file ? $this->file->getClientOriginalExtension() : '';
    }

    public function move(string $targetDir, string $newName = null): bool
    {
        if (!$this->file) {
            return false;
        }
        if (!$newName) {
            $newName = $this->getRandomName();
        }
        $targetDir = rtrim($targetDir, '/\\');
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $this->file->move($targetDir, $newName);
        $this->savedName = $newName;
        $this->hasMoved = true;
        return true;
    }

    public function getSize(): int
    {
        return $this->file ? $this->file->getSize() : 0;
    }

    public function getMimeType(): string
    {
        return $this->file ? $this->file->getMimeType() : '';
    }

    public function __call($method, $args)
    {
        if ($this->file && method_exists($this->file, $method)) {
            return call_user_func_array([$this->file, $method], $args);
        }
        return null;
    }
}
