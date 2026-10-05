<?php

namespace CodeIgniter;

class Model extends \App\Models\CI4Model
{
}

class Controller extends \Illuminate\Routing\Controller
{
}

namespace Config;

class Services
{
    public static function session()
    {
        return new \App\Libraries\CI4Session();
    }

    public static function validation()
    {
        return new \App\Libraries\CI4Validation();
    }

    public static function cart()
    {
        return new \App\Libraries\CI4Cart();
    }

    public static function image($handler = null)
    {
        return new \App\Libraries\CI4Image();
    }

    public static function request()
    {
        return new \App\Libraries\CI4Request();
    }

    public static function email()
    {
        return new \App\Libraries\CI4Email();
    }

    public static function pager()
    {
        return new \App\Libraries\CI4Pager();
    }
}

class Database
{
    public static function connect()
    {
        return \App\Libraries\CI4Connection::getInstance();
    }
}

