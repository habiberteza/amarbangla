<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\CommissionModel;
use App\Models\ShopsModel;
use App\Models\SupcommiinvoiceModel;
use App\Models\SuppecommisionModel;

class ShopCommission extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $supcommiinvoiceModel;
    protected $suppecommisionModel;
    protected $commissionModel;
    protected $shopsModel;
    private $module_name = 'Shop_commission';
    public function __construct()
    {
        $this->supcommiinvoiceModel = new SupcommiinvoiceModel();
        $this->suppecommisionModel = new SuppecommisionModel();
        $this->commissionModel = new CommissionModel();
        $this->shopsModel = new ShopsModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){

        $data['supCommision'] = $this->supcommiinvoiceModel->where('sch_id', Auth()->sch_id)->findAll();

        $data['totalsale'] = $this->supcommiinvoiceModel->selectSum('amount')->where( 'sch_id', Auth()->sch_id)->first()->amount;
        $data['totalcomm'] = $this->supcommiinvoiceModel->selectSum('commission')->where('sch_id', Auth()->sch_id)->first()->commission;

        $data['totalDue'] = $this->suppecommisionModel->where('sch_id', Auth()->sch_id)->first()->due_commision;
        $data['totalPay'] = $this->suppecommisionModel->where('sch_id', Auth()->sch_id)->first()->pay_commision;

        $data['montsale'] = $this->supcommiinvoiceModel->select('createdDtm')->selectSum('amount')->selectSum('commission')->where('sch_id', Auth()->sch_id)->groupBy('MONTH(createdDtm),YEAR(createdDtm)')->findAll();


        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Shop_commission/index',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }

    public function multipay(){
        $data['supCommision'] = $this->supcommiinvoiceModel->where('sch_id', Auth()->sch_id)->where('status','0')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Shop_commission/commission_multipay',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }

    public function paid(){
        $data['supCommision'] = $this->supcommiinvoiceModel->where('sch_id', Auth()->sch_id)->where('status','1')->findAll();
        $data['status'] = 'paid';

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Shop_commission/commission',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }

    public function unpaid(){
        $data['supCommision'] = $this->supcommiinvoiceModel->where('sch_id', Auth()->sch_id)->where('status','0')->findAll();
        $data['status'] = 'unpaid';

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Shop_commission/commission',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }

    public function multipay_action(){
        $shopBalance = Auth()->cash;

        $invoiceId = $this->request->getPost('invoiceId[]');
        if (empty($invoiceId)){
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any item! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/shop_commission_multipay');
        }

        $count = count($invoiceId);

        $commission = 0;
        foreach ($invoiceId as $key => $value) {
            $commission += get_data_by_id('commission', 'sup_commi_invoice', 'invoice_id', $value);
        }
        $availableBalance = checkNagadBalance($commission);

        if ($availableBalance == true) {

            DB()->transStart();

            for ($i = 0; $i < $count; $i++) {
                $newshopBalance = Auth()->cash;
                $newcom = get_data_by_id('commission', 'sup_commi_invoice', 'invoice_id', $invoiceId[$i]);


                $shopreserveBalance = Auth()->reserve_cash;
                $reserveCash = $shopreserveBalance + $newcom;

                $reserveData['sch_id'] = Auth()->sch_id;
                $reserveData['reserve_cash'] = $reserveCash;
                $this->shopsModel->update($reserveData['sch_id'], $reserveData);



                $this->supcommiinvoiceModel->where('invoice_id',$invoiceId[$i])->where('sch_id',Auth()->sch_id)->set('status','2')->update();
            }

            DB()->transComplete();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Pay Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/shop_commission_multipay');

        } else {

            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Shop balance is too low for this pay <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/shop_commission_multipay');
        }
    }

    public function pay_action($invoice_id){
        $commission = get_data_by_id('commission', 'sup_commi_invoice', 'invoice_id', $invoice_id);

        $availableBalance = checkNagadBalance($commission);

        if ($availableBalance == true) {

            DB()->transStart();

                $shopreserveBalance = Auth()->reserve_cash;
                $reserveCash = $shopreserveBalance + $commission;
                $reserveData['sch_id'] = Auth()->sch_id;
                $reserveData['reserve_cash'] = $reserveCash;
                $this->shopsModel->update($reserveData['sch_id'], $reserveData);




                $this->supcommiinvoiceModel->where('invoice_id',$invoice_id)->where('sch_id',Auth()->sch_id)->set('status','2')->update();

            DB()->transComplete();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Pay Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/shop_commission');

        } else {

            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Shop balance is too low for this pay <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/shop_commission');
        }
    }






}