<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Validator;

class CI4Validation
{
    protected $rules = [];
    protected $errors = [];
    protected $requestData = [];

    public function setRule(string $field, ?string $label = null, string $rules = '', array $errors = [])
    {
        $this->rules[$field] = $rules;
        return $this;
    }

    public function setRules(array $rules, array $messages = [])
    {
        foreach ($rules as $field => $def) {
            if (is_array($def) && isset($def['rules'])) {
                $this->rules[$field] = $def['rules'];
            } else {
                $this->rules[$field] = $def;
            }
        }
        return $this;
    }

    public function withRequest($request)
    {
        if (is_object($request) && method_exists($request, 'all')) {
            $this->requestData = $request->all();
        } elseif (is_object($request) && method_exists($request, 'getPost')) {
            $this->requestData = !empty($_POST) ? $_POST : (request()->post() ?: request()->all());
        } else {
            $this->requestData = request()->all();
        }
        return $this;
    }

    public function run(?array $data = null): bool
    {
        $input = $data ?? ($this->requestData ?: array_merge($_GET, $_POST, request()->all()));

        $laravelRules = [];
        foreach ($this->rules as $field => $r) {
            if (is_string($r)) {
                $parts = explode('|', $r);
                $cleanParts = [];
                foreach ($parts as $p) {
                    $p = trim($p);
                    if ($p === 'valid_email') {
                        $cleanParts[] = 'email';
                    } elseif (strpos($p, 'max_length') === 0) {
                        $len = filter_var($p, FILTER_SANITIZE_NUMBER_INT);
                        $cleanParts[] = "max:{$len}";
                    } elseif (strpos($p, 'min_length') === 0) {
                        $len = filter_var($p, FILTER_SANITIZE_NUMBER_INT);
                        $cleanParts[] = "min:{$len}";
                    } elseif ($p === 'trim') {
                        // ignore
                    } else {
                        $cleanParts[] = $p;
                    }
                }
                $laravelRules[$field] = implode('|', $cleanParts);
            } else {
                $laravelRules[$field] = $r;
            }
        }

        $validator = Validator::make($input, $laravelRules);
        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            return false;
        }

        $this->errors = [];
        return true;
    }

    public function listErrors(string $template = 'list'): string
    {
        if (empty($this->errors)) {
            return '';
        }
        return implode('<br>', $this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getError(string $field): string
    {
        return $this->errors[$field] ?? ($this->errors[0] ?? '');
    }
}
