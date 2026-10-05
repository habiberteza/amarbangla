<?php

namespace App\Controllers\Agent;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\CommissionModel;
use App\Models\GlobaladdressModel;
use App\Models\InvoiceModel;
use App\Models\LedgersellerModel;
use App\Models\PackageModel;
use App\Models\SellerModel;

class Sellers extends BaseController
{
    protected $validation;
    protected $session;
    protected $sellerModel;
    protected $globaladdressModel;
    protected $commissionModel;
    protected $ledgersellerModel;
    protected $invoiceModel;
    protected $packageModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->sellerModel = new SellerModel();
        $this->globaladdressModel = new GlobaladdressModel();
        $this->ledgersellerModel = new LedgersellerModel();
        $this->invoiceModel = new InvoiceModel();
        $this->commissionModel = new CommissionModel();
        $this->packageModel = new PackageModel();
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->permission = new Permission();
        $this->crop = \Config\Services::image();
    }
    public function index() {

        $data['seller'] = $this->sellerModel->where('agent_id',Auth_agent()->agent_id)->where('deleted IS NULL')->findAll();
        echo view('Agent/Sellers/index',$data);
    }
    public function create() {

        echo view('Agent/Sellers/create');
    }
    public function create_action(){

        $data['name'] = $this->request->getPost('name');
        $data['mobile'] = $this->request->getPost('mobile');
        $data['password'] = SHA1($this->request->getPost('password'));
        $data['con_password'] = SHA1($this->request->getPost('con_password'));
        $data['pass'] = $this->request->getPost('password');
        $data['agent_id'] = Auth_agent()->agent_id;
        $data['role_id'] = '2';
        $data['balance'] = '0';
        $data['createdBy'] = Auth_agent()->agent_id;

        $this->validation->setRules([
            'name' => ['label' => 'name', 'rules' => 'required'],
            'mobile' => ['label' => 'mobile', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required|max_length[155]'],
            'con_password' => ['label' => 'Con password', 'rules' => 'required|matches[password]'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/sellers_create');
        } else {
            $check = checkUniqueField($data['mobile'], 'mobile', 'seller');
            if ($check == true) {

                $this->sellerModel->insert($data);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('agent/sellers_create');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">This number already exists! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('agent/sellers_create');
            }

        }
    }
    public function update($seller_id) {

        $data['seller'] = $this->sellerModel->where('seller_id',$seller_id)->first();
        $data['address'] = $this->globaladdressModel ->where('global_address_id',$data['seller']->global_address_id)->first();

        echo view('Agent/Sellers/update',$data);
    }
    public function update_action()
    {

        $data['seller_id'] = $this->request->getPost('seller_id');
        $data['name'] = $this->request->getPost('name');
        $data['mobile'] = $this->request->getPost('mobile');
        $data['pass'] = $this->request->getPost('password');
        $data['password'] = SHA1($this->request->getPost('password'));
        $data['con_password'] = SHA1($this->request->getPost('con_password'));

        $this->validation->setRules([
            'name' => ['label' => 'name', 'rules' => 'required'],
            'mobile' => ['label' => 'mobile', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required'],
            'con_password' => ['label' => 'Con password', 'rules' => 'required|matches[password]'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/sellers_update/' . $data['seller_id'].'?active=general');
        } else {

            $this->sellerModel->update($data['seller_id'], $data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/sellers_update/' . $data['seller_id'].'?active=general');

        }
    }
    public function personal_action()
    {

        $data['seller_id'] = $this->request->getPost('seller_id');
        $data['father_name'] = $this->request->getPost('father_name');
        $data['mother_name'] = $this->request->getPost('mother_name');
        $data['age'] = $this->request->getPost('age');
        $data['agent_id'] = $this->request->getPost('agent_id');

        $this->validation->setRules([
            'seller_id' => ['label' => 'seller_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/sellers_update/' . $data['seller_id'].'?active=personal');
        } else {

            $this->sellerModel->update($data['seller_id'], $data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/sellers_update/' . $data['seller_id'].'?active=personal');

        }
    }
    public function address_action()
    {

        $dataUp['seller_id'] = $this->request->getPost('seller_id');
        $data['division'] = $this->request->getPost('division');
        $data['zila'] = $this->request->getPost('district');
        $data['upazila'] = $this->request->getPost('upazila');
        $data['pourashava'] = $this->request->getPost('pourashava');
        $data['ward'] = $this->request->getPost('ward');

        $this->validation->setRules([
            'seller_id' => ['label' => 'seller_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($dataUp) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/sellers_update/' . $dataUp['seller_id'].'?active=user');
        } else {
            $address = $this->globaladdressModel->where($data)->first();
            if (!empty($address)) {
                $dataUp['global_address_id'] = $address->global_address_id;
                $this->sellerModel->update($dataUp['seller_id'], $dataUp);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('agent/sellers_update/' . $dataUp['seller_id'] . '?active=user');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Global address not match! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('agent/sellers_update/' . $dataUp['seller_id'].'?active=user');
            }

        }
    }
    public function photo_action() {
        $data['seller_id'] = $this->request->getPost('seller_id');
        $this->validation->setRules([
            'seller_id' => ['label' => 'seller_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/sellers_update/' . $data['seller_id'] . '?active=photo');
        } else {
            if (!empty($_FILES['pic']['name'])) {
                $target_dir = FCPATH . '/uploads/seller_image/';
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                //old image unlink
                $old_img = get_data_by_id('pic', 'seller', 'seller_id', $data['seller_id']);
                if (!empty($old_img)) {
                    unlink($target_dir . '' . $old_img);
                }

                //new image uplode
                $pic = $this->request->getFile('pic');
                $namePic = $pic->getRandomName();
                $pic->move($target_dir, $namePic);
                $pro_nameimg = 'profile_' . $pic->getName();
                $this->crop->withFile($target_dir . '' . $namePic)->fit(300, 300, 'center')->save($target_dir . '' . $pro_nameimg);
                unlink($target_dir . '' . $namePic);
                $data['pic'] = $pro_nameimg;

                $this->sellerModel->update($data['seller_id'], $data);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('agent/sellers_update/' . $data['seller_id'] . '?active=photo');
            } else {
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('agent/sellers_update/' . $data['seller_id'] . '?active=photo');
            }
        }
    }
    public function ledger($seller_id){
        $data['ledger'] = $this->ledgersellerModel->where('seller_id',$seller_id)->orderBy('ledg_seller_id', 'DESC')->findAll();
        echo view('Agent/Sellers/ledger',$data);
    }
    public function commission($seller_id){
        $data['commission'] = $this->commissionModel->where('seller_id',$seller_id)->orderBy('commission_id', 'DESC')->findAll();
        $data['sellerId'] = $seller_id;

        echo view('Agent/Sellers/commission',$data);
    }
    public function sellers_order($seller_id){
        $data['order'] = $this->invoiceModel->where('seller_id', $seller_id)->orderBy('invoice_id', 'DESC')->findAll();
        $data['sellerId'] = $seller_id;
        echo view('Agent/Sellers/order',$data);
    }


}
