<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\LedgerbankModel;
use App\Models\LedgersuppliersModel;

class LedgerSuppliers extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $ledgersuppliersModel;
    private $module_name = 'Ledger_suppliers';
    public function __construct()
    {
        $this->ledgersuppliersModel = new LedgersuppliersModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){
        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Ledger_suppliers/index');
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function search(){

        $supplier_id = $this->request->getPost('id');
        $data['ledgerSupplier'] = $this->ledgersuppliersModel->where('supplier_id',$supplier_id)->orderBy('ledg_sup_id','DESC')->findAll();
        $data['supplier_id'] = $supplier_id;

        echo view('Shop_admin/Ledger_suppliers/ledger',$data);

    }
    public function search_date(){
        $supplier_id = $this->request->getPost('supplierId');
        $st_date = $this->request->getPost('st_date');
        $en_date = $this->request->getPost('en_date');
        $data['supplier_id'] = $supplier_id;

        $data['ledgerSupplier'] = $this->ledgersuppliersModel->where('supplier_id',$supplier_id)->where('createdDtm >=', $st_date . ' 00:00:00')->where('createdDtm <=', $en_date . ' 23:59:59')->orderBy('ledg_sup_id','DESC')->findAll();

        echo view('Shop_admin/Ledger_suppliers/ledger',$data);

    }







}