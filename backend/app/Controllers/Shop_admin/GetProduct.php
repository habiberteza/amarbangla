<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Models\BankModel;
use App\Libraries\Permission;
use App\Models\DemocategoryModel;
use App\Models\DemoproductsModel;
use App\Models\LedgerbankModel;
use App\Models\LedgerloanModel;
use App\Models\LedgernagodanModel;
use App\Models\LedgersuppliersModel;
use App\Models\LoanproviderModel;
use App\Models\ProductcategoryModel;
use App\Models\ProductsModel;
use App\Models\ShopsModel;
use App\Models\StoresModel;
use App\Models\SuppliersModel;

class GetProduct extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $loanproviderModel;
    protected $ledgerloanModel;
    protected $suppliersModel;
    protected $ledgersuppliersModel;
    protected $bankModel;
    protected $ledgerbankModel;
    protected $shopsModel;
    protected $ledgernagodanModel;
    protected $democategoryModel;
    protected $demoproductsModel;
    protected $storesModel;
    protected $productsModel;
    protected $productcategoryModel;
    private $module_name = 'Settings';
    public function __construct()
    {
        $this->loanproviderModel = new LoanproviderModel();
        $this->ledgerloanModel = new LedgerloanModel();
        $this->suppliersModel = new SuppliersModel();
        $this->ledgersuppliersModel = new LedgersuppliersModel();
        $this->bankModel = new BankModel();
        $this->ledgerbankModel = new LedgerbankModel();
        $this->shopsModel = new ShopsModel();
        $this->ledgernagodanModel = new LedgernagodanModel();
        $this->democategoryModel = new DemocategoryModel();
        $this->demoproductsModel = new DemoproductsModel();
        $this->storesModel = new StoresModel();
        $this->productsModel = new ProductsModel();
        $this->productcategoryModel = new ProductcategoryModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){

        $data['product'] = $this->demoproductsModel->paginate(12);
        $pager = $this->demoproductsModel->pager;
        $data['pager'] = $pager->links('default','default_full');

        $data['category'] = $this->democategoryModel->where('parent_pro_cat', '0')->findAll();


        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Get_product/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function account_holder_action(){

        $data['sch_id'] = Auth()->sch_id;
        $data['loan_pro_id'] = $this->request->getPost('loan_pro_id');
        $data['particulars'] = $this->request->getPost('particulars');
        $data['trangaction_type'] = $this->request->getPost('trangaction_type');
        $data['amount'] = $this->request->getPost('amount');


        $this->validation->setRules([
            'loan_pro_id' => ['label' => 'Account Holder', 'rules' => 'required'],
            'particulars' => ['label' => 'particulars', 'rules' => 'required'],
            'trangaction_type' => ['label' => 'trangaction_type', 'rules' => 'required'],
            'amount' => ['label' => 'amount', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/get_product?active=account');
        } else {
            DB()->transStart();
            $loanData['loan_pro_id'] = $data['loan_pro_id'];
            $loanData['balance'] = $data['amount'];
            $this->loanproviderModel->update($loanData['loan_pro_id'], $loanData);

            $this->ledgerloanModel->delete($data['loan_pro_id']);


            $dataLedg['sch_id'] = Auth()->sch_id;
            $dataLedg['loan_pro_id'] = $data['loan_pro_id'];
            $dataLedg['particulars'] = $data['particulars'];
            $dataLedg['trangaction_type'] = 'Dr.';
            $dataLedg['amount'] = $data['amount'];
            $dataLedg['rest_balance'] = $data['amount'];
            $dataLedg['createdBy'] = Auth()->user_id;
            $dataLedg['createdDtm'] = date('Y-m-d h:i:s');

            $this->ledgerloanModel->insert($dataLedg);

            DB()->transComplete();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/get_product?active=account');

        }
    }

    public function suppliers_action(){

        $data['sch_id'] = Auth()->sch_id;
        $data['supplier_id'] = $this->request->getPost('supplier_id');
        $data['particulars'] = $this->request->getPost('particulars');
        $data['trangaction_type'] = $this->request->getPost('trangaction_type');
        $data['amount'] = $this->request->getPost('amount');


        $this->validation->setRules([
            'supplier_id' => ['label' => 'supplier', 'rules' => 'required'],
            'particulars' => ['label' => 'particulars', 'rules' => 'required'],
            'trangaction_type' => ['label' => 'trangaction_type', 'rules' => 'required'],
            'amount' => ['label' => 'amount', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/get_product?active=suppliers');
        } else {
            DB()->transStart();
            $upData['supplier_id'] = $data['supplier_id'];
            $upData['balance'] = $data['amount'];
            $this->suppliersModel->update($upData['supplier_id'], $upData);

            $this->ledgersuppliersModel->delete($data['supplier_id']);


            $dataLedg['sch_id'] = Auth()->sch_id;
            $dataLedg['supplier_id'] = $data['supplier_id'];
            $dataLedg['particulars'] = $data['particulars'];
            $dataLedg['trangaction_type'] = 'Dr.';
            $dataLedg['amount'] = $data['amount'];
            $dataLedg['rest_balance'] = $data['amount'];
            $dataLedg['createdBy'] = Auth()->user_id;
            $dataLedg['createdDtm'] = date('Y-m-d h:i:s');

            $this->ledgersuppliersModel->insert($dataLedg);

            DB()->transComplete();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/get_product?active=suppliers');

        }
    }

    public function bank_action(){

        $data['sch_id'] = Auth()->sch_id;
        $data['bank_id'] = $this->request->getPost('bank_id');
        $data['particulars'] = $this->request->getPost('particulars');
        $data['trangaction_type'] = $this->request->getPost('trangaction_type');
        $data['amount'] = $this->request->getPost('amount');


        $this->validation->setRules([
            'bank_id' => ['label' => 'bank', 'rules' => 'required'],
            'particulars' => ['label' => 'particulars', 'rules' => 'required'],
            'trangaction_type' => ['label' => 'trangaction_type', 'rules' => 'required'],
            'amount' => ['label' => 'amount', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/get_product?active=bank');
        } else {
            DB()->transStart();
            $upData['bank_id'] = $data['bank_id'];
            $upData['balance'] = $data['amount'];
            $this->bankModel->update($upData['bank_id'], $upData);

            $this->ledgerbankModel->delete($data['bank_id']);


            $dataLedg['sch_id'] = Auth()->sch_id;
            $dataLedg['bank_id'] = $data['bank_id'];
            $dataLedg['particulars'] = $data['particulars'];
            $dataLedg['trangaction_type'] = 'Dr.';
            $dataLedg['amount'] = $data['amount'];
            $dataLedg['rest_balance'] = $data['amount'];
            $dataLedg['createdBy'] = Auth()->user_id;
            $dataLedg['createdDtm'] = date('Y-m-d h:i:s');
            $this->ledgerbankModel->insert($dataLedg);

            DB()->transComplete();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/get_product?active=bank');

        }
    }
    public function cash_action(){

        $data['sch_id'] = Auth()->sch_id;
        $data['particulars'] = $this->request->getPost('particulars');
        $data['trangaction_type'] = $this->request->getPost('trangaction_type');
        $data['amount'] = $this->request->getPost('amount');


        $this->validation->setRules([
            'particulars' => ['label' => 'particulars', 'rules' => 'required'],
            'trangaction_type' => ['label' => 'trangaction_type', 'rules' => 'required'],
            'amount' => ['label' => 'amount', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/get_product?active=cash');
        } else {
            DB()->transStart();
            $upData['sch_id'] = Auth()->sch_id;
            $upData['cash'] = $data['amount'];
            $this->shopsModel->update($upData['sch_id'], $upData);

            $this->ledgernagodanModel->delete($data['sch_id']);


            $dataLedg['sch_id'] = Auth()->sch_id;
            $dataLedg['particulars'] = $data['particulars'];
            $dataLedg['trangaction_type'] = 'Dr.';
            $dataLedg['amount'] = $data['amount'];
            $dataLedg['rest_balance'] = $data['amount'];
            $dataLedg['createdBy'] = Auth()->user_id;
            $dataLedg['createdDtm'] = date('Y-m-d h:i:s');
            $this->ledgernagodanModel->insert($dataLedg);

            DB()->transComplete();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/get_product?active=cash');

        }
    }

    public function update_action(){
        $userId = Auth()->user_id;;
        $shopId = Auth()->sch_id;;
        $storeId = $this->storesModel->where('sch_id', $shopId)->where('is_default', 1)->first()->store_id;
        $supplierId = $this->request->getPost('supplier_id');
        $proName = $this->request->getPost('name');
        $catId = $this->request->getPost('prod_cat_id');
        $this_prod_id = $this->request->getPost('pro_id');

        $response = 0;
        DB()->transStart();

        $checkProduct = $this->productsModel->where('sch_id', $shopId)->where('deleted', null)->where( 'name', $proName)->countAllResults();
        if ($checkProduct == 0) {
            $pCId = get_data_by_id('parent_pro_cat', 'demo_category', 'cat_id', $catId);
            $parentCatName = get_data_by_id('product_category', 'demo_category', 'cat_id', $pCId);
            $catName = get_data_by_id('product_category', 'demo_category', 'cat_id', $catId);
            $description = get_data_by_id('description', 'demo_products', 'id', $this_prod_id);


            //parent category create(start)
            $parCatCheck = $this->productcategoryModel->where('sch_id', $shopId)->where('parent_pro_cat', 0)->where( 'product_category', $parentCatName);
            if ( !empty($parCatCheck->first()->prod_cat_id)) {
                $parentCatId = $parCatCheck->first()->prod_cat_id;
            } else {
                $parentCreate['sch_id'] = $shopId;
                $parentCreate['product_category'] = $parentCatName;
                $parentCreate['status'] = '1';
                $this->productcategoryModel->insert($parentCreate);
                $parentCatId = $this->productcategoryModel->getInsertID();
            }
            //parent category create(end)


            //sub category create (start)
            $subCatCheck = $this->productcategoryModel->where('sch_id', $shopId)->where('parent_pro_cat !=' , 0)->where('product_category', $catName);
            if (!empty($subCatCheck->first()->prod_cat_id)) {
                $subCatId = $subCatCheck->first()->prod_cat_id;
            } else {
                $subCreate['sch_id'] = $shopId;
                $subCreate['product_category'] = $catName;
                $subCreate['parent_pro_cat'] = $parentCatId;
                $subCreate['status'] = '1';
                $this->productcategoryModel->insert($subCreate);
                $subCatId = $this->productcategoryModel->getInsertID();
            }
            //sub category create (end)


            //insert product(start)
            $data['sch_id'] = $shopId;
            $data['store_id'] = $storeId;
            $data['name'] = $proName;
            $data['demo_id'] = $this_prod_id;
            $data['unit'] = $this->request->getPost('unit');
            $data['size'] = $this->request->getPost('size');
            $data['description'] = $description;
            $data['quantity'] = $this->request->getPost('qty');
            $data['purchase_price'] = $this->request->getPost('purchase_price');
            $data['selling_price'] = $this->request->getPost('selling_price');
            $data['supplier_id'] = $supplierId;
            $data['prod_cat_id'] = $subCatId;
            $data['purchase_type'] = '1';
            $data['createdBy'] = $userId;
            $data['createdDtm'] = date('Y-m-d h:i:s');
            if ($this->productsModel->insert($data)) {
                $response = 1;
            }
            //insert product(end)
        }

        DB()->transComplete();

        print $response;
    }


    public function product_show_key_search()
    {
        $keyWord = trim((string) $this->request->getPost("proId"));
        if (empty($keyWord)) {
            $data['product'] = $this->demoproductsModel->limit(30)->findAll();
        } else {
            $where = "(`name` LIKE '%" . addslashes($keyWord) . "%' OR `id` LIKE '%" . addslashes($keyWord) . "%')";
            $data['product'] = $this->demoproductsModel->where($where)->limit(50)->findAll();
        }

        echo view('Shop_admin/Get_product/product', $data);
    }

    public function product_show_by_category()
    {
        $keyWord = $this->request->getPost("cat_id");
        $parent = checkDemoParentCategory($keyWord);

        if ($parent == 0) {
            $parentget = $this->democategoryModel->where('parent_pro_cat', $keyWord)->findAll();
            $catIds = array_map(fn($c) => $c->cat_id, $parentget);
            if (!empty($catIds)) {
                $data['product'] = $this->demoproductsModel->whereIn('prod_cat_id', $catIds)->limit(50)->findAll();
            } else {
                $data['product'] = [];
            }
            echo view('Shop_admin/Get_product/product', $data);
        } else {
            $data['product'] = $this->demoproductsModel->where('prod_cat_id', $keyWord)->limit(50)->findAll();
            echo view('Shop_admin/Get_product/product', $data);
        }
    }










}