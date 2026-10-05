<?php

namespace App\Controllers\Super_admin;

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
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {
            $search = trim($this->request->getGet('search') ?? '');
            $perPage = (int) ($this->request->getGet('per_page') ?? 25);
            if ($perPage <= 0 || $perPage > 200) $perPage = 25;

            $builder = $this->adpostModel->where('deleted IS NULL')->orderBy('ad_post_id', 'DESC');
            if (!empty($search)) {
                $builder = $builder->where(function($b) use ($search) {
                    $b->like('title', $search)
                      ->orLike('description', $search);
                });
            }

            $data['post'] = $builder->paginate($perPage);
            $data['pager'] = $this->adpostModel->pager;
            $data['search'] = $search;
            $data['per_page'] = $perPage;

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Grouppost/index',$data);
            echo view('Super_admin/footer');
        }
    }


    public function update($ad_post_id) {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['post'] = $this->adpostModel->where('ad_post_id',$ad_post_id)->first();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Grouppost/update',$data);
            echo view('Super_admin/footer');
        }
    }

    public function update_action()
    {
        $supuserId = $this->session->userIdSuper;

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
            return redirect()->to('super_admin/group_post_update/' . $data['ad_post_id']);
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
//                $pro_nameimg = 'post_' . $pic->getName();
//                $this->crop->withFile($target_dir . '' . $namePic)->fit(390, 100, 'center')->save($target_dir . '' . $pro_nameimg);
//                unlink($target_dir . '' . $namePic);
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
//                $pro_nameimg = 'post_' . $pic->getName();
//                $this->crop->withFile($target_dir . '' . $namePic)->fit(390, 100, 'center')->save($target_dir . '' . $pro_nameimg);
//                unlink($target_dir . '' . $namePic);
                $data['banner_2'] = $namePic;

            }

            $this->adpostModel->update($data['ad_post_id'], $data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/group_post_update/' . $data['ad_post_id']);

        }
    }



    public function delete($ad_post_id) {

        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {
            $supuserId = $this->session->userIdSuper;
            $data['ad_post_id'] = $ad_post_id;

            $checkComm = $this->db->get_where('comment', array('ad_post_id' => $id))->num_rows();
            if (!empty($checkComm)) {
                $this->db->where('ad_post_id', $id)->delete('comment');
            }

            $checkLike = $this->db->get_where('like_post', array('ad_post_id' => $id))->num_rows();
            if (!empty($checkLike)) {
                $this->db->where('ad_post_id', $id)->delete('like_post');
            }


            $this->adpostModel->delete($data['ad_post_id']);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/group_post');

        }
    }



}
