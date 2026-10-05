<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Models\BankdepositModel;
use App\Models\BankModel;
use App\Libraries\Permission;
use App\Models\LedgerbankModel;
use App\Models\LedgernagodanModel;
use App\Models\ShopsModel;

class BankDeposit extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $bankModel;
    protected $bankdepositModel;
    protected $shopsModel;
    protected $ledgernagodanModel;
    protected $ledgerbankModel;
    private $module_name = 'Bank_deposit';
    public function __construct()
    {
        $this->bankdepositModel = new BankdepositModel();
        $this->bankModel = new BankModel();
        $this->ledgerbankModel = new LedgerbankModel();
        $this->shopsModel = new ShopsModel();
        $this->ledgernagodanModel = new LedgernagodanModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){

        $data['bankDeposit'] = $this->bankdepositModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('bank_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Bank_deposit/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function create(){

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/Bank_deposit/create');
        }else{
            echo view('Shop_admin/no_permission');
        }

    }
    public function create_action(){

        $data['sch_id'] = Auth()->sch_id;
        $data['bank_id'] = $this->request->getPost('bank_id');
        $data['amount'] = $this->request->getPost('amount');
        $data['commont'] = $this->request->getPost('commont');
        $data['createdBy'] = Auth()->user_id;
        $data['createdDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'bank_id' => ['label' => 'Bank', 'rules' => 'required'],
            'amount' => ['label' => 'Amount', 'rules' => 'required'],
            'commont' => ['label' => 'Comment', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/bank_deposit_create');
        } else {
            if (Auth()->cash >= $data['amount']) {

                if ($data['amount'] > 0) {
//                    DB()->transStart();
                        //insert deposit amount in deposit table (start)
                        $this->bankdepositModel->insert($data);
                        //insert deposit amount in deposit table (end)

                        //shops deduct balance
                        $shopUpBalance = Auth()->cash - $data['amount'];
                        $shopData['sch_id'] = Auth()->sch_id;
                        $shopData['cash'] = $shopUpBalance;
                        $this->shopsModel->update($shopData['sch_id'], $shopData);

                        //insert ledger_nagodan
                        $lgNagData['sch_id'] = Auth()->sch_id;
                        $lgNagData['bank_id'] = $data['bank_id'];
                        $lgNagData['trangaction_type'] = 'Cr.';
                        $lgNagData['particulars'] = 'Bank Cash Deposit';
                        $lgNagData['amount'] = $data['amount'];
                        $lgNagData['rest_balance'] = $shopUpBalance;
                        $lgNagData['createdBy'] = Auth()->user_id;
                        $lgNagData['createdDtm'] = date('Y-m-d h:i:s');
                        $this->ledgernagodanModel->insert($lgNagData);

                        //bank In balance
                        $bankBalance = get_data_by_id('balance', 'bank', 'bank_id', $data['bank_id']);
                        $bankUpBalance = $bankBalance + $data['amount'];
                        $bankdata['bank_id'] = $data['bank_id'];
                        $bankdata['balance'] = $bankUpBalance;
                        $this->bankModel->update($bankdata['bank_id'],$bankdata);

                        //insert ledger_bank
                        $ledBankData['sch_id'] = Auth()->sch_id;
                        $ledBankData['bank_id'] = $data['bank_id'];
                        $ledBankData['amount'] = $data['amount'];
                        $ledBankData['particulars'] = 'Bank Cash Deposit';
                        $ledBankData['trangaction_type'] = 'Dr.';
                        $ledBankData['rest_balance'] = $bankUpBalance;
                        $ledBankData['createdBy'] = Auth()->user_id;
                        $ledBankData['createdDtm'] = date('Y-m-d h:i:s');
                        $this->ledgerbankModel->insert($ledBankData);
                    DB()->transComplete();
                    $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Add Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    return redirect()->to('shop_admin/bank_deposit_create');
                }else {
                    $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Invalid Amount <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    return redirect()->to('shop_admin/bank_deposit_create');
                }




            }else {
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Shop balance is too low for this Deposit <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/bank_deposit_create');
            }

        }
    }






}