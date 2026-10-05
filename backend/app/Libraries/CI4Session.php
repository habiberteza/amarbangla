<?php

namespace App\Libraries;

class CI4Session
{
    public function get($key = null)
    {
        if ($key === null) {
            return session()->all();
        }
        return session()->get($key, $_SESSION[$key] ?? null);
    }

    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                session()->put($k, $v);
                $_SESSION[$k] = $v;
            }
        } else {
            session()->put($key, $value);
            $_SESSION[$key] = $value;
        }
    }

    public function setFlashdata($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                session()->flash($k, $v);
            }
        } else {
            session()->flash($key, $value);
        }
    }

    public function getFlashdata($key = null)
    {
        return session()->get($key);
    }

    public function remove($key)
    {
        session()->forget($key);
        unset($_SESSION[$key]);
    }

    public function destroy()
    {
        session()->flush();
        $_SESSION = [];
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __isset($name)
    {
        return session()->has($name) || isset($_SESSION[$name]);
    }
}
