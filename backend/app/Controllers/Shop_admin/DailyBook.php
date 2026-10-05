<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Models\BankModel;
use App\Libraries\Permission;
use App\Models\LedgerbankModel;
use App\Models\LedgernagodanModel;

class DailyBook extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $ledgernagodanModel;
    protected $ledgerbankModel;
    private $module_name = 'Daily_book';
    public function __construct()
    {
        $this->ledgernagodanModel = new LedgernagodanModel();
        $this->ledgerbankModel = new LedgerbankModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){

        $data['cashLedger'] = $this->ledgernagodanModel->where('sch_id',Auth()->sch_id)->like('createdDtm', date('Y-m-d'))->orderBy("createdDtm", "DESC")->findAll();
        $rest_balance = $this->ledgernagodanModel->like('createdDtm', date('Y-m-d'))->where("sch_id", Auth()->sch_id)->orderBy("createdDtm", "DESC")->first();
        $data['cashrest_balance'] = empty($rest_balance) ? 0 : $rest_balance->rest_balance;

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Daily_book/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function search_bank_ledger(){

        $bankId = $this->request->getPost('id');
        $date = $this->request->getPost('date');
        $searchDate = empty($date) ? date('Y-m-d') : $date;


        $bankRest = $this->ledgerbankModel->like('createdDtm', $searchDate)->where("bank_id", $bankId)->orderBy("createdDtm", "DESC")->first();
        $data['restBalance'] = empty($bankRest) ? 0 : $bankRest->rest_balance;

        $data['bankLedger'] = $this->ledgerbankModel->like('createdDtm', $searchDate)->where("bank_id", $bankId)->orderBy("createdDtm", "DESC")->findAll();

        echo view('Shop_admin/Daily_book/bank_ledger', $data);

    }
    public function search(){
        $date = $this->request->getPost('date');
        $rest = $this->ledgernagodanModel->like('createdDtm', $date)->where("sch_id", Auth()->sch_id)->orderBy("createdDtm", "DESC")->first();
        $data['cashrest_balance'] = empty($rest) ? 0 : $rest->rest_balance;

        $data['cashLedger'] = $this->ledgernagodanModel->like('createdDtm', $date)->where("sch_id", Auth()->sch_id)->orderBy("createdDtm", "DESC")->findAll();
        $data['date'] = $date;

        echo view('Shop_admin/Daily_book/index', $data);
    }









}