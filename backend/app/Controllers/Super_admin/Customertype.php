<?php

namespace App\Controllers\Super_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\CustomertypeModel;

class Customertype extends BaseController
{
    protected $validation;
    protected $session;
    protected $customertypeModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->customertypeModel = new CustomertypeModel();
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

            $data['customer'] = $this->customertypeModel->where('deleted IS NULL')->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Customertype/index',$data);
            echo view('Super_admin/footer');
        }
    }

    public function create() {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Customertype/create');
            echo view('Super_admin/footer');
        }
    }

    public function create_action(){
        $supuserId = $this->session->userIdSuper;

        $data['type_name'] = $this->request->getPost('type_name');

        $this->validation->setRules([
            'type_name' => ['label' => 'name', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/customer_type_create');
        } else {
            $this->customertypeModel->insert($data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/customer_type_create');

        }
    }



    public function update($cus_type_id) {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['category'] = $this->customertypeModel->where('cus_type_id',$cus_type_id)->first();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Customertype/update',$data);
            echo view('Super_admin/footer');
        }
    }

    public function update_action()
    {
        $supuserId = $this->session->userIdSuper;

        $data['cus_type_id'] = $this->request->getPost('cus_type_id');
        $data['type_name'] = $this->request->getPost('type_name');

        $this->validation->setRules([
            'type_name' => ['label' => 'name', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/customer_type_update/' . $data['cus_type_id']);
        } else {

            $this->customertypeModel->update($data['cus_type_id'], $data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/customer_type_update/' . $data['cus_type_id']);

        }
    }



    public function delete($cus_type_id) {

        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {
            $supuserId = $this->session->userIdSuper;
            $data['cus_type_id'] = $cus_type_id;
            $data['deleted'] = $supuserId;
            $data['deletedRole'] = $supuserId;

            $this->customertypeModel->update($data['cus_type_id'], $data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/customer_type');

        }
    }



}
