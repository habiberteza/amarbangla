<?php

namespace App\Controllers\Super_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\CampaignModel;
use App\Models\ColorfamilyModel;
use App\Models\CustomertypeModel;
use App\Models\ProductsModel;

class Campaign extends BaseController
{
    protected $validation;
    protected $session;
    protected $campaignModel;
    protected $productsModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->campaignModel = new CampaignModel();
        $this->productsModel = new ProductsModel();
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

            $builder = $this->campaignModel
                ->select('campaign.*, shops.name as shop_name')
                ->leftJoin('shops', 'shops.sch_id', '=', 'campaign.sch_id')
                ->where('campaign.deleted IS NULL')
                ->orderBy('campaign.campaign_id', 'DESC');

            if (!empty($search)) {
                $builder = $builder->where(function($b) use ($search) {
                    $b->like('campaign.title', $search)
                      ->orLike('campaign.description', $search)
                      ->orLike('shops.name', $search);
                });
            }

            $data['campaign'] = $builder->paginate($perPage);
            $data['pager'] = $this->campaignModel->pager;
            $data['search'] = $search;
            $data['per_page'] = $perPage;

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Campaign/index',$data);
            echo view('Super_admin/footer');
        }
    }

    public function status_update(){
        $supuserId = $this->session->userIdSuper;

        $data['campaign_id'] = $this->request->getPost('campaign_id');
        $data['status'] = $this->request->getPost('status');
        $this->campaignModel->update($data['campaign_id'],$data);

        print '<div class="alert alert-success alert-dismissible" role="alert">Status update Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

    }



    public function update($campaign_id) {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['campaign'] = $this->campaignModel->where('campaign_id',$campaign_id)->first();

            $data['product'] = $this->productsModel->where('sch_id', $data['campaign']->sch_id)->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Campaign/update',$data);
            echo view('Super_admin/footer');
        }
    }

    public function update_action()
    {
        $supuserId = $this->session->userIdSuper;

        $data['campaign_id'] = $this->request->getPost('campaign_id');
        $data['title'] = $this->request->getPost('title');
        $data['description'] = $this->request->getPost('description');
        $data['prod_id'] = $this->request->getPost('prod_id');
        $data['start_date'] = $this->request->getPost('start_date');
        $data['end_date'] = $this->request->getPost('end_date');

        $this->validation->setRules([
            'title' => ['label' => 'Title', 'rules' => 'required'],
            'description' => ['label' => 'Description', 'rules' => 'required'],
            'prod_id' => ['label' => 'Product', 'rules' => 'required'],
            'start_date' => ['label' => 'start_date', 'rules' => 'required'],
            'end_date' => ['label' => 'end_date', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/campaign_update/' . $data['campaign_id']);
        } else {
            if (!empty($_FILES['image']['name'])) {
                $target_dir = FCPATH . '/uploads/campaign/';
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                //old image unlink
                $old_img = get_data_by_id('image', 'campaign', 'campaign_id', $data['campaign_id']);
                if (!empty($old_img)) {
                    unlink($target_dir . '' . $old_img);
                }

                //new image uplode
                $pic = $this->request->getFile('image');
                $namePic = $pic->getRandomName();
                $pic->move($target_dir, $namePic);
                $pro_nameimg = 'campaign_' . $pic->getName();
                $this->crop->withFile($target_dir . '' . $namePic)->fit(390, 100, 'center')->save($target_dir . '' . $pro_nameimg);
                unlink($target_dir . '' . $namePic);
                $data['image'] = $pro_nameimg;

            }

            $this->campaignModel->update($data['campaign_id'], $data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/campaign_update/' . $data['campaign_id']);

        }
    }



    public function delete($color_family_id) {

        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {
            $supuserId = $this->session->userIdSuper;
            $data['color_family_id'] = $color_family_id;

            $this->colorfamilyModel->delete($data['color_family_id']);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/product_color');

        }
    }



}
