<?php

namespace App\Controllers\Agent;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\ShopsModel;
use App\Models\SupcommiinvoiceModel;

class SuperCommission extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $shopsModel;
    protected $supcommiinvoiceModel;

    public function __construct()
    {
        $this->shopsModel = new ShopsModel();
        $this->supcommiinvoiceModel = new SupcommiinvoiceModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }


    public function index() {
        $data['commission'] = $this->shopsModel->where('agent_id', Auth_agent()->agent_id)->findAll();
        echo view('Agent/SuperCommission/index',$data);
    }
    public function unpaid_list($sch_id) {
        $data['commission'] = $this->supcommiinvoiceModel->where('sch_id', $sch_id)->where('status', '0')->findAll();
        echo view('Agent/SuperCommission/unpaid_list',$data);
    }
    public function paid_list($sch_id) {
        $data['commission'] = $this->supcommiinvoiceModel->where('sch_id', $sch_id)->where('status', '1')->findAll();
        echo view('Agent/SuperCommission/paid_list',$data);
    }
}