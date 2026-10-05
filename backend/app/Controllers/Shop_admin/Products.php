<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Models\ProductfeaturesModel;
use App\Models\ProductsModel;
use App\Libraries\Permission;
use App\Models\ProductcategoryModel;
use App\Models\ProductspecialModel;
use App\Models\RelatedgroupproductModel;

class Products extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $crop;
    protected $productsModel;
    protected $productcategoryModel;
    protected $productfeaturesModel;
    protected $productspecialModel;
    protected $relatedgroupproductModel;
    private $module_name = 'Products';
    public function __construct()
    {
        $this->productsModel = new ProductsModel();
        $this->productcategoryModel = new ProductcategoryModel();
        $this->productfeaturesModel = new ProductfeaturesModel();
        $this->productspecialModel = new ProductspecialModel();
        $this->relatedgroupproductModel = new RelatedgroupproductModel();
        $this->crop = \Config\Services::image();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){


        $data['product'] = $this->productsModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('prod_id','DESC')->findAll();
        $data['productcategory'] = $this->productcategoryModel->where('sch_id',Auth()->sch_id)->where('parent_pro_cat', 0)->where('status !=', 1)->where('deleted IS NULL')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Products/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function create(){

        $data['bank'] = $this->productsModel->where('sch_id',Auth()->sch_id)->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/Products/create');
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
            $this->productsModel->insert($data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Add Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/bank_create');

        }
    }
    public function update($prod_id){

        $data['product'] = $this->productsModel->where('prod_id',$prod_id)->first();

        $data['features'] = $this->productfeaturesModel->where('prod_id',$prod_id)->first();
        $data['special'] = $this->productspecialModel->where('prod_id',$prod_id)->first();
        $data['relPro'] = $this->relatedgroupproductModel->where('prod_id',$prod_id)->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Products/update', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function update_action(){
        $data['prod_id'] = $this->request->getPost('prod_id');
        $data['name'] = $this->request->getPost('name');
        $data['supplier_id'] = $this->request->getPost('supplier_id');
        $data['serial_number'] = $this->request->getPost('serial_number');
        $data['updatedBy'] = Auth()->user_id;
        $data['updateDtm'] = date('Y-m-d h:i:s');
        $data['store_id'] = empty($this->request->getPost('store_id')) ? NULL : $this->request->getPost('store_id');


        $this->validation->setRules([
            'name' => ['label' => 'name', 'rules' => 'required'],
            'supplier_id' => ['label' => 'Supplier', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/products_update/'.$data['prod_id'].'?active=info');
        }else{
            $this->productsModel->update($data['prod_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/products_update/'.$data['prod_id'].'?active=info');
        }
    }
    public function update_detail_action(){
        $data['prod_id'] = $this->request->getPost('prod_id');
        $data['brand_id'] = empty($this->request->getPost('brand_id')) ? NULL : $this->request->getPost('brand_id');
        $data['selling_price'] = $this->request->getPost('selling_price');
        $data['seller_commission'] = $this->request->getPost('commission');
        $data['size'] = $this->request->getPost('size');
        $data['description'] = $this->request->getPost('description');
        $data['warranty'] = $this->request->getPost('warranty');
        $data['updatedBy'] = Auth()->user_id;
        $data['updateDtm'] = date('Y-m-d h:i:s');
        
        $subCatId = $this->request->getPost('sub_cat_id');
        $catId = $this->request->getPost('prod_cat_id');
        if (!empty($subCatId)) {
            $data['prod_cat_id'] = $subCatId;
        } else {
            $data['prod_cat_id'] = $catId;
        }


        $this->validation->setRules([
            'prod_id' => ['label' => 'products', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/products_update/'.$data['prod_id'].'?active=detail');
        }else{
            $this->productsModel->update($data['prod_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/products_update/'.$data['prod_id'].'?active=detail');
        }
    }
    public function update_meta_data_action(){
        $data['prod_id'] = $this->request->getPost('prod_id');
        $data['meta_title'] = $this->request->getPost('meta_title');
        $data['meta_keyword'] = $this->request->getPost('meta_keyword');
        $data['meta_description'] = $this->request->getPost('meta_description');
        $data['updatedBy'] = Auth()->user_id;
        $data['updateDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'prod_id' => ['label' => 'products', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/products_update/'.$data['prod_id'].'?active=metaData');
        }else{
            $this->productsModel->update($data['prod_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/products_update/'.$data['prod_id'].'?active=metaData');
        }
    }
    public function update_related_product_action(){
        $data['prod_id'] = $this->request->getPost('prod_id');
        $data['color_family_id'] = $this->request->getPost('color_family_id');
        $data['size'] = $this->request->getPost('size');
        $data['product_code'] = $this->request->getPost('product_code');
        $data['updatedBy'] = Auth()->user_id;
        $data['updateDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'prod_id' => ['label' => 'products', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/products_update/'.$data['prod_id'].'?active=relatedProduct');
        }else{
            $this->productsModel->update($data['prod_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/products_update/'.$data['prod_id'].'?active=relatedProduct');
        }
    }
    public function update_image_action(){
        $data['prod_id'] = $this->request->getPost('prod_id');

        $this->validation->setRules([
            'prod_id' => ['label' => 'products', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/products_update/'.$data['prod_id'].'?active=image');
        }else{

            $target_dir = FCPATH . '/uploads/product_image/'.$data['prod_id'].'/';

            if (!empty($_FILES['userfile']['name'])) {
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                $uploadData = array();
                //new image uplode
                $pic = $this->request->getFileMultiple('userfile');
                $lastKey =  array_key_last($pic);
                foreach ($pic as $key => $val){
                    if ($key != $lastKey) {
                        $namePic = 'product_' . $val->getRandomName();
                        $val->move($target_dir, $namePic);

                        $this->crop->withFile($target_dir . '' . $namePic)->fit(210, 210, 'center')->save($target_dir . '210_' . $namePic,100);
                        $this->crop->withFile($target_dir . '' . $namePic)->fit(150, 150, 'center')->save($target_dir . '150_' . $namePic,100);
                        $this->crop->withFile($target_dir . '' . $namePic)->fit(90, 90, 'center')->save($target_dir . '90_' . $namePic,100);
                        $this->crop->withFile($target_dir . '' . $namePic)->fit(98, 98, 'center')->save($target_dir . '98_' . $namePic,100);
                        $this->crop->withFile($target_dir . '' . $namePic)->fit(70, 70, 'center')->save($target_dir . '70_' . $namePic,100);

                        $uploadData[$key] = $namePic;
                    }
                }
                if (!empty($uploadData)) {
                    $json = json_encode($uploadData);
                    $data['picture'] = $json;
                    $this->productsModel->update($data['prod_id'], $data);
                }

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/products_update/'.$data['prod_id'].'?active=image');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/products_update/'.$data['prod_id'].'?active=image');
            }



        }
    }
    public function update_product_features_action(){
        $data['prod_id'] = $this->request->getPost('prod_id');
        $data['popular'] = !empty($this->request->getPost('popular'))?'1':'0';
        $data['featured'] = !empty($this->request->getPost('featured'))?'1':'0';
        $data['hot'] = !empty($this->request->getPost('hot'))?'1':'0';

        $this->validation->setRules([
            'prod_id' => ['label' => 'products', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/products_update/'.$data['prod_id'].'?active=productFeatures');
        }else{
            $check = $this->productfeaturesModel->where('prod_id',$data['prod_id'])->countAllResults();
            if (empty($check)){
                $this->productfeaturesModel->insert($data);
            }else{
                $this->productfeaturesModel->set($data)->where('prod_id',$data['prod_id'])->update();
            }
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/products_update/'.$data['prod_id'].'?active=productFeatures');
        }
    }
    public function product_special_action(){
        $data['prod_id'] = $this->request->getPost('prod_id');
        $data['special_price'] = $this->request->getPost('special_price');
        $data['start_date'] = $this->request->getPost('start_date');
        $data['end_date'] = $this->request->getPost('end_date');

        $this->validation->setRules([
            'prod_id' => ['label' => 'products', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/products_update/'.$data['prod_id'].'?active=productSpecial');
        }else{
            $check = $this->productspecialModel->where('prod_id',$data['prod_id'])->countAllResults();
            if (empty($check)){
                $this->productspecialModel->insert($data);
            }else{
                $this->productspecialModel->set($data)->where('prod_id',$data['prod_id'])->update();
            }
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/products_update/'.$data['prod_id'].'?active=productSpecial');
        }
    }

    public function status_update($prod_id,$status){
        $data['prod_id'] = $prod_id;
        $data['status'] = $status;

        $this->productsModel->update($data['prod_id'],$data);
        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('shop_admin/products');
    }

    public function check_sub_cat(){
        $userId = Auth()->user_id;
        $shopId = Auth()->sch_id;

        $category_id = $this->request->getPost('category_id');

        $query = $this->productcategoryModel->where('sch_id', $shopId)->where('parent_pro_cat', $category_id)->where('deleted', null)->findAll();

        $options = '<option value="">Please Select</option>';
        foreach ($query as $row) {
            $options .= '<option value="' . $row->prod_cat_id . '" ';
            $options .= '>' . $row->product_category . '</option>';
        }
        print $options;

    }

    public function price_update(){

        $data['category'] = $this->productcategoryModel->where('sch_id', Auth()->sch_id)->where('parent_pro_cat', 0)->where('status !=',1)->where('deleted',null)->findAll();

        $data['products_data'] = $this->productsModel->where('sch_id',Auth()->sch_id)->where('status !=',1)->where('deleted',null)->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/Products/update_list',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }

    public function search_price_update(){
        $shopId = Auth()->sch_id;

        $keyWord = $this->request->getPost("catId");
        $parentCat = checkParentCategory($keyWord);
        $view = '';

        if ($parentCat == 0) {
            $data = $this->productcategoryModel->join('products','products.prod_cat_id = product_category.prod_cat_id')->where('product_category.parent_pro_cat', $keyWord)->where('products.sch_id', $shopId)->where('products.quantity >', 0)->findAll();

            foreach ($data as $row) {
                    $chec = ($row->show_global_price == '1') ? 'checked' : '';
                    $red = ($row->show_global_price == '1') ? 'readonly' : '';
                    $message = ($row->purchase_price > $row->selling_price) ? '<span style="color:red;">ক্রয় মূল্য থেকে বিক্রয়মূলক কম হয়ে গেছে।</span>' : '';
                    $proId = "'" . $row->prod_id . "'";
                    $box = '';
                    if (!empty($row->demo_id)) {
                        $box = '<div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" name="productId[]" id="checkBox_' . $row->prod_id . '" onclick="proPriceUpdateSuper(this.value,' . $proId . ')" value="' . $row->prod_id . '"  ' . $chec . '  >
                                    </div>';
                    }
                    $view .= '<tr>
                            <td>' . $box . ' </td>
                            <td>' . $row->name . '</td>
                            <td class="hi-im" >' . singleImage_by_productId($row->prod_id, '70') . '</td>
                            <td id="val_' . $row->prod_id . '"><input type="text" class="form-control" id="price_' . $row->prod_id . '" value="' . $row->selling_price . '" ' . $red . ' > <p id="pp_' . $row->prod_id . '"> ' . $message . '</p></td>                        
                            
                            <td id="proRow_' . $row->prod_id . '">';
                    if ($row->show_global_price == '0') {
                        $view .= '<button class="btn btn-xs btn-warning" type="button"  onclick="update_price(' . $row->prod_id . ')" >Update</button>';
                    }

                    $view .= '</td></tr>';
                }

        } else {
            $data = $this->productsModel->where('prod_cat_id', $keyWord)->where('sch_id', $shopId)->where('quantity >', 0)->findAll();

            foreach ($data as $row) {
                $chec = ($row->show_global_price == '1') ? 'checked' : '';
                $red = ($row->show_global_price == '1') ? 'readonly' : '';
                $message = ($row->purchase_price > $row->selling_price) ? '<p style="color:red;">ক্রয় মূল্য থেকে বিক্রয়মূলক কম হয়ে গেছে।</p>' : '';
                $proId = "'" . $row->prod_id . "'";
                $box = '';
                if (!empty($row->demo_id)) {
                    $box = '<div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" name="productId[]" id="checkBox_' . $row->prod_id . '" onclick="proPriceUpdateSuper(this.value,' . $proId . ')" value="' . $row->prod_id . '"  ' . $chec . '  >
                            </div>';
                }
                $view .= '<tr>
                            <td>' . $box . '</td>
                            <td>' . $row->name . '</td>
                            <td class="hi-im" >' . singleImage_by_productId($row->prod_id, '70') . '</td>                      
                            
                            <td id="val_' . $row->prod_id . '"><input type="text" class="form-control" id="price_' . $row->prod_id . '" value="' . $row->selling_price . '" ' . $red . ' > <p id="pp_' . $row->prod_id . '"> ' . $message . '</p></td>                        
                            
                            <td id="proRow_' . $row->prod_id . '">';
                if ($row->show_global_price == '0') {
                    $view .= '<button class="btn btn-xs btn-warning" type="button" onclick="update_price(' . $row->prod_id . ')" >Update</button>';
                }

                $view .= '</td></tr>';
            }
        }


        if (!empty($view)) {
            echo $view;
        } else {
            echo 'No data available  ';
        }
    }

    public function price_update_action(){
        $proId = $this->request->getPost('pro_id');
        $price = $this->request->getPost('price');

        $data['prod_id'] = $proId;
        $data['selling_price'] = $price;
        $this->productsModel->update($data['prod_id'], $data);

        $prodRowdata = $this->productsModel->where('prod_id', $proId)->first();
        if ($prodRowdata->purchase_price > $prodRowdata->selling_price) {
            $data = '<span style="color:red;">ক্রয় মূল্য থেকে বিক্রয়মূলক কম হয়ে গেছে।</span>';
        } else {
            $data = '<span style="color:green;">Price Update Success</span>';
        }
        print $data;
    }

    public function price_update_super_action(){
        $shopId = Auth()->sch_id;
        $proId = $this->request->getPost('productId[]');

        //inactive show_global_price
        $prodResult = $this->productsModel->where('sch_id', $shopId )->where('demo_id !=', null)->findAll();
        foreach ($prodResult as $val) {
            $data['prod_id'] = $val->prod_id;
            $data['show_global_price'] = '0';
            $this->productsModel->update($data['prod_id'],$data);
        }

        //active or price update
        if (!empty($proId)) {
            foreach ($proId as $val) {
                $prodRow = $this->productsModel->where('prod_id', $val)->first();
                $demoPrice = get_data_by_id('selling_price', 'demo_products', 'id', $prodRow->demo_id);
                if (!empty($demoPrice)) {
                    $dataPrice['prod_id'] = $val;
                    $dataPrice['selling_price'] = $demoPrice;
                    $dataPrice['show_global_price'] = '1';
                    $this->productsModel->update($dataPrice['prod_id'], $dataPrice);
                }
            }
            
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Price Update Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/products_price_update');
        } else {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any product <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/products_price_update');
        }
    }

    public function short_list(){
        $data['product'] = $this->productsModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->where('quantity  <=',5)->orderBy('prod_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Products/short_list', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function print_list(){
        $data['product'] = $this->productsModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('prod_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Products/print_list', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }

    public function barcode()
    {
        $data['barcodeqty'] = $this->request->getPost('barcodeqty');

        $data['generator'] = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $tabGenSet = DB()->table('gen_settings');

        $sizeBarcode = $tabGenSet->where('label', 'barcode_img_size')->get()->getRow()->value;
        $data['barcodeSize'] = empty($sizeBarcode) ? '100' : $sizeBarcode;

        $typeBarcode = $tabGenSet->where('label', 'barcode_type')->get()->getRow()->value;
        $data['barcodeType'] = empty($typeBarcode) ? 'C128' : $typeBarcode;

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Products/barcode', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }





}