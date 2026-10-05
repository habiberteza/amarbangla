<?php

namespace App\Controllers\Super_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\ShopcategoryModel;

class Shopcategory extends BaseController
{
    protected $validation;
    protected $session;
    protected $shopcategoryModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->shopcategoryModel = new ShopcategoryModel();
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

            $data['shopsCategory'] = $this->shopcategoryModel->where('deleted IS NULL')->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Shopcategory/index',$data);
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
            echo view('Super_admin/Shopcategory/create');
            echo view('Super_admin/footer');
        }
    }

    public function create_action(){
        $supuserId = $this->session->userIdSuper;

        $data['name'] = $this->request->getPost('name');
        $data['parent_cat_id'] = $this->request->getPost('parent_cat_id');
        $data['createdBy'] = $supuserId;

        $this->validation->setRules([
            'name' => ['label' => 'name', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shop_category_create');
        } else {
            $this->shopcategoryModel->insert($data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shop_category_create');

        }
    }



    public function update($shop_cat_id) {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['shopcategory'] = $this->shopcategoryModel->where('shop_cat_id',$shop_cat_id)->first();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Shopcategory/update',$data);
            echo view('Super_admin/footer');
        }
    }

    public function update_action()
    {
        $supuserId = $this->session->userIdSuper;

        $data['shop_cat_id'] = $this->request->getPost('shop_cat_id');
        $data['name'] = $this->request->getPost('name');
        $data['parent_cat_id'] = $this->request->getPost('parent_cat_id');

        $this->validation->setRules([
            'name' => ['label' => 'name', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shop_category_update/' . $data['shop_cat_id'] . '?active=general');
        } else {

            $this->shopcategoryModel->update($data['shop_cat_id'], $data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shop_category_update/' . $data['shop_cat_id'] . '?active=general');

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
