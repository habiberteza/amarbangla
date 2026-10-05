<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Models\BankModel;
use App\Libraries\Permission;
use App\Models\LedgeremployeeModel;

class LedgerEmployee extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $ledgeremployeeModel;
    private $module_name = 'Ledger_employee';
    public function __construct()
    {
        $this->ledgeremployeeModel = new LedgeremployeeModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){
        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Ledger_employee/index');
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function search(){

        $employee_id = $this->request->getPost('employee_id');
        $data['ledgerEmployee'] = $this->ledgeremployeeModel->where('employee_id',$employee_id)->orderBy('ledg_emp_id','DESC')->findAll();

        echo view('Shop_admin/Ledger_employee/ledger',$data);

    }
    public function search_date(){

        $employee_id = $this->request->getPost('employeeId');
        $st_date = $this->request->getPost('st_date');
        $en_date = $this->request->getPost('en_date');

        $data['ledgerEmployee'] = $this->ledgeremployeeModel->where('employee_id',$employee_id)->where('createdDtm >=', $st_date . ' 00:00:00')->where('createdDtm <=', $en_date . ' 23:59:59')->orderBy('ledg_emp_id','DESC')->findAll();

        echo view('Shop_admin/Ledger_employee/ledger',$data);

    }







}