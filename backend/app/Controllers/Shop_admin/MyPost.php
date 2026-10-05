<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Models\AdpostModel;
use App\Libraries\Permission;

class MyPost extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $crop;
    protected $adpostModel;
    private $module_name = 'My_post';
    public function __construct()
    {
        $this->adpostModel = new AdpostModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->crop = \Config\Services::image();
        $this->permission = new permission();
    }

    public function index(){

        $data['adPost'] = $this->adpostModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('ad_post_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/My_post/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function create(){


        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/My_post/create');
        }else{
            echo view('Shop_admin/no_permission');
        }

    }
    public function create_action(){

        $data['sch_id'] = Auth()->sch_id;
        $data['title'] = $this->request->getPost('title');
        $data['youtube_video'] = $this->request->getPost('youtube_video');
        $data['facebook_video'] = $this->request->getPost('facebook_video');
        $data['description'] = $this->request->getPost('description');
        $data['createdBy'] = Auth()->user_id;
        $data['createdDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'title' => ['label' => 'title', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/my_post_create');
        } else {
            $target_dir = FCPATH . '/uploads/post/';
            if (!empty($_FILES['banner_1']['name'])) {
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                //new image uplode
                $pic = $this->request->getFile('banner_1');
                $namePic = $pic->getRandomName();
                $pic->move($target_dir, $namePic);
                $pro_nameimg = 'post_' . $pic->getName();
                $this->crop->withFile($target_dir . '' . $namePic)->fit(320, 320, 'center')->save($target_dir . '' . $pro_nameimg);
                unlink($target_dir . '' . $namePic);
                $data['banner_1'] = $pro_nameimg;
            }

            if (!empty($_FILES['banner_2']['name'])) {
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                //new image uplode
                $banner_2 = $this->request->getFile('banner_2');
                $namePic = $banner_2->getRandomName();
                $banner_2->move($target_dir, $namePic);
                $pro_nameimg = 'post_' . $banner_2->getName();
                $this->crop->withFile($target_dir . '' . $namePic)->fit(320, 320, 'center')->save($target_dir . '' . $pro_nameimg);
                unlink($target_dir . '' . $namePic);
                $data['banner_2'] = $pro_nameimg;
            }

            $this->adpostModel->insert($data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Add Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/my_post_create');

        }
    }
    public function update($ad_post_id){

        $data['adpost'] = $this->adpostModel->where('ad_post_id',$ad_post_id)->first();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/My_post/update', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function update_action(){
        $data['ad_post_id'] = $this->request->getPost('ad_post_id');
        $data['title'] = $this->request->getPost('title');
        $data['youtube_video'] = $this->request->getPost('youtube_video');
        $data['facebook_video'] = $this->request->getPost('facebook_video');
        $data['description'] = $this->request->getPost('description');
        $data['updatedBy'] = Auth()->user_id;
        $data['updatedDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'title' => ['label' => 'title', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/my_post_update/'.$data['ad_post_id']);
        } else {

            $target_dir = FCPATH . '/uploads/post/';
            if (!empty($_FILES['banner_1']['name'])) {
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                //old image unlink
                $old_img = get_data_by_id('banner_1', 'ad_post', 'ad_post_id', $data['ad_post_id']);
                if (!empty($old_img)) {
                    unlink($target_dir . '' . $old_img);
                }

                //new image uplode
                $pic = $this->request->getFile('banner_1');
                $namePic = $pic->getRandomName();
                $pic->move($target_dir, $namePic);
                $pro_nameimg = 'post_' . $pic->getName();
                $this->crop->withFile($target_dir . '' . $namePic)->fit(320, 320, 'center')->save($target_dir . '' . $pro_nameimg);
                unlink($target_dir . '' . $namePic);
                $data['banner_1'] = $pro_nameimg;
            }

            if (!empty($_FILES['banner_2']['name'])) {
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }
                //old image unlink
                $old_img = get_data_by_id('banner_2', 'ad_post', 'ad_post_id', $data['ad_post_id']);
                if (!empty($old_img)) {
                    unlink($target_dir . '' . $old_img);
                }
                //new image uplode
                $banner_2 = $this->request->getFile('banner_2');
                $namePic = $banner_2->getRandomName();
                $banner_2->move($target_dir, $namePic);
                $pro_nameimg = 'post_' . $banner_2->getName();
                $this->crop->withFile($target_dir . '' . $namePic)->fit(320, 320, 'center')->save($target_dir . '' . $pro_nameimg);
                unlink($target_dir . '' . $namePic);
                $data['banner_2'] = $pro_nameimg;
            }

            $this->adpostModel->update($data['ad_post_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/my_post_update/'.$data['ad_post_id']);

        }
    }
    public function delete($ad_post_id){
        $data['ad_post_id'] = $ad_post_id;
        $data['deleted'] = Auth()->user_id;
        $data['deletedRole'] = Auth()->role_id;

        $this->adpostModel->update($data['ad_post_id'],$data);
        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('shop_admin/my_post');
    }







}