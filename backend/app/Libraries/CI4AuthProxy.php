<?php

namespace App\Libraries;

use Illuminate\Contracts\Auth\Factory as AuthFactory;

function get_ci4_shop_auth($shopId)
{
    static $cache = [];
    if (!$shopId) {
        return null;
    }
    if (isset($cache[$shopId])) {
        return $cache[$shopId];
    }
    try {
        $table = \Config\Database::connect()->table('shops');
        $table->select('*,shops.name as shopName, shops.email as shopEmail, shops.mobile as shopMobile, users.name as userName, users.mobile as userMobile,users.email as userEmail');
        $table->join('users', 'users.sch_id = shops.sch_id');
        $table->where('shops.sch_id', $shopId)->where('users.is_default', '1');
        $data = $table->get()->getRow();
        if ($data) {
            $cache[$shopId] = $data;
            return $data;
        }
        $shopOnly = \Config\Database::connect()->table('shops')->where('sch_id', $shopId)->get()->getRow();
        if ($shopOnly) {
            $shopOnly->shopName = $shopOnly->name ?? '';
            $shopOnly->shopEmail = $shopOnly->email ?? '';
            $shopOnly->shopMobile = $shopOnly->mobile ?? '';
            $shopOnly->userName = 'Shop Owner';
            $shopOnly->userMobile = $shopOnly->mobile ?? '';
            $shopOnly->userEmail = $shopOnly->email ?? '';
            $cache[$shopId] = $shopOnly;
            return $shopOnly;
        }
    } catch (\Throwable $e) {
        return null;
    }
    return null;
}

class CI4AuthProxy implements AuthFactory
{
    protected $factory;

    public function __construct($factory = null)
    {
        $this->factory = $factory;
    }

    protected function getFactory()
    {
        if (!$this->factory && function_exists('app')) {
            try {
                $this->factory = app(\Illuminate\Contracts\Auth\Factory::class);
            } catch (\Throwable $e) {
                return null;
            }
        }
        return $this->factory;
    }

    public function guard($name = null)
    {
        $factory = $this->getFactory();
        return $factory ? $factory->guard($name) : null;
    }

    public function shouldUse($name)
    {
        $factory = $this->getFactory();
        if ($factory) {
            $factory->shouldUse($name);
        }
    }

    public function __call($method, $parameters)
    {
        $factory = $this->getFactory();
        if ($factory && method_exists($factory, $method)) {
            return call_user_func_array([$factory, $method], $parameters);
        }
        if ($factory) {
            try {
                $guard = $factory->guard();
                if ($guard && method_exists($guard, $method)) {
                    return call_user_func_array([$guard, $method], $parameters);
                }
            } catch (\Throwable $e) {}
        }
        return null;
    }

    public function __get($property)
    {
        $shopId = $_SESSION['shopId'] ?? (function_exists('session') ? session('shopId') : null);
        if ($shopId) {
            $shopAuth = get_ci4_shop_auth($shopId);
            if ($shopAuth && isset($shopAuth->$property)) {
                return $shopAuth->$property;
            }
        }

        $factory = $this->getFactory();
        if ($factory) {
            try {
                $user = $factory->user();
                if ($user && isset($user->$property)) {
                    return $user->$property;
                }
            } catch (\Throwable $e) {}
        }

        return null;
    }

    public function __isset($property)
    {
        $shopId = $_SESSION['shopId'] ?? (function_exists('session') ? session('shopId') : null);
        if ($shopId) {
            $shopAuth = get_ci4_shop_auth($shopId);
            if ($shopAuth && isset($shopAuth->$property)) {
                return true;
            }
        }
        return false;
    }
}
