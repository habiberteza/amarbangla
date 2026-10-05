<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\WarrantymanageModel;

class WarrantyManage extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $warrantymanageModel;
    private $module_name = 'Warranty_manage';
    public function __construct()
    {
        $this->warrantymanageModel = new WarrantymanageModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){
        $data['warrantyManage'] = $this->warrantymanageModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('warranty_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Warranty_manage/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function create(){
        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/Warranty_manage/create');
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function create_action(){

        $data['sch_id'] = Auth()->sch_id;
        $data['product_name'] = $this->request->getPost('product_name');
        $data['receive_date'] = $this->request->getPost('receive_date');
        $data['delivery_date'] = $this->request->getPost('delivery_date');
        $data['customer_address'] = $this->request->getPost('customer_address');
        $data['customer_name'] = $this->request->getPost('customer_name');
        $data['mobile'] = $this->request->getPost('mobile');
        $data['createdBy'] = Auth()->user_id;
        $data['createdDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'product_name' => ['label' => 'product_name', 'rules' => 'required'],
            'receive_date' => ['label' => 'receive_date', 'rules' => 'required'],
            'delivery_date' => ['label' => 'delivery_date', 'rules' => 'required'],
            'customer_address' => ['label' => 'customer_address', 'rules' => 'required'],
            'customer_name' => ['label' => 'customer_name', 'rules' => 'required'],
            'mobile' => ['label' => 'mobile', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/warranty_manage_create');
        } else {
            $this->warrantymanageModel->insert($data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Add Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/warranty_manage_create');

        }
    }
    public function update($warranty_id){
        $data['warranty'] = $this->warrantymanageModel->where('warranty_id',$warranty_id)->first();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Warranty_manage/update', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function update_action(){
        $data['warranty_id'] = $this->request->getPost('warranty_id');
        $data['product_name'] = $this->request->getPost('product_name');
        $data['receive_date'] = $this->request->getPost('receive_date');
        $data['delivery_date'] = $this->request->getPost('delivery_date');
        $data['customer_address'] = $this->request->getPost('customer_address');
        $data['customer_name'] = $this->request->getPost('customer_name');
        $data['mobile'] = $this->request->getPost('mobile');
        $data['updatedBy'] = Auth()->user_id;
        $data['updatedDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'product_name' => ['label' => 'product_name', 'rules' => 'required'],
            'receive_date' => ['label' => 'receive_date', 'rules' => 'required'],
            'delivery_date' => ['label' => 'delivery_date', 'rules' => 'required'],
            'customer_address' => ['label' => 'customer_address', 'rules' => 'required'],
            'customer_name' => ['label' => 'customer_name', 'rules' => 'required'],
            'mobile' => ['label' => 'mobile', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/warranty_manage_update/'.$data['warranty_id']);
        } else {
            $this->warrantymanageModel->update($data['warranty_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/warranty_manage_update/'.$data['warranty_id']);

        }
    }
    public function delete($warranty_id){
        $data['warranty_id'] = $warranty_id;
        $data['deleted'] = Auth()->user_id;
        $data['deletedRole'] = Auth()->role_id;

        $this->warrantymanageModel->update($data['warranty_id'],$data);
        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('shop_admin/warranty_manage');
    }








}