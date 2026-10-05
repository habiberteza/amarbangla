<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Models\ProductsModel;
use App\Models\LoanproviderModel;
use App\Models\RolesModel;
use App\Models\SuppliersModel;
use App\Models\BankModel;
use App\Models\PurchaseModel;
use App\Models\PurchaseitemModel;
use App\Models\PackageModel;
use App\Models\ShopsModel;
use App\Libraries\Permission;

class Dashboard extends BaseController
{
    protected $session;
    protected $productModel;
    protected $loanproviderModel;
    protected $suppliersModel;
    protected $bankModel;
    protected $purchaseModel;
    protected $purchaseitemModel;
    protected $packageModel;
    protected $shopsModel;
    protected $rolesModel;
    protected $permission;

    private $module_name = 'Dashboard';
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->productModel = new ProductsModel();
        $this->loanproviderModel = new LoanproviderModel();
        $this->suppliersModel = new SuppliersModel();
        $this->bankModel = new BankModel();
        $this->purchaseModel = new PurchaseModel();
        $this->packageModel = new PackageModel();
        $this->shopsModel = new ShopsModel();
        $this->rolesModel = new RolesModel();
        $this->purchaseitemModel = new PurchaseitemModel();
        $this->permission = new permission();
    }


    public function index()
    {
        $shopId = $this->session->shopId;
        $roleId = $this->session->role;

        $totalProduct = $this->productModel->where('sch_id', $shopId)->countAllResults();


        $row = $this->shopsModel->where('sch_id', $shopId)->first();

        $loanprovider = $this->loanproviderModel->select('balance')->where('sch_id', $shopId)->findAll();

        // Lone Due balance count (start)
        $totalLoneDue = 0;
        foreach ($loanprovider as $result) {
            if ($result->balance < 0) {
                $totalLoneDue += $result->balance;
            }
        }
        // Lone Due balance count (end)

        //Supplier Balance  total due count (start)
        $supplier = $this->suppliersModel->select('balance')->where('sch_id', $shopId)->findAll();
        $totalsuppDue = 0;
        foreach ($supplier as $result) {
            if ($result->balance < 0) {
                $totalsuppDue += $result->balance;
            }
        }
        //Supplier Balance  total due count (end)

        //Total All Due calculet(start)
        $totalDue = $totalLoneDue + $totalsuppDue;
        //Total All Due calculet(end)

        //total Bank Balance calculet (start)
        $bank = $this->bankModel->selectSum('balance')->where('sch_id', $shopId)->first();
        $totalBankBal = $bank->balance;
        //total Bank Balance calculet (end)

        //purchase table null value delete (start)
        $purchId = $this->purchaseModel->where(['sch_id' => $shopId, 'due' => NULL])->findAll();
        //  var_dump($purchId);
        foreach ($purchId as $value) {
            // purchasa itame fiend count (start)
            $purItem = $this->purchaseitemModel->select('purchase_item_id')->where('purchase_id', $value->purchase_id)->countAllResults();
            // purchasa itame fiend count (end)

            //deleted Nul value in purchase (start)
            if ($purItem < 1) {
                $this->purchaseModel->where('purchase_id', $value->purchase_id)->delete();
            }
            //deleted Nul value in purchase (end)
        }
        //purchase table null value delete (end)

        //Total due amount calculet (start)
        $totalGet = $totalLoneDue;
        //Total due amount calculet (end)

        $invoice = $this->packageModel->where(['sch_id' => $shopId, 'status' => '0'])->first();

        if ($row) {
            $data = array(
                'id' => $row->sch_id,
                'name' => $row->name,
                'address' => $row->address,
                'totalProduct' => $totalProduct,
                'totalDue' => $totalDue,
                'totalGet' => $totalGet,
                'totalBankBal' => $totalBankBal,
                'invoiceCount' => $invoice,
                'opening_status' => $row->opening_status,
            );
        }


        $permi = $this->permission->module_permission($roleId, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Dashboard/dashboard', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }

    public function opening_status()
    {
        $shopId = $this->session->shopId;
        $row = $this->shopsModel->where('sch_id',$shopId)->first();
        if ($row->opening_status == 1) {
            $data = array(
                'opening_status' => '0',
            );
        } else {
            $data = array(
                'opening_status' => '1',
            );
        }
       $this->shopsModel->where('sch_id',$shopId)->update($shopId, $data);

        print '<div class="alert alert-success alert-dismissible" role="alert">Successfully update <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }
}