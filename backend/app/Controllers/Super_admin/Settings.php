<?php

namespace App\Controllers\Super_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\AdminModel;

class Settings extends BaseController
{
    protected $validation;
    protected $session;
    protected $adminModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
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

            $data['admin'] = $this->adminModel->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Settings/index',$data);
            echo view('Super_admin/footer');
        }
    }


    public function update($user_id) {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['admin'] = $this->adminModel->where('user_id',$user_id)->first();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Settings/update',$data);
            echo view('Super_admin/footer');
        }
    }

    public function update_action()
    {
        $supuserId = $this->session->userIdSuper;

        $data['user_id'] = $this->request->getPost('user_id');
        $data['name'] = $this->request->getPost('name');
        $data['email'] = $this->request->getPost('email');
        $data['password'] = $this->request->getPost('password');
        $data['con_password'] = $this->request->getPost('con_password');
        $data['status'] = $this->request->getPost('status');

        $this->validation->setRules([
            'name' => ['label' => 'name', 'rules' => 'required'],
            'email' => ['label' => 'email', 'rules' => 'required'],
            'password' => ['label' => 'password', 'rules' => 'required'],
            'con_password' => ['label' => 'Con password', 'rules' => 'required|matches[password]'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/settings_update/'.$data['user_id']. '?active=general');
        } else {
            $data['password'] = sha1($this->request->getPost('password'));
            $this->adminModel->update($data['user_id'],$data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/settings_update/'.$data['user_id']. '?active=general');

        }
    }

    public function personal_action()
    {

        $data['user_id'] = $this->request->getPost('user_id');
        $data['ComName'] = $this->request->getPost('ComName');
        $data['country'] = $this->request->getPost('country');
        $data['mobile'] = $this->request->getPost('mobile');
        $data['address'] = $this->request->getPost('address');

        $this->validation->setRules([
            'ComName' => ['label' => 'ComName', 'rules' => 'required'],
            'mobile' => ['label' => 'Mobile', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/settings_update/' . $data['user_id'] . '?active=personal');
        } else {


            $this->adminModel->update($data['user_id'], $data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/settings_update/' . $data['user_id'] . '?active=personal');
        }
    }

    public function photo_action()
    {

        $data['user_id'] = $this->request->getPost('user_id');

        $this->validation->setRules([
            'user_id' => ['label' => 'user_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/settings_update/' . $data['user_id'] . '?active=photo');
        } else {

            if (!empty($_FILES['pic']['name'])) {
                $target_dir = FCPATH . '/uploads/admin_image/';
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                //old image unlink
                $old_img = get_data_by_id('pic', 'admin', 'user_id', $data['user_id']);
                if (!empty($old_img)) {
                    unlink($target_dir . '' . $old_img);
                }

                //new image uplode
                $pic = $this->request->getFile('pic');
                $namePic = $pic->getRandomName();
                $pic->move($target_dir, $namePic);
                $pro_nameimg = 'admin_' . $pic->getName();
                $this->crop->withFile($target_dir . '' . $namePic)->fit(150, 167, 'center')->save($target_dir . '' . $pro_nameimg);
                unlink($target_dir . '' . $namePic);
                $data['pic'] = $pro_nameimg;

                $this->adminModel->update($data['user_id'], $data);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/settings_update/' . $data['user_id'] . '?active=photo');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any photo! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/settings_update/' . $data['user_id'] . '?active=photo');
            }



        }
    }







}
