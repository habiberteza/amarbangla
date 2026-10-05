<?php

namespace App\Libraries;

use Illuminate\Http\Request as LaravelRequest;

class CI4Request
{
    /** @var LaravelRequest */
    protected $request;

    public function __construct(LaravelRequest $request = null)
    {
        $this->request = $request ?: request();
    }

    public function getPost($key = null)
    {
        if ($key === null) {
            return !empty($_POST) ? $_POST : $this->request->post();
        }
        return $_POST[$key] ?? $this->request->post($key, $this->request->input($key));
    }

    public function getGet($key = null)
    {
        if ($key === null) {
            return !empty($_GET) ? $_GET : $this->request->query();
        }
        return $_GET[$key] ?? $this->request->query($key);
    }

    public function getVar($key = null)
    {
        if ($key === null) {
            return array_merge($_GET, $_POST, $this->request->all());
        }
        return $this->request->input($key, $_POST[$key] ?? ($_GET[$key] ?? null));
    }

    public function getFile(string $key): ?CI4UploadedFile
    {
        $file = $this->request->file($key);
        if ($file) {
            return new CI4UploadedFile($file);
        }
        if (isset($_FILES[$key]) && !empty($_FILES[$key]['tmp_name'])) {
            $f = new \Illuminate\Http\UploadedFile(
                $_FILES[$key]['tmp_name'],
                $_FILES[$key]['name'],
                $_FILES[$key]['type'] ?? null,
                $_FILES[$key]['error'] ?? null,
                true
            );
            return new CI4UploadedFile($f);
        }
        return null;
    }

    public function getFiles(): array
    {
        $files = [];
        foreach ($this->request->allFiles() as $key => $file) {
            $files[$key] = new CI4UploadedFile($file);
        }
        return $files;
    }

    public function getIPAddress(): string
    {
        return $this->request->ip() ?: '127.0.0.1';
    }

    public function getMethod(bool $upper = false): string
    {
        $m = $this->request->method();
        return $upper ? strtoupper($m) : strtolower($m);
    }

    public function isAJAX(): bool
    {
        return $this->request->ajax();
    }

    public function getPath(): string
    {
        return $this->request->path();
    }

    public function setGlobal(string $type, $value)
    {
        return $this;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->request, $name], $arguments);
    }
}
