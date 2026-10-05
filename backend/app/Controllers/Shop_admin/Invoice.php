<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\BankModel;
use App\Models\CommissionModel;
use App\Models\CustomersModel;
use App\Models\DeliveryboyModel;
use App\Models\DeliveryModel;
use App\Models\InvoiceitemModel;
use App\Models\InvoiceModel;
use App\Models\LedgerbankModel;
use App\Models\LedgerdeliverybalanceModel;
use App\Models\LedgerdeliveryboyModel;
use App\Models\LedgerModel;
use App\Models\LedgernagodanModel;
use App\Models\LedgersellerModel;
use App\Models\LedgersuppliersModel;
use App\Models\PackageModel;
use App\Models\ProductsModel;
use App\Models\PurchaseitemModel;
use App\Models\PurchaseModel;
use App\Models\SalesModel;
use App\Models\SellerModel;
use App\Models\ShopsModel;
use App\Models\SupcommiinvoiceModel;
use App\Models\SuppecommisionModel;
use App\Models\SuppliersModel;

class Invoice extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $packageModel;
    protected $invoiceitemModel;
    protected $purchaseModel;
    protected $purchaseitemModel;
    protected $suppliersModel;
    protected $ledgersuppliersModel;
    protected $productsModel;
    protected $sellerModel;
    protected $ledgersellerModel;
    protected $commissionModel;
    protected $deliveryModel;
    protected $shopsModel;
    protected $ledgerdeliverybalanceModel;
    protected $deliveryboyModel;
    protected $ledgerdeliveryboyModel;
    protected $supcommiinvoiceModel;
    protected $suppecommisionModel;
    protected $salesModel;
    protected $customersModel;
    protected $ledgerModel;
    protected $ledgernagodanModel;
    protected $bankModel;
    protected $ledgerbankModel;
    protected $invoiceModel;
    private $module_name = 'Invoice';
    public function __construct()
    {
        $this->packageModel = new PackageModel();
        $this->invoiceModel = new InvoiceModel();
        $this->invoiceitemModel = new InvoiceitemModel();
        $this->purchaseModel = new PurchaseModel();
        $this->purchaseitemModel = new PurchaseitemModel();
        $this->suppliersModel = new SuppliersModel();
        $this->ledgersuppliersModel = new LedgersuppliersModel();
        $this->productsModel = new ProductsModel();
        $this->sellerModel = new SellerModel();
        $this->ledgersellerModel = new LedgersellerModel();
        $this->commissionModel = new CommissionModel();
        $this->deliveryModel = new DeliveryModel();
        $this->shopsModel = new ShopsModel();
        $this->deliveryboyModel = new DeliveryboyModel();
        $this->ledgerdeliverybalanceModel = new LedgerdeliverybalanceModel();
        $this->ledgerdeliveryboyModel = new LedgerdeliveryboyModel();
        $this->supcommiinvoiceModel = new SupcommiinvoiceModel();
        $this->suppecommisionModel = new SuppecommisionModel();
        $this->salesModel = new SalesModel();
        $this->customersModel = new CustomersModel();
        $this->ledgerModel = new LedgerModel();
        $this->ledgernagodanModel = new LedgernagodanModel();
        $this->bankModel = new BankModel();
        $this->ledgerbankModel = new LedgerbankModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){


        $data['package'] = $this->packageModel->where('sch_id',Auth()->sch_id)->orderBy('package_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Invoice/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function view($package_id){


        $query = $this->packageModel->where('package_id',$package_id)->first();

        $data['invoiceId'] = $query->invoice_id;
        $data['customerId'] = get_data_by_id('customer_id', 'invoice', 'invoice_id', $query->invoice_id);

        $data['package'] = $package_id;
        $data['subtotal'] = $query->price;
        $data['delCharge'] = $query->delivery_charge;
        $data['finalAmount'] = $query->price + $query->delivery_charge;
        $data['dueAmount'] = $query->price + $query->delivery_charge;
        $data['createdDtm'] = $query->createdDtm;
        $data['status'] = $query->status;
        $data['shopsName'] = Auth()->shopName;

        $data['invoiceItem'] = $this->invoiceitemModel->where('package_id', $package_id)->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Invoice/view', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }

    public function a4_print($package_id){


        $query = $this->packageModel->where('package_id',$package_id)->first();

        $data['invoiceId'] = $query->invoice_id;
        $data['customerId'] = get_data_by_id('customer_id', 'invoice', 'invoice_id', $query->invoice_id);

        $data['package'] = $package_id;
        $data['subtotal'] = $query->price;
        $data['delCharge'] = $query->delivery_charge;
        $data['finalAmount'] = $query->price + $query->delivery_charge;
        $data['dueAmount'] = $query->price + $query->delivery_charge;
        $data['createdDtm'] = $query->createdDtm;
        $data['status'] = $query->status;
        $data['shopsName'] = Auth()->shopName;
        $data['shopsPhone'] = Auth()->mobile;

        $data['invoiceItem'] = $this->invoiceitemModel->where('package_id', $package_id)->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Invoice/a4-print', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function pos_print($package_id){


        $query = $this->packageModel->where('package_id',$package_id)->first();

        $data['invoiceId'] = $query->invoice_id;
        $data['customerId'] = get_data_by_id('customer_id', 'invoice', 'invoice_id', $query->invoice_id);

        $data['package'] = $package_id;
        $data['subtotal'] = $query->price;
        $data['delCharge'] = $query->delivery_charge;
        $data['finalAmount'] = $query->price + $query->delivery_charge;
        $data['dueAmount'] = $query->price + $query->delivery_charge;
        $data['createdDtm'] = $query->createdDtm;
        $data['status'] = $query->status;
        $data['shopsName'] = Auth()->shopName;
        $data['shopsPhone'] = Auth()->mobile;

        $data['invoiceItem'] = $this->invoiceitemModel->where('package_id', $package_id)->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Invoice/pos-print', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }

    public function package_action($package_id){
        $data['invoice_data'] = $this->invoiceitemModel->where('package_id', $package_id)->findAll();

        $query = $this->invoiceitemModel->selectSum('total_price')->where('package_id', $package_id)->first();

        $deliveryCharge = get_data_by_id('delivery_charge', 'package', 'package_id', $package_id);

        $data['total'] = $query->total_price + $deliveryCharge;
        $data['delCharge'] = $deliveryCharge;
        $data['packageId'] = $package_id;

        $data['invoiceId'] = get_data_by_id('invoice_id', 'package', 'package_id', $package_id);

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Invoice/paid', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }

    public function package_create_action(){
        $customerId = $this->request->getPost('customer_id');
        $proId = $this->request->getPost('productId[]');
        $quantity = $this->request->getPost('qty[]');
        $proPrice = $this->request->getPost('price[]');
        $prodsaleDisc = $this->request->getPost('disc[]');
        $prodsubtotal = $this->request->getPost('subtotal[]');
        $prosubTo = $this->request->getPost('suballtotal[]');
        $entiresaleDisc = $this->request->getPost('saleDisc');
        $vat = $this->request->getPost('vat');
        $vatAmount = $this->request->getPost('vatAmount');
        $amount = $this->request->getPost('grandtotal2');
        $finalAmount = $this->request->getPost('grandtotal');
        $nagod = $this->request->getPost('nagod');
        $bankAmount = $this->request->getPost('bankAmount');
        $bankId = $this->request->getPost('bank_id');
        $chequeNo = $this->request->getPost('chequeNo');
        $chequeAmount = $this->request->getPost('chequeAmount');
        $dueAmount = $this->request->getPost('grandtotaldue');
        $packageId = $this->request->getPost('package_id');

        $invoiceId = get_data_by_id('invoice_id', 'package', 'package_id', $packageId);
        $checkSeller = checkSellerInvoice($invoiceId);
        $checkDeliveryBoy = checkDeliveryBoyInvoice($invoiceId);
        $delChar = get_data_by_id('delivery_charge', 'package', 'package_id', $packageId);

        $totalsell = $finalAmount - $delChar;


        if ($delChar == NULL) {
            $this->session->setFlashdata('message', '<div class="alert alert-dark alert-dismissible" role="alert">Please update delivery charge!! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/invoice_view/'.$packageId);
        } else {

            DB()->transStart();
            $finalCommission = 0;
            //invoice itame insert
            $number = count($proId);
            for ($i = 0; $i < $number; $i++) {

                $checkpurType = purchaseTypeCheck($proId[$i]);

                if ($checkpurType == true) {
                    $supplierId = get_data_by_id('supplier_id', 'products', 'prod_id', $proId[$i]);


                    $purchData['sch_id'] = Auth()->sch_id;
                    $purchData['supplier_id'] = $supplierId;
                    $purchData['amount'] = $prodsubtotal[$i];
                    $purchData['due'] = $prodsubtotal[$i];

                    $this->purchaseModel->insert($purchData);
                    $purcheId = $this->purchaseModel->getInsertID();


                    //purchase item insert
                    $purchPrice = get_data_by_id('purchase_price', 'products', 'prod_id', $proId[$i]);
                    $totalPurPrice = $purchPrice * $quantity[$i];
                    $this->purchase_item_insert($purcheId,$proId[$i],$quantity[$i],$totalPurPrice,$purchPrice);

                    //supplier data insert
                    $this->supplier_data_insert($supplierId,$totalPurPrice,$purcheId);

                } else {
                    //product Qnt Update in product table (start)
                    $productQnt = get_data_by_id('quantity', 'products', 'prod_id', $proId[$i]);
                    $qnt = $productQnt - $quantity[$i];

                    $qntProData['prod_id'] = $proId[$i];
                    $qntProData['quantity'] = $qnt;
                    $qntProData['updatedBy'] = Auth()->user_id;
                    $this->productsModel->update($qntProData['prod_id'], $qntProData);
                    //product Qnt Update in product table (end)
                }


                //calculating profit for individual item and updating the profit column (start)
                $productPurPrice = get_data_by_id('purchase_price', 'products', 'prod_id', $proId[$i]);
                $purPrice = $productPurPrice * $quantity[$i];
                $profit = $prosubTo[$i] - $purPrice;

                $this->invoiceitemModel->where('package_id',$packageId)->where('prod_id',$proId[$i])->set('profit',$profit)->update();
                //calculating profit for individual item and updating the profit column (end)


                // seller balance update(start)
                if ($checkSeller == TRUE) {
                    $this->seller_data_update($packageId,$invoiceId,$proId[$i],$prodsubtotal[$i],$finalCommission);
                }
                //seller balance update(end)

            }


            if ($checkSeller == TRUE) {
                $this->seller_commission_update($packageId,$invoiceId,$finalCommission);
            }

            $checkDelivery = $this->deliveryModel->where('package_id', $packageId)->countAllResults();
            if (!empty($checkDelivery)) {
                $deliveryboyId = get_data_by_id('delivery_boy_id', 'delivery', 'package_id', $packageId);
                if (!empty($deliveryboyId)) {
                    $this->delivery_boy_data_update($packageId,$deliveryboyId,$invoiceId);
                }
            } else {

                $delivData['sch_id'] = Auth()->sch_id;
                $delivData['invoice_id'] = $invoiceId;
                $delivData['package_id'] = $packageId;
                $delivData['status'] = '1';
                $delivData['accepetDtm'] = date('Y-m-d h:i:s');
                $delivData['createdDtm'] = date('Y-m-d h:i:s');
                $delivData['createdBy'] = Auth()->user_id;
                $this->deliveryModel->insert($delivData);
            }

            // update delivery status
            $upStatusDe['package_id'] = $packageId;
            $upStatusDe['status'] = '1';
            $this->deliveryModel->update($upStatusDe['package_id'], $upStatusDe);


            //Update salse profit,status,due in invoice table (start)
            $totalProfit = $this->invoiceitemModel->selectSum('profit')->where('package_id', $packageId)->first()->profit;

            $inData['package_id'] = $packageId;
            $inData['profit'] = $totalProfit;
            $inData['updatedBy'] = Auth()->user_id;
            $inData['status'] = '1';
            $this->packageModel->update($inData['package_id'], $inData);
            //Update salse profit,status,due in invoice table (end)


            // total sale amount
            $sup_comm = get_data_by_id('sup_comm', 'shops', 'sch_id', Auth()->sch_id);

            // supper commission  invoice (start)
            $supCom = ($totalsell * $sup_comm) / 100;
            $supperCommData['invoice_id'] = $invoiceId;
            $supperCommData['package_id'] = $packageId;
            $supperCommData['sch_id'] = Auth()->sch_id;
            $supperCommData['percent'] = $sup_comm;
            $supperCommData['amount'] = $totalsell;
            $supperCommData['commission'] = $supCom;
            $supperCommData['status'] = '0';
            $supperCommData['createdDtm'] = date('Y-m-d h:i:s');
            $supperCommData['createdBy'] = Auth()->user_id;
            $this->supcommiinvoiceModel->insert($supperCommData);

            // supper commission invoice (end)


            // supper commission update(start)
            $supCommision = get_data_by_id('commision', 'supper_commision', 'sch_id', Auth()->sch_id);
            $selcommiSuperad = $supCommision + $supCom;

            $due_commision = get_data_by_id('due_commision', 'supper_commision', 'sch_id', Auth()->sch_id);
            $totalduecommision = $due_commision + $supCom;


            $selcomData['sch_id'] = Auth()->sch_id;
            $selcomData['commision'] = $selcommiSuperad;
            $selcomData['due_commision'] = $totalduecommision;
            $selcomData['updatedDtm'] = date('Y-m-d h:i:s');
            $this->suppecommisionModel->update($selcomData['sch_id'], $selcomData);
            //supper commission update(end)


            // total sale amount


            //create sals in sales table(start)
            $saleData['sch_id'] = Auth()->sch_id;
            $saleData['invoice_id'] = $invoiceId;
            $saleData['package_id'] = $packageId;
            $this->salesModel->insert($saleData);
            //create sals in sales table(end)


            //customer balance update in customer table (start)
            $customerCash = get_data_by_id('balance', 'customers', 'customer_id', $customerId);
            $newCash = $customerCash + $finalAmount;
            //update balance
            $custData['customer_id'] = $customerId;
            $custData['balance'] = $newCash;
            $custData['updatedBy'] = Auth()->user_id;
            $this->customersModel->update($custData['customer_id'],$custData);
            //customer balance update in customer table (end)


            //insert customer ledger in ledger(start)
            $ledgerData['sch_id'] = Auth()->sch_id;
            $ledgerData['customer_id'] = $customerId;
            $ledgerData['invoice_id'] = $invoiceId;
            $ledgerData['package_id'] = $packageId;
            $ledgerData['trangaction_type'] = 'Cr.';
            $ledgerData['particulars'] = 'Sales Cash Due';
            $ledgerData['amount'] = $finalAmount;
            $ledgerData['rest_balance'] = $newCash;
            $ledgerData['createdBy'] = Auth()->user_id;
            $this->ledgerModel->insert($ledgerData);
            //insert customer ledger in ledger(end)


            //cash pay shop cash update and create nagod ledger (start)
            if ($nagod > 0) {
                //cash pay amount update shops cash (start)
                $shopsCash = get_data_by_id('cash', 'shops', 'sch_id', Auth()->sch_id);
                $upCahs = $shopsCash + $nagod;


                $shopsData['sch_id'] = Auth()->sch_id;
                $shopsData['cash'] = $upCahs;
                $shopsData['updatedBy'] = Auth()->user_id;
                $this->shopsModel->update($shopsData['sch_id'], $shopsData);
                //cash pay amount update shops cash (end)


                //insert ledger in ledger_nagodan cash pay amount(start)
                $lgNagData['sch_id'] = Auth()->sch_id;
                $lgNagData['invoice_id'] = $invoiceId;
                $lgNagData['package_id'] = $packageId;
                $lgNagData['trangaction_type'] = 'Dr.';
                $lgNagData['particulars'] = 'Sales Cash Pay';
                $lgNagData['amount'] = $nagod;
                $lgNagData['rest_balance'] = $upCahs;
                $lgNagData['createdBy'] = Auth()->user_id;
                $this->ledgernagodanModel->insert($lgNagData);
                //     //insert ledger in ledger_nagodan cash pay amount(start)


                //cash pay amount and customer balance amount calculate and update customer balance (start)
                if ($customerId) {
                    //customer balance calculate (start)
                    $custCash = get_data_by_id('balance', 'customers', 'customer_id', $customerId);
                    $newcastCash = $custCash - $nagod;
                    //customer balance calculate (end)


                    //update calculate balance in customer table(start)

                    $custnewData['customer_id'] = $customerId;
                    $custnewData['balance'] = $newcastCash;
                    $custnewData['updatedBy'] = Auth()->user_id;

                    $this->customersModel->update($custnewData['customer_id'], $custnewData);
                    //update calculate balance in customer table(end)


                    //create ledger in ledger table
                    $ledgernogodData['sch_id'] = Auth()->sch_id;
                    $ledgernogodData['customer_id'] = $customerId;
                    $ledgernogodData['invoice_id'] = $invoiceId;
                    $ledgernogodData['package_id'] = $packageId;
                    $ledgernogodData['trangaction_type'] = 'Dr.';
                    $ledgernogodData['particulars'] = 'Sales Cash Pay';
                    $ledgernogodData['amount'] = $nagod;
                    $ledgernogodData['rest_balance'] = $newcastCash;
                    $ledgernogodData['createdBy'] = Auth()->user_id;
                    $this->ledgerModel->insert($ledgernogodData);
                }
                //cash pay amount and customer balance amount calculate and update customer balance (end)
            }
            //cash pay shop cash update and create nagod ledger (end)


            // bank pay amount calculate and bank balance update (start)
            if ($bankAmount > 0) {
                //bank pay amount calculate and update bank balance (start)
                $bankCash = get_data_by_id('balance', 'bank', 'bank_id', $bankId);
                $upCahs = $bankCash + $bankAmount;


                $bankData['bank_id'] = $bankId;
                $bankData['balance'] = $upCahs;
                $bankData['updatedBy'] = Auth()->user_id;
                $this->bankModel->update($bankData['bank_id'], $bankData);
                //bank pay amount calculate and update bank balance (end)


                //insert ledger in table ledger_bank (start)
                $lgBankData['sch_id'] = Auth()->sch_id;
                $lgBankData['bank_id'] = $bankId;
                $lgBankData['invoice_id'] = $invoiceId;
                $lgBankData['package_id'] = $packageId;
                $lgBankData['particulars'] = 'Sales Bank Pay';
                $lgBankData['trangaction_type'] = 'Dr.';
                $lgBankData['amount'] = $bankAmount;
                $lgBankData['rest_balance'] = $upCahs;
                $lgBankData['createdBy'] = Auth()->user_id;

                $this->ledgerbankModel->insert($lgBankData);
                //insert ledger in table ledger_bank (end)


                if ($customerId) {
                    //bank pay amount calculate and customer balance update (start)
                    $cusCash = get_data_by_id('balance', 'customers', 'customer_id', $customerId);
                    $bankastCash = $cusCash - $bankAmount;


                    $custnewData['customer_id'] = $customerId;
                    $custnewData['balance'] = $bankastCash;
                    $custnewData['updatedBy'] = Auth()->user_id;

                    $this->customersModel->update($custnewData['customer_id'],$custnewData);
                    //bank pay amount calculate and customer balance update (start)


                    //insert ledger in table ledger (start)
                    $ledgerbankData['sch_id'] = Auth()->sch_id;
                    $ledgerbankData['customer_id'] = $customerId;
                    $ledgerbankData['invoice_id'] = $invoiceId;
                    $ledgerbankData['package_id'] = $packageId;
                    $ledgerbankData['trangaction_type'] = 'Dr.';
                    $ledgerbankData['particulars'] = 'Sales Bank Pay';
                    $ledgerbankData['amount'] = $bankAmount;
                    $ledgerbankData['rest_balance'] = $bankastCash;
                    $ledgerbankData['createdBy'] = Auth()->user_id;
                    $this->ledgerModel->insert($ledgerbankData);
                    //insert ledger in table ledger (start)

                }

            }
            // bank pay amount calculate and bank balance update (end)


            // invoice status change
            $chInvSt = $this->invoice_package_status_check($invoiceId,$packageId);
            if ($chInvSt == true){
                $invData['invoice_id'] = $invoiceId;
                $invData['status'] = '1';
                $this->invoiceModel->update($invData['invoice_id'],$invData);
            }
            // invoice status change


            DB()->transComplete();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Your invoice was paid successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/invoice/');
        }
    }


    public function purchase_item_insert($purcheId,$proId,$quantity,$totalPurPrice,$purchPrice){
        $itemData['purchase_id'] = $purcheId;
        $itemData['prod_id'] = $proId;
        $itemData['purchase_price'] = $purchPrice;
        $itemData['quantity'] = $quantity;
        $itemData['total_price'] = $totalPurPrice;
        $this->purchaseitemModel ->insert($itemData);
    }

    public function supplier_data_insert($supplierId,$totalPurPrice,$purcheId){
        $supplierBalance = get_data_by_id('balance', 'suppliers', 'supplier_id', $supplierId);
        $suppNewCash = $supplierBalance - $totalPurPrice;

        $suppData['supplier_id'] = $supplierId;
        $suppData['balance'] = $suppNewCash;
        $suppData['updatedBy'] = Auth()->user_id;
        $this->suppliersModel->update($suppData['supplier_id'], $suppData);

        //create suppliers ledger
        $lgData['sch_id'] = Auth()->sch_id;
        $lgData['supplier_id'] = $supplierId;
        $lgData['purchase_id'] = $purcheId;
        $lgData['trangaction_type'] = 'Dr.';
        $lgData['particulars'] = 'Purchase Cash Due';
        $lgData['amount'] = $totalPurPrice;
        $lgData['rest_balance'] = $suppNewCash;
        $lgData['createdBy'] = Auth()->user_id;
        $lgData['createdDtm'] = date('Y-m-d h:i:s');
        $this->ledgersuppliersModel->insert($lgData);
    }

    public function seller_data_update($packageId,$invoiceId,$proId,$prodsubtotal,$finalCommission){
        $sellerId = get_data_by_id('seller_id', 'invoice', 'invoice_id', $invoiceId);

        $sellerBalance = get_data_by_id('balance', 'seller', 'seller_id', $sellerId);

        $procommission = get_data_by_id('seller_commission', 'products', 'prod_id', $proId);

        $commission = ($prodsubtotal * $procommission) / 100;
        $finalCommission += $commission;

        $sellerRestBalance = $sellerBalance - $commission;

        $dataBalSeller['seller_id'] = $sellerId;
        $dataBalSeller['balance'] = $sellerRestBalance;
        $this->sellerModel->update($dataBalSeller['seller_id'], $dataBalSeller);


        $sellerLedger['sch_id'] = Auth()->sch_id;
        $sellerLedger['seller_id'] = $sellerId;
        $sellerLedger['invoice_id'] = $invoiceId;
        $sellerLedger['package_id'] = $packageId;
        $sellerLedger['trangaction_type'] = 'Dr.';
        $sellerLedger['particulars'] = 'Sales commission';
        $sellerLedger['amount'] = $commission;
        $sellerLedger['rest_balance'] = $sellerRestBalance;
        $sellerLedger['createdBy'] = Auth()->user_id;
        $sellerLedger['createdDtm'] = date('Y-m-d h:i:s');
        $this->ledgersellerModel->insert($sellerLedger);
    }

    public function seller_commission_update($packageId,$invoiceId,$finalCommission){
        $sellerId = get_data_by_id('seller_id', 'invoice', 'invoice_id', $invoiceId);
        $commissionData['invoice_id'] = $invoiceId;
        $commissionData['package_id'] = $packageId;
        $commissionData['seller_id'] = $sellerId;
        $commissionData['commission'] = $finalCommission;
        $commissionData['createdBy'] = Auth()->user_id;
        $commissionData['createdDtm'] = date('Y-m-d h:i:s');
        $this->commissionModel->insert($commissionData);
    }

    public function delivery_boy_data_update($packageId,$deliveryboyId,$invoiceId){
        $delBoyCharge = get_data_by_id('delivery_charge', 'package', 'package_id', $packageId);

        // delivery Balance updata
        $shopsdelBal = get_data_by_id('del_balance', 'shops', 'sch_id', Auth()->sch_id);
        $restDelBalanceShop = $shopsdelBal + $delBoyCharge;
        $updelbalanceShop['sch_id'] = Auth()->sch_id;
        $updelbalanceShop['del_balance'] = $restDelBalanceShop;

        $this->shopsModel->update($updelbalanceShop['sch_id'], $updelbalanceShop);


        // delivery Balance ledger insert

        $delivery_BalLedger['sch_id'] = Auth()->sch_id;
        $delivery_BalLedger['delivery_boy_id'] = $deliveryboyId;
        $delivery_BalLedger['invoice_id'] = $invoiceId;
        $delivery_BalLedger['package_id'] = $packageId;
        $delivery_BalLedger['trangaction_type'] = 'Dr.';
        $delivery_BalLedger['particulars'] = 'Delivery charge';
        $delivery_BalLedger['amount'] = $delBoyCharge;
        $delivery_BalLedger['rest_balance'] = $restDelBalanceShop;
        $delivery_BalLedger['createdBy'] = Auth()->user_id;
        $delivery_BalLedger['createdDtm'] = date('Y-m-d h:i:s');
        $this->ledgerdeliverybalanceModel->insert($delivery_BalLedger);


        //delivery boy balance update
        $delBoyChargefinal = (80 * $delBoyCharge) / 100;
        $delboyprevBal = get_data_by_id('balance', 'delivery_boy', 'delivery_boy_id', $deliveryboyId);
        $restDelBalance = $delboyprevBal - $delBoyChargefinal;

        $upDelBalrest['delivery_boy_id'] = $deliveryboyId;
        $upDelBalrest['balance'] = $restDelBalance;
        $this->deliveryboyModel->update($upDelBalrest['delivery_boy_id'], $upDelBalrest);


        //delivery boy ledger insert
        $delivery_boyLedger['sch_id'] = Auth()->sch_id;
        $delivery_boyLedger['delivery_boy_id'] = $deliveryboyId;
        $delivery_boyLedger['invoice_id'] = $invoiceId;
        $delivery_boyLedger['package_id'] = $packageId;
        $delivery_boyLedger['trangaction_type'] = 'Dr.';
        $delivery_boyLedger['particulars'] = 'Delivery charge';
        $delivery_boyLedger['amount'] = $delBoyChargefinal;
        $delivery_boyLedger['rest_balance'] = $restDelBalance;
        $delivery_boyLedger['createdBy'] = Auth()->user_id;
        $delivery_boyLedger['createdDtm'] = date('Y-m-d h:i:s');
        $this->ledgerdeliveryboyModel->insert($delivery_boyLedger);


        //delivery boy commission insert
        $delBoycommissionData['invoice_id'] = $invoiceId;
        $delBoycommissionData['package_id'] = $packageId;
        $delBoycommissionData['delivery_boy_id'] = $deliveryboyId;
        $delBoycommissionData['commission'] = $delBoyChargefinal;
        $delBoycommissionData['createdBy'] = Auth()->user_id;
        $delBoycommissionData['createdDtm'] = date('Y-m-d h:i:s');
        $this->commissionModel->insert($delBoycommissionData);
    }
    public function invoice_package_status_check($invoiceId,$packageId){
        $query = $this->packageModel->where('invoice_id',$invoiceId)->where('package_id !=',$packageId)->where('status !=','1')->countAllResults();
        if (empty($query)){
            return true;
        }else{
            return false;
        }
    }









}