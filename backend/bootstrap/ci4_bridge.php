<?php

if (!isset($_SESSION)) {
    $_SESSION = [];
}

if (!function_exists('auth')) {
    function auth($guard = null)
    {
        if (!is_null($guard)) {
            return app(\Illuminate\Contracts\Auth\Factory::class)->guard($guard);
        }
        return new \App\Libraries\CI4AuthProxy();
    }
}
