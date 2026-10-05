<?php

namespace App\Controllers\Super_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\ShopsModel;
use App\Models\SmsrequestModel;

class Smspanel extends BaseController
{
    protected $validation;
    protected $session;
    protected $smsrequestModel;
    protected $shopsModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->smsrequestModel = new SmsrequestModel();
        $this->shopsModel = new ShopsModel();
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->permission = new Permission();
        $this->crop = \Config\Services::image();
    }
    public function index() {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['sms'] = $this->smsrequestModel->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Smspanel/index',$data);
            echo view('Super_admin/footer');
        }
    }
    public function update_status()
    {
        $supuserId = $this->session->userIdSuper;

        $data['sms_request_id'] = $this->request->getPost('sms_request_id');
        $data['status'] = $this->request->getPost('status');

        $this->smsrequestModel->update($data['sms_request_id'],$data);

        if ($data['status'] == 1) {
            $shopId = get_data_by_id('sch_id', 'sms_request', 'sms_request_id', $data['sms_request_id']);
            $newSmsQty = get_data_by_id('sms_qty', 'sms_request', 'sms_request_id', $data['sms_request_id']);
            $oldSmsQty = get_data_by_id('sms', 'shops', 'sch_id', $shopId);
            $newTotal = $oldSmsQty + $newSmsQty;

            $dataShop['sch_id'] = $shopId;
            $dataShop['sms'] = $newTotal;

            $this->shopsModel->update($dataShop['sch_id'],$dataShop);
        }

        print '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';


    }







}
