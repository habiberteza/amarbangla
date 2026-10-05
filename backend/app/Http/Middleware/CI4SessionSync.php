<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CI4SessionSync
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!isset($_SESSION)) {
            $_SESSION = [];
        }

        // Synchronize all existing Laravel session attributes into $_SESSION
        if ($request->hasSession()) {
            foreach ($request->session()->all() as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }

        $response = $next($request);

        // Synchronize back any modifications to $_SESSION into Laravel session
        if ($request->hasSession() && isset($_SESSION)) {
            foreach ($_SESSION as $key => $value) {
                $request->session()->put($key, $value);
            }
        }

        return $response;
    }
}
