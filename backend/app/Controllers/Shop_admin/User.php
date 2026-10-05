<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Models\BankModel;
use App\Libraries\Permission;
use App\Models\UsersModel;

class User extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $usersModel;
    protected $crop;
    private $module_name = 'User';
    public function __construct()
    {
        $this->usersModel = new UsersModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
        $this->crop = \Config\Services::image();
    }

    public function index(){


        $data['user'] = $this->usersModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('user_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/User/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function create(){


        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/User/create');
        }else{
            echo view('Shop_admin/no_permission');
        }

    }
    public function create_action(){

        $data['sch_id'] = Auth()->sch_id;
        $data['name'] = $this->request->getPost('name');
        $data['email'] = $this->request->getPost('email');
        $data['password'] = sha1($this->request->getPost('password'));
        $data['con_password'] = sha1($this->request->getPost('con_password'));
        $data['role_id'] = $this->request->getPost('role_id');
        $data['status'] = $this->request->getPost('status');
        $data['createdBy'] = Auth()->user_id;
        $data['createdDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'name' => ['label' => 'name', 'rules' => 'required'],
            'email' => ['label' => 'email', 'rules' => 'required'],
            'role_id' => ['label' => 'role_id', 'rules' => 'required'],
            'status' => ['label' => 'status', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required|max_length[155]'],
            'con_password' => ['label' => 'Con password', 'rules' => 'required|matches[password]'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/user_create');
        } else {
            $emailUnique = is_unique_super('users', 'email', $data['email']);
            if ($emailUnique == true) {
                $data['pass'] = $this->request->getPost('password');
                $this->usersModel->insert($data);
                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Add Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/user_create');
            }else {
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Email already in use <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/user_create');
            }

        }
    }
    public function update($user_id){

        $data['user'] = $this->usersModel->where('user_id',$user_id)->first();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/User/update', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function update_action(){
        $data['user_id'] = $this->request->getPost('user_id');
        $data['name'] = $this->request->getPost('name');
        $data['email'] = $this->request->getPost('email');
        $data['password'] = sha1($this->request->getPost('password'));
        $data['con_password'] = sha1($this->request->getPost('con_password'));
        $data['status'] = $this->request->getPost('status');
        $data['updatedBy'] = Auth()->user_id;
        $data['updatedDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'name' => ['label' => 'name', 'rules' => 'required'],
            'email' => ['label' => 'email', 'rules' => 'required'],
            'status' => ['label' => 'status', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required|max_length[155]'],
            'con_password' => ['label' => 'Con password', 'rules' => 'required|matches[password]'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/user_update/'.$data['user_id'].'?active=general');
        } else {
            $emailUnique = is_unique_super_update('users', 'email', $data['email'], 'user_id', $data['user_id']);
            if ($emailUnique == true) {
                $data['pass'] = $this->request->getPost('password');
                $this->usersModel->update($data['user_id'], $data);
                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/user_update/' . $data['user_id'] . '?active=general');
            }else {
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Email already in use <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/user_update/' . $data['user_id'] . '?active=general');
            }

        }
    }

    public function personal_action(){
        $data['user_id'] = $this->request->getPost('user_id');
        $data['mobile'] = $this->request->getPost('mobile');
        $data['address'] = $this->request->getPost('address');
        $data['role_id'] = $this->request->getPost('role_id');
        $data['updatedBy'] = Auth()->user_id;
        $data['updatedDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'mobile' => ['label' => 'mobile', 'rules' => 'required'],
            'address' => ['label' => 'address', 'rules' => 'required'],
            'role_id' => ['label' => 'role_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/user_update/'.$data['user_id'].'?active=personal');
        } else {
            $this->usersModel->update($data['user_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/user_update/'.$data['user_id'].'?active=personal');

        }
    }
    public function photo_action(){
        $data['user_id'] = $this->request->getPost('user_id');

        $this->validation->setRules([
            'user_id' => ['label' => 'user_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/user_update/'.$data['user_id'].'?active=personal');
        } else {

            if (!empty($_FILES['pic']['name'])) {
                $target_dir = FCPATH . '/uploads/users_image/';
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                //old image unlink
                $old_img = get_data_by_id('pic', 'users', 'user_id', $data['user_id']);
                if (!empty($old_img)) {
                    unlink($target_dir . '' . $old_img);
                }

                //new image uplode
                $pic = $this->request->getFile('pic');
                $namePic = $pic->getRandomName();
                $pic->move($target_dir, $namePic);
                $pro_nameimg = 'users_' . $pic->getName();
                $this->crop->withFile($target_dir . '' . $namePic)->fit(300, 300, 'center')->save($target_dir . '' . $pro_nameimg);
                unlink($target_dir . '' . $namePic);
                $data['pic'] = $pro_nameimg;


                $this->usersModel->update($data['user_id'],$data);
                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/user_update/'.$data['user_id'].'?active=photo');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/user_update/'.$data['user_id'].'?active=photo');
            }

        }
    }
    public function delete($user_id){
        $data['user_id'] = $user_id;
        $data['deleted'] = Auth()->user_id;
        $data['deletedRole'] = Auth()->role_id;

        $this->usersModel->update($data['user_id'],$data);
        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('shop_admin/user');
    }







}