<?php

namespace App\Controllers\Super_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\CustomertypeModel;
use App\Models\WebsitesettingsModel;

class Websitesettings extends BaseController
{
    protected $validation;
    protected $session;
    protected $websitesettingsModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->websitesettingsModel = new WebsitesettingsModel();
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->permission = new Permission();
        $this->crop = \Config\Services::image();
    }
    public function logo() {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['logoData'] = $this->websitesettingsModel->where('label','logo')->first();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Websitesettings/logo',$data);
            echo view('Super_admin/footer');
        }
    }

    public function logo_action() {
        if (!empty($_FILES['userfile2']['name'])) {
            $target_dir = FCPATH . '/uploads/website_image/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777);
            }

            //old image unlink
            $old_img = get_data_by_id('value', 'website_settings', 'label', 'logo');
            if (!empty($old_img)) {
                unlink($target_dir . '' . $old_img);
            }

            //new image uplode
            $pic = $this->request->getFile('userfile2');
            $namePic = $pic->getRandomName();
            $pic->move($target_dir, $namePic);
            $pro_nameimg = 'logo_' . $pic->getName();
            $this->crop->withFile($target_dir . '' . $namePic)->fit(250, 95, 'center')->save($target_dir . '' . $pro_nameimg);
            unlink($target_dir . '' . $namePic);

            $data['value'] = $pro_nameimg;

            $this->websitesettingsModel->set('value', $data['value'])->where('label','logo')->update();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/logo');
        } else {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/logo');
        }
    }
    public function banner() {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['banner_left'] = $this->websitesettingsModel->where('label','banner_left')->first();
            $data['banner_right'] = $this->websitesettingsModel->where('label','banner_right')->first();
            $data['banner_top'] = $this->websitesettingsModel->where('label','banner_top')->first();
            $data['banner_top2'] = $this->websitesettingsModel->where('label','banner_top2')->first();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Websitesettings/banner',$data);
            echo view('Super_admin/footer');
        }
    }
    public function banner_action_left() {
        if (!empty($_FILES['userfile2']['name'])) {
            $target_dir = FCPATH . '/uploads/website_image/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777);
            }

            //old image unlink
            $old_img = get_data_by_id('value', 'website_settings', 'label', 'banner_left');
            if (!empty($old_img)) {
                unlink($target_dir . '' . $old_img);
            }

            //new image uplode
            $pic = $this->request->getFile('userfile2');
            $namePic = $pic->getRandomName();
            $pic->move($target_dir, $namePic);
            $pro_nameimg = 'banner_' . $pic->getName();
            $this->crop->withFile($target_dir . '' . $namePic)->fit(270, 355, 'center')->save($target_dir . '' . $pro_nameimg);
            unlink($target_dir . '' . $namePic);

            $data['value'] = $pro_nameimg;

            $this->websitesettingsModel->set('value', $data['value'])->where('label','banner_left')->update();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/banner');
        } else {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/banner');
        }
    }

    public function banner_action_right() {
        if (!empty($_FILES['userfile2']['name'])) {
            $target_dir = FCPATH . '/uploads/website_image/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777);
            }

            //old image unlink
            $old_img = get_data_by_id('value', 'website_settings', 'label', 'banner_right');
            if (!empty($old_img)) {
                unlink($target_dir . '' . $old_img);
            }

            //new image uplode
            $pic = $this->request->getFile('userfile2');
            $namePic = $pic->getRandomName();
            $pic->move($target_dir, $namePic);
            $pro_nameimg = 'banner_' . $pic->getName();
            $this->crop->withFile($target_dir . '' . $namePic)->fit(270, 355, 'center')->save($target_dir . '' . $pro_nameimg);
            unlink($target_dir . '' . $namePic);

            $data['value'] = $pro_nameimg;

            $this->websitesettingsModel->set('value', $data['value'])->where('label','banner_right')->update();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/banner');
        } else {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/banner');
        }
    }
    public function banner_action_top() {
        if (!empty($_FILES['userfile2']['name'])) {
            $target_dir = FCPATH . '/uploads/website_image/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777);
            }

            //old image unlink
            $old_img = get_data_by_id('value', 'website_settings', 'label', 'banner_top');
            if (!empty($old_img)) {
                unlink($target_dir . '' . $old_img);
            }

            //new image uplode
            $pic = $this->request->getFile('userfile2');
            $namePic = $pic->getRandomName();
            $pic->move($target_dir, $namePic);
            $pro_nameimg = 'banner_' . $pic->getName();
            $this->crop->withFile($target_dir . '' . $namePic)->fit(495, 171, 'center')->save($target_dir . '' . $pro_nameimg);
            unlink($target_dir . '' . $namePic);

            $data['value'] = $pro_nameimg;

            $this->websitesettingsModel->set('value', $data['value'])->where('label','banner_top')->update();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/banner');
        } else {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/banner');
        }
    }
    public function banner_action_bottom() {
        if (!empty($_FILES['userfile2']['name'])) {
            $target_dir = FCPATH . '/uploads/website_image/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777);
            }

            //old image unlink
            $old_img = get_data_by_id('value', 'website_settings', 'label', 'banner_top2');
            if (!empty($old_img)) {
                unlink($target_dir . '' . $old_img);
            }

            //new image uplode
            $pic = $this->request->getFile('userfile2');
            $namePic = $pic->getRandomName();
            $pic->move($target_dir, $namePic);
            $pro_nameimg = 'banner_' . $pic->getName();
            $this->crop->withFile($target_dir . '' . $namePic)->fit(495, 171, 'center')->save($target_dir . '' . $pro_nameimg);
            unlink($target_dir . '' . $namePic);

            $data['value'] = $pro_nameimg;

            $this->websitesettingsModel->set('value', $data['value'])->where('label','banner_top2')->update();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/banner');
        } else {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/banner');
        }
    }

    public function banner_product(){
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['banner_product_page'] = $this->websitesettingsModel->where('label','banner_product_page')->first();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Websitesettings/banner_product',$data);
            echo view('Super_admin/footer');
        }
    }

    public function banner_product_action(){
        if (!empty($_FILES['userfile2']['name'])) {
            $target_dir = FCPATH . '/uploads/website_image/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777);
            }

            //old image unlink
            $old_img = get_data_by_id('value', 'website_settings', 'label', 'banner_product_page');
            if (!empty($old_img)) {
                unlink($target_dir . '' . $old_img);
            }

            //new image uplode
            $pic = $this->request->getFile('userfile2');
            $namePic = $pic->getRandomName();
            $pic->move($target_dir, $namePic);
            $pro_nameimg = 'banner_' . $pic->getName();
            $this->crop->withFile($target_dir . '' . $namePic)->fit(270, 355, 'center')->save($target_dir . '' . $pro_nameimg);
            unlink($target_dir . '' . $namePic);

            $data['value'] = $pro_nameimg;

            $this->websitesettingsModel->set('value', $data['value'])->where('label','banner_product_page')->update();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/banner_product');
        } else {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/banner_product');
        }
    }

    public function slider(){
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['slider_1'] = $this->websitesettingsModel->where('label','slider_1')->first()->value;
            $data['slider_2'] = $this->websitesettingsModel->where('label','slider_2')->first()->value;
            $data['slider_3'] = $this->websitesettingsModel->where('label','slider_3')->first()->value;

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Websitesettings/slider',$data);
            echo view('Super_admin/footer');
        }
    }

    public function slider_action(){
        $label = $this->request->getPost('label');

        if (!empty($_FILES['userfile2']['name'])) {
            $target_dir = FCPATH . '/uploads/website_image/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777);
            }

            //old image unlink
            $old_img = get_data_by_id('value', 'website_settings', 'label', $label);
            if (!empty($old_img)) {
                unlink($target_dir . '' . $old_img);
            }

            //new image uplode
            $pic = $this->request->getFile('userfile2');
            $namePic = $pic->getRandomName();
            $pic->move($target_dir, $namePic);
            $pro_nameimg = 'slider_' . $pic->getName();
            $this->crop->withFile($target_dir . '' . $namePic)->fit(870, 475, 'center')->save($target_dir . '' . $pro_nameimg);
            unlink($target_dir . '' . $namePic);

            $data['value'] = $pro_nameimg;

            $this->websitesettingsModel->set('value', $data['value'])->where('label',$label)->update();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/slider');

        } else {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/slider');
        }
    }

    public function footer(){
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['email'] = $this->websitesettingsModel->where('label','email')->first()->value;
            $data['phone'] = $this->websitesettingsModel->where('label','phone')->first()->value;
            $data['address'] = $this->websitesettingsModel->where('label','address')->first()->value;
            $data['facebook'] = $this->websitesettingsModel->where('label','facebook')->first()->value;
            $data['twitter'] = $this->websitesettingsModel->where('label','twitter')->first()->value;
            $data['youtube'] = $this->websitesettingsModel->where('label','youtube')->first()->value;

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Websitesettings/footer',$data);
            echo view('Super_admin/footer');
        }
    }

    public function footer_action(){
        $data['email'] = $this->request->getPost('email');
        $data['phone'] = $this->request->getPost('phone');
        $data['address'] = $this->request->getPost('address');

        $data['facebook'] = $this->request->getPost('facebook');
        $data['twitter'] = $this->request->getPost('twitter');
        $data['youtube'] = $this->request->getPost('youtube');

        foreach ($data as $key => $val) {
            $this->websitesettingsModel->set('value', $val)->where('label', $key)->update();
        }
        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('super_admin/website_settings/footer');
    }

    public function mobile_slider(){
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['slider_1_mob'] = $this->websitesettingsModel->where('label','slider_1_mob')->first()->value;
            $data['slider_2_mob'] = $this->websitesettingsModel->where('label','slider_2_mob')->first()->value;
            $data['slider_3_mob'] = $this->websitesettingsModel->where('label','slider_3_mob')->first()->value;

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Websitesettings/mobile_slider',$data);
            echo view('Super_admin/footer');
        }
    }

    public function mobile_slider_action(){
        $label = $this->request->getPost('label');

        if (!empty($_FILES['userfile2']['name'])) {
            $target_dir = FCPATH . '/uploads/website_image/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777);
            }

            //old image unlink
            $old_img = get_data_by_id('value', 'website_settings', 'label', $label);
            if (!empty($old_img)) {
                unlink($target_dir . '' . $old_img);
            }

            //new image uplode
            $pic = $this->request->getFile('userfile2');
            $namePic = $pic->getRandomName();
            $pic->move($target_dir, $namePic);
            $pro_nameimg = 'slider_mob_' . $pic->getName();
            $this->crop->withFile($target_dir . '' . $namePic)->fit(390, 100, 'center')->save($target_dir . '' . $pro_nameimg);
            unlink($target_dir . '' . $namePic);

            $data['value'] = $pro_nameimg;

            $this->websitesettingsModel->set('value', $data['value'])->where('label',$label)->update();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/mobile_slider');

        } else {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/mobile_slider');
        }
    }

    public function home_banner(){
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['home_banner'] = $this->websitesettingsModel->where('label','home_banner')->first()->value;
            $data['home_banner_2'] = $this->websitesettingsModel->where('label','home_banner_2')->first()->value;
            $data['home_banner_3'] = $this->websitesettingsModel->where('label','home_banner_3')->first()->value;
            $data['home_banner_4'] = $this->websitesettingsModel->where('label','home_banner_4')->first()->value;

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Websitesettings/home_banner',$data);
            echo view('Super_admin/footer');
        }
    }

    public function home_banner_action(){

        if (!empty($_FILES['userfile2']['name'])) {
            $target_dir = FCPATH . '/uploads/website_image/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777);
            }

            //old image unlink
            $old_img = get_data_by_id('value', 'website_settings', 'label', 'home_banner');
            if (!empty($old_img)) {
                unlink($target_dir . '' . $old_img);
            }

            //new image uplode
            $pic = $this->request->getFile('userfile2');
            $namePic = $pic->getRandomName();
            $pic->move($target_dir, $namePic);
            $pro_nameimg = 'home_banner_' . $pic->getName();
            $this->crop->withFile($target_dir . '' . $namePic)->fit(315, 100, 'center')->save($target_dir . '' . $pro_nameimg);
            unlink($target_dir . '' . $namePic);

            $data['value'] = $pro_nameimg;

            $this->websitesettingsModel->set('value', $data['value'])->where('label','home_banner')->update();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/home_banner');

        } else {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/home_banner');
        }
    }

    public function home_banner_small_action(){
        $label = $this->request->getPost('label');

        if (!empty($_FILES['userfile2']['name'])) {
            $target_dir = FCPATH . '/uploads/website_image/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777);
            }

            //old image unlink
            $old_img = get_data_by_id('value', 'website_settings', 'label', $label);
            if (!empty($old_img)) {
                unlink($target_dir . '' . $old_img);
            }

            //new image uplode
            $pic = $this->request->getFile('userfile2');
            $namePic = $pic->getRandomName();
            $pic->move($target_dir, $namePic);
            $pro_nameimg = 'slider_mob_' . $pic->getName();
            $this->crop->withFile($target_dir . '' . $namePic)->fit(90, 90, 'center')->save($target_dir . '' . $pro_nameimg);
            unlink($target_dir . '' . $namePic);

            $data['value'] = $pro_nameimg;

            $this->websitesettingsModel->set('value', $data['value'])->where('label',$label)->update();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/home_banner');

        } else {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/website_settings/home_banner');
        }
    }






}
