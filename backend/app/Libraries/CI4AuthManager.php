<?php

namespace App\Libraries;

use Illuminate\Auth\AuthManager;

class CI4AuthManager extends AuthManager
{
    protected static array $shopAuthCache = [];

    public static function getShopAuth($shopId)
    {
        if (!$shopId) {
            return null;
        }
        if (isset(self::$shopAuthCache[$shopId])) {
            return self::$shopAuthCache[$shopId];
        }
        try {
            $table = \Config\Database::connect()->table('shops');
            $table->select('*,shops.name as shopName, shops.email as shopEmail, shops.mobile as shopMobile, users.name as userName, users.mobile as userMobile,users.email as userEmail');
            $table->join('users', 'users.sch_id = shops.sch_id');
            $table->where('shops.sch_id', $shopId)->where('users.is_default', '1');
            $data = $table->get()->getRow();
            if ($data) {
                self::$shopAuthCache[$shopId] = $data;
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
                self::$shopAuthCache[$shopId] = $shopOnly;
                return $shopOnly;
            }
        } catch (\Throwable $e) {
            return null;
        }
        return null;
    }

    public function __get($property)
    {
        $shopId = $_SESSION['shopId'] ?? (function_exists('session') ? session('shopId') : null);
        if ($shopId) {
            $shopAuth = self::getShopAuth($shopId);
            if ($shopAuth && isset($shopAuth->$property)) {
                return $shopAuth->$property;
            }
        }

        try {
            $user = $this->guard()->user();
            if ($user && isset($user->$property)) {
                return $user->$property;
            }
        } catch (\Throwable $e) {}

        return null;
    }

    public function __isset($property)
    {
        $shopId = $_SESSION['shopId'] ?? (function_exists('session') ? session('shopId') : null);
        if ($shopId) {
            $shopAuth = self::getShopAuth($shopId);
            if ($shopAuth && isset($shopAuth->$property)) {
                return true;
            }
        }

        try {
            $user = $this->guard()->user();
            if ($user && isset($user->$property)) {
                return true;
            }
        } catch (\Throwable $e) {}

        return false;
    }
}
