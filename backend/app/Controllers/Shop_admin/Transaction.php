<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\BankModel;
use App\Models\ChaqueModel;
use App\Models\EmployeeModel;
use App\Models\LedgerbankModel;
use App\Models\LedgeremployeeModel;
use App\Models\LedgerexpenseModel;
use App\Models\LedgerloanModel;
use App\Models\LedgernagodanModel;
use App\Models\LedgerothersalesModel;
use App\Models\LedgersuppliersModel;
use App\Models\LedgervatModel;
use App\Models\LoanproviderModel;
use App\Models\ShopsModel;
use App\Models\SupcomitrnsModel;
use App\Models\SuppliersModel;
use App\Models\TransactionModel;
use App\Models\VatregisterModel;

class Transaction extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $transactionModel;
    protected $supcomitrnsModel;
    protected $ledgersuppliersModel;
    protected $suppliersModel;
    protected $shopsModel;
    protected $ledgernagodanModel;
    protected $bankModel;
    protected $chaqueModel;
    protected $ledgerbankModel;
    protected $ledgerloanModel;
    protected $loanproviderModel;
    protected $ledgerexpenseModel;
    protected $ledgerothersalesModel;
    protected $employeeModel;
    protected $ledgeremployeeModel;
    protected $ledgervatModel;
    protected $vatregisterModel;
    private $module_name = 'Transaction';
    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->supcomitrnsModel = new SupcomitrnsModel();
        $this->ledgersuppliersModel = new LedgersuppliersModel();
        $this->suppliersModel = new SuppliersModel();
        $this->ledgernagodanModel = new LedgernagodanModel();
        $this->shopsModel = new ShopsModel();
        $this->bankModel = new BankModel();
        $this->ledgerbankModel = new LedgerbankModel();
        $this->chaqueModel = new ChaqueModel();
        $this->ledgerloanModel = new LedgerloanModel();
        $this->loanproviderModel = new LoanproviderModel();
        $this->ledgerexpenseModel = new LedgerexpenseModel();
        $this->ledgerothersalesModel = new LedgerothersalesModel();
        $this->employeeModel = new EmployeeModel();
        $this->ledgeremployeeModel = new LedgeremployeeModel();
        $this->ledgervatModel = new LedgervatModel();
        $this->vatregisterModel = new VatregisterModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){


        $data['transaction_data'] = $this->transactionModel->where('sch_id',Auth()->sch_id)->findAll();
        $data['supComiTrns'] = $this->supcomitrnsModel->where('sch_id',Auth()->sch_id)->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Transaction/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function create(){

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/Transaction/create');
        }else{
            echo view('Shop_admin/no_permission');
        }

    }
    public function create_action(){

        $data['sch_id'] = Auth()->sch_id;
        $data['name'] = $this->request->getPost('name');
        $data['account_no'] = $this->request->getPost('account_no');
        $data['createdBy'] = Auth()->user_id;
        $data['createdDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'name' => ['label' => 'name', 'rules' => 'required'],
            'account_no' => ['label' => 'Account No', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/bank_create');
        } else {
            $this->bankModel->insert($data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Add Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/bank_create');

        }
    }

    public function supplierTransaction(){
        $suppId = $this->request->getPost('suppId');

        $data = $this->ledgersuppliersModel->where('supplier_id', $suppId)->orderBy('ledg_sup_id','DESC')->findAll(12);

        $suppliersBalance = get_data_by_id('balance', 'suppliers', 'supplier_id', $suppId);

        $view = '<span class="pull-right"> Balance: ' . showWithCurrencySymbol($suppliersBalance) . '</span>';


        $view .= '<table class="table table-bordered table-striped" id="example2" >
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Particulars</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                <tbody>';
        $i = '';
        foreach ($data as $row) {
            $particulars = ($row->particulars == NULL) ? "Purchase" : $row->particulars;
            $amountCr = ($row->trangaction_type != "Cr.") ? "---" : showWithCurrencySymbol($row->amount);
            $amountDr = ($row->trangaction_type != "Dr.") ? "---" : showWithCurrencySymbol($row->amount);
            $transId = ($row->trans_id == NULL) ? "---" : $row->trans_id;
            $purchaseId = ($row->purchase_id == NULL) ? "---" : $row->purchase_id;
            $view .= '<tr>
                        <td>' . ++$i . '</td>
                        <td>' . bdDateFormat($row->createdDtm) . '</td>
                        <td>' . get_data_by_id('name', 'suppliers', 'supplier_id', $row->supplier_id) . '</td>
                        <td>' . $particulars . '</td>
                        <td>' . $amountDr . '</td>
                        <td>' . $amountCr . '</td>
                        <td>' . showWithCurrencySymbol($row->rest_balance) . '</td>
                    </tr>';
        }

        $view .= '</tbody> </table>';


        print $view;
    }
    public function supplier_action(){
        $userId = Auth()->user_id;
        $shopId = Auth()->sch_id;

        $chequeNo = $this->request->getPost('chequeNo');
        $chequeAmount = str_replace(',', '', $this->request->getPost('chequeAmount'));
        $data['amount'] = str_replace(',', '', $this->request->getPost('amount'));
        //Supplier Balance
        $data['supplier_id'] = $this->request->getPost('supplier_id');
        $supplierBalance = get_data_by_id('balance', 'suppliers', 'supplier_id', $data['supplier_id']);
        $restBalance = $supplierBalance + $data['amount'];
        //Payment Type
        $paymentType = $this->request->getPost('payment_type');
        $data['trangaction_type'] = $this->request->getPost('trangaction_type');
        $data['particulars'] = $this->request->getPost('particulars');
        //shop data
        $shopBalance = get_data_by_id('cash', 'shops', 'sch_id', $shopId);
        $shopUpdateBalance = $shopBalance - $data['amount'];

        if ($paymentType == 1) {
            $bankId = $this->request->getPost('bank_id');
            if ($bankId) {
                $bankCash = get_data_by_id('balance', 'bank', 'bank_id', $bankId);
                $bankUpData = $bankCash - $data['amount'];
            }
            $availableBalance = checkBankBalance($bankId, $data['amount']);
        }
        if ($paymentType == 2) {
            $availableBalance = checkNagadBalance($data['amount']);
        }


        $this->validation->setRules([
            'supplier_id' => ['label' => 'Supplier', 'rules' => 'required'],
            'trangaction_type' => ['label' => 'Transaction type', 'rules' => 'required'],
            'amount' => ['label' => 'Amount', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/transaction_create?active=supplier');
        } else {

            if ($availableBalance == true) {

                DB()->transStart();

                if ($data['trangaction_type'] == 1) {
                    //insert Transaction table
                    $transdata['sch_id'] = $shopId;
                    $transdata['supplier_id'] = $data['supplier_id'];
                    $transdata['title'] = $data['particulars'];
                    $transdata['trangaction_type'] = 'Cr.';
                    $transdata['amount'] = $data['amount'];
                    $transdata['createdBy'] = $userId;
                    $transdata['createdDtm'] = date('Y-m-d h:i:s');

                    $this->transactionModel->insert($transdata);
                    $ledgSupId = $this->transactionModel->getInsertID();

                    //insert data
                    $dataLedSup['sch_id'] = $shopId;
                    $dataLedSup['trans_id'] = $ledgSupId;
                    $dataLedSup['supplier_id'] = $data['supplier_id'];
                    $dataLedSup['particulars'] = $data['particulars'];
                    $dataLedSup['trangaction_type'] = 'Cr.';
                    $dataLedSup['amount'] = $data['amount'];
                    $dataLedSup['rest_balance'] = $restBalance;
                    $dataLedSup['createdBy'] = $userId;
                    $this->ledgersuppliersModel->insert($dataLedSup);

                    //Suppliers Balance Update
                    $dataSuppBlan['balance'] = $restBalance;
                    $dataSuppBlan['updatedBy'] = $userId;
                    $this->suppliersModel->update($data['supplier_id'], $dataSuppBlan);

                    //admin transaction
                    if ($paymentType == 2) {
                        //shop balance update
                        $shopData['sch_id'] = $shopId;
                        $shopData['cash'] = $shopUpdateBalance;
                        $shopData['updatedBy'] = $userId;

                        $this->shopsModel->update($shopData['sch_id'], $shopData);

                        //insert ledger_nagodan
                        $lgNagData['sch_id'] = $shopId;
                        $lgNagData['trans_id'] = $ledgSupId;
                        $lgNagData['trangaction_type'] = 'Cr.';
                        $lgNagData['particulars'] = $data['particulars'];
                        $lgNagData['amount'] = $data['amount'];
                        $lgNagData['rest_balance'] = $shopUpdateBalance;
                        $lgNagData['createdBy'] = $userId;
                        $lgNagData['createdDtm'] = date('Y-m-d h:i:s');

                        $this->ledgernagodanModel->insert($lgNagData);

                    } else {

                        $bankData['bank_id'] = $bankId;
                        $bankData['balance'] = $bankUpData;
                        $bankData['updatedBy'] = $userId;
                        $this->bankModel->update($bankData['bank_id'], $bankData);

                        //insert ledger_bank
                        $lgBankData['sch_id'] = $shopId;
                        $lgBankData['bank_id'] = $bankId;
                        $lgBankData['trans_id'] = $ledgSupId;
                        $lgBankData['trangaction_type'] = 'Cr.';
                        $lgBankData['particulars'] = $data['particulars'];
                        $lgBankData['amount'] = $data['amount'];
                        $lgBankData['rest_balance'] = $bankUpData;
                        $lgBankData['createdBy'] = $userId;
                        $lgBankData['createdDtm'] = date('Y-m-d h:i:s');
                        $this->ledgerbankModel->insert($lgBankData);

                    }
                } else {
                    if ($chequeNo > 0) {

                        //cheque pay amount calculate and insert cheque tabile(start)
                        $chequeData['sch_id'] = $shopId;
                        $chequeData['chaque_number'] = $chequeNo;
                        $chequeData['to'] = $userId;
                        $chequeData['from'] = $data['supplier_id'];
                        $chequeData['amount'] = $chequeAmount;
                        $chequeData['createdDtm'] = date('Y-m-d h:i:s');
                        $this->chaqueModel->insert($chequeData);

                        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                        return redirect()->to('shop_admin/transaction_create?active=supplier');
                        //cheque pay amount calculate and insert cheque tabile(end)

                    } else {

                        //insert Transaction table
                        $transdata['sch_id'] = $shopId;
                        $transdata['supplier_id'] = $data['supplier_id'];
                        $transdata['title'] = $data['particulars'];
                        $transdata['trangaction_type'] = 'Dr.';
                        $transdata['amount'] = $data['amount'];
                        $transdata['createdBy'] = $userId;
                        $transdata['createdDtm'] = date('Y-m-d h:i:s');
                        $this->transactionModel->insert($transdata);
                        $ledgSupId2 = $this->transactionModel->getInsertID();

                        //insert data
                        $supplierBalance2 = get_data_by_id('balance', 'suppliers', 'supplier_id', $data['supplier_id']);
                        $restBalance2 = $supplierBalance2 - $data['amount'];

                        $data2['sch_id'] = $shopId;
                        $data2['trans_id'] = $ledgSupId2;
                        $data2['supplier_id'] = $data['supplier_id'];
                        $data2['particulars'] = $data['particulars'];
                        $data2['trangaction_type'] = 'Dr.';
                        $data2['amount'] = $data['amount'];
                        $data2['rest_balance'] = $restBalance2;
                        $data2['createdBy'] = $userId;
                        $this->ledgersuppliersModel->insert($data2);

                        //Suppliers Balance Update(start)
                        $dataSuppBlan2['balance'] = $restBalance2;
                        $dataSuppBlan2['updatedBy'] = $userId;
                        $this->suppliersModel->update($data['supplier_id'], $dataSuppBlan2);
                        //Suppliers Balance Update(start)

                        //admin transaction
                        if ($paymentType == 2) {
                            //shop balance update
                            $shopBalance2 = get_data_by_id('cash', 'shops', 'sch_id', $shopId);
                            $shopUpdateBalance2 = $shopBalance2 + $data['amount'];

                            $shopData2['sch_id'] = $shopId;
                            $shopData2['cash'] = $shopUpdateBalance2;
                            $shopData2['updatedBy'] = $userId;

                            $this->shopsModel->update($shopData2['sch_id'], $shopData2);

                            //insert ledger_nagodan
                            $lgNagData2['sch_id'] = $shopId;
                            $lgNagData2['trans_id'] = $ledgSupId2;
                            $lgNagData2['trangaction_type'] = 'Dr.';
                            $lgNagData2['particulars'] = $data['particulars'];
                            $lgNagData2['amount'] = $data['amount'];
                            $lgNagData2['rest_balance'] = $shopUpdateBalance2;
                            $lgNagData2['createdBy'] = $userId;
                            $this->ledgernagodanModel->insert($lgNagData2);

                        } else {
                            if ($bankId) {
                                $bankCash2 = get_data_by_id('balance', 'bank', 'bank_id', $bankId);
                                $bankUpData2 = $bankCash2 + $data['amount'];
                            }


                            $bankData2['bank_id'] = $bankId;
                            $bankData2['balance'] = $bankUpData2;
                            $bankData2['updatedBy'] = $userId;
                            $this->bankModel->update($bankData2['bank_id'], $bankData2);


                            //insert ledger_bank
                            $lgBankData2['sch_id'] = $shopId;
                            $lgBankData2['bank_id'] = $bankId;
                            $lgBankData2['trans_id'] = $ledgSupId2;
                            $lgBankData2['trangaction_type'] = 'Dr.';
                            $lgBankData2['particulars'] = $data['particulars'];
                            $lgBankData2['amount'] = $data['amount'];
                            $lgBankData2['rest_balance'] = $bankUpData2;
                            $lgBankData2['createdBy'] = $userId;
                            $lgBankData2['createdDtm'] = date('Y-m-d h:i:s');

                            $this->ledgerbankModel->insert($lgBankData2);

                        }

                    }
                }

                DB()->transComplete();

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/transaction_create?active=supplier');

            } else {

                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Not Enough Balance <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/transaction_create?active=supplier');
            }
        }
    }

    public function account_holder_transaction(){
        $lonProvId = $this->request->getPost('lonProvId');


        $data = $this->ledgerloanModel->where('loan_pro_id', $lonProvId)->orderBy('ledg_loan_id', 'DESC')->findAll(10);

        $loanProBalance = get_data_by_id('balance', 'loan_provider', 'loan_pro_id', $lonProvId);

        $view = '<span class="pull-right"> Balance: ' . showWithCurrencySymbol($loanProBalance) . '</span>';
        $view .= '<table class="table table-bordered table-striped text-capitalize" >
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>';
        $i = '';
        foreach ($data as $row) {
            $particulars = ($row->particulars == NULL) ? "Loan" : '<a href="' . site_url('transaction/read/' . $row->trans_id) . '">' . $row->particulars . '</a>';
            $loanProId = get_data_by_id('name', 'loan_provider', "loan_pro_id", $row->loan_pro_id);
            $amountCr = ($row->trangaction_type != "Cr.") ? "---" : showWithCurrencySymbol($row->amount);
            $amountDr = ($row->trangaction_type != "Dr.") ? "---" : showWithCurrencySymbol($row->amount);
            $view .= '<tr>
                                    <td>' . bdDateFormat($row->createdDtm) . '</td>
                                    <td>' . $particulars . '</td>
                                    <td>' . $amountDr . '</td>
                                    <td>' . $amountCr . '</td>
                                    <td>' . showWithCurrencySymbol($row->rest_balance) . '</td>
                                </tr>';
        }

        $view .= '</tbody></table>';


        print $view;
    }

    public function account_holder_action(){
        $userId = Auth()->user_id;
        $shopId = Auth()->sch_id;
        $data['amount'] = str_replace(',', '', $this->request->getPost('amount'));
        $data['loan_pro_id'] = $this->request->getPost('loan_pro_id');
        //loan_pro Balance
        $loanProBalance = get_data_by_id('balance', 'loan_provider', 'loan_pro_id', $data['loan_pro_id']);
        $restBalance = $loanProBalance + $data['amount'];
        $restBalanceDr = $loanProBalance - $data['amount'];
        //Payment Type
        $paymentType = $this->request->getPost('payment_type');
        //shop data
        $shopBalance = get_data_by_id('cash', 'shops', 'sch_id', $shopId);
        $shopUpdateBalance = $shopBalance - $data['amount'];
        $shopUpdateBalanceCr = $shopBalance + $data['amount'];

        $data['trangaction_type'] = $this->request->getPost('trangaction_type');

        $chequeNo = $this->request->getPost('chequeNo');
        $chequeAmount = $this->request->getPost('chequeAmount');
        $bankId = $this->request->getPost('bank_id');
        if ($paymentType == 1) {

            if ($bankId) {
                $bankCash = get_data_by_id('balance', 'bank', 'bank_id', $bankId);
                $bankUpData = $bankCash - $data['amount'];
                $bankUpDataCr = $bankCash + $data['amount'];
            }
            $availableBalance = checkBankBalance($bankId, $data['amount']);
        }
        if ($paymentType == 2) {
            $availableBalance = checkNagadBalance($data['amount']);
        }

        $this->validation->setRules([
            'loan_pro_id' => ['label' => 'Account Holder', 'rules' => 'required'],
            'trangaction_type' => ['label' => 'Transaction type', 'rules' => 'required'],
            'amount' => ['label' => 'Amount', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/transaction_create?active=account');
        } else {

            if ($chequeNo > 0) {

                //cheque pay amount calculate and insert cheque tabile(start)
                $chequeData['sch_id'] = $shopId;
                $chequeData['chaque_number'] = $chequeNo;
                $chequeData['to'] = $userId;
                $chequeData['from_loan_provider'] = $data['loan_pro_id'];
                $chequeData['amount'] = $chequeAmount;
                $chequeData['createdDtm'] = date('Y-m-d h:i:s');

                $this->chaqueModel->insert($chequeData);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/transaction_create?active=account');
                //cheque pay amount calculate and insert cheque tabile(end)

            } else {


                //Cr.
                if ($data['trangaction_type'] == 2) {
                    DB()->transStart();
                    //insert Transaction table
                    $transdata['sch_id'] = $shopId;
                    $transdata['loan_pro_id'] = $data['loan_pro_id'];
                    $transdata['title'] = $this->request->getPost('particulars');
                    $transdata['trangaction_type'] = 'Dr.';
                    $transdata['amount'] = $data['amount'];
                    $transdata['createdBy'] = $userId;
                    $transdata['createdDtm'] = date('Y-m-d h:i:s');

                    $this->transactionModel->insert($transdata);
                    $transId = $this->transactionModel->getInsertID();

                    //insert data
                    $dataLedLon['sch_id'] = $shopId;
                    $dataLedLon['trans_id'] = $transId;
                    $dataLedLon['loan_pro_id'] = $data['loan_pro_id'];
                    $dataLedLon['particulars'] = $this->request->getPost('particulars');
                    $dataLedLon['trangaction_type'] = 'Dr.';
                    $dataLedLon['amount'] = $data['amount'];
                    $dataLedLon['rest_balance'] = $restBalanceDr;
                    $dataLedLon['createdBy'] = $userId;
                    $dataLedLon['createdDtm'] = date('Y-m-d h:i:s');
                    $this->ledgerloanModel->insert($dataLedLon);

                    //loan_provider Balance Update
                    $dataLonProBlan['balance'] = $restBalanceDr;
                    $dataLonProBlan['updatedBy'] = $userId;
                    $this->loanproviderModel->update($data['loan_pro_id'], $dataLonProBlan);


                    //admin transaction
                    if ($paymentType == 2) {
                        //shop balance update
                        $shopData['sch_id'] = $shopId;
                        $shopData['cash'] = $shopUpdateBalanceCr;
                        $shopData['updatedBy'] = $userId;
                        $this->shopsModel->update($shopData['sch_id'], $shopData);


                        //insert ledger_nagodan
                        $lgNagData['sch_id'] = $shopId;
                        $lgNagData['trans_id'] = $transId;
                        $lgNagData['trangaction_type'] = 'Dr.';
                        $lgNagData['particulars'] = $this->request->getPost('particulars');
                        $lgNagData['amount'] = $data['amount'];
                        $lgNagData['rest_balance'] = $shopUpdateBalanceCr;
                        $lgNagData['createdBy'] = $userId;
                        $lgNagData['createdDtm'] = date('Y-m-d h:i:s');
                        $this->ledgernagodanModel->insert($lgNagData);

                    } else {

                        $bankData['bank_id'] = $bankId;
                        $bankData['balance'] = $bankUpDataCr;
                        $bankData['updatedBy'] = $userId;
                        $this->bankModel->update($bankData['bank_id'], $bankData);

                        //insert ledger_bank
                        $lgBankData['sch_id'] = $shopId;
                        $lgBankData['bank_id'] = $bankId;
                        $lgBankData['trans_id'] = $transId;
                        $lgBankData['trangaction_type'] = 'Dr.';
                        $lgBankData['particulars'] = $this->request->getPost('particulars');
                        $lgBankData['amount'] = $data['amount'];
                        $lgBankData['rest_balance'] = $bankUpDataCr;
                        $lgBankData['createdBy'] = $userId;
                        $lgBankData['createdDtm'] = date('Y-m-d h:i:s');
                        $this->ledgerbankModel->insert($lgBankData);

                    }
                    DB()->transComplete();


                    $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    return redirect()->to('shop_admin/transaction_create?active=account');
                }
            }
            //Dr.
            if ($data['trangaction_type'] == 1) {

                if ($availableBalance == true) {
                    DB()->transStart();
                    //insert Transaction table
                    $transdata['sch_id'] = $shopId;
                    $transdata['loan_pro_id'] = $data['loan_pro_id'];
                    $transdata['title'] = $this->request->getPost('particulars');
                    $transdata['trangaction_type'] = 'Cr.';
                    $transdata['amount'] = $data['amount'];
                    $transdata['createdBy'] = $userId;
                    $transdata['createdDtm'] = date('Y-m-d h:i:s');

                    $this->transactionModel->insert($transdata);
                    $transId = $this->transactionModel->getInsertID();

                    //insert data
                    $dataLeLo['sch_id'] = $shopId;
                    $dataLeLo['trans_id'] = $transId;
                    $dataLeLo['loan_pro_id'] = $data['loan_pro_id'];
                    $dataLeLo['particulars'] = $this->request->getPost('particulars');
                    $dataLeLo['trangaction_type'] = 'Cr.';
                    $dataLeLo['amount'] = $data['amount'];
                    $dataLeLo['rest_balance'] = $restBalance;
                    $dataLeLo['createdBy'] = $userId;
                    $dataLeLo['createdDtm'] = date('Y-m-d h:i:s');
                    $this->ledgerloanModel->insert($dataLeLo);

                    //loan_provider Balance Update
                    $dataLonProBlan['loan_pro_id'] = $data['loan_pro_id'];
                    $dataLonProBlan['balance'] = $restBalance;
                    $dataLonProBlan['updatedBy'] = $userId;
                    $this->loanproviderModel->update($dataLonProBlan['loan_pro_id'], $dataLonProBlan);

                    //admin transaction
                    if ($paymentType == 2) {
                        //shop balance update
                        $shopData['sch_id'] = $shopId;
                        $shopData['cash'] = $shopUpdateBalance;
                        $shopData['updatedBy'] = $userId;
                        $this->shopsModel->update($shopData['sch_id'], $shopData);

                        //insert ledger_nagodan
                        $lgNagData['sch_id'] = $shopId;
                        $lgNagData['trans_id'] = $transId;
                        $lgNagData['particulars'] = $this->request->getPost('particulars');
                        $lgNagData['trangaction_type'] = 'Cr.';
                        $lgNagData['amount'] = $data['amount'];
                        $lgNagData['rest_balance'] = $shopUpdateBalance;
                        $lgNagData['createdBy'] = $userId;
                        $lgNagData['createdDtm'] = date('Y-m-d h:i:s');
                        $this->ledgernagodanModel->insert($lgNagData);

                    } else {

                        $bankData['bank_id'] = $bankId;
                        $bankData['balance'] = $bankUpData;
                        $bankData['updatedBy'] = $userId;
                        $this->bankModel->update($bankData['bank_id'], $bankData);

                        //insert ledger_bank
                        $lgBankData['sch_id'] = $shopId;
                        $lgBankData['bank_id'] = $bankId;
                        $lgBankData['trans_id'] = $transId;
                        $lgBankData['trangaction_type'] = 'Cr.';
                        $lgBankData['particulars'] = $this->request->getPost('particulars');
                        $lgBankData['amount'] = $data['amount'];
                        $lgBankData['rest_balance'] = $bankUpData;
                        $lgBankData['createdBy'] = $userId;
                        $lgBankData['createdDtm'] = date('Y-m-d h:i:s');
                        $this->ledgerbankModel->insert($lgBankData);

                    }
                    DB()->transComplete();

                    $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    return redirect()->to('shop_admin/transaction_create?active=account');

                } else {


                    $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Not Enough Balance <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    return redirect()->to('shop_admin/transaction_create?active=account');
                }
            }
        }
    }

    public function bank_transaction(){
        $bankId = $this->request->getPost('bankId');

        $data = $this->ledgerbankModel->where('bank_id', $bankId)->orderBy('ledgBank_id', 'DESC')->findAll(10);

        $view = '<table class="table table-bordered table-striped" >
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th>Bank</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>';
        $i = '';
        foreach ($data as $row) {
            $particulars = ($row->particulars == NULL) ? "Transaction" : $row->particulars;
            $bankId = get_data_by_id('name', 'bank', "bank_id", $row->bank_id);
            $amountCr = ($row->trangaction_type != "Cr.") ? "---" : $row->amount;
            $amountDr = ($row->trangaction_type != "Dr.") ? "---" : $row->amount;
            $view .= '<tr>
                                    <td>' . ++$i . '</td>
                                    <td>' . bdDateFormat($row->createdDtm) . '</td>
                                    <td>' . $particulars . '</td>
                                    <td>' . $bankId . '</td>
                                    <td>' . $amountDr . '</td>
                                    <td>' . $amountCr . '</td>
                                    <td>' . $row->rest_balance . '</td>
                                </tr>';
        }

        $view .= '</tbody></table>';

        print $view;
    }

    public function bank_balance(){
        $amount = $this->request->getPost('balance');
        $bankId = $this->request->getPost('bank_id');

        $bankBalance = get_data_by_id('balance', 'bank', 'bank_id', $bankId);
        if ($amount > $bankBalance) {
            print '<span style="color:red">Balance is too low</span>';
        } else {
            print '<span style="color:green">Balance is ok</span>';
        }
    }

    public function bank_action(){
        $userId = Auth()->user_id;
        $shopId = Auth()->sch_id;

        $data['bank_id'] = $this->request->getPost('bank_id');
        $data['bank_id2'] = $this->request->getPost('bank_id2');
        $particulars = $this->request->getPost('particulars');
        $data['amount'] = $this->request->getPost('amount');

        $bankBalance = get_data_by_id('balance', 'bank', 'bank_id', $data['bank_id']);
        $firstBankBalance = $bankBalance - $data['amount'];

        $this->validation->setRules([
            'bank_id' => ['label' => 'bank', 'rules' => 'required'],
            'bank_id2' => ['label' => 'bank 2', 'rules' => 'required'],
            'amount' => ['label' => 'Amount', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/transaction_create?active=fund');
        } else {

            if ($bankBalance > $data['amount']) {

                DB()->transStart();
                //insert Transaction table
                $transdata['sch_id'] = $shopId;
                $transdata['title'] = 'Withdraw';
                $transdata['bank_id'] = $data['bank_id'];
                $transdata['trangaction_type'] = 'Cr.';
                $transdata['amount'] = $data['amount'];
                $transdata['createdBy'] = $userId;
                $transdata['createdDtm'] = date('Y-m-d h:i:s');
                $this->transactionModel->insert($transdata);
                $transaction = $this->transactionModel->getInsertID();


                //bank balance update  (start)
                $firstBankData['balance'] = $firstBankBalance;
                $firstBankData['updatedBy'] = $userId;
                $this->bankModel->update($data['bank_id'],$firstBankData);
                //bank balance update  (end)



                //Bank ledger create (start)
                $firstLedgerData['sch_id'] = $shopId;
                $firstLedgerData['bank_id'] = $data['bank_id'];
                $firstLedgerData['trans_id'] = $transaction;
                $firstLedgerData['particulars'] = 'Withdraw';
                $firstLedgerData['trangaction_type'] = 'Cr.';
                $firstLedgerData['amount'] = $data['amount'];
                $firstLedgerData['rest_balance'] = $firstBankBalance;
                $firstLedgerData['createdBy'] = $userId;
                $firstLedgerData['createdDtm'] = date('Y-m-d h:i:s');
                $this->ledgerbankModel->insert($firstLedgerData);
                //Bank ledgher create (end)


                //2Nd bank balance update (Start)
                $bankBalance2 = get_data_by_id('balance', 'bank', 'bank_id', $data['bank_id2']);
                $lastBankBalance = $bankBalance2 + $data['amount'];


                $lastBankData['balance'] = $lastBankBalance;
                $lastBankData['updatedBy'] = $userId;
                $this->bankModel->update($data['bank_id2'], $lastBankData);
                //2Nd bank balance update (Start)

                //Bank ledger create (start)
                $lastLedgerData['sch_id'] = $shopId;
                $lastLedgerData['bank_id'] = $data['bank_id2'];
                $lastLedgerData['trans_id'] = $transaction;
                $lastLedgerData['particulars'] = $particulars;
                $lastLedgerData['trangaction_type'] = 'Dr.';
                $lastLedgerData['amount'] = $data['amount'];
                $lastLedgerData['rest_balance'] = $lastBankBalance;
                $lastLedgerData['createdBy'] = $userId;
                $lastLedgerData['createdDtm'] = date('Y-m-d h:i:s');
                $this->ledgerbankModel->insert($lastLedgerData);
                //Bank ledgher create (end)

                DB()->transComplete();

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/transaction_create?active=fund');


            } else {

                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Not Enough Balance <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/transaction_create?active=fund');
            }
        }
    }

    public function expense_action(){
        $userId = Auth()->user_id;
        $shopId = Auth()->sch_id;
        $data['memo_number'] = $this->request->getPost('memo_number');
        $data['amount'] = str_replace(',', '', $this->request->getPost('amount'));
        //Payment Type
        $paymentType = $this->request->getPost('payment_type');
        //shop data
        $shopBalance = get_data_by_id('cash', 'shops', 'sch_id', $shopId);
        $shopUpdateBalance = $shopBalance - $data['amount'];

        if ($paymentType == 1) {
            $bankId = $this->request->getPost('bank_id');
            if ($bankId) {
                $bankCash = get_data_by_id('balance', 'bank', 'bank_id', $bankId);
                $bankUpData = $bankCash - $data['amount'];
            }
            $availableBalance = checkBankBalance($bankId, $data['amount']);
        }
        if ($paymentType == 2) {
            $availableBalance = checkNagadBalance($data['amount']);
        }

        $this->validation->setRules([
            'memo_number' => ['label' => 'Memo number', 'rules' => 'required'],
            'amount' => ['label' => 'Amount', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/transaction_create?active=expense');
        } else {

            if ($availableBalance == true) {

                DB()->transStart();
                //insert Transaction table
                $transdata['sch_id'] = $shopId;
                $transdata['title'] = $this->request->getPost('particulars');
                $transdata['trangaction_type'] = 'Cr.';
                $transdata['amount'] = $data['amount'];
                $transdata['createdBy'] = $userId;
                $transdata['createdDtm'] = date('Y-m-d h:i:s');
                $this->transactionModel->insert($transdata);
                $ledgtranId = $this->transactionModel->getInsertID();


                //insert data
                $dataEx['sch_id'] = $shopId;
                $dataEx['memo_number'] = $this->request->getPost('memo_number');
                $dataEx['trans_id'] = $ledgtranId;
                $dataEx['particulars'] = $this->request->getPost('particulars');
                $dataEx['trangaction_type'] = 'Cr.';
                $dataEx['amount'] = $data['amount'];
                $dataEx['createdBy'] = $userId;
                $dataEx['createdDtm'] = date('Y-m-d h:i:s');
                $this->ledgerexpenseModel->insert($dataEx);


                //admin transaction
                if ($paymentType == 2) {
                    //shop balance update
                    $shopData['sch_id'] = $shopId;
                    $shopData['cash'] = $shopUpdateBalance;
                    $shopData['updatedBy'] = $userId;
                    $this->shopsModel->update($shopData['sch_id'], $shopData);

                    //insert ledger_nagodan
                    $lgNagData['sch_id'] = $shopId;
                    $lgNagData['trans_id'] = $ledgtranId;
                    $lgNagData['particulars'] = $this->request->getPost('particulars');
                    $lgNagData['trangaction_type'] = 'Cr.';
                    $lgNagData['amount'] = $data['amount'];
                    $lgNagData['rest_balance'] = $shopUpdateBalance;
                    $lgNagData['createdBy'] = $userId;
                    $lgNagData['createdDtm'] = date('Y-m-d h:i:s');
                    $this->ledgernagodanModel->insert($lgNagData);

                } else {

                    $bankData['bank_id'] = $bankId;
                    $bankData['balance'] = $bankUpData;
                    $bankData['updatedBy'] = $userId;
                    $this->bankModel->update($bankData['bank_id'], $bankData);


                    //insert ledger_bank
                    $lgBankData['sch_id'] = $shopId;
                    $lgBankData['bank_id'] = $bankId;
                    $lgBankData['trans_id'] = $ledgtranId;
                    $lgBankData['particulars'] = $this->request->getPost('particulars');
                    $lgBankData['trangaction_type'] = 'Cr.';
                    $lgBankData['amount'] = $data['amount'];
                    $lgBankData['rest_balance'] = $bankUpData;
                    $lgBankData['createdBy'] = $userId;
                    $lgBankData['createdDtm'] = date('Y-m-d h:i:s');
                    $this->ledgerbankModel->insert($lgBankData);

                }

                DB()->transComplete();

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/transaction_create?active=expense');

            } else {
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Not Enough Balance <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/transaction_create?active=expense');
            }
        }
    }

    public function others_action(){
        $userId = Auth()->user_id;
        $shopId = Auth()->sch_id;
        
        $data['amount'] = str_replace(',', '', $this->request->getPost('amount'));
        //shop data
        $shopBalance = get_data_by_id('cash', 'shops', 'sch_id', $shopId);
        $shopUpdateBalance = $shopBalance + $data['amount'];

        $this->validation->setRules([
            'amount' => ['label' => 'Amount', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/transaction_create?active=other');
        } else {

            DB()->transStart();
            //insert Transaction table
            $transdata['sch_id'] = $shopId;
            $transdata['title'] = $this->request->getPost('particulars');
            $transdata['trangaction_type'] = 'Dr.';
            $transdata['amount'] = $data['amount'];
            $transdata['createdBy'] = $userId;
            $transdata['createdDtm'] = date('Y-m-d h:i:s');
            $this->transactionModel->insert($transdata);
            $ledgtranId = $this->transactionModel->getInsertID();



            //insert data
            $dataLOS['sch_id'] = $shopId;
            $dataLOS['trans_id'] = $ledgtranId;
            $dataLOS['particulars'] = $this->request->getPost('particulars');
            $dataLOS['trangaction_type'] = 'Dr.';
            $dataLOS['amount'] = $data['amount'];
            $dataLOS['createdBy'] = $userId;
            $dataLOS['createdDtm'] = date('Y-m-d h:i:s');
            $this->ledgerothersalesModel->insert($dataLOS);


            //shop balance update
            $shopData['sch_id'] = $shopId;
            $shopData['cash'] = $shopUpdateBalance;
            $shopData['updatedBy'] = $userId;
            $this->shopsModel->update($shopData['sch_id'], $shopData);

            //insert ledger_nagodan
            $lgNagData['sch_id'] = $shopId;
            $lgNagData['trans_id'] = $ledgtranId;
            $lgNagData['particulars'] = $this->request->getPost('particulars');
            $lgNagData['trangaction_type'] = 'Dr.';
            $lgNagData['amount'] = $data['amount'];
            $lgNagData['rest_balance'] = $shopUpdateBalance;
            $lgNagData['createdBy'] = $userId;
            $lgNagData['createdDtm'] = date('Y-m-d h:i:s');
            $this->ledgernagodanModel->insert($lgNagData);

            DB()->transComplete();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/transaction_create?active=other');

        }
    }

    public function search_employee_salary(){
        $response = array();

        $employeeId = $this->request->getPost('id');
        $response['salary'] = $this->employeeModel->where('employee_id', $employeeId)->first()->salary;

        $data = $this->ledgeremployeeModel->where('employee_id', $employeeId)->orderBy('ledg_emp_id', "DESC")->findAll(10);
        $view = '<table class="table table-bordered table-striped" >
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th>Particulars</th>
                            <th>Employee</th>
                            <th>Salary</th>
                        </tr>
                    </thead>
                  <tbody>';
        $i = '';
        foreach ($data as $row) {
            $particulars = ($row->particulars == NULL) ? "Transaction" : $row->particulars;
            $employeeId = get_data_by_id('name', 'employee', "employee_id", $row->employee_id);
            $amountDr = ($row->trangaction_type != "Dr.") ? "---" : showWithCurrencySymbol($row->amount);
            $view .= '<tr>
                                    <td>' . ++$i . '</td>
                                    <td>' . bdDateFormat($row->createdDtm) . '</td>
                                    <td>' . $particulars . '</td>
                                    <td>' . $employeeId . '</td>
                                    <td>' . $amountDr . '</td>
                                </tr>';
        }

        $view .= '</tbody></table>';

        $response['view'] = $view;

        return $this->response->setJSON($response);
    }

    public function employee_action(){
        $userId = Auth()->user_id;
        $shopId = Auth()->sch_id;
        
        $data['amount'] = str_replace(',', '', $this->request->getPost('amount'));
        $data['employee_id'] = $this->request->getPost('employee_id');
        //Supplier Balance
        $employeeBalance = get_data_by_id('balance', 'employee', 'employee_id', $data['employee_id']);
        $restBalance = $employeeBalance + $data['amount'];
        //Payment Type
        $paymentType = $this->request->getPost('payment_type');
        //shop data
        $shopBalance = get_data_by_id('cash', 'shops', 'sch_id', $shopId);
        $shopUpdateBalance = $shopBalance - $data['amount'];

        if ($paymentType == 1) {
            $bankId = $this->request->getPost('bank_id');
            if ($bankId) {
                $bankCash = get_data_by_id('balance', 'bank', 'bank_id', $bankId);
                $bankUpData = $bankCash - $data['amount'];
            }
            $availableBalance = checkBankBalance($bankId, $data['amount']);
        }
        if ($paymentType == 2) {
            $availableBalance = checkNagadBalance($data['amount']);
        }

        $this->validation->setRules([
            'employee_id' => ['label' => 'Employee', 'rules' => 'required'],
            'amount' => ['label' => 'Amount', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/transaction_create?active=employee');
        } else {

            if ($availableBalance == true) {

                DB()->transStart();
                //insert Transaction table
                $transdata['sch_id'] = $shopId;
                $transdata['employee_id'] = $data['employee_id'];
                $transdata['title'] = 'Salary';
                $transdata['trangaction_type'] = 'Dr.';
                $transdata['amount'] = $data['amount'];
                $transdata['createdBy'] = $userId;
                $transdata['createdDtm'] = date('Y-m-d h:i:s');
                $this->transactionModel->insert($transdata);
                $ledgSupId = $this->transactionModel->getInsertID();

                //insert data
                $dataLE['sch_id'] = $shopId;
                $dataLE['trans_id'] = $ledgSupId;
                $dataLE['employee_id'] = $data['employee_id'];
                $dataLE['particulars'] = 'Salary';
                $dataLE['trangaction_type'] = 'Dr.';
                $dataLE['amount'] = $data['amount'];
                $dataLE['rest_balance'] = $restBalance;
                $dataLE['createdBy'] = $userId;
                $dataLE['createdDtm'] = date('Y-m-d h:i:s');
                $this->ledgeremployeeModel->insert($dataLE);

                //Suppliers Balance Update
                $dataemployeeBlan['balance'] = $restBalance;
                $dataemployeeBlan['updatedBy'] = $userId;
                $this->employeeModel->update($data['employee_id'], $dataemployeeBlan);

                //admin transaction
                if ($paymentType == 2) {
                    //shop balance update
                    $shopData['sch_id'] = $shopId;
                    $shopData['cash'] = $shopUpdateBalance;
                    $shopData['updatedBy'] = $userId;
                    $this->shopsModel->update($shopData['sch_id'], $shopData);

                    //insert ledger_nagodan
                    $lgNagData['sch_id'] = $shopId;
                    $lgNagData['trans_id'] = $ledgSupId;
                    $lgNagData['trangaction_type'] = 'Cr.';
                    $lgNagData['particulars'] = 'Employee Salary';
                    $lgNagData['amount'] = $data['amount'];
                    $lgNagData['rest_balance'] = $shopUpdateBalance;
                    $lgNagData['createdBy'] = $userId;
                    $lgNagData['createdDtm'] = date('Y-m-d h:i:s');
                    $this->ledgernagodanModel->insert($lgNagData);

                } else {

                    $bankData['bank_id'] = $bankId;
                    $bankData['balance'] = $bankUpData;
                    $bankData['updatedBy'] = $userId;
                    $this->bankModel->update($bankData['bank_id'], $bankData);


                    //insert ledger_bank
                    $lgBankData['sch_id'] = $shopId;
                    $lgBankData['bank_id'] = $bankId;
                    $lgBankData['trans_id'] = $ledgSupId;
                    $lgBankData['trangaction_type'] = 'Cr.';
                    $lgBankData['particulars'] = 'Employee Salary';
                    $lgBankData['amount'] = $data['amount'];
                    $lgBankData['rest_balance'] = $bankUpData;
                    $lgBankData['createdBy'] = $userId;
                    $lgBankData['createdDtm'] = date('Y-m-d h:i:s');
                    $this->ledgerbankModel->insert($lgBankData);

                }

                DB()->transComplete();

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/transaction_create?active=employee');

            } else {
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Not Enough Balance <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/transaction_create?active=employee');
            }
        }
    }

    public function vat_ledger(){
        $vatId = $this->request->getPost('vatId');

        $data = $this->ledgervatModel->where('vat_id', $vatId)->orderBy('ledg_vat_id', 'DESC')->findAll(10);

        $vatBalance = get_data_by_id('balance', 'vat_register', 'vat_id', $vatId);

        $view = '<span class="pull-right"> Balance: ' . showWithCurrencySymbol($vatBalance) . '</span>';
        $view .= '<table class="table table-bordered table-striped" >
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Particulars</th>
                            <th>Loan Provider</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                  <tbody>';
        $i = '';
        foreach ($data as $row) {
            $particulars = ($row->particulars == NULL) ? "Loan" : $row->particulars;
            $vat_register = get_data_by_id('name', 'vat_register', "vat_id", $row->vat_id);
            $amountCr = ($row->trangaction_type != "Cr.") ? "---" : showWithCurrencySymbol($row->amount);
            $amountDr = ($row->trangaction_type != "Dr.") ? "---" : showWithCurrencySymbol($row->amount);
            $view .= '<tr>
                                    <td>' . bdDateFormat($row->createdDtm) . '</td>
                                    <td>' . $particulars . '</td>
                                    <td>' . $vat_register . '</td>
                                    <td>' . $amountDr . '</td>
                                    <td>' . $amountCr . '</td>
                                    <td>' . showWithCurrencySymbol($row->rest_balance) . '</td>
                                </tr>';
        }

        $view .= '</tbody></table>';


        print $view;
    }

    public function vat_action(){
        $userId = Auth()->user_id;
        $shopId = Auth()->sch_id;

        $data['amount'] = str_replace(',', '', $this->request->getPost('amount'));
        $data['vat_id'] = $this->request->getPost('vat_id');

        //Vat Balance
        $previousVat = get_data_by_id('balance', 'vat_register', 'vat_id', $data['vat_id']);
        $restBalance = $previousVat - $data['amount'];


        //Payment Type
        $paymentType = $this->request->getPost('payment_type');

        //shop data
        $shopBalance = get_data_by_id('cash', 'shops', 'sch_id', $shopId);
        $shopUpdateBalance = $shopBalance - $data['amount'];

        if ($paymentType == 1) {
            $bankId = $this->request->getPost('bank_id');
            if ($bankId) {
                $bankCash = get_data_by_id('balance', 'bank', 'bank_id', $bankId);
                $bankUpData = $bankCash - $data['amount'];
            }
            $availableBalance = checkBankBalance($bankId, $data['amount']);
        }

        if ($paymentType == 2) {
            $availableBalance = checkNagadBalance($data['amount']);
        }

        $this->validation->setRules([
            'vat_id' => ['label' => 'vat sid', 'rules' => 'required'],
            'amount' => ['label' => 'Amount', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/transaction_create?active=vat');
        } else {

            if ($availableBalance == true) {

                DB()->transStart();
                //insert Transaction table
                $transdata['sch_id'] = $shopId;
                $transdata['vat_id'] = $data['vat_id'];
                $transdata['title'] = $this->request->getPost('particulars');
                $transdata['trangaction_type'] = 'Cr.';
                $transdata['amount'] = $data['amount'];
                $transdata['createdBy'] = $userId;
                $transdata['createdDtm'] = date('Y-m-d h:i:s');
                $this->transactionModel->insert($transdata);
                $transactionId = $this->transactionModel->getInsertID();


                //insert data ledger_vat
                $dataLV['sch_id'] = $shopId;
                $dataLV['trans_id'] = $transactionId;
                $dataLV['vat_id'] = $data['vat_id'];
                $dataLV['particulars'] = $this->request->getPost('particulars');
                $dataLV['trangaction_type'] = 'Cr.';
                $dataLV['amount'] = $data['amount'];
                $dataLV['rest_balance'] = $restBalance;
                $dataLV['createdBy'] = $userId;
                $dataLV['createdDtm'] = date('Y-m-d h:i:s');
                $this->ledgervatModel->insert($dataLV);

                //vat register Balance Update
                $datavatBlan['balance'] = $restBalance;
                $datavatBlan['updatedBy'] = $userId;
                $this->vatregisterModel->update($data['vat_id'], $datavatBlan);

                //admin transaction
                if ($paymentType == 2) {
                    //shop balance update
                    $shopData['sch_id'] = $shopId;
                    $shopData['cash'] = $shopUpdateBalance;
                    $shopData['updatedBy'] = $userId;
                    $this->shopsModel->update($shopData['sch_id'], $shopData);

                    //insert ledger_nagodan
                    $lgNagData['sch_id'] = $shopId;
                    $lgNagData['trans_id'] = $transactionId;
                    $lgNagData['trangaction_type'] = 'Cr.';
                    $lgNagData['particulars'] = $this->request->getPost('particulars');
                    $lgNagData['amount'] = $data['amount'];
                    $lgNagData['rest_balance'] = $shopUpdateBalance;
                    $lgNagData['createdBy'] = $userId;
                    $lgNagData['createdDtm'] = date('Y-m-d h:i:s');
                    $this->ledgernagodanModel->insert($lgNagData);


                } else {


                    $bankData['bank_id'] = $bankId;
                    $bankData['balance'] = $bankUpData;
                    $bankData['updatedBy'] = $userId;
                    $bankData['createdDtm'] = date('Y-m-d h:i:s');
                    $this->bankModel->update($bankData['bank_id'], $bankData);

                    //insert ledger_bank
                    $lgBankData['sch_id'] = $shopId;
                    $lgBankData['bank_id'] = $bankId;
                    $lgBankData['trans_id'] = $transactionId;
                    $lgBankData['trangaction_type'] = 'Cr.';
                    $lgBankData['particulars'] = $this->request->getPost('particulars');
                    $lgBankData['amount'] = $data['amount'];
                    $lgBankData['rest_balance'] = $bankUpData;
                    $lgBankData['createdBy'] = $userId;
                    $lgBankData['createdDtm'] = date('Y-m-d h:i:s');
                    $this->ledgerbankModel->insert($lgBankData);

                }

                DB()->transComplete();

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/transaction_create?active=vat');

            } else {

                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Not Enough Balance <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/transaction_create?active=vat');
            }
        }
    }

    public function sale_commission_action(){
        $userId = Auth()->user_id;
        $shopId = Auth()->sch_id;

        $transactionType = $this->request->getPost('trangaction_type');
        $amount = $this->request->getPost('amount');

        $supperDuecommision = get_data_by_id('due_commision', 'supper_commision', 'sch_id', $shopId);

        if ($supperDuecommision >= $amount) {

            if ($transactionType == 2) {
                $type = 'Cr.';
            } else {
                $type = 'Dr.';
            }


            $data['sch_id'] = $shopId;
            $data['amount'] = $amount;
            $data['trangaction_type'] = $type;
            $data['status'] = '0';
            $data['createdBy'] = $userId;
            $data['createdDtm'] = date('Y-m-d h:i:s');
            $this->supcomitrnsModel->insert($data);


            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/transaction_create?active=sale');
        } else {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please input the correct amount! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/transaction_create?active=sale');
        }
    }



}