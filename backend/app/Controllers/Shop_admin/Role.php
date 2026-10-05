<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\ProductsModel;
use App\Models\RolesModel;

class Role extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $rolesModel;
    private $module_name = 'Role';
    public function __construct()
    {
        $this->rolesModel = new RolesModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){

        $data['role'] = $this->rolesModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('role_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Role/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function create(){
        $data['permission'] = json_decode($this->permission->all_permissions);

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/Role/create',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function create_action(){

        $data['sch_id'] = Auth()->sch_id;
        $data['role'] = $this->request->getPost('role');
        $data['createdBy'] = Auth()->user_id;
        $data['createdDtm'] = date('Y-m-d h:i:s');

        $permission = $this->request->getPost('permission[][]');
        $this->validation->setRules([
            'role' => ['label' => 'role', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/role_create');
        } else {

            $perm = json_encode($permission);
            $data['permission'] = $perm;

            $this->rolesModel->insert($data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Add Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/role_create');

        }
    }
    public function update($role_id){

        $data['role'] = $this->rolesModel->where('role_id',$role_id)->first();
        $adminRole= $this->rolesModel->where('sch_id', Auth()->sch_id)->where('is_default', '1')->first();

        $data['permission'] = json_decode($adminRole->permission);
        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Role/update', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function update_action(){
        $data['role_id'] = $this->request->getPost('role_id');
        $data['role'] = $this->request->getPost('role');
        $data['updatedBy'] = Auth()->user_id;
        $data['updatedDtm'] = date('Y-m-d h:i:s');

        $permission = $this->request->getPost('permission[][]');
        $this->validation->setRules([
            'role' => ['label' => 'role', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/role_update/'.$data['role_id']);
        } else {

            $data['permission'] = json_encode($permission);

            $this->rolesModel->update($data['role_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/role_update/'.$data['role_id']);

        }
    }
    public function delete($role_id){
        $data['role_id'] = $role_id;
        $data['deleted'] = Auth()->user_id;
        $data['deletedRole'] = Auth()->role_id;

        $this->rolesModel->update($data['role_id'],$data);
        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('shop_admin/role');
    }







}