<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\LoanproviderModel;

class Loan_provider extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;

    protected $loanproviderModel;
    private $module_name = 'Loan_provider';
    public function __construct()
    {
        $this->loanproviderModel = new LoanproviderModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){
        $data['loanProvider'] = $this->loanproviderModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('loan_pro_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Loan_provider/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function create(){
        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/Loan_provider/create');
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function create_action(){

        $data['sch_id'] = Auth()->sch_id;
        $data['name'] = $this->request->getPost('name');
        $data['phone'] = $this->request->getPost('phone');
        $data['address'] = $this->request->getPost('address');
        $data['createdBy'] = Auth()->user_id;
        $data['createdDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'name' => ['label' => 'Name', 'rules' => 'required'],
            'phone' => ['label' => 'Phone', 'rules' => 'required'],
            'address' => ['label' => 'Address', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/loan_provider_create');
        } else {
            $this->loanproviderModel->insert($data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Add Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/loan_provider_create');

        }
    }
    public function update($loan_pro_id){
        $data['loanProvider'] = $this->loanproviderModel->where('loan_pro_id',$loan_pro_id)->first();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Loan_provider/update', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function update_action(){
        $data['loan_pro_id'] = $this->request->getPost('loan_pro_id');
        $data['name'] = $this->request->getPost('name');
        $data['phone'] = $this->request->getPost('phone');
        $data['address'] = $this->request->getPost('address');
        $data['updatedBy'] = Auth()->user_id;
        $data['updatedDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'name' => ['label' => 'Name', 'rules' => 'required'],
            'phone' => ['label' => 'Phone', 'rules' => 'required'],
            'address' => ['label' => 'Address', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/loan_provider_update/'.$data['loan_pro_id']);
        } else {
            $this->loanproviderModel->update($data['loan_pro_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/loan_provider_update/'.$data['loan_pro_id']);

        }
    }
    public function delete($loan_pro_id){
        $data['loan_pro_id'] = $loan_pro_id;
        $data['deleted'] = Auth()->user_id;
        $data['deletedRole'] = Auth()->role_id;

        $this->loanproviderModel->update($data['loan_pro_id'],$data);
        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('shop_admin/loan_provider');
    }








}