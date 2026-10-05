<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\ProductsModel;

class StockReport extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $productsModel;
    private $module_name = 'Stock_report';
    public function __construct()
    {
        $this->productsModel = new ProductsModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){
        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Stock_report/index');
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function search(){

        $storeId = $this->request->getPost('storeId');
        $shopId = Auth()->sch_id;

        $data['product'] = $this->productsModel->where('store_id', $storeId)->where('sch_id', $shopId)->where('purchase_type', '0')->orderBy('prod_id', "DESC")->findAll();


        $data['quantity'] = $this->productsModel->selectSum("quantity")->where('store_id', $storeId)->where('purchase_type', '0')->where('sch_id', $shopId)->orderBy('prod_id', "DESC")->first()->quantity;
        $data['purchasePrice'] = 0;
        $purQu = $this->productsModel->where('store_id', $storeId)->where('sch_id', $shopId)->where('purchase_type', '0')->orderBy('prod_id', "DESC")->findAll();
        foreach ($purQu as $pur) {
            $data['purchasePrice'] += $pur->quantity * $pur->purchase_price;
        }
        $data['name'] = get_data_by_id('name', 'stores', 'store_id', $storeId);

        echo view('Shop_admin/Stock_report/ledger',$data);

    }








}