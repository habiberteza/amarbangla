<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\CommissionModel;
use App\Models\LedgernagodanModel;
use App\Models\LedgersellerModel;
use App\Models\PurchaseitemModel;
use App\Models\PurchaseModel;
use App\Models\SellerModel;
use App\Models\ShopsModel;


class SellerCommission extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $sellerModel;
    protected $ledgersellerModel;
    protected $commissionModel;
    protected $purchaseModel;
    protected $purchaseitemModel;

    protected $shopsModel;
    protected $ledgernagodanModel;
    private $module_name = 'Seller_commission';
    public function __construct()
    {
        $this->sellerModel = new SellerModel();
        $this->ledgersellerModel = new LedgersellerModel();
        $this->commissionModel = new CommissionModel();
        $this->purchaseModel = new PurchaseModel();
        $this->purchaseitemModel = new PurchaseitemModel();
        $this->shopsModel = new ShopsModel();
        $this->ledgernagodanModel = new LedgernagodanModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){
        $sellerdata = $this->commissionModel->join('package', 'package.package_id = commission.package_id')->where('sch_id = ' . Auth()->sch_id)->where('commission.delivery_boy_id IS NULL')->groupBy('commission.seller_id')->findAll();
        $data = [ 'seller' => $sellerdata ];

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Seller_commission/index',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function search(){

        $sellerId = $this->request->getPost('sellerId');

        $data['seller'] = $this->commissionModel->join('package', 'package.package_id = commission.package_id')->where('sch_id = ' . Auth()->sch_id)->where('commission.seller_id =', $sellerId)->findAll();

        echo view('Shop_admin/Seller_commission/search',$data);

    }

    public function pay($packageId, $sellerId){

        DB()->transStart();
            $query = $this->commissionModel->where('package_id' , $packageId)->where( 'seller_id' , $sellerId)->first();

            $commission = $query->commission;
            $commissionId = $query->commission_id;

            $comData['commission_id'] = $query->commission_id;
            $comData['com_status'] = '1';
            $this->commissionModel->update($comData['commission_id'], $comData);


            //seller register Balance Update
            $prevBalance = get_data_by_id('balance', 'seller', 'seller_id', $sellerId);
            $restBalance = $prevBalance + $commission;

            $datasellBlan['seller_id'] = $sellerId;
            $datasellBlan['balance'] = $restBalance;
            $datasellBlan['updatedBy'] = Auth()->user_id;
            $this->sellerModel->update($datasellBlan['seller_id'], $datasellBlan);


            //insert data ledger_seller
            $data['sch_id']= Auth()->sch_id;
            $data['commission_id'] = $commissionId;
            $data['seller_id'] = $sellerId;
            $data['particulars'] = 'Sale Commission Get';
            $data['trangaction_type'] = 'Cr.';
            $data['amount'] = $commission;
            $data['rest_balance'] = $restBalance;
            $data['createdBy'] = Auth()->user_id;
            $data['createdDtm'] = date('Y-m-d h:i:s');
            $this->ledgersellerModel->insert($data);


            //shop balance update
            $prevshopsBalance = Auth()->cash;
            $shopUpdateBalance = $prevshopsBalance - $commission;

            $shopData['sch_id'] = Auth()->sch_id;
            $shopData['cash'] = $shopUpdateBalance;
            $shopData['updatedBy'] = Auth()->user_id;
            $this->shopsModel->update($shopData['sch_id'], $shopData);

            //insert ledger_nagodan
            $lgNagData['sch_id'] = Auth()->sch_id;
            $lgNagData['commission_id'] = $commissionId;
            $lgNagData['trangaction_type'] = 'Cr.';
            $lgNagData['particulars'] = 'Sale Commission Pay';
            $lgNagData['amount'] = $commission;
            $lgNagData['rest_balance'] = $shopUpdateBalance;
            $lgNagData['createdBy'] = Auth()->user_id;
            $lgNagData['createdDtm'] = date('Y-m-d h:i:s');
            $this->ledgernagodanModel->insert($lgNagData);
        DB()->transComplete();

        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Pay Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('shop_admin/seller_commission');
    }

    public function multi_pay(){
        $commissionData = $this->commissionModel->join('package', 'package.package_id = commission.package_id')->where('sch_id = ' . Auth()->sch_id)->where('com_status =', '0')->where('commission.delivery_boy_id IS NULL')->orderBy('commission_id', 'DESC')->findAll();
        $sellerData = $this->commissionModel->join('package', 'package.package_id = commission.package_id')->where('sch_id = ' . Auth()->sch_id)->where('commission.delivery_boy_id IS NULL')->groupBy('commission.seller_id')->findAll();
        $data = [ 'seller' => $sellerData,'commissionData' => $commissionData];

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Seller_commission/multi_pay',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }

    public function pay_action(){
        $invoiceId = $this->request->getPost('invoiceId[]');
        if (!empty($invoiceId)) {
            $num = count($invoiceId);
            DB()->transStart();
            for ($i = 0; $i < $num; $i++) {
                $commission = get_data_by_id('commission', 'commission', 'invoice_id', $invoiceId[$i]);
                $sellerId = get_data_by_id('seller_id', 'commission', 'invoice_id', $invoiceId[$i]);
                $commissionId = get_data_by_id('commission_id', 'commission', 'invoice_id', $invoiceId[$i]);

                $comData['commission_id'] = $commissionId;
                $comData['com_status'] = '1';
                $this->commissionModel->update($comData['commission_id'], $comData);


                //seller register Balance Update
                $prevBalance = get_data_by_id('balance', 'seller', 'seller_id', $sellerId);
                $restBalance = $prevBalance + $commission;

                $datasellBlan['seller_id'] = $sellerId;
                $datasellBlan['balance'] = $restBalance;
                $datasellBlan['updatedBy'] = Auth()->user_id;
                $this->sellerModel->update($datasellBlan['seller_id'], $datasellBlan);

                //insert data ledger_seller
                $data['sch_id']= Auth()->sch_id;
                $data['commission_id'] = $commissionId;
                $data['seller_id'] = $sellerId;
                $data['particulars'] = 'Sale Commission Get';
                $data['trangaction_type'] = 'Cr.';
                $data['amount'] = $commission;
                $data['rest_balance'] = $restBalance;
                $data['createdBy'] = Auth()->user_id;
                $data['createdDtm'] = date('Y-m-d h:i:s');
                $this->ledgersellerModel->insert($data);


                //shop balance update
                $prevshopsBalance = Auth()->cash;
                $shopUpdateBalance = $prevshopsBalance - $commission;

                $shopData['sch_id'] = Auth()->sch_id;
                $shopData['cash'] = $shopUpdateBalance;
                $shopData['updatedBy'] = Auth()->user_id;
                $this->shopsModel->update($shopData['sch_id'], $shopData);

                //insert ledger_nagodan
                $lgNagData['sch_id'] = Auth()->sch_id;
                $lgNagData['commission_id'] = $commissionId;
                $lgNagData['trangaction_type'] = 'Cr.';
                $lgNagData['particulars'] = 'Sale Commission Pay';
                $lgNagData['amount'] = $commission;
                $lgNagData['rest_balance'] = $shopUpdateBalance;
                $lgNagData['createdBy'] = Auth()->user_id;
                $lgNagData['createdDtm'] = date('Y-m-d h:i:s');
                $this->ledgernagodanModel->insert($lgNagData);

            }
            DB()->transComplete();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Pay Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/seller_commission_multi_pay');
        }else{
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/seller_commission_multi_pay');
        }
    }

    public function report(){
        $sellerdata = $this->commissionModel->join('package', 'package.package_id = commission.package_id')->where('package.sch_id = ' . Auth()->sch_id)->where('commission.delivery_boy_id IS NULL')->groupBy('commission.seller_id')->findAll();
        $data = [ 'seller' => $sellerdata ];

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Seller_commission/report',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }

    public function reportSearch(){
        $sellerId = $this->request->getPost('sellerId');

        $name = get_data_by_id('name', 'seller', 'seller_id', $sellerId);

        $pay = $this->commissionModel->selectSum('commission.commission')->join('package', 'package.package_id = commission.package_id')->where('commission.seller_id', $sellerId)->where('commission.com_status', '1')->where('package.sch_id = ' . Auth()->sch_id)->first();

        $paycommision = (!empty($pay)) ? $pay->commission : 0;

        $due = $this->commissionModel->selectSum('commission.commission')->join('package', 'package.package_id = commission.package_id')->where('commission.seller_id', $sellerId)->where('commission.com_status', '0')->where('package.sch_id = ' . Auth()->sch_id)->first();
        $duecommision =(!empty($due)) ? $due->commission : 0;

        $view = '<table class="table table-bordered table-striped bg-success" >
                    <tr>
                        <td><b>Name</b> </td><td>' . $name . '</td>
                        <td><b>Due commision</b></td> <td>' . showWithCurrencySymbol($duecommision) . '</td>
                        <td><b>Pay commision</b></td> <td>' . showWithCurrencySymbol($paycommision) . '</td>
                    </tr>
                </table>';
        print $view;
    }





}