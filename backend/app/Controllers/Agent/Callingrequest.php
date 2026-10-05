<?php

namespace App\Controllers\Agent;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\AgentpermittedareaModel;
use App\Models\CallingrequestModel;
use App\Models\CustomersModel;
use App\Models\GlobaladdressModel;
use App\Models\InvoiceitemModel;
use App\Models\InvoiceModel;
use App\Models\LedgerModel;

class Callingrequest extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $crop;
    protected $callingrequestModel;
    protected $globaladdressModel;


    public function __construct()
    {
        $this->callingrequestModel = new CallingrequestModel();
        $this->globaladdressModel = new GlobaladdressModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
        $this->crop = \Config\Services::image();
    }


    public function index() {
        $data['result'] = $this->callingrequestModel->where('agent_id',Auth_agent()->agent_id)->orderBy('calling_id','DESC')->findAll();

        echo view('Agent/Callingrequest/index',$data);
    }

    public function status_update(){

        $data['calling_id'] = $this->request->getPost('calling_id');
        $data['status'] = $this->request->getPost('status');
        $this->callingrequestModel->update($data['calling_id'], $data);

        print '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }






}