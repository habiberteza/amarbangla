<?php

namespace App\Controllers\Super_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\GlobaladdressModel;


class Globaladdress extends BaseController
{
    protected $validation;
    protected $session;
    protected $globaladdressModel;

    protected $crop;
    protected $permission;

    public function __construct(){
        $this->globaladdressModel = new GlobaladdressModel();
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
            $division = $this->request->getGet('division');
            $district = $this->request->getGet('district');
            $upazila = $this->request->getGet('upazila');
            $perPage = (int) ($this->request->getGet('per_page') ?? 25);
            if ($perPage <= 0 || $perPage > 200) $perPage = 25;

            $builder = $this->globaladdressModel->orderBy('global_address_id', 'DESC');
            if (!empty($division)) {
                $builder = $builder->where('division', $division);
            }
            if (!empty($district)) {
                $builder = $builder->where('zila', $district);
            }
            if (!empty($upazila)) {
                $builder = $builder->where('upazila', $upazila);
            }

            $data['address_data'] = $builder->paginate($perPage);
            $data['pager'] = $this->globaladdressModel->pager;
            $data['selected_division'] = $division;
            $data['selected_district'] = $district;
            $data['selected_upazila'] = $upazila;
            $data['per_page'] = $perPage;

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Globaladdress/index',$data);
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
            echo view('Super_admin/Globaladdress/create');
            echo view('Super_admin/footer');
        }
    }

    public function create_action(){

        $data['division'] = $this->request->getPost('division');
        $data['zila'] = $this->request->getPost('district');
        $data['upazila'] = $this->request->getPost('upazila');
        $data['pourashava'] = $this->request->getPost('pourashava');
        $data['ward'] = $this->request->getPost('ward');

        $this->validation->setRules([
            'division' => ['label' => 'division', 'rules' => 'required'],
            'zila' => ['label' => 'zila', 'rules' => 'required'],
            'upazila' => ['label' => 'upazila', 'rules' => 'required'],
            'pourashava' => ['label' => 'pourashava', 'rules' => 'required'],
            'ward' => ['label' => 'ward', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/global_address_create/');
        } else {
            $address = $this->globaladdressModel->where($data)->first();
            if (empty($address)) {

                $this->globaladdressModel->insert($data);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data insert successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/global_address_create/');


            } else {
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Already exists in this address! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/global_address_create/');
            }
        }
    }

    public function update($global_address_id) {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['address'] = $this->globaladdressModel->where('global_address_id',$global_address_id)->first();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Globaladdress/update',$data);
            echo view('Super_admin/footer');
        }
    }


    public function update_action(){

        $dataup['global_address_id'] = $this->request->getPost('global_address_id');
        $data['division'] = $this->request->getPost('division');
        $data['zila'] = $this->request->getPost('district');
        $data['upazila'] = $this->request->getPost('upazila');
        $data['pourashava'] = $this->request->getPost('pourashava');
        $data['ward'] = $this->request->getPost('ward');

        $this->validation->setRules([
            'division' => ['label' => 'division', 'rules' => 'required'],
            'zila' => ['label' => 'zila', 'rules' => 'required'],
            'upazila' => ['label' => 'upazila', 'rules' => 'required'],
            'pourashava' => ['label' => 'pourashava', 'rules' => 'required'],
            'ward' => ['label' => 'ward', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/global_address_update/'.$dataup['global_address_id']);
        } else {
            $address = $this->globaladdressModel->where($data)->where('global_address_id !='.$dataup['global_address_id'])->first();
            if (empty($address)) {

                $this->globaladdressModel->update($dataup['global_address_id'],$data);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data insert successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/global_address_update/'.$dataup['global_address_id']);


            } else {
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Already exists in this address! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/global_address_update/'.$dataup['global_address_id']);
            }
        }
    }

    public function delete($global_address_id) {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['global_address_id'] = $global_address_id;
            $this->globaladdressModel->delete($data['global_address_id']);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data delete successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/global_address/');
        }
    }

    public function search() {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {
            $data['division'] = $this->request->getPost('division');
            $data['district'] = $this->request->getPost('district');
            $data['upazila'] = $this->request->getPost('upazila');

            $division = empty($data['division']) ? '1=1' : array('division' => $data['division']);
            $district = empty($data['district']) ? '1=1' : array('zila' => $data['district']);
            $upazila = empty($data['upazila']) ? '1=1' : array('upazila' => $data['upazila']);

            $data['address_data'] = $this->globaladdressModel->where($division)->where($district)->where($upazila)->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Globaladdress/search',$data);
            echo view('Super_admin/footer');

        }
    }




}
