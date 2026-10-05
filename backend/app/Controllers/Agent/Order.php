<?php

namespace App\Controllers\Agent;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\PackageModel;

class Order extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $crop;
    protected $packageModel;


    public function __construct()
    {
        $this->packageModel = new PackageModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
        $this->crop = \Config\Services::image();
    }


    public function index() {
        $data['order'] =$this->packageModel->join('shops', 'shops.sch_id = package.sch_id')->where('shops.agent_id',Auth_agent()->agent_id)->orderBy('package.invoice_id', 'DESC')->findAll();
        echo view('Agent/Order/index',$data);
    }






}