<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Models\BankModel;
use App\Libraries\Permission;
use App\Models\BankwithdrawModel;
use App\Models\ChaqueModel;
use App\Models\CustomersModel;
use App\Models\LedgerbankModel;
use App\Models\LedgerloanModel;
use App\Models\LedgerModel;
use App\Models\LedgernagodanModel;
use App\Models\LoanproviderModel;
use App\Models\ShopsModel;

class Chaque extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $customersModel;
    protected $chaqueModel;
    protected $shopsModel;
    protected $ledgernagodanModel;
    protected $ledgerModel;
    protected $loanproviderModel;
    protected $ledgerloanModel;
    private $module_name = 'Chaque';
    public function __construct()
    {
        $this->chaqueModel = new ChaqueModel();
        $this->customersModel = new CustomersModel();
        $this->ledgerModel = new LedgerModel();
        $this->loanproviderModel = new LoanproviderModel();
        $this->ledgerloanModel = new LedgerloanModel();
        $this->shopsModel = new ShopsModel();
        $this->ledgernagodanModel = new LedgernagodanModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){
        $data['chaque'] = $this->chaqueModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('chaque_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Chaque/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }

    }

    public function paid($chaque_id){

        $chaqueRow = $this->chaqueModel->where('chaque_id',$chaque_id)->first();

        $customerId = $chaqueRow->customer_id;
        $loneProviderId = $chaqueRow->from_loan_provider;

        //shops cash calculet(start)
        $chaqueBalance = $chaqueRow->amount;
        $shopBalance = Auth()->cash;
        $totalBalance = $shopBalance + $chaqueBalance;
        //shops cash calculet(end)

        DB()->transStart();
        if ($customerId > 0) {
            //chaque amount calculet and update customer Balance (start)
            $customerBalance = get_data_by_id('balance', 'customers', 'customer_id', $customerId);
            $customerBalanceUpdate = $customerBalance - $chaqueBalance;
            $cusUpData['customer_id'] = $customerId;
            $cusUpData['balance'] = $customerBalanceUpdate;
            $cusUpData['updatedBy'] = Auth()->user_id;
            $this->customersModel->update($cusUpData['customer_id'],$cusUpData);
            //chaque amount calculet and update customer Balance (end)


            //chaque amount insert in ledger table (start)
            $lgCusData['sch_id'] = Auth()->sch_id;
            $lgCusData['chaque_id'] = $chaque_id;
            $lgCusData['customer_id'] = $customerId;
            $lgCusData['trangaction_type'] = 'Dr.';
            $lgCusData['particulars'] = 'Chaque Cash Approved';
            $lgCusData['amount'] = $chaqueBalance;
            $lgCusData['rest_balance'] = $customerBalanceUpdate;
            $lgCusData['createdDtm'] = date('Y-m-d h:i:s');
            $this->ledgerModel->insert($lgCusData);
            //chaque amount insert in ledger table (end)

        }

        if ($loneProviderId > 0) {

            //update loneprovider balance in loan_provider table(start)
            $loneProviderBalance = get_data_by_id('balance', 'loan_provider', 'loan_pro_id', $loneProviderId);
            $loneProviderBalanceUpdate = $loneProviderBalance - $chaqueBalance;

            $loneProUpData['loan_pro_id'] = $loneProviderId;
            $loneProUpData['balance'] = $loneProviderBalanceUpdate;
            $loneProUpData['updatedBy'] = Auth()->user_id;
            $this->loanproviderModel->update($loneProUpData['loan_pro_id'],$loneProUpData);
            //update loneprovider balance in loan_provider table(end)


            //chaque amount insert in ledger_loan table (start)
            $lgloneProData['sch_id'] = Auth()->sch_id;
            $lgloneProData['chaque_id'] = $chaque_id;
            $lgloneProData['loan_pro_id'] = $loneProviderId;
            $lgloneProData['trangaction_type'] = 'Dr.';
            $lgloneProData['particulars'] = 'Chaque Cash Approved';
            $lgloneProData['amount'] = $chaqueBalance;
            $lgloneProData['rest_balance'] = $loneProviderBalanceUpdate;
            $lgloneProData['createdDtm'] = date('Y-m-d h:i:s');
            $this->ledgerloanModel->insert($lgloneProData);
            //chaque amount insert in ledger_loan table (end)
        }


        //chaque amount calculet and shops balance update (start)
        $shopBalUpData['sch_id'] = Auth()->sch_id;
        $shopBalUpData['cash'] = $totalBalance;
        $shopBalUpData['updatedBy'] = Auth()->user_id;
        $this->shopsModel->update($shopBalUpData['sch_id'],$shopBalUpData);
        //chaque amount calculet and shops balance update (end)


        //chaque amount insert in ledger_nagodan table (start)
        $lgNagData['sch_id'] = Auth()->sch_id;
        $lgNagData['chaque_id'] = $chaque_id;
        $lgNagData['trangaction_type'] = 'Dr.';
        $lgNagData['particulars'] = 'Chaque Cash Approved';
        $lgNagData['amount'] = $chaqueBalance;
        $lgNagData['rest_balance'] = $totalBalance;
        $lgNagData['createdDtm'] = date('Y-m-d h:i:s');
        $this->ledgernagodanModel->insert($lgNagData);
        //chaque amount insert in ledger_nagodan table (end)


        //update chaque status in chaque table (start)
        $chaqueUpdata['chaque_id'] = $chaque_id;
        $chaqueUpdata['status'] = 'Approved';
        $chaqueUpdata['updatedBy'] = Auth()->user_id;
        $this->chaqueModel->update($chaqueUpdata['chaque_id'],$chaqueUpdata);
        //update chaque status in chaque table (end)
        DB()->transComplete();

        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Add Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('shop_admin/bank_withdraw_create');


    }






}