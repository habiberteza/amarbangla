<?php

namespace App\Controllers\Agent;

use App\Controllers\BaseController;
use App\Libraries\Permission;

class Dashboard extends BaseController
{
    protected $session;
    protected $permission;

    private $module_name = 'Dashboard';
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->permission = new permission();
    }


    public function index() {

            echo view('Agent/Dashboard/dashboard');
    }
}