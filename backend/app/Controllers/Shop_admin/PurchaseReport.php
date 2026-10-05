<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\PurchaseitemModel;
use App\Models\PurchaseModel;
use App\Models\SuppliersModel;


class PurchaseReport extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $suppliersModel;
    protected $purchaseModel;
    protected $purchaseitemModel;
    private $module_name = 'Purchase_report';
    public function __construct()
    {
        $this->suppliersModel = new SuppliersModel();
        $this->purchaseModel = new PurchaseModel();
        $this->purchaseitemModel = new PurchaseitemModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){

        //all suppliers purchase total amount show (start)
        $suppliers = $this->suppliersModel->where('sch_id', Auth()->sch_id)->findAll();
        //all suppliers purchase total amount show (start)


        //Purchase item show list (start)
        $i = 0;
        $purchase = array();
        $purchaseId = $this->purchaseModel->where('sch_id', Auth()->sch_id)->findAll();
        foreach ($purchaseId as $value) {
            $query = $this->purchaseitemModel->where('purchase_id', $value->purchase_id)->orderBy('purchase_item_id', 'DESC')->findAll(10);
            $purchase[$i] = $query;
            $i++;
        }
        //Purchase item show list (end)


        $data = [ 'suppliers' => $suppliers, 'purchaseItem' => $purchase, ];


        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Purchase_report/index',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function search(){

        $st_date = $this->request->getPost('st_date');
        $en_date = $this->request->getPost('en_date');

        $i = 0;
        $purchaseItem = array();
        $purchaseId = $this->purchaseModel->where('sch_id', Auth()->role_id)->findAll();
        foreach ($purchaseId as $value) {
            $query = $this->purchaseitemModel->where('purchase_id', $value->purchase_id)->where('createdDtm >=', $st_date . ' 00:00:00')->where('createdDtm <=', $en_date . ' 23:59:59')->findAll();
            $purchaseItem[$i] = $query;
            $i++;
        }
        $data['purchaseItem'] = $purchaseItem;
        $data['st_date'] = $st_date;
        $data['en_date'] = $en_date;

        echo view('Shop_admin/Purchase_report/report',$data);

    }







}