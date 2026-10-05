<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Mycart;
use App\Libraries\Permission;
use App\Models\BankModel;
use App\Models\LedgerbankModel;
use App\Models\LedgernagodanModel;
use App\Models\LedgersuppliersModel;
use App\Models\ProductcategoryModel;
use App\Models\ProductsModel;
use App\Models\PurchaseitemModel;
use App\Models\PurchaseModel;
use App\Models\ShopsModel;
use App\Models\StoresModel;
use App\Models\SuppliersModel;

class Purchase extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $crop;
    protected $cart;
    protected $purchaseModel;
    protected $purchaseitemModel;
    protected $productcategoryModel;
    protected $suppliersModel;
    protected $ledgersuppliersModel;
    protected $shopsModel;
    protected $ledgernagodanModel;
    protected $bankModel;
    protected $productsModel;
    protected $ledgerbankModel;
    protected $storesModel;

    private $module_name = 'Purchase';
    public function __construct()
    {
        $this->purchaseModel = new PurchaseModel();
        $this->purchaseitemModel = new PurchaseitemModel();
        $this->productcategoryModel = new ProductcategoryModel();
        $this->suppliersModel = new SuppliersModel();
        $this->ledgersuppliersModel = new LedgersuppliersModel();
        $this->shopsModel = new ShopsModel();
        $this->ledgernagodanModel = new LedgernagodanModel();
        $this->bankModel = new BankModel();
        $this->ledgerbankModel = new LedgerbankModel();
        $this->productsModel = new ProductsModel();
        $this->storesModel = new StoresModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->crop = \Config\Services::image();
        $this->permission = new permission();
        $this->cart = new Mycart();
    }

    public function index(){


        $data['purchase'] = $this->purchaseModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('purchase_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Purchase/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function view($purchase_id){

        $data['purchase'] = $this->purchaseModel->where('purchase_id',$purchase_id)->first();
        $data['purchaseItem'] = $this->purchaseitemModel->where('purchase_id',$purchase_id)->findAll();
        $data['purchaseId'] = $purchase_id;

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['read']) and $permi['create'] == 1) {
            echo view('Shop_admin/Purchase/view',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }

    }
    public function create(){


        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/Purchase/create');
        }else{
            echo view('Shop_admin/no_permission');
        }

    }
    public function create_action(){

        $data['sch_id'] = Auth()->sch_id;
        $data['type_id'] = $this->request->getPost('type_id');
        $data['supplier_id'] = $this->request->getPost('supplier_id');


        $this->validation->setRules([
            'type_id' => ['label' => 'type', 'rules' => 'required'],
            'supplier_id' => ['label' => 'supplier', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/purchase_create');
        } else {

            $dataPur = array(
                'sch_id' => Auth()->sch_id,
                'supplier_id' => $data['supplier_id'],
                'createdBy' => Auth()->user_id,
                'createdDtm' => date('Y-m-d h:i:s'),

            );
            $this->purchaseModel->insert($dataPur);
            $purchaseId = $this->purchaseModel->getInsertID();

            $purchaseData = array(
                'supplierId' => $data['supplier_id'],
                'purchaseId' => $purchaseId,
            );
            $this->session->set($purchaseData);

            if ($data['type_id'] == 1) {
                return redirect()->to('shop_admin/purchase_new_product');
            } else {
                return redirect()->to('shop_admin/purchase_existing_product');
            }
        }
    }
    public function new_product(){
        $data['cart'] = $this->cart->contents();
        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Purchase/new_product',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }

    public function get_sub_category(){
        $category_id = $this->request->getPost('category_id');
        $query = $this->productcategoryModel->where('sch_id', Auth()->sch_id)->where('parent_pro_cat', $category_id)->where('deleted', null)->findAll();
        $options = '';
        foreach ($query as $row) {
            $options .= '<option value="' . $row->prod_cat_id . '" ';
            $options .= '>' . $row->product_category . '</option>';
        }
        print $options;
    }
    public function add_to_cart(){
        $subCatId = $this->request->getPost('subCatId');
        $category = $this->request->getPost('category');
        $name = $this->request->getPost('name');
        $unit = $this->request->getPost('unit');
        $price = $this->request->getPost('price');
        $salePrice = $this->request->getPost('salePrice');
        $qty = $this->request->getPost('qty');

        $i = count($this->cart->contents());

        $data['id'] = ++$i;
        $data['name'] = strval($name);
        $data['unit'] = $unit;
        $data['qty'] = $qty;
        $data['price'] = $price;
        $data['salePrice'] = $salePrice;

        if (!empty($subCatId)) {
            $data['cat_id'] = $subCatId;
        } else {
            $data['cat_id'] = $category;
        }

        $this->cart->insert($data);
    }
    public function remove_cart(){
        $id = $this->request->getPost('id');
        $this->cart->remove($id);
    }
    public function clear_cart(){
        $this->cart->destroy();
    }
    public function check_shop_balance(){
        $cash = $this->request->getPost('cash');
        $balance = Auth()->cash;

        if ($cash > $balance) {
            print '<span style="color:red">Balance is too low</span>';
        } else {
            print '<span style="color:green">Balance is ok</span>';
        }
    }

    public function check_bank_balance(){
        $amount = $this->request->getPost('balance');
        $bankId = $this->request->getPost('bank_id');
        $bankBalance = get_data_by_id('balance', 'bank', 'bank_id', $bankId);
        if ($amount > $bankBalance) {
            print '<span style="color:red">Balance is too low</span>';
        } else {
            print '<span style="color:green">Balance is ok</span>';
        }
    }
    public function action(){
        $userId = Auth()->user_id;
        $shopId = Auth()->sch_id;

        $purchaseId = $this->request->getPost('purchase_id');
        $supplierId = $this->request->getPost('supplier_id');

        $totalPrice = str_replace(',', '', $this->request->getPost('totalPrice'));
        $cashAmount = str_replace(',', '', $this->request->getPost('cash'));
        $bankAmount = str_replace(',', '', $this->request->getPost('bank'));
        $dueAmount = str_replace(',', '', $this->request->getPost('due'));
        $bankId = $this->request->getPost('bank_id');

        $name = $this->request->getPost('name[]');
        $number = count($name);

        DB()->transStart();

        //purchase total price calculate to suppliers balance and update suppliers balance or create suppliers ledger (start)
        $supplierCash = get_data_by_id('balance', 'suppliers', 'supplier_id', $supplierId);
        $newCash = $supplierCash - $totalPrice;


        $suppData['supplier_id'] = $supplierId;
        $suppData['balance'] = $newCash;
        $suppData['updatedBy'] = $userId;
        $this->suppliersModel->update($suppData['supplier_id'], $suppData);


        //create suppliers ledger
        $lgSuplData['sch_id'] = $shopId;
        $lgSuplData['supplier_id'] = $supplierId;
        $lgSuplData['purchase_id'] = $purchaseId;
        $lgSuplData['trangaction_type'] = 'Dr.';
        $lgSuplData['particulars'] = 'Purchase Cash Due';
        $lgSuplData['amount'] = $totalPrice;
        $lgSuplData['rest_balance'] = $newCash;
        $lgSuplData['createdBy'] = $userId;
        $lgSuplData['createdDtm'] = date('Y-m-d h:i:s');
        $this->ledgersuppliersModel->insert($lgSuplData);
        //purchase total price calculate to suppliers balance and update suppliers balance or create suppliers ledger (end)

        if ($cashAmount > 0) {

            //purchase pay cash amount calculate to shops cash and update shops cash or create ledger_nagodan statment in ledger_nagodan table (start)
            $shopsCash = get_data_by_id('cash', 'shops', 'sch_id', $shopId);

            if ($shopsCash >= $cashAmount) {
                $upCahs = $shopsCash - $cashAmount;

                $shopsData['sch_id'] = $shopId;
                $shopsData['cash'] = $upCahs;
                $shopsData['updatedBy'] = $userId;
                $this->shopsModel->update($shopsData['sch_id'], $shopsData);

                //nagodan ledger create
                $lgNagData['sch_id'] = $shopId;
                $lgNagData['purchase_id'] = $purchaseId;
                $lgNagData['particulars'] = 'Purchase Cash Pay';
                $lgNagData['trangaction_type'] = 'Cr.';
                $lgNagData['amount'] = $cashAmount;
                $lgNagData['rest_balance'] = $upCahs;
                $lgNagData['createdBy'] = $userId;
                $lgNagData['createdDtm'] = date('Y-m-d h:i:s');
                $this->ledgernagodanModel->insert($lgNagData);
                //purchase pay cash amount calculate to shops cash and update shops cash or create ledger_nagodan statment in ledger_nagodan table (end)


                //purchase pay cash amount calculate to suppliers balance and update suppliers balance or create supplier ledger in ledger_suppliers table (start)
                $supplierccCash = get_data_by_id('balance', 'suppliers', 'supplier_id', $supplierId);
                $suppCash = $supplierccCash + $cashAmount;


                $cashsuppData['supplier_id'] = $supplierId;
                $cashsuppData['balance'] = $suppCash;
                $cashsuppData['updatedBy'] = $userId;
                $this->suppliersModel->update($cashsuppData['supplier_id'], $cashsuppData);

                //suppliers ledger create
                $lgSuplData['sch_id'] = $shopId;
                $lgSuplData['supplier_id'] = $supplierId;
                $lgSuplData['purchase_id'] = $purchaseId;
                $lgSuplData['particulars'] = 'Purchase Cass Pay';
                $lgSuplData['trangaction_type'] = 'Cr.';
                $lgSuplData['amount'] = $cashAmount;
                $lgSuplData['rest_balance'] = $suppCash;
                $lgSuplData['createdBy'] = $userId;
                $lgSuplData['createdDtm'] = date('Y-m-d h:i:s');
                $this->ledgersuppliersModel->insert($lgSuplData);
            }
            //purchase pay cash amount calculate to suppliers balance and update suppliers balance or create supplier ledger in ledger_suppliers table (end)
        }

        if ($bankAmount > 0) {

            //purchase pay bank amount calculate to bank balance and update bank balance or create bank ledger in ledger_bank table (start)
            $bankCash = get_data_by_id('balance', 'bank', 'bank_id', $bankId);

            if ($bankCash >= $bankAmount) {

                $upCahs = $bankCash - $bankAmount;

                $bankData['bank_id'] = $bankId;
                $bankData['balance'] = $upCahs;
                $bankData['updatedBy'] = $userId;
                $this->bankModel->update($bankData['bank_id'], $bankData);

                //bank ledger create
                $lgBankData['sch_id'] = $shopId;
                $lgBankData['bank_id'] = $bankId;
                $lgBankData['purchase_id'] = $purchaseId;
                $lgBankData['trangaction_type'] = 'Cr.';
                $lgBankData['particulars'] = 'Purchase Bank Pay';
                $lgBankData['amount'] = $bankAmount;
                $lgBankData['rest_balance'] = $upCahs;
                $lgBankData['createdBy'] = $userId;
                $lgBankData['createdDtm'] = date('Y-m-d h:i:s');
                $this->ledgerbankModel->insert($lgBankData);
                //purchase pay bank amount calculate to bank balance and update bank balance or create bank ledger in ledger_bank table (end)


                //purchase pay bank amount calculate to suppliers balance and update suppliers balance or create supplier ledger in ledger_suppliers table (start)
                $supplierbbCash = get_data_by_id('balance', 'suppliers', 'supplier_id', $supplierId);
                $suppBaCash = $supplierbbCash + $bankAmount;


                $banksuppData['supplier_id'] = $supplierId;
                $banksuppData['balance'] = $suppBaCash;
                $banksuppData['updatedBy'] = $userId;
                $this->db->update($banksuppData['supplier_id'], $banksuppData);


                //suppliers ledger create
                $lgSuplData['sch_id'] = $shopId;
                $lgSuplData['supplier_id'] = $supplierId;
                $lgSuplData['purchase_id'] = $purchaseId;
                $lgSuplData['particulars'] = 'Purchase Bank Pay';
                $lgSuplData['trangaction_type'] = 'Cr.';
                $lgSuplData['amount'] = $bankAmount;
                $lgSuplData['rest_balance'] = $suppBaCash;
                $lgSuplData['createdBy'] = $userId;
                $lgSuplData['createdDtm'] = date('Y-m-d h:i:s');
                $this->ledgersuppliersModel->insert($lgSuplData);
            }
            //purchase pay bank amount calculate to suppliers balance and update suppliers balance or create supplier ledger in ledger_suppliers table (end)
        }


        //purchase product insert in product table and purchase item table (start)

        $store = $this->storesModel->where('sch_id', $shopId)->where('is_default', 1)->first();
        $storeId = $store->store_id;

        for ($i = 0; $i < $number; $i++) {
            //insert purchase product

            $data['sch_id'] = $shopId;
            $data['store_id'] = $storeId;
            $data['name'] = $name[$i];
            $data['unit'] = $this->request->getPost('unit[]')[$i];
            $data['quantity'] = $this->request->getPost('quantity[]')[$i];
            $data['purchase_price'] = $this->request->getPost('purchase_price[]')[$i];
            $data['selling_price'] = $this->request->getPost('selling_price[]')[$i];
            $data['supplier_id'] = $supplierId;
            $data['prod_cat_id'] = $this->request->getPost('prod_cat_id[]')[$i];
            $data['createdBy'] = $userId;
            $data['createdDtm'] = date('Y-m-d h:i:s');
            $this->productsModel->insert($data);
            $prodId = $this->productsModel->getInsertID();



            //insert purchase Item in purchase item table
            $purchasePrice = get_data_by_id('purchase_price', 'products', 'prod_id', $prodId);
            $quantity = get_data_by_id('quantity', 'products', 'prod_id', $prodId);

            $total_price = $quantity * $purchasePrice;


            $purchaseData['purchase_id'] = $purchaseId;
            $purchaseData['prod_id'] = $prodId;
            $purchaseData['purchase_price'] = $purchasePrice;
            $purchaseData['quantity'] = $quantity;
            $purchaseData['total_price'] = $total_price;
            $purchaseData['createdBy'] = $userId;
            $this->purchaseitemModel->insert($purchaseData);
        }
        //purchase product insert in product table and purchase item table (end)


        //purchase all pay amount detail Update in purchase table(start)
        $parsData['purchase_id'] = $purchaseId;
        $parsData['amount'] = $totalPrice;
        $parsData['nagad_paid'] = $cashAmount;
        $parsData['bank_paid'] = $bankAmount;
        $parsData['bank_id'] = $bankId;
        $parsData['due'] = $dueAmount;
        $parsData['updatedBy'] = $userId;
        $this->purchaseModel->update($parsData['purchase_id'], $parsData);
        //purchase all pay amount detail Update in purchase table(end)

        DB()->transComplete();

        $this->cart->destroy();
        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('shop_admin/purchase');
    }

    public function existing_product(){
        $shopId = Auth()->sch_id;
        $data['product'] = $this->productsModel->where('supplier_id',$this->session->supplierId)->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Purchase/existing_product',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }

    public function existing_action(){
        $userId = Auth()->user_id;
        $shopId = Auth()->sch_id;

        $purchaseId = $this->request->getPost('purchase_id');
        $supplierId = $this->request->getPost('supplier_id');

        $returnchecked = $this->request->getPost('returnchecked[]');
        $products = $this->request->getPost('prod_id[]');
        $productQuantity = $this->request->getPost('quantity[]');
        $productPrice = $this->request->getPost('purchase_price[]');
        //payment data
        $totalPrice = str_replace(',', '', $this->request->getPost('totalPrice'));
        $cashAmount = str_replace(',', '', $this->request->getPost('cash'));
        $bankAmount = str_replace(',', '', $this->request->getPost('bank'));
        $dueAmount = str_replace(',', '', $this->request->getPost('due'));
        $bankId = $this->request->getPost('bank_id');

        DB()->transStart();
        if ($totalPrice > 0) {
            //purchase total price calculate to suppliers balance and update suppliers balance or create suppliers ledger (start)
            $supplierCash = get_data_by_id('balance', 'suppliers', 'supplier_id', $supplierId);
            $newCash = $supplierCash - $totalPrice;


            $suppData['supplier_id'] = $supplierId;
            $suppData['balance'] = $newCash;
            $suppData['updatedBy'] = $userId;
            $this->suppliersModel->update($suppData['supplier_id'], $suppData);

            //create suppliers ledger
            $lgSupData['sch_id'] = $shopId;
            $lgSupData['supplier_id'] = $supplierId;
            $lgSupData['purchase_id'] = $purchaseId;
            $lgSupData['particulars'] = 'Purchase Cash Due';
            $lgSupData['trangaction_type'] = 'Dr.';
            $lgSupData['amount'] = $totalPrice;
            $lgSupData['rest_balance'] = $newCash;
            $lgSupData['createdBy'] = $userId;
            $lgSupData['createdDtm'] = date('Y-m-d h:i:s');
            $this->ledgersuppliersModel->insert($lgSupData);
            //purchase total price calculate to suppliers balance and update suppliers balance or create suppliers ledger (end)


            if ($cashAmount > 0) {

                //purchase pay cash amount calculate to shops cash and update shops cash or create ledger_nagodan statement in ledger_nagodan table (start)
                $shopsCash = get_data_by_id('cash', 'shops', 'sch_id', $shopId);

                if ($shopsCash >= $cashAmount) {
                    $upCahs = $shopsCash - $cashAmount;

                    $shopsData['sch_id'] = $shopId;
                    $shopsData['cash'] = $upCahs;
                    $shopsData['updatedBy'] = $userId;
                    $this->shopsModel->update($shopsData['sch_id'], $shopsData);

                    //nagodan ledger create
                    $lgNagData['sch_id'] = $shopId;
                    $lgNagData['purchase_id'] = $purchaseId;
                    $lgNagData['trangaction_type'] = 'Cr.';
                    $lgNagData['particulars'] = 'Purchase Cash Pay';
                    $lgNagData['amount'] = $cashAmount;
                    $lgNagData['rest_balance'] = $upCahs;
                    $lgNagData['createdBy'] = $userId;
                    $lgNagData['createdDtm'] = date('Y-m-d h:i:s');
                    $this->ledgernagodanModel->insert($lgNagData);
                    //purchase pay cash amount calculate to shops cash and update shops cash or create ledger_nagodan statement in ledger_nagodan table (end)


                    //purchase pay cash amount calculate to suppliers balance and update suppliers balance or create supplier ledger in ledger_suppliers table (start)
                    $supplierccCash = get_data_by_id('balance', 'suppliers', 'supplier_id', $supplierId);
                    $suppCash = $supplierccCash + $cashAmount;


                    $cashsuppData['supplier_id'] = $supplierId;
                    $cashsuppData['balance'] = $suppCash;
                    $cashsuppData['updatedBy'] = $userId;
                    $cashsuppData['createdDtm'] = date('Y-m-d h:i:s');
                    $this->suppliersModel->update($cashsuppData['supplier_id'], $cashsuppData);

                    //suppliers ledger create
                    $lgSuplData['sch_id'] = $shopId;
                    $lgSuplData['supplier_id'] = $supplierId;
                    $lgSuplData['purchase_id'] = $purchaseId;
                    $lgSuplData['particulars'] = 'Purchase Cash Pay';
                    $lgSuplData['trangaction_type'] = 'Cr.';
                    $lgSuplData['amount'] = $cashAmount;
                    $lgSuplData['rest_balance'] = $suppCash;
                    $lgSuplData['createdBy'] = $userId;
                    $lgSuplData['createdDtm'] = date('Y-m-d h:i:s');
                    $this->ledgersuppliersModel->insert($lgSuplData);
                }
                //purchase pay cash amount calculate to suppliers balance and update suppliers balance or create supplier ledger in ledger_suppliers table (end)
            }

            if ($bankAmount > 0) {

                //purshase pay bank amountcalculet to bank balance and update bank balance or create bank ledger in ledger_bank table (start)
                $bankCash = get_data_by_id('balance', 'bank', 'bank_id', $bankId);

                if ($bankCash >= $bankAmount) {

                    $upCahs = $bankCash - $bankAmount;

                    $bankData['bank_id'] = $bankId;
                    $bankData['balance'] = $upCahs;
                    $bankData['updatedBy'] = $userId;
                    $bankData['createdDtm'] = date('Y-m-d h:i:s');
                    $this->bankModel->update($bankData['bank_id'], $bankData);

                    //bank ledger create
                    $lgBankData['sch_id'] = $shopId;
                    $lgBankData['bank_id'] = $bankId;
                    $lgBankData['purchase_id'] = $purchaseId;
                    $lgBankData['trangaction_type'] = 'Cr.';
                    $lgBankData['particulars'] = 'Purchase Bank Pay';
                    $lgBankData['amount'] = $bankAmount;
                    $lgBankData['rest_balance'] = $upCahs;
                    $lgBankData['createdBy'] = $userId;
                    $lgBankData['createdDtm'] = date('Y-m-d h:i:s');
                    $this->ledgerbankModel->insert($lgBankData);
                    //purchase pay bank amount calculate to bank balance and update bank balance or create bank ledger in ledger_bank table (end)


                    //purchase pay bank amount calculate to suppliers balance and update suppliers balance or create supplier ledger in ledger_suppliers table (start)
                    $supplierbbCash = get_data_by_id('balance', 'suppliers', 'supplier_id', $supplierId);
                    $suppBaCash = $supplierbbCash + $bankAmount;



                    $banksuppData['supplier_id'] = $supplierId;
                    $banksuppData['balance'] = $suppBaCash;
                    $banksuppData['updatedBy'] = $userId;
                    $this->suppliersModel->update($banksuppData['supplier_id'], $banksuppData);

                    //suppliers ledger create
                    $lgSuplData['sch_id'] = $shopId;
                    $lgSuplData['supplier_id'] = $supplierId;
                    $lgSuplData['purchase_id'] = $purchaseId;
                    $lgSuplData['particulars'] = 'Purchase Bank Pay';
                    $lgSuplData['trangaction_type'] = 'Cr.';
                    $lgSuplData['amount'] = $bankAmount;
                    $lgSuplData['rest_balance'] = $suppBaCash;
                    $lgSuplData['createdBy'] = $userId;
                    $lgSuplData['createdDtm'] = date('Y-m-d h:i:s');
                    $this->ledgersuppliersModel->insert($lgSuplData);
                }
                //purchase pay bank amount calculate to suppliers balance and update suppliers balance or create supplier ledger in ledger_suppliers table (end)
            }


            //purchase product insert in product table and purchase item table (start)
            foreach ($returnchecked as $value) {
                $k = array_flip($products);
                $key = $k[$value];

                $product_id = $value;
                $quantity = $productQuantity[$key];
                $price = $productPrice[$key];
                //Update each product quantity and price
                $exixQunt = get_data_by_id('quantity', 'products', 'prod_id', $product_id);
                $newQunt = $exixQunt + $quantity;



                $data['prod_id'] = $product_id;
                $data['quantity'] = $newQunt;
                $data['purchase_price'] = $price;
                $this->productsModel->update($data['prod_id'], $data);

                //inset purchase Item in purchase item table
                $total_price = $price * $quantity;

                $purItemData['prod_id'] = $product_id;
                $purItemData['purchase_id'] = $purchaseId;
                $purItemData['quantity'] = $quantity;
                $purItemData['purchase_price'] = $price;
                $purItemData['total_price'] = $total_price;
                $purItemData['createdBy'] = $userId;
                $purItemData['createdDtm'] = date('Y-m-d h:i:s');
                $this->purchaseitemModel->insert($purItemData);
            }
            //purchase product insert in product table and purchase item table (end)


            //purchase all pay amount detail Update in purchase table(start)
            $parsData['purchase_id'] = $purchaseId;
            $parsData['amount'] = $totalPrice;
            $parsData['nagad_paid'] = $cashAmount;
            $parsData['bank_paid'] = $bankAmount;
            $parsData['bank_id'] = $bankId;
            $parsData['due'] = $dueAmount;
            $parsData['updatedBy'] = $userId;
            $this->purchaseModel->update($parsData['purchase_id'], $parsData);
            //purchase all pay amount detail Update in purchase table(end)
        } else {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Invalid Quantity <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/purchase_existing_product');
        }
        DB()->transComplete();
        
        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('shop_admin/purchase');
    }







}