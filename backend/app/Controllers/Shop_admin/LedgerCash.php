<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\LedgerbankModel;
use App\Models\LedgernagodanModel;

class LedgerCash extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $ledgernagodanModel;
    private $module_name = 'Ledger_nagodan';
    public function __construct()
    {
        $this->ledgernagodanModel = new LedgernagodanModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){
        $data['ledgerNagod'] = $this->ledgernagodanModel->where('sch_id',Auth()->sch_id)->orderBy('ledg_nagodan_id','DESC')->findAll();
        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Ledger_cash/index',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function search_date(){
        $st_date = $this->request->getPost('st_date');
        $en_date = $this->request->getPost('en_date');

        $data['ledgerNagod'] = $this->ledgernagodanModel->where('sch_id',Auth()->sch_id)->where('createdDtm >=', $st_date . ' 00:00:00')->where('createdDtm <=', $en_date . ' 23:59:59')->orderBy('ledg_nagodan_id','DESC')->findAll();

        echo view('Shop_admin/Ledger_cash/ledger',$data);

    }







}