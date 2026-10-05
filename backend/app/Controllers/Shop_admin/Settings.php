<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Models\BankModel;
use App\Libraries\Permission;
use App\Models\GensettingsModel;
use App\Models\GlobaladdressModel;
use App\Models\ShopsModel;
use App\Models\VatregisterModel;

class Settings extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $shopsModel;
    protected $gensettingsModel;
    protected $vatregisterModel;
    protected $globaladdressModel;
    protected $crop;
    private $module_name = 'Settings';
    public function __construct()
    {
        $this->shopsModel = new ShopsModel();
        $this->gensettingsModel = new GensettingsModel();
        $this->vatregisterModel = new VatregisterModel();
        $this->globaladdressModel = new GlobaladdressModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->crop = \Config\Services::image();
        $this->permission = new permission();
    }

    public function index(){


        $data['row'] = $this->shopsModel->where('sch_id',Auth()->sch_id)->first();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Settings/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function create(){

        $data['bank'] = $this->bankModel->where('sch_id',Auth()->sch_id)->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/Bank/create');
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
    public function update($sch_id){

        $data['row'] = $this->shopsModel->where('sch_id',$sch_id)->first();

        $data['gensattData'] = $this->gensettingsModel->where('sch_id', $sch_id)->findAll();
        $data['vatRegister'] = $this->vatregisterModel->where('sch_id', $sch_id)->where('is_default', '1')->first();
        $data['banner'] = $this->gensettingsModel->select('value')->where('sch_id', $sch_id)->where('label', 'customer_panel_banner')->first()->value;
        $data['video'] = $this->gensettingsModel->select('value')->where('sch_id', $sch_id)->where('label', 'customer_panel_video')->first()->value;

        $address = $this->globaladdressModel->where('global_address_id', $data['row']->global_address_id)->first();

        $data['division'] = (empty($address->division)) ? 0 : $address->division;
        $data['pourashava'] = (empty($address->pourashava)) ? 0 : $address->pourashava;
        $data['ward'] = (empty($address->ward)) ? 0 : $address->ward;
        $data['zila'] = (empty($address->zila)) ? 0 : $address->zila;
        $data['upazila'] = (empty($address->upazila)) ? 0 : $address->upazila;


        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Settings/update', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function update_action(){
        $data['sch_id'] = Auth()->sch_id;
        $data['name'] = $this->request->getPost('name');
        $data['email'] = $this->request->getPost('email');
        $data['mobile'] = $this->request->getPost('mobile');
        $data['comment'] = $this->request->getPost('comment');

        $this->validation->setRules([
            'name' => ['label' => 'name', 'rules' => 'required'],
            'email' => ['label' => 'email', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/settings_update/'.$data['sch_id'].'?active=general');
        } else {
            $this->shopsModel->update($data['sch_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/settings_update/'.$data['sch_id'].'?active=general');

        }
    }
    public function general_action(){
        $data['sch_id'] = Auth()->sch_id;
        $id = $this->request->getPost('id[]');
        $value = $this->request->getPost('value[]');


        $this->validation->setRules([
            'sch_id' => ['label' => 'sch_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/settings_update/'.$data['sch_id'].'?active=personal');
        } else {
            foreach ($id as $key => $val) {
                $this->gensettingsModel->set('value',$value[$key])->where('settings_id', $id[$key])->update();
            }
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/settings_update/'.$data['sch_id'].'?active=personal');

        }
    }

    public function photo_action(){
        $data['sch_id'] = Auth()->sch_id;


        $this->validation->setRules([
            'sch_id' => ['label' => 'sch_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/settings_update/'.$data['sch_id'].'?active=photo');
        } else {

            $target_dir = FCPATH . '/uploads/schools/';
            if (!empty($_FILES['logo']['name'])) {
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                //old image unlink
                $old_img = Auth()->logo;
                if (!empty($old_img)) {
                    unlink($target_dir . '' . $old_img);
                }

                //new image uplode
                $logo = $this->request->getFile('logo');
                $namePic = $logo->getRandomName();
                $logo->move($target_dir, $namePic);
                $pro_nameimg = 'logo_' . $logo->getName();
                $this->crop->withFile($target_dir . '' . $namePic)->fit(350, 100, 'center')->save($target_dir . '' . $pro_nameimg);
                unlink($target_dir . '' . $namePic);
                $data['logo'] = $pro_nameimg;
            }

            if (!empty($_FILES['image']['name'])) {
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                //old image unlink
                $old_img = Auth()->image;
                if (!empty($old_img)) {
                    unlink($target_dir . '' . $old_img);
                }

                //new image uplode
                $image = $this->request->getFile('image');
                $namePic = $image->getRandomName();
                $image->move($target_dir, $namePic);
                $pro_nameimg = 'profile_' . $image->getName();
                $this->crop->withFile($target_dir . '' . $namePic)->fit(160, 160, 'center')->save($target_dir . '' . $pro_nameimg);
                unlink($target_dir . '' . $namePic);
                $data['image'] = $pro_nameimg;
            }

            if (!empty($_FILES['banner']['name'])) {
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                //old image unlink
                $old_img = Auth()->banner;
                if (!empty($old_img)) {
                    unlink($target_dir . '' . $old_img);
                }

                //new image uplode
                $banner = $this->request->getFile('banner');
                $namePic = $banner->getRandomName();
                $banner->move($target_dir, $namePic);
                $pro_nameimg = 'banner_' . $banner->getName();
                $this->crop->withFile($target_dir . '' . $namePic)->fit(302, 129, 'center')->save($target_dir . '' . $pro_nameimg);
                unlink($target_dir . '' . $namePic);
                $data['banner'] = $pro_nameimg;
            }


            $this->shopsModel->update($data['sch_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/settings_update/'.$data['sch_id'].'?active=photo');

        }
    }

    public function vat_action(){
        $data['sch_id'] = Auth()->sch_id;
        $data['vat_id'] = $this->request->getPost('vat_id');
        $data['name'] = $this->request->getPost('name');
        $data['vat_register_no'] = $this->request->getPost('vat_register_no');

        $this->validation->setRules([
            'vat_id' => ['label' => 'vat_id', 'rules' => 'required'],
            'name' => ['label' => 'name', 'rules' => 'required'],
            'vat_register_no' => ['label' => 'register no', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/settings_update/'.$data['sch_id'].'?active=vat');
        } else {
            $this->vatregisterModel->update($data['vat_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/settings_update/'.$data['sch_id'].'?active=vat');

        }
    }
    public function address_action(){
        $data['sch_id'] = Auth()->sch_id;
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
            return redirect()->to('shop_admin/settings_update/'.$data['sch_id'].'?active=address');
        } else {

            if (!empty($address)) {
                $data['global_address_id'] = $address->global_address_id;
                $this->shopsModel->update($data['sch_id'], $data);
                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/settings_update/' . $data['sch_id'] . '?active=address');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Global address not match <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('shop_admin/settings_update/'.$data['sch_id'].'?active=address');
            }

        }
    }

    public function customer_action(){

        $data['customer_panel_video'] = $this->request->getPost('video');

        $this->validation->setRules([
            'customer_panel_video' => ['label' => 'video', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/settings_update/'.Auth()->sch_id.'?active=customer');
        } else {

            $target_dir = FCPATH . '/uploads/customer_dashboard/';
            if (!empty($_FILES['banner']['name'])) {
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                //old image unlink
                $old_img = $this->gensettingsModel->select('value')->where('sch_id', Auth()->sch_id)->where('label', 'customer_panel_banner')->first()->value;;
                if (!empty($old_img)) {
                    unlink($target_dir . '' . $old_img);
                }

                $old_img2 = $this->gensettingsModel->select('value')->where('sch_id', Auth()->sch_id)->where('label', 'customer_panel_banner_mobile')->first()->value;;
                if (!empty($old_img)) {
                    unlink($target_dir . '' . $old_img2);
                }

                //new image uplode
                $logo = $this->request->getFile('banner');
                $namePic = $logo->getRandomName();
                $logo->move($target_dir, $namePic);
                $pro_nameimg = 'banner_' . $logo->getName();
                $this->crop->withFile($target_dir . '' . $namePic)->fit(400, 210, 'center')->save($target_dir . '' . $pro_nameimg);
//                unlink($target_dir . '' . $namePic);
                $data['customer_panel_banner'] = $namePic;
                $data['customer_panel_banner_mobile'] = $pro_nameimg;
            }
            foreach ($data as $key => $value){
                $val['value'] = $value;
                $this->gensettingsModel->set($val)->where('label',$key)->where('sch_id', Auth()->sch_id)->update();
            }
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/settings_update/' . Auth()->sch_id . '?active=customer');


        }
    }






    public function search_district(){
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


    public function database_backup(){
        helper('filesystem');

        $db = \Config\Database::connect();
        $dbname = $db->database;
        $path = FCPATH . '/uploads/';             // change path here
        $filename = $dbname . '_' . date('dMY_Hi') . '.sql';   // change file name here
        $prefs = ['filename' => $filename];              // I only set the file name, for complete prefs see below

        $util = (new \CodeIgniter\Database\Database())->loadUtils($db);
        $backup = $util->backup($prefs);

        write_file($path . $filename . '.gz', $backup);
        return $this->response->download($path . $filename . '.gz', null);
    }


}