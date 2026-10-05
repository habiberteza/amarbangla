<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\LedgersuppliersModel;
use App\Models\PurchaseModel;
use App\Models\SuppliersModel;

class Suppliers extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $suppliers;
    protected $purchaseModel;
    protected $ledgersuppliersModel;
    private $module_name = 'Suppliers';
    public function __construct()
    {
        $this->suppliers = new SuppliersModel();
        $this->ledgersuppliersModel = new LedgersuppliersModel();
        $this->purchaseModel = new PurchaseModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){
        $data['suppliers'] = $this->suppliers->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('supplier_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Suppliers/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function create(){
        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/Suppliers/create');
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
            return redirect()->to('shop_admin/suppliers_create');
        } else {
            $this->suppliers->insert($data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Add Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/suppliers_create');

        }
    }
    public function update($supplier_id){
        $data['suppliers'] = $this->suppliers->where('supplier_id',$supplier_id)->first();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Suppliers/update', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function update_action(){
        $data['supplier_id'] = $this->request->getPost('supplier_id');
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
            return redirect()->to('shop_admin/suppliers_update/'.$data['supplier_id']);
        } else {
            $this->suppliers->update($data['supplier_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/suppliers_update/'.$data['supplier_id']);

        }
    }
    public function delete($supplier_id){
        $data['supplier_id'] = $supplier_id;
        $data['deleted'] = Auth()->user_id;
        $data['deletedRole'] = Auth()->role_id;

        $this->suppliers->update($data['supplier_id'],$data);
        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('shop_admin/suppliers');
    }
    public function products($supplier_id){
        $data['purchase'] = $this->purchaseModel->where('sch_id',Auth()->sch_id)->where('supplier_id',$supplier_id)->findAll();
        $data['supplierId'] = $supplier_id;
        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Suppliers/products', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function transaction($supplier_id){
        $data['suppLedger'] = $this->ledgersuppliersModel->where('supplier_id',$supplier_id)->findAll();
        $data['supplierId'] = $supplier_id;
        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Suppliers/transaction', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }







}