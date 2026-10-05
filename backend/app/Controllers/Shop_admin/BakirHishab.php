<?php
namespace App\Controllers\Shop_admin;

use App\Models\LedgernagodanModel;
use CodeIgniter\Files\File;
use App\Models\LedgerloanModel;
use App\Controllers\BaseController;
use App\Models\RolesModel;
use App\Models\ChaqueModel;
use App\Models\TransactionModel;
use App\Models\BankModel;
use App\Models\LedgerbankModel;
use App\Models\LoanproviderModel;
use App\Models\ShopsModel;
use App\Libraries\Permission;

class BakirHishab extends BaseController
{
    protected $validation;
    protected $transactionModel;
    protected $chaqueModel;
    protected $ledgerloanModel;
    protected $loanproviderModel;
    protected $shopsModel;
    protected $ledgernagodanModel;
    protected $bankModel;
    protected $ledgerbankModel;
    private $session;
    private $permission;
    private $module_name = 'Transaction';
    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->chaqueModel = new ChaqueModel();
        $this->ledgerloanModel = new LedgerloanModel();
        $this->loanproviderModel = new LoanproviderModel();
        $this->ledgernagodanModel = new LedgernagodanModel();
        $this->shopsModel = new ShopsModel();
        $this->bankModel = new BankModel();
        $this->ledgerbankModel = new LedgerbankModel();
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->permission = new Permission();
    }

    public function index(){

        $data['transaction_data'] = $this->transactionModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->findAll();
        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Bakir_hishab/index',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }

    public function create(){

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Bakir_hishab/create');
        }else{
            echo view('Shop_admin/no_permission');
        }

    }

    public function create_action()
    {
        
        $userId = $this->session->userId;
        $shopId = $this->session->shopId;
        $data['amount'] = $this->request->getPost('amount');

        $amount = str_replace(',', '', $this->request->getPost('amount'));
        $loanProId = $this->request->getPost('loan_pro_id');

        //loan_pro Balance
        $loanProBalance = get_data_by_id('balance', 'loan_provider', 'loan_pro_id', $loanProId);
        $restBalance = $loanProBalance + $amount;
        $restBalanceDr = $loanProBalance - $amount;

        //Payment Type
        $paymentType = $this->request->getPost('payment_type');

        //shop data
        $shopBalance = get_data_by_id('cash', 'shops', 'sch_id', $shopId);
        $shopUpdateBalance = $shopBalance - $amount;
        $shopUpdateBalanceCr = $shopBalance + $amount;

        $trangactionType = $this->request->getPost('trangaction_type');

        $chequeNo = $this->request->getPost('chequeNo');
        $chequeAmount = $this->request->getPost('chequeAmount');

        if ($paymentType == 1) {
            $bankId = $this->request->getPost('bank_id');
            if ($bankId) {
                $bankCash = get_data_by_id('balance', 'bank', 'bank_id', $bankId);
                $bankUpData = $bankCash - $amount;
                $bankUpDataCr = $bankCash + $amount;
            }
            $availableBalance = checkBankBalance($bankId, $amount);
        }
        if ($paymentType == 2) {
            $availableBalance = checkNagadBalance($amount);
        }


        $this->validation->setRules([
            'amount' => ['label' => 'amount', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/bakir_hishab_create');
        } else {

            //sms setting(start)
            $smsMessage = 'AmarBangla ' . profile_name() . ' ' . $this->request->getPost('particulars') . ' ' . $amount;
            $smsPhone = get_data_by_id('phone', 'loan_provider', 'loan_pro_id', $loanProId);
            //sms setting(end)

            if ($chequeNo > 0) {

                //cheque pay amount calculate and insert cheque tabile(start)
                $chequeData['sch_id'] = $shopId;
                $chequeData['chaque_number'] = $chequeNo;
                $chequeData['to'] = $userId;
                $chequeData['from_loan_provider'] = $loanProId;
                $chequeData['amount'] = $chequeAmount;
                $chequeData['createdDtm'] = date('Y-m-d h:i:s');
                $this->chaqueModel->insert($chequeData);

                //smsSend
                sms_send($smsMessage, $smsPhone);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/bakir_hishab_create');
                //cheque pay amount calculate and insert cheque tabile(end)

            } else {
                //Cr.
                if ($trangactionType == 2) {
                    DB()->transStart();
                    //insert Transaction table
                    $image = null;
                    if (!empty($_FILES['image']['name'])) {
                        $target_dir = FCPATH . '/uploads/tran_lone_image/';
                        if (!file_exists($target_dir)) {
                            mkdir($target_dir, 0777);
                        }
                        //new image uplode
                        $pic = $this->request->getFile('image');
                        $namePic = $pic->getRandomName();
                        $pic->move($target_dir, $namePic);
//                        $pro_nameimg = 'users_' . $pic->getName();
//                        $this->crop->withFile($target_dir . '' . $namePic)->fit(300, 300, 'center')->save($target_dir . '' . $pro_nameimg);
//                        unlink($target_dir . '' . $namePic);
                        $image = $namePic;
                    }



                    $transData['sch_id'] = $shopId;
                    $transData['loan_pro_id'] = $loanProId;
                    $transData['title'] = $this->request->getPost('particulars');
                    $transData['trangaction_type'] = 'Dr.';
                    $transData['amount'] = $amount;
                    $transData['image'] = $image;
                    $transData['createdBy'] = $userId;
                    $transData['createdDtm'] = date('Y-m-d h:i:s');
                    $this->transactionModel->insert($transData);
                    $transId = $this->transactionModel->getInsertID();

                    //insert data
                    $dataLon['sch_id'] = $shopId;
                    $dataLon['trans_id'] = $transId;
                    $dataLon['loan_pro_id'] = $loanProId;
                    $dataLon['particulars'] = $this->request->getPost('particulars');
                    $dataLon['trangaction_type'] = 'Dr.';
                    $dataLon['amount'] = $amount;
                    $dataLon['rest_balance'] = $restBalanceDr;
                    $dataLon['createdBy'] = $userId;
                    $dataLon['createdDtm'] = date('Y-m-d h:i:s');
                    $this->ledgerloanModel->insert($dataLon);


                    //loan_provider Balance Update
                    $dataLonProBlan['balance'] = $restBalanceDr;
                    $dataLonProBlan['updatedBy'] = $userId;
                    $this->loanproviderModel->set('loan_pro_id',$loanProId)->where($dataLonProBlan)->update();


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
                        $lgNagData['amount'] = $amount;
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
                        $lgBankData['amount'] = $amount;
                        $lgBankData['rest_balance'] = $bankUpDataCr;
                        $lgBankData['createdBy'] = $userId;
                        $lgBankData['createdDtm'] = date('Y-m-d h:i:s');
                        $this->ledgerbankModel->insert($lgBankData);

                    }
                    DB()->transComplete();

                    //smsSend
                    sms_send($smsMessage, $smsPhone);


                    $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    return redirect()->to('shop_admin/bakir_hishab_create');
                }
            }
            //Dr.
            if ($trangactionType == 1) {

                if ($availableBalance == true) {
                    DB()->transStart();
                    //insert Transaction table

                    $image = null;
                    if (!empty($_FILES['image']['name'])) {
                        $target_dir = FCPATH . '/uploads/tran_lone_image/';
                        if (!file_exists($target_dir)) {
                            mkdir($target_dir, 0777);
                        }
                        //new image uplode
                        $pic = $this->request->getFile('image');
                        $namePic = $pic->getRandomName();
                        $pic->move($target_dir, $namePic);
//                        $pro_nameimg = 'users_' . $pic->getName();
//                        $this->crop->withFile($target_dir . '' . $namePic)->fit(300, 300, 'center')->save($target_dir . '' . $pro_nameimg);
//                        unlink($target_dir . '' . $namePic);
                        $image = $namePic;
                    }



                    $transdata['sch_id'] = $shopId;
                    $transdata['loan_pro_id'] = $loanProId;
                    $transdata['title'] = $this->request->getPost('particulars');
                    $transdata['trangaction_type'] = 'Cr.';
                    $transdata['amount'] = $amount;
                    $transdata['image'] = $image;
                    $transdata['createdBy'] = $userId;
                    $transdata['createdDtm'] = date('Y-m-d h:i:s');
                    $this->transactionModel->insert($transdata);
                    $transId = $this->transactionModel->getInsertID();


                    //insert data
                    $dataLeLo['sch_id'] = $shopId;
                    $dataLeLo['trans_id'] = $transId;
                    $dataLeLo['loan_pro_id'] = $loanProId;
                    $dataLeLo['particulars'] = $this->request->getPost('particulars');
                    $dataLeLo['trangaction_type'] = 'Cr.';
                    $dataLeLo['amount'] = $amount;
                    $dataLeLo['rest_balance'] = $restBalance;
                    $dataLeLo['createdBy'] = $userId;
                    $dataLeLo['createdDtm'] = date('Y-m-d h:i:s');
                    $this->ledgerloanModel->insert($dataLeLo);



                    //loan_provider Balance Update
                    $dataLonProBlan['balance'] = $restBalance;
                    $dataLonProBlan['updatedBy'] = $userId;
                    $this->loanproviderModel->set('loan_pro_id',$loanProId)->where($dataLonProBlan)->update();


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
                        $lgNagData['amount'] = $amount;
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
                        $lgBankData['amount'] = $amount;
                        $lgBankData['rest_balance'] = $bankUpData;
                        $lgBankData['createdBy'] = $userId;
                        $lgBankData['createdDtm'] = date('Y-m-d h:i:s');
                        $this->ledgerbankModel->insert($lgBankData);

                    }
                    DB()->transComplete();


                    //sms send(start)
                    sms_send($smsMessage, $smsPhone);
                    //sms send(end)

                    $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    return redirect()->to('shop_admin/bakir_hishab_create');

                } else {
                    $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Not Enough Balance<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    return redirect()->to('shop_admin/bakir_hishab_create');
                }
            }
        }
    }

    public function lonProvData()
    {
        $lonProvId = $this->request->getPost('lonProvId');

        
        $data= $this->ledgerloanModel->where('loan_pro_id', $lonProvId)->orderBy('ledg_loan_id', "DESC")->findAll(10);
       
        
        $loanProBalance = get_data_by_id('balance', 'loan_provider', 'loan_pro_id', $lonProvId);

        $view = '<span class="pull-right"> Balance: ' . showWithCurrencySymbol($loanProBalance) . '</span>';
        $view .= '<table class="table table-bordered table-striped text-capitalize" id="TFtable">
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

        $view .= '</tbody>
                            <tfoot>
                                <tr>
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                </tr>
                            </tfoot>
                        </table>';


        print $view;
    }

}