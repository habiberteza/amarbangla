<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Models\BankModel;
use App\Libraries\Permission;
use App\Models\ProductsModel;
use App\Models\ReturnpurchaseModel;

class ReturnPurchase extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $returnpurchaseModel;
    protected $productsModel;
    private $module_name = 'Return_purchase';
    public function __construct()
    {
        $this->returnpurchaseModel = new ReturnpurchaseModel();
        $this->productsModel = new ProductsModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){


        $data['returnPurchase'] = $this->returnpurchaseModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('rtn_purchase_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Return_purchase/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function create(){

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/Return_purchase/create');
        }else{
            echo view('Shop_admin/no_permission');
        }

    }
    public function return_product(){
        $shopId = Auth()->sch_id;
        $supplierId = $this->request->getPost('supplier_id');

        $data['product'] = $this->productsModel->where('supplier_id',$supplierId)->where('sch_id',$shopId)->where('quantity >',0)->findAll();
        $data['supplierId'] = $supplierId;

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/Return_purchase/return_product',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }

    }
    public function create_action(){

        $data['sch_id'] = Auth()->sch_id;
        $data['name'] = $this->request->getPost('name');
        $data['account_no'] = $this->request->getPost('account_no');
        $data['createdBy'] = Auth()->user_id;
        $data['createdDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'name' => ['label' => 'name', 'rules' => 'required'],
            'account_no' => ['label' => 'Account No', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/bank_create');
        } else {
            $this->bankModel->insert($data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Add Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/bank_create');

        }
    }
    public function update($bank_id){

        $data['bank'] = $this->bankModel->where('bank_id',$bank_id)->first();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Bank/update', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function update_action(){
        $data['bank_id'] = $this->request->getPost('bank_id');
        $data['name'] = $this->request->getPost('name');
        $data['account_no'] = $this->request->getPost('account_no');
        $data['updatedBy'] = Auth()->user_id;
        $data['updatedDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'name' => ['label' => 'name', 'rules' => 'required'],
            'account_no' => ['label' => 'Account No', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/bank_update/'.$data['bank_id']);
        } else {
            $this->bankModel->update($data['bank_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/bank_update/'.$data['bank_id']);

        }
    }
    public function delete($bank_id){
        $data['bank_id'] = $bank_id;
        $data['deleted'] = Auth()->user_id;
        $data['deletedRole'] = Auth()->role_id;

        $this->bankModel->update($data['bank_id'],$data);
        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('shop_admin/bank');
    }







}