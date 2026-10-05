<?php

namespace App\Controllers\Agent;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\CampaignModel;
use App\Models\ColorfamilyModel;
use App\Models\CustomertypeModel;
use App\Models\ProductsModel;
use App\Models\ShopsModel;

class Campaign extends BaseController
{
    protected $validation;
    protected $session;
    protected $campaignModel;
    protected $productsModel;
    protected $shopsModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->campaignModel = new CampaignModel();
        $this->productsModel = new ProductsModel();
        $this->shopsModel = new ShopsModel();
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->permission = new Permission();
        $this->crop = \Config\Services::image();
    }
    public function index() {

        $campaign = $this->shopsModel->join('campaign','campaign.sch_id = shops.sch_id')->where('shops.agent_id', Auth_agent()->agent_id)->findAll();
        
        $data['campaign'] = $campaign;
        echo view('Agent/Campaign/index',$data);
    }

    public function status_update(){
        $data['campaign_id'] = $this->request->getPost('campaign_id');
        $data['status'] = $this->request->getPost('status');
        $this->campaignModel->update($data['campaign_id'],$data);

        print '<div class="alert alert-success alert-dismissible" role="alert">Status update Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

    }



    public function update($campaign_id) {
        $data['campaign'] = $this->campaignModel->where('campaign_id',$campaign_id)->first();
        $data['product'] = $this->productsModel->where('sch_id', $data['campaign']->sch_id)->findAll();

        echo view('Agent/Campaign/update',$data);
    }

    public function update_action(){

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
            return redirect()->to('agent/campaign_update/' . $data['campaign_id']);
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
            return redirect()->to('agent/campaign_update/' . $data['campaign_id']);

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
