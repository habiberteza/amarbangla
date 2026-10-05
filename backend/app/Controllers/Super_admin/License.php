<?php

namespace App\Controllers\Super_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\LicenseModel;
use App\Models\ShopsModel;
use App\Models\UsersModel;

class License extends BaseController
{
    protected $validation;
    protected $session;
    protected $licenseModel;
    protected $shopsModel;
    protected $usersModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->licenseModel = new LicenseModel();
        $this->shopsModel = new ShopsModel();
        $this->usersModel = new UsersModel();
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

            $data['license'] = $this->licenseModel->where('deleted IS NULL')->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/License/index',$data);
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
            echo view('Super_admin/License/create');
            echo view('Super_admin/footer');
        }
    }

    public function create_action(){
        $supuserId = $this->session->userIdSuper;
        $licKey = uniqid();
        $data['sch_id'] = $this->request->getPost('sch_id');
        $data['lic_key'] = $licKey;
        $data['start_date'] = $this->request->getPost('start_date');
        $data['end_date'] = $this->request->getPost('end_date');
        $data['createdDtm'] = date('Y-m-d h:i:s');
        $data['createdBy'] = $supuserId;

        $this->validation->setRules([
            'sch_id' => ['label' => 'shop', 'rules' => 'required'],
            'start_date' => ['label' => 'start date', 'rules' => 'required'],
            'end_date' => ['label' => 'end date', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/license_create');
        } else {
            DB()->transStart();

            $this->licenseModel->insert($data);

            $dateShops['status'] = '1';
            $this->shopsModel->update($data['sch_id'],$dateShops);


            $dataUser['status'] = '1';
            $dataUser['updatedDtm'] = date('Y-m-d h:i:s');
            $this->usersModel->update($data['sch_id'],$dataUser);

            DB()->transComplete();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/license_create');

        }
    }



    public function update($lic_id) {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['license'] = $this->licenseModel->where('lic_id',$lic_id)->first();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/License/update',$data);
            echo view('Super_admin/footer');
        }
    }

    public function update_action()
    {
        $supuserId = $this->session->userIdSuper;

        $licKey = uniqid();
        $data['sch_id'] = $this->request->getPost('sch_id');
        $data['lic_id'] = $this->request->getPost('lic_id');
        $data['lic_key'] = $licKey;
        $data['start_date'] = $this->request->getPost('start_date');
        $data['end_date'] = $this->request->getPost('end_date');

        $this->validation->setRules([
            'sch_id' => ['label' => 'shop', 'rules' => 'required'],
            'start_date' => ['label' => 'start date', 'rules' => 'required'],
            'end_date' => ['label' => 'end date', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/license_update/'.$data['lic_id']);
        } else {
            DB()->transStart();

            $dataUp['start_date'] = $data['start_date'];
            $dataUp['end_date'] = $data['end_date'];

            $this->licenseModel->update($data['lic_id'],$dataUp);

            $dateShops['status'] = '1';
            $this->shopsModel->update($data['sch_id'],$dateShops);


            $dataUser['status'] = '1';
            $dataUser['updatedDtm'] = date('Y-m-d h:i:s');
            $this->usersModel->update($data['sch_id'],$dataUser);

            DB()->transComplete();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/license_update/'.$data['lic_id']);

        }
    }

    public function others_action()
    {

        $data['shop_cat_id'] = $this->request->getPost('shop_cat_id');
        $data['show_home'] = $this->request->getPost('show_home');
        $data['title'] = $this->request->getPost('title');

        $this->validation->setRules([
            'show_home' => ['label' => 'show_home', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shop_category_update/' . $data['shop_cat_id'] . '?active=personal');
        } else {

            if (!empty($_FILES['image']['name'])) {
                $target_dir = FCPATH . '/uploads/schools/';
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                //old image unlink
                $old_img = get_data_by_id('image', 'shop_category', 'shop_cat_id', $data['shop_cat_id']);
                if (!empty($old_img)) {
                    unlink($target_dir . '' . $old_img);
                }

                //new image uplode
                $pic = $this->request->getFile('image');
                $namePic = $pic->getRandomName();
                $pic->move($target_dir, $namePic);
                $pro_nameimg = 'shop_cat_' . $pic->getName();
                $this->crop->withFile($target_dir . '' . $namePic)->fit(150, 167, 'center')->save($target_dir . '' . $pro_nameimg);
                unlink($target_dir . '' . $namePic);
                $data['image'] = $pro_nameimg;
            }


            $this->shopcategoryModel->update($data['shop_cat_id'], $data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shop_category_update/' . $data['shop_cat_id'] . '?active=personal');
        }
    }


    public function delete($shop_cat_id) {

        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {
            $supuserId = $this->session->userIdSuper;
            $data['shop_cat_id'] = $shop_cat_id;
            $data['deleted'] = $supuserId;
            $data['deletedRole'] = $supuserId;

            $this->shopcategoryModel->update($data['shop_cat_id'], $data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shop_category');

        }
    }



}
