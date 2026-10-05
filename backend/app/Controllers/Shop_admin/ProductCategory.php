<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\ProductcategoryModel;

class ProductCategory extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $crop;
    protected $productcategoryModel;
    private $module_name = 'Product_category';
    public function __construct()
    {
        $this->productcategoryModel = new ProductcategoryModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->crop = \Config\Services::image();
        $this->permission = new permission();
    }

    public function index(){


        $data['category'] = $this->productcategoryModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('prod_cat_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/ProductCategory/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function create(){


        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/ProductCategory/create');
        }else{
            echo view('Shop_admin/no_permission');
        }

    }
    public function create_action(){

        $data['sch_id'] = Auth()->sch_id;
        $data['product_category'] = $this->request->getPost('product_category');
        $data['parent_pro_cat'] = $this->request->getPost('parent_pro_cat');
        $data['status'] = $this->request->getPost('status');
        $data['createdBy'] = Auth()->user_id;
        $data['createdDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'product_category' => ['label' => 'product category', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/product_category_create');
        } else {

            $this->productcategoryModel->insert($data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Add Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/product_category_create');

        }
    }
    public function update($prod_cat_id){

        $data['cat'] = $this->productcategoryModel->where('prod_cat_id',$prod_cat_id)->first();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/ProductCategory/update', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function update_action(){
        $data['prod_cat_id'] = $this->request->getPost('prod_cat_id');
        $data['product_category'] = $this->request->getPost('product_category');
        $data['parent_pro_cat'] = $this->request->getPost('parent_pro_cat');
        $data['status'] = $this->request->getPost('status');
        $data['updatedBy'] = Auth()->user_id;
        $data['updatedDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'product_category' => ['label' => 'product category', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/product_category_update/'.$data['prod_cat_id']);
        } else {

            $this->productcategoryModel->update($data['prod_cat_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/product_category_update/'.$data['prod_cat_id']);

        }
    }
    public function delete($prod_cat_id){
        $data['prod_cat_id'] = $prod_cat_id;
        $data['deleted'] = Auth()->user_id;
        $data['deletedRole'] = Auth()->role_id;

        $this->productcategoryModel->update($data['prod_cat_id'],$data);
        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('shop_admin/product_category');
    }







}