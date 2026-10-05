<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Route;

class CI4RouteBridge
{
    protected $currentPrefix = '';
    protected $currentGroupOptions = [];

    public function setAutoRoute(bool $val) {}
    public function setDefaultNamespace(string $val) {}
    public function setDefaultController(string $val) {}
    public function setDefaultMethod(string $val) {}
    public function setTranslateURIDashes(bool $val) {}
    public function set404Override($val = null) {}

    public function group(string $name, $options, \Closure $callback = null)
    {
        if ($callback === null && is_callable($options)) {
            $callback = $options;
            $options = [];
        }

        $prevPrefix = $this->currentPrefix;
        $prevOptions = $this->currentGroupOptions;

        $cleanName = trim($name, '/');
        $this->currentPrefix = empty($prevPrefix) ? $cleanName : $prevPrefix . '/' . $cleanName;
        $this->currentGroupOptions = array_merge($prevOptions, is_array($options) ? $options : []);

        if (is_callable($callback)) {
            $callback($this);
        }

        $this->currentPrefix = $prevPrefix;
        $this->currentGroupOptions = $prevOptions;
    }

    public function get(string $uri, $to, array $options = [])
    {
        $this->addRoute('get', $uri, $to, $options);
    }

    public function post(string $uri, $to, array $options = [])
    {
        $this->addRoute('post', $uri, $to, $options);
    }

    public function match(array $verbs, string $uri, $to, array $options = [])
    {
        foreach ($verbs as $verb) {
            $this->addRoute(strtolower($verb), $uri, $to, $options);
        }
    }

    protected function addRoute(string $verb, string $uri, $to, array $options = [])
    {
        $uri = trim($uri, '/');
        if (!empty($this->currentPrefix)) {
            $fullUri = $uri === '' ? $this->currentPrefix : $this->currentPrefix . '/' . $uri;
        } else {
            $fullUri = $uri === '' ? '/' : $uri;
        }

        // Keep Vue storefront on root /
        if ($fullUri === '/' || $fullUri === '') {
            $fullUri = 'ci4_home';
        }

        // Super admin routes are fully migrated and handled by native Laravel controllers
        if (str_starts_with($fullUri, 'super_admin')) {
            return;
        }

        // Parse target controller and action
        if (!is_string($to)) {
            return;
        }

        $parts = explode('::', $to);
        $controllerName = $parts[0];
        $actionPart = $parts[1] ?? 'index';

        // Strip parameter placeholders like /$1, /$2
        $action = preg_replace('/\/\$[0-9]+/', '', $actionPart);
        $action = trim($action);

        $controllerClass = 'App\\Controllers\\' . ltrim($controllerName, '\\');

        // Convert CI4 route parameters: (:num), (:any), (:segment), (:alphanum)
        // Also handles missing leading slash e.g. shops_delete(:num)
        $paramCount = 0;
        $laravelUri = preg_replace_callback('/(?:\/)?\((:num|:any|:segment|:alphanum)\)/', function () use (&$paramCount) {
            $paramCount++;
            return '/{p' . $paramCount . '}';
        }, $fullUri);

        // Ensure leading slash for consistency
        $laravelUri = '/' . ltrim($laravelUri, '/');

        $filter = $this->currentGroupOptions['filter'] ?? ($options['filter'] ?? null);

        // Register route with web middleware for session & cookie support
        $route = Route::$verb($laravelUri, function (...$args) use ($controllerClass, $action, $filter, $laravelUri) {
            // Apply CI4 Filter logic
            if ($filter === 'AuthCheck') {
                $shopId = $_SESSION['shopId'] ?? (function_exists('session') ? session('shopId') : null);
                if (empty($shopId)) {
                    return redirect('/shop_admin/login');
                }
            } elseif ($filter === 'AlreadyLoggedIn') {
                $shopId = $_SESSION['shopId'] ?? (function_exists('session') ? session('shopId') : null);
                if (!empty($shopId)) {
                    return redirect('/shop_admin/dashboard');
                }
            } elseif ($filter === 'AgentAuthCheck') {
                $agentId = $_SESSION['agentId'] ?? (function_exists('session') ? session('agentId') : null);
                if (empty($agentId)) {
                    return redirect('/agent/login');
                }
            } elseif ($filter === 'AgentLoggedIn') {
                $agentId = $_SESSION['agentId'] ?? (function_exists('session') ? session('agentId') : null);
                if (!empty($agentId)) {
                    return redirect('/agent/dashboard');
                }
            } elseif (str_starts_with($laravelUri, '/super_admin') && !in_array($laravelUri, ['/super_admin', '/super_admin/login_action', '/super_admin/logout'])) {
                $userIdSuper = $_SESSION['userIdSuper'] ?? (function_exists('session') ? session('userIdSuper') : null);
                if (empty($userIdSuper) && !auth()->check()) {
                    return redirect('/super_admin');
                }
            }

            if (!class_exists($controllerClass)) {
                abort(404, "Controller {$controllerClass} not found");
            }
            $controller = app()->make($controllerClass);
            return $controller->callAction($action, $args);
        })->middleware('web');

        // Add constraints for placeholder parameters
        if ($paramCount > 0) {
            for ($i = 1; $i <= $paramCount; $i++) {
                $route->where('p' . $i, '[^/]+');
            }
        }
    }
}
