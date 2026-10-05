<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\ShopsModel;
use App\Models\SmsrequestModel;

class SmsPanel extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $smsrequestModel;
    protected $shopsModel;
    private $module_name = 'Sms_panel';
    public function __construct()
    {
        $this->smsrequestModel = new SmsrequestModel();
        $this->shopsModel = new ShopsModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){

        $data['result'] = $this->smsrequestModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('sms_request_id','DESC')->findAll();
        $data['totalSms'] = $this->shopsModel->where('sch_id',Auth()->sch_id)->first()->sms;

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Sms_panel/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }

    public function update($sms_request_id){

        $data['row'] = $this->smsrequestModel->where('sms_request_id',$sms_request_id)->first();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Sms_panel/update', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function update_action(){
        $data['sms_request_id'] = $this->request->getPost('sms_request_id');
        $data['sms_qty'] = $this->request->getPost('sms_qty');
        $data['updatedBy'] = Auth()->user_id;
        $data['updatedDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'sms_qty' => ['label' => 'sms_qty', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/sms_panel_update/'.$data['sms_request_id']);
        } else {
            $this->smsrequestModel->update($data['sms_request_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/sms_panel_update/'.$data['sms_request_id']);

        }
    }








}