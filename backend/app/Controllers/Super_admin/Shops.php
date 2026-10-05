<?php

namespace App\Controllers\Super_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\GensettingsModel;
use App\Models\GlobaladdressModel;
use App\Models\RolesModel;
use App\Models\ShopsModel;
use App\Models\StoresModel;
use App\Models\SuppecommisionModel;
use App\Models\UsersModel;
use App\Models\VatregisterModel;

class Shops extends BaseController
{
    protected $validation;
    protected $session;
    protected $shopsModel;
    protected $roleModel;
    protected $usersModel;
    protected $gensettingsModel;
    protected $storesModel;
    protected $suppecommisionModel;
    protected $vatregisterModel;
    protected $globaladdressModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->shopsModel = new ShopsModel();
        $this->roleModel = new RolesModel();
        $this->usersModel = new UsersModel();
        $this->gensettingsModel = new GensettingsModel();
        $this->storesModel = new StoresModel();
        $this->suppecommisionModel = new SuppecommisionModel();
        $this->vatregisterModel = new VatregisterModel();
        $this->globaladdressModel = new GlobaladdressModel();
        $this->permission = new Permission();
        $this->crop = \Config\Services::image();
    }
    public function index() {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['shops'] = $this->shopsModel->where('deleted IS NULL')->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Shops/index',$data);
            echo view('Super_admin/footer');
        }
    }

    public function create() {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Shops/create');
            echo view('Super_admin/footer');
        }
    }

    public function create_action(){
        $supuserId = $this->session->userIdSuper;

        $data['name'] = $this->request->getPost('name');
        $data['email'] = $this->request->getPost('email');
        $data['password'] = $this->request->getPost('password');
        $data['con_password'] = $this->request->getPost('con_password');
        $data['status'] = $this->request->getPost('status');

        $this->validation->setRules([
            'name' => ['label' => 'name', 'rules' => 'required'],
            'email' => ['label' => 'email', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required|max_length[155]'],
            'con_password' => ['label' => 'Con password', 'rules' => 'required|matches[password]'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shops_create');
        } else {
            $emailUnique = is_unique_super('users', 'email', $data['email']);
            if ($emailUnique == true) {
                DB()->transStart();

                //shop create query
                $this->shopsModel->insert($data);
                $shopsId = $this->shopsModel->getInsertID();
                //shop create query


                //roles insert in roles table(start)
                $role['sch_id'] = $shopsId;
                $role['role'] = 'Admin';
                $role['is_default'] = '1';
                $role['permission'] = $this->permission->admin_permissions;
                $role['createdBy'] = $supuserId;
                $role['createdDtm'] = date('Y-m-d h:i:s');
                $this->roleModel->insert($role);
                $roleId = $this->roleModel->getInsertID();
                //roles insert in roles table(start)


                //create users in users table (start)
                $userData['sch_id'] = $shopsId;
                $userData['role_id'] = $roleId;
                $userData['is_default'] = '1';
                $userData['name'] = $this->request->getPost('name');
                $userData['email'] = $this->request->getPost('email');
                $userData['password'] = sha1($this->request->getPost('password'));
                $userData['pass'] = $this->request->getPost('password');
                $userData['status'] = $this->request->getPost('status');
                $userData['createdBy'] = $supuserId;
                $userData['createdDtm'] = date('Y-m-d h:i:s');
                $this->usersModel->insert($userData);
                //create users in users table (end)


                //create Vat in vat_register table (start)
                $vatData['sch_id'] = $shopsId;
                $vatData['is_default'] = '1';
                $vatData['name'] = "Default Vat Name";
                $vatData['vat_register_no'] = "BIN-0000-01";
                $vatData['createdBy'] = $supuserId;
                $vatData['createdDtm'] = date('Y-m-d h:i:s');
                $this->vatregisterModel->insert($vatData);
                //create Vat in vat_register table (end)


                // create default store in stores table(start)
                $storeData['sch_id'] = $shopsId;
                $storeData['name'] = 'Default';
                $storeData['description'] = 'Default Store';
                $storeData['is_default'] = '1';
                $storeData['createdDtm'] = date('Y-m-d h:i:s');
                $this->storesModel->insert($storeData);
                // create default store in stores table(end)


                //general settings insert in gen_settings table (start)
                $gen_settingsData = array(
                    array('sch_id' => $shopsId, 'label' => 'barcode_img_size', 'value' => '100'
                    ),
                    array('sch_id' => $shopsId, 'label' => 'barcode_type', 'value' => 'C128A'
                    ),
                    array('sch_id' => $shopsId, 'label' => 'business_type', 'value' => 'Ownership business'
                    ),
                    array('sch_id' => $shopsId, 'label' => 'currency', 'value' => 'BDT'
                    ),
                    array('sch_id' => $shopsId, 'label' => 'currency_before_symbol', 'value' => '৳'
                    ),
                    array('sch_id' => $shopsId, 'label' => 'currency_after_symbol', 'value' => '/-'
                    ),
                    array('sch_id' => $shopsId, 'label' => 'running_year', 'value' => '2018-2019'
                    ),
                    array('sch_id' => $shopsId, 'label' => 'disable_frontend', 'value' => '0'
                    ),
                    array('sch_id' => $shopsId, 'label' => 'phone_code', 'value' => '880'
                    ),
                    array('sch_id' => $shopsId, 'label' => 'country', 'value' => 'Bangladesh'
                    ),
                    array('sch_id' => $shopsId, 'label' => 'customer_panel_banner', 'value' => ''
                    ),
                    array('sch_id' => $shopsId, 'label' => 'customer_panel_banner_mobile', 'value' => ''
                    ),
                    array('sch_id' => $shopsId, 'label' => 'customer_panel_video', 'value' => ''
                    ),

                );
                $this->gensettingsModel->insertBatch($gen_settingsData);
                //general settings insert in gen_settings table (end)


                // supper commission create
                $supCommiData = array(
                    'sch_id' => $shopsId,
                    'commision' => '0',
                    'createdBy' => '1',
                    'createdDtm' => date('Y-m-d h:i:s'),
                );
                $this->suppecommisionModel->insert($supCommiData);
                // supper commission create


                DB()->transComplete();

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/shops_create');

            }else {
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Email already in use <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/shops_create');
            }


        }
    }

    public function view($sch_id) {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['shops'] = $this->shopsModel->where('sch_id',$sch_id)->first();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Shops/view',$data);
            echo view('Super_admin/footer');
        }
    }

    public function update($sch_id) {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['shops'] = $this->shopsModel->where('sch_id',$sch_id)->first();
            $data['user'] = $this->usersModel->where('sch_id',$sch_id)->where('is_default','1')->first();
            $address = $this->globaladdressModel->where('global_address_id', $data['shops']->global_address_id)->first();

            $data['division'] = (empty($address->division)) ? 0 : $address->division;
            $data['pourashava'] = (empty($address->pourashava)) ? 0 : $address->pourashava;
            $data['ward'] = (empty($address->ward)) ? 0 : $address->ward;
            $data['zila'] = (empty($address->zila)) ? 0 : $address->zila;
            $data['upazila'] = (empty($address->upazila)) ? 0 : $address->upazila;

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Shops/update',$data);
            echo view('Super_admin/footer');
        }
    }

    public function general_update()
    {
        $supuserId = $this->session->userIdSuper;

        $data['sch_id'] = $this->request->getPost('sch_id');
        $data['name'] = $this->request->getPost('name');
        $data['email'] = $this->request->getPost('email');
        $data['status'] = $this->request->getPost('status');

        $this->validation->setRules([
            'name' => ['label' => 'name', 'rules' => 'required'],
            'email' => ['label' => 'email', 'rules' => 'required'],
            'status' => ['label' => 'status', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shops_update/' . $data['sch_id'] . '?active=general');
        } else {

            $this->shopsModel->update($data['sch_id'], $data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shops_update/' . $data['sch_id'] . '?active=general');

        }
    }

    public function personal_update()
    {

        $data['sch_id'] = $this->request->getPost('sch_id');
        $data['mobile'] = $this->request->getPost('mobile');
        $data['address'] = $this->request->getPost('address');
        $data['comment'] = $this->request->getPost('comment');
        $data['sup_comm'] = $this->request->getPost('sup_comm');
        $data['agent_id'] = $this->request->getPost('agent_id');

        $this->validation->setRules([
            'mobile' => ['label' => 'mobile', 'rules' => 'required|is_natural_no_zero|alpha_numeric_space|min_length[5]|max_length[12]'],
            'address' => ['label' => 'address', 'rules' => 'required'],
            'comment' => ['label' => 'comment', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shops_update/' . $data['sch_id'] . '?active=personal');
        } else {
            $this->shopsModel->update($data['sch_id'], $data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shops_update/' . $data['sch_id'] . '?active=personal');
        }
    }

    public function photo_update()
    {
        $data['sch_id'] = $this->request->getPost('sch_id');


        if (!empty($_FILES['logo']['name'])) {
            $target_dir = FCPATH . '/uploads/schools/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777);
            }

            //old image unlink
            $old_img = get_data_by_id('logo', 'shops', 'sch_id', $data['sch_id']);
            if (!empty($old_img)) {
                unlink($target_dir . '' . $old_img);
            }

            //new image uplode
            $pic = $this->request->getFile('logo');
            $namePic = $pic->getRandomName();
            $pic->move($target_dir, $namePic);
            $pro_nameimg = 'profile_' . $pic->getName();
            $this->crop->withFile($target_dir . '' . $namePic)->fit(350, 100, 'center')->save($target_dir . '' . $pro_nameimg);
            unlink($target_dir . '' . $namePic);
            $data['logo'] = $pro_nameimg;
        }

        if (!empty($_FILES['profile_image']['name'])) {
            $target_dir = FCPATH . '/uploads/schools/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777);
            }

            //old image unlink
            $old_img_pro = get_data_by_id('image', 'shops', 'sch_id', $data['sch_id']);
            if (!empty($old_img_pro)) {
                unlink($target_dir . '' . $old_img_pro);
            }

            //new image uplode
            $picpRO = $this->request->getFile('profile_image');
            $namePicPRO = $picpRO->getRandomName();
            $picpRO->move($target_dir, $namePicPRO);
            $pro_nameimg_Pro = 'pro_' . $picpRO->getName();
            $this->crop->withFile($target_dir . '' . $namePicPRO)->fit(160, 160, 'center')->save($target_dir . '' . $pro_nameimg_Pro);
            unlink($target_dir . '' . $namePicPRO);
            $data['image'] = $pro_nameimg_Pro;
        }


        $this->shopsModel->update($data['sch_id'], $data);

        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('super_admin/shops_update/' . $data['sch_id'] . '?active=photo');
    }

    public function user_update()
    {
        $sch_id = $this->request->getPost('sch_id');
        $data['user_id'] = $this->request->getPost('user_id');
        $data['email'] = $this->request->getPost('email');
        $data['password'] = sha1($this->request->getPost('password'));
        $data['con_password'] = sha1($this->request->getPost('con_password'));

        $this->validation->setRules([
            'email' => ['label' => 'email', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required|max_length[155]'],
            'con_password' => ['label' => 'Con password', 'rules' => 'required|matches[password]'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shops_update/' . $sch_id . '?active=user');
        } else {

            $emailUnique = is_unique_super_update('users', 'email', $data['email'], 'user_id', $data['user_id']);
            if ($emailUnique == true) {
                $data['pass'] = $data['password'];
                $this->usersModel->update($data['user_id'], $data);
                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/shops_update/' . $sch_id . '?active=user');
            } else {
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Email already in use <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/shops_update/' . $sch_id . '?active=user');
            }
        }
    }

    public function category_update(){
        $data['sch_id'] = $this->request->getPost('sch_id');
        $data['shop_cat_id'] = $this->request->getPost('sub_cat_id');
        $data['type'] = $this->request->getPost('type');
        $data['priority'] = $this->request->getPost('priority');
        $data['home_feature'] = $this->request->getPost('home_feature');

        $this->validation->setRules([
            'type' => ['label' => 'type', 'rules' => 'required'],
            'priority' => ['label' => 'priority', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shops_update/' . $data['sch_id'] . '?active=shop_category');
        } else {
            $this->shopsModel->update($data['sch_id'], $data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shops_update/' . $data['sch_id'] . '?active=shop_category');
        }
    }

    public function address_action(){
        $data['sch_id'] = $this->request->getPost('sch_id');
        $data['address'] = $this->request->getPost('address');

        $division = $this->request->getPost('division');
        $zila = $this->request->getPost('district');
        $upazila = $this->request->getPost('upazila');
        $ward = $this->request->getPost('ward');
        $pourashava = $this->request->getPost('pourashava');

        $address =$this->globaladdressModel->where('division', $division)->where('zila', $zila)->where( 'upazila', $upazila)->where('pourashava', $pourashava)->where('ward', $ward)->first();


        $this->validation->setRules([
            'address' => ['label' => 'address', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shops_update/'.$data['sch_id'].'?active=address');
        } else {

            if (!empty($address)) {
                $data['global_address_id'] = $address->global_address_id;
                $this->shopsModel->update($data['sch_id'], $data);
                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/shops_update/' . $data['sch_id'] . '?active=address');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Global address not match <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/shops_update/'.$data['sch_id'].'?active=address');
            }

        }
    }

    public function shops_delete($sch_id) {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {
            $supuserId = $this->session->userIdSuper;
            $data['sch_id'] = $sch_id;
            $data['deleted'] = $supuserId;
            $data['deletedRole'] = $supuserId;

            $this->shopsModel->update($data['sch_id'], $data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/shops');

        }
    }

    public function get_sub_category(){
        $catId = $this->request->getPost('catId');

        $table = DB()->table('shop_category');
        $query = $table->where('parent_cat_id', $catId )->get()->getResult();
        $options = '';
        foreach ($query as $row) {
            $options .= '<option value="' . $row->shop_cat_id . '" ';
            $options .= '>' . $row->name . '</option>';
        }
        print $options;
    }

    public function search_district()
    {

        $divisionsId = $this->request->getPost("divisionsId");

        $district = districtView();
        $row = '<option value="">Please Select</option>';
        foreach ($district as $rows) {
            if ($rows['division_id'] == $divisionsId) {
                $row .= '<option value="' . $rows['id'] . '">' . $rows['name'] . '</option>';
            }
        }
        echo $row;
    }

    public function search_upazila()
    {
        $districtId = $this->request->getPost("districtId");

        $upazila = upazilasView();
        $row = '<option value="">Please Select</option>';
        foreach ($upazila as $rows) {
            if ($rows['district_id'] == $districtId) {
                $row .= '<option value="' . $rows['id'] . '">' . $rows['name'] . '</option>';
            }
        }
        echo $row;
    }

    public function search(){
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {
            if (!empty($this->request->getPost('division'))) {

                $division = empty($this->request->getPost('division')) ? '1=1' : array('global_address.division' => $this->request->getPost('division'));
                $district = empty($this->request->getPost('district')) ? '1=1' : array('global_address.zila' => $this->request->getPost('district'));
                $upazila = empty($this->request->getPost('upazila')) ? '1=1' : array('global_address.upazila' => $this->request->getPost('upazila'));
                $pVal = $this->request->getPost('pourashava');
                if (!empty($pVal)) {
                    if ($pVal === '1' || $pVal === 'Pourashava') {
                        $pourashava = "global_address.pourashava IN ('1', 'Pourashava')";
                    } elseif ($pVal === '2' || $pVal === 'Union') {
                        $pourashava = "global_address.pourashava IN ('2', 'Union')";
                    } elseif ($pVal === '0' || $pVal === 'City Corporation') {
                        $pourashava = "global_address.pourashava IN ('0', 'City Corporation')";
                    } else {
                        $pourashava = array('global_address.pourashava' => $pVal);
                    }
                } else {
                    $pourashava = '1=1';
                }
                $ward = empty($this->request->getPost('ward')) ? '1=1' : array('global_address.ward' => $this->request->getPost('ward'));

                $shops = $this->globaladdressModel->join('shops','shops.global_address_id = global_address.global_address_id')->where($division)->where($district)->where($upazila)->where($pourashava)->where($ward)->findAll();

                $data['shop_data'] = $shops;
                $data['division'] = $this->request->getPost('division');
                $data['district'] = $this->request->getPost('district');
                $data['upazila'] = $this->request->getPost('upazila');
                $data['pourashava'] = $this->request->getPost('pourashava');
                $data['ward'] = $this->request->getPost('ward');


                echo view('Super_admin/header');
                echo view('Super_admin/sidebar');
                echo view('Super_admin/Shops/result',$data);
                echo view('Super_admin/footer');
            }else {
                $this->session->setFlashdata('message', '<div style="margin-top: 12px" class="alert alert-danger" id="message">Sorry! Something is wrong.</div>');
                return redirect()->to(site_url("super_admin"));
            }

        }
    }






}
