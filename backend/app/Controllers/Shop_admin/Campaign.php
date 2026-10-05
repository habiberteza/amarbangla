<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\CampaignModel;
use App\Models\ProductsModel;

class Campaign extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $campaignModel;
    protected $productsModel;
    protected $crop;
    private $module_name = 'Campaign';
    public function __construct()
    {
        $this->campaignModel = new CampaignModel();
        $this->productsModel = new ProductsModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->crop = \Config\Services::image();
        $this->permission = new permission();
    }

    public function index(){


        $data['campaign'] = $this->campaignModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('campaign_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Campaign/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function create(){

        $data['product'] = $this->productsModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/Campaign/create',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }

    }
    public function create_action(){

        $data['sch_id'] = Auth()->sch_id;
        $data['title'] = $this->request->getPost('title');
        $data['prod_id'] = $this->request->getPost('prod_id');
        $data['start_date'] = $this->request->getPost('start_date');
        $data['end_date'] = $this->request->getPost('end_date');
        $data['description'] = $this->request->getPost('description');
        $data['createdBy'] = Auth()->user_id;
        $data['createdDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'title' => ['label' => 'Title', 'rules' => 'required'],
            'prod_id' => ['label' => 'Prod_id', 'rules' => 'required'],
            'start_date' => ['label' => 'Start Date', 'rules' => 'required'],
            'end_date' => ['label' => 'End Date', 'rules' => 'required'],
            'description' => ['label' => 'Description', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/campaign_create');
        } else {
            if (!empty($_FILES['image']['name'])) {
                $target_dir = FCPATH . '/uploads/campaign/';
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
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



            $this->campaignModel->insert($data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Add Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/campaign_create');

        }
    }
    public function update($campaign_id){
        $data['product'] = $this->productsModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->findAll();
        $data['campaign'] = $this->campaignModel->where('campaign_id',$campaign_id)->first();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Campaign/update', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function update_action(){
        $data['campaign_id'] = $this->request->getPost('campaign_id');
        $data['title'] = $this->request->getPost('title');
        $data['prod_id'] = $this->request->getPost('prod_id');
        $data['start_date'] = $this->request->getPost('start_date');
        $data['end_date'] = $this->request->getPost('end_date');
        $data['description'] = $this->request->getPost('description');
        $data['updatedBy'] = Auth()->user_id;
        $data['updatedDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'title' => ['label' => 'Title', 'rules' => 'required'],
            'prod_id' => ['label' => 'Prod_id', 'rules' => 'required'],
            'start_date' => ['label' => 'Start Date', 'rules' => 'required'],
            'end_date' => ['label' => 'End Date', 'rules' => 'required'],
            'description' => ['label' => 'Description', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/campaign_update/'.$data['campaign_id']);
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


            $this->campaignModel->update($data['campaign_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/campaign_update/'.$data['campaign_id']);

        }
    }
    public function delete($bank_id){
        $data['bank_id'] = $bank_id;
        $data['deleted'] = Auth()->user_id;
        $data['deletedRole'] = Auth()->role_id;

        $this->campaignModel->update($data['bank_id'],$data);
        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('shop_admin/bank');
    }







}