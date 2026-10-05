<?php

namespace App\Controllers\Super_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\GensettingssuperModel;
use App\Models\ShopsModel;
use App\Models\SmsrequestModel;

class Generalsettings extends BaseController
{
    protected $validation;
    protected $session;
    protected $gensettingssuperModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->gensettingssuperModel = new GensettingssuperModel();
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

            $data['settings'] = $this->gensettingssuperModel->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Generalsettings/index',$data);
            echo view('Super_admin/footer');
        }
    }
    public function update_action(){
        $data['settings_id_sup'] = $this->request->getPost('id[]');
        $data['value'] = $this->request->getPost('value[]');

        foreach ($data['settings_id_sup'] as $key => $val) {
            $this->gensettingssuperModel->set('value', $data['value'][$key])->where('settings_id_sup', $data['settings_id_sup'][$key])->update();
        }

        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('super_admin/general_settings');

    }







}
