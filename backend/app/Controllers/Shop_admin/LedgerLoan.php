<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\LedgerloanModel;

class LedgerLoan extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $ledgerloanModel;
    private $module_name = 'Ledger_loan';
    public function __construct()
    {
        $this->ledgerloanModel = new LedgerloanModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){
        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Ledger_loan/index');
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function search(){

        $loan_pro_id = $this->request->getPost('id');
        $data['ledgerLoan'] = $this->ledgerloanModel->where('loan_pro_id',$loan_pro_id)->orderBy('ledg_loan_id','DESC')->findAll();
        $data['loan_provider'] = $loan_pro_id;

        echo view('Shop_admin/Ledger_loan/ledger',$data);

    }
    public function search_date(){
        $loan_pro_id = $this->request->getPost('loanProId');
        $st_date = $this->request->getPost('st_date');
        $en_date = $this->request->getPost('en_date');
        $data['loan_provider'] = $loan_pro_id;

        $data['ledgerLoan'] = $this->ledgerloanModel->where('loan_pro_id',$loan_pro_id)->where('createdDtm >=', $st_date . ' 00:00:00')->where('createdDtm <=', $en_date . ' 23:59:59')->orderBy('ledg_loan_id','DESC')->findAll();

        echo view('Shop_admin/Ledger_loan/ledger',$data);

    }







}