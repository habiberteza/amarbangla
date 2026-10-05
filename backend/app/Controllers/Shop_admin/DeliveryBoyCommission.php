<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\CommissionModel;
use App\Models\DeliveryModel;
use App\Models\LedgerdeliverybalanceModel;
use App\Models\LedgerdeliveryboyModel;
use App\Models\LedgernagodanModel;
use App\Models\PurchaseitemModel;
use App\Models\PurchaseModel;
use App\Models\ShopsModel;


class DeliveryBoyCommission extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $deliveryModel;
    protected $ledgerdeliveryboyModel;
    protected $commissionModel;
    protected $purchaseModel;
    protected $purchaseitemModel;

    protected $shopsModel;
    protected $ledgernagodanModel;
    protected $ledgerdeliverybalanceModel;
    private $module_name = 'Delivery_boy_commission';
    public function __construct()
    {
        $this->deliveryModel = new DeliveryModel();
        $this->ledgerdeliveryboyModel = new LedgerdeliveryboyModel();
        $this->commissionModel = new CommissionModel();
        $this->purchaseModel = new PurchaseModel();
        $this->purchaseitemModel = new PurchaseitemModel();
        $this->shopsModel = new ShopsModel();
        $this->ledgernagodanModel = new LedgernagodanModel();
        $this->ledgerdeliverybalanceModel = new LedgerdeliverybalanceModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){

        $data['deliveryCommission'] = $this->commissionModel->join('package', 'package.package_id = commission.package_id')->where('sch_id = ' . Auth()->sch_id)->where('commission.seller_id IS NULL')->findAll();
        $data['delivery'] = $this->commissionModel->join('package', 'package.package_id = commission.package_id')->where('sch_id = ' . Auth()->sch_id)->where('commission.seller_id IS NULL')->groupBy('commission.delivery_boy_id')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Delivery_boy_commission/index',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function search(){

        $delivery_boy_id = $this->request->getPost('delivery_boy_id');

        $data['deliveryCommission'] = $this->commissionModel->join('package', 'package.package_id = commission.package_id')->where('sch_id = ' . Auth()->sch_id)->where('commission.delivery_boy_id =', $delivery_boy_id)->findAll();

        echo view('Shop_admin/Delivery_boy_commission/search',$data);

    }

    public function pay($packageId, $deliv){

        $query = $this->commissionModel->where('package_id', $packageId)->where('delivery_boy_id', $deliv)->first();

        if ($query->com_status == 0) {
            DB()->transStart();

                $commission = $query->commission;
                $commissionId = $query->commission_id;

                $comData['commission_id'] = $commissionId;
                $comData['com_status'] = '1';
                $this->commissionModel->update($comData['commission_id'], $comData);


                //delivery_boy register Balance Update
                $prevBalance = get_data_by_id('balance', 'delivery_boy', 'delivery_boy_id', $deliv);
                $restBalance = $prevBalance + $commission;

                $datasdelBlan['delivery_boy_id'] = $deliv;
                $datasdelBlan['balance'] = $restBalance;
                $datasdelBlan['updatedBy'] = Auth()->user_id;
                $this->deliveryModel->update($datasdelBlan['delivery_boy_id'], $datasdelBlan);

                //insert data ledger_delivery_boy
                $data['commission_id'] = $commissionId;
                $data['delivery_boy_id'] = $deliv;
                $data['particulars'] = 'Delivery Commission Get';
                $data['trangaction_type'] = 'Cr.';
                $data['amount'] = $commission;
                $data['rest_balance'] = $restBalance;
                $data['createdBy'] = Auth()->user_id;
                $data['createdDtm'] = date('Y-m-d h:i:s');
                $this->ledgerdeliveryboyModel->insert($data);


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
                $lgNagData['particulars'] = 'Delivery Commission Pay';
                $lgNagData['amount'] = $commission;
                $lgNagData['rest_balance'] = $shopUpdateBalance;
                $lgNagData['createdBy'] = Auth()->user_id;
                $lgNagData['createdDtm'] = date('Y-m-d h:i:s');
                $this->ledgernagodanModel->insert($lgNagData);


                $prevdelBalan = Auth()->del_balance;
                $delBalanrest = $prevdelBalan - $commission;
                $shopData['sch_id'] = Auth()->sch_id;
                $shopData['del_balance'] = $delBalanrest;
                $shopData['updatedBy'] = Auth()->user_id;
                $this->shopsModel->update($shopData['sch_id'], $shopData);

                //insert ledger_delivery_balance
                $lgdelBalData['sch_id'] = Auth()->sch_id;
                $lgdelBalData['delivery_boy_id'] = $deliv;
                $lgdelBalData['trangaction_type'] = 'Cr.';
                $lgdelBalData['particulars'] = 'Delivery Commission Pay';
                $lgdelBalData['amount'] = $commission;
                $lgdelBalData['rest_balance'] = $delBalanrest;
                $lgdelBalData['createdBy'] = Auth()->user_id;
                $lgdelBalData['createdDtm'] = date('Y-m-d h:i:s');

                $this->ledgerdeliverybalanceModel->insert($lgdelBalData);

            DB()->transComplete();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Pay Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/delivery_boy_commission');
        } else {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">All ready pay commission! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/delivery_boy_commission');
        }






    }

    public function multi_pay(){
        $data['deliveryCommission'] = $this->commissionModel->join('package', 'package.package_id = commission.package_id')->where('sch_id = ' . Auth()->sch_id)->where('com_status =', '0')->where('commission.seller_id IS NULL')->orderBy('commission_id', 'DESC')->findAll();
        $data['deliveryBoy'] = $this->commissionModel->join('package', 'package.package_id = commission.package_id')->where('sch_id = ' . Auth()->sch_id)->where('commission.seller_id IS NULL')->groupBy('commission.delivery_boy_id')->findAll();


        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Delivery_boy_commission/multi_pay',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }

    public function pay_action(){
        $invoiceId = $this->request->getPost('invoiceId[]');
        $packageId = $this->request->getPost('packageId[]');
        $deliveryboyId = $this->request->getPost('delivery_boy_id[]');

        if (!empty($invoiceId)) {
            $num = count($packageId);
            DB()->transStart();
            for ($i = 0; $i < $num; $i++) {

                $query = $this->commissionModel->where('package_id', $packageId[$i])->where('delivery_boy_id', $deliveryboyId[$i])->first();

                $commission = $query->commission;
                $commissionId = $query->commission_id;

                $comData['commission_id'] = $commissionId;
                $comData['com_status'] = '1';
                $this->commissionModel->update($comData['commission_id'], $comData);


                //delivery_boy register Balance Update
                $prevBalance = get_data_by_id('balance', 'delivery_boy', 'delivery_boy_id', $deliveryboyId[$i]);
                $restBalance = $prevBalance + $commission;

                $datadelBlan['delivery_boy_id'] = $deliveryboyId[$i];
                $datadelBlan['balance'] = $restBalance;
                $datadelBlan['updatedBy'] = Auth()->user_id;
                $this->deliveryModel->update($datadelBlan['delivery_boy_id'], $datadelBlan);


                //insert data ledger_delivery_boy
                $data['sch_id'] = Auth()->sch_id;
                $data['commission_id'] = $commissionId;
                $data['delivery_boy_id'] = $deliveryboyId[$i];
                $data['particulars'] = 'Delivery Commission Get';
                $data['trangaction_type'] = 'Cr.';
                $data['amount'] = $commission;
                $data['rest_balance'] = $restBalance;
                $data['createdBy'] = Auth()->user_id;
                $data['createdDtm'] = date('Y-m-d h:i:s');
                $this->ledgerdeliveryboyModel->insert($data);


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
                $lgNagData['particulars'] = 'Delivery Commission Pay';
                $lgNagData['amount'] = $commission;
                $lgNagData['rest_balance'] = $shopUpdateBalance;
                $lgNagData['createdBy'] = Auth()->user_id;
                $lgNagData['createdDtm'] = date('Y-m-d h:i:s');
                $this->ledgernagodanModel->insert($lgNagData);


                $prevdelBalan = Auth()->del_balance;
                $delBalanrest = $prevdelBalan - $commission;
                $shopData['sch_id'] = Auth()->sch_id;
                $shopData['del_balance'] = $delBalanrest;
                $shopData['updatedBy'] = Auth()->user_id;
                $this->shopsModel->update($shopData['sch_id'], $shopData);

                //insert ledger_delivery_balance
                $lgdelBalData['sch_id'] = Auth()->sch_id;
                $lgdelBalData['delivery_boy_id'] = $deliveryboyId[$i];
                $lgdelBalData['trangaction_type'] = 'Cr.';
                $lgdelBalData['particulars'] = 'Delivery Commission Pay';
                $lgdelBalData['amount'] = $commission;
                $lgdelBalData['rest_balance'] = $delBalanrest;
                $lgdelBalData['createdBy'] = Auth()->user_id;
                $lgdelBalData['createdDtm'] = date('Y-m-d h:i:s');
                $this->ledgerdeliverybalanceModel->insert($lgdelBalData);

            }
            DB()->transComplete();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Pay Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/delivery_boy_commission_multi_pay');
        }else{
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/delivery_boy_commission_multi_pay');
        }
    }

    public function report(){
        $deliverydata = $this->commissionModel->join('package', 'package.package_id = commission.package_id')->where('package.sch_id = ' . Auth()->sch_id)->where('commission.seller_id IS NULL')->groupBy('commission.delivery_boy_id')->findAll();
        $data = [ 'deliveryBoy' => $deliverydata ];

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Delivery_boy_commission/report',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }

    public function reportSearch(){

        $deliveryBoyId = $this->request->getPost('deliveryBoyId');

        $name = get_data_by_id('name', 'delivery_boy', 'delivery_boy_id', $deliveryBoyId);

        $pay = $this->commissionModel->selectSum('commission')->join('package', 'package.package_id = commission.package_id')->where('commission.delivery_boy_id', $deliveryBoyId)->where('commission.com_status', '1')->where('package.sch_id = ' . Auth()->sch_id)->first();
        $paycommision =(!empty($pay)) ?  $pay->commission : 0;

        $due = $this->commissionModel->selectSum('commission')->join('package', 'package.package_id = commission.package_id')->where('commission.delivery_boy_id', $deliveryBoyId)->where('commission.com_status', '0')->where('package.sch_id = ' . Auth()->sch_id)->first();

        $duecommision = (!empty($due)) ?  $due->commission : 0;

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