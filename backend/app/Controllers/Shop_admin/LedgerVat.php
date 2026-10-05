<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\LedgervatModel;

class LedgerVat extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $ledgervatModel;
    private $module_name = 'Ledger_vat';
    public function __construct()
    {
        $this->ledgervatModel = new LedgervatModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){
        $data['ledgerVat'] = $this->ledgervatModel->where('sch_id',Auth()->sch_id)->orderBy('ledg_vat_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Ledger_vat/index',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }








}