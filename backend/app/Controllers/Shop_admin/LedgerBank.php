<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\LedgerbankModel;

class LedgerBank extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $ledgerbankModel;
    private $module_name = 'Ledger_bank';
    public function __construct()
    {
        $this->ledgerbankModel = new LedgerbankModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){
        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Ledger_bank/index');
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function search(){

        $bank_id = $this->request->getPost('bank_id');
        $data['ledgerBank'] = $this->ledgerbankModel->where('bank_id',$bank_id)->orderBy('ledgBank_id','DESC')->findAll();
        $data['bank_id'] = $bank_id;

        echo view('Shop_admin/Ledger_bank/ledger',$data);

    }
    public function search_date(){
        $bank_id = $this->request->getPost('bankId');
        $st_date = $this->request->getPost('st_date');
        $en_date = $this->request->getPost('en_date');
        $data['bank_id'] = $bank_id;

        $data['ledgerBank'] = $this->ledgerbankModel->where('bank_id',$bank_id)->where('createdDtm >=', $st_date . ' 00:00:00')->where('createdDtm <=', $en_date . ' 23:59:59')->orderBy('ledgBank_id','DESC')->findAll();

        echo view('Shop_admin/Ledger_bank/ledger',$data);

    }







}