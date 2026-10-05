<?php

namespace App\Controllers\Agent;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\AdpostModel;
use App\Models\CampaignModel;
use App\Models\ColorfamilyModel;
use App\Models\CustomertypeModel;
use App\Models\ProductsModel;

class Grouppost extends BaseController
{
    protected $validation;
    protected $session;
    protected $adpostModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->adpostModel = new AdpostModel();
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->permission = new Permission();
        $this->crop = \Config\Services::image();
    }
    public function index() {

        $data['post'] = $this->adpostModel->where('deleted IS NULL')->findAll();
        echo view('Agent/Grouppost/index',$data);
    }


    public function update($ad_post_id) {
        $data['post'] = $this->adpostModel->where('ad_post_id',$ad_post_id)->first();
        echo view('Agent/Grouppost/update',$data);
    }

    public function update_action()
    {

        $data['ad_post_id'] = $this->request->getPost('ad_post_id');
        $data['title'] = $this->request->getPost('title');
        $data['youtube_video'] = $this->request->getPost('youtube_video');
        $data['facebook_video'] = $this->request->getPost('facebook_video');
        $data['description'] = $this->request->getPost('description');

        $this->validation->setRules([
            'title' => ['label' => 'Title', 'rules' => 'required'],
            'description' => ['label' => 'Description', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/group_post_update/' . $data['ad_post_id']);
        } else {

            if (!empty($_FILES['banner_1']['name'])) {
                $target_dir = FCPATH . '/uploads/post/';
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
                $data['banner_1'] = $namePic;

            }

            if (!empty($_FILES['banner_2']['name'])) {
                $target_dir = FCPATH . '/uploads/post/';
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                //old image unlink
                $old_img = get_data_by_id('banner_2', 'ad_post', 'ad_post_id', $data['ad_post_id']);
                if (!empty($old_img)) {
                    unlink($target_dir . '' . $old_img);
                }

                //new image uplode
                $pic = $this->request->getFile('banner_2');
                $namePic = $pic->getRandomName();
                $pic->move($target_dir, $namePic);
                $data['banner_2'] = $namePic;

            }

            $this->adpostModel->update($data['ad_post_id'], $data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/group_post_update/' . $data['ad_post_id']);

        }
    }




}
