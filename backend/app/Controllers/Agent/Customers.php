<?php

namespace App\Controllers\Agent;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\AgentpermittedareaModel;
use App\Models\CustomersModel;
use App\Models\GlobaladdressModel;
use App\Models\InvoiceitemModel;
use App\Models\InvoiceModel;
use App\Models\LedgerModel;

class Customers extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $crop;
    protected $agentpermittedareaModel;
    protected $customersModel;
    protected $invoiceModel;
    protected $invoiceitemModel;
    protected $ledgerModel;
    protected $globaladdressModel;


    public function __construct()
    {
        $this->agentpermittedareaModel = new AgentpermittedareaModel();
        $this->customersModel = new CustomersModel();
        $this->invoiceModel = new InvoiceModel();
        $this->invoiceitemModel = new InvoiceitemModel();
        $this->ledgerModel = new LedgerModel();
        $this->globaladdressModel = new GlobaladdressModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
        $this->crop = \Config\Services::image();
    }


    public function index() {

        $customers = $this->agentpermittedareaModel->join('customers','customers.global_address_id = agent_permitted_area.global_address_id')->where('agent_permitted_area.agent_id', Auth_agent()->agent_id)->where('customers.deleted IS NULL')->findAll();

        $data['result'] = $customers;
        echo view('Agent/Customers/index',$data);
    }
    public function order_list($customer_id) {
        $data['result'] = $this->invoiceModel->where('customer_id', $customer_id)->findAll();

        echo view('Agent/Customers/order_list',$data);
    }
    public function invoice($invoice_id) {

        $data['invoiceId'] = $invoice_id;

        echo view('Agent/Customers/invoice',$data);
    }
    public function customer_update($customer_id){
        $data['category'] = $this->customersModel->where('customer_id',$customer_id)->first();
        $data['address'] = $this->globaladdressModel->where('global_address_id',$data['category']->global_address_id)->first();

        echo view('Agent/Customers/update',$data);
    }
    public function customer_update_action(){

        $data['customer_id'] = $this->request->getPost('customer_id');
        $data['customer_name'] = $this->request->getPost('customer_name');
        $data['mobile'] = $this->request->getPost('mobile');
        $data['cus_type_id'] = $this->request->getPost('cus_type_id');
        $data['password'] = SHA1($this->request->getPost('password'));
        $data['con_password'] = SHA1($this->request->getPost('con_password'));

        $this->validation->setRules([
            'customer_name' => ['label' => 'name', 'rules' => 'required'],
            'mobile' => ['label' => 'mobile', 'rules' => 'required'],
            'cus_type_id' => ['label' => 'User Type', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required|max_length[155]'],
            'con_password' => ['label' => 'Con password', 'rules' => 'required|matches[password]'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/customer_update/' . $data['customer_id'].'?active=general');
        } else {

            $this->customersModel->update($data['customer_id'], $data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/customer_update/' . $data['customer_id'].'?active=general');

        }
    }
    public function customer_personal_update(){
        $data['customer_id'] = $this->request->getPost('customer_id');
        $data['father_name'] = $this->request->getPost('father_name');
        $data['mother_name'] = $this->request->getPost('mother_name');
        $data['age'] = $this->request->getPost('age');
        $data['nid'] = $this->request->getPost('nid');


        $this->validation->setRules([
            'customer_id' => ['label' => 'customer_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/customer_update/' . $data['customer_id'].'?active=personal');
        } else {

            $this->customersModel->update($data['customer_id'], $data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/customer_update/' . $data['customer_id'].'?active=personal');

        }
    }
    public function customer_address_update(){
        $customer_id = $this->request->getPost('customer_id');
        $address = $this->request->getPost('address');

        $data['division'] = $this->request->getPost('division');
        $data['zila'] = $this->request->getPost('district');
        $data['upazila'] = $this->request->getPost('upazila');
        $data['pourashava'] = $this->request->getPost('pourashava');
        $data['ward'] = $this->request->getPost('ward');



        $this->validation->setRules([
            'division' => ['label' => 'division', 'rules' => 'required'],
            'zila' => ['label' => 'zila', 'rules' => 'required'],
            'upazila' => ['label' => 'upazila', 'rules' => 'required'],
            'pourashava' => ['label' => 'pourashava', 'rules' => 'required'],
            'ward' => ['label' => 'ward', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/customer_update/' . $customer_id.'?active=user');
        } else {

            $glAddress = $this->globaladdressModel->where($data)->first();
            if (!empty($glAddress)) {
                $upData['customer_id'] = $customer_id;
                $upData['global_address_id'] = $glAddress->global_address_id;
                $upData['address'] = $address;
                $this->customersModel->update($upData['customer_id'],$upData);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('agent/customer_update/' . $customer_id . '?active=user');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Global address not match!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('agent/customer_update/' . $customer_id.'?active=user');
            }

        }
    }
    public function customer_photo_update(){
        $data['customer_id'] = $this->request->getPost('customer_id');
        $this->validation->setRules([
            'customer_id' => ['label' => 'customer_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/customer_update/' . $data['customer_id'].'?active=photo');
        } else {
            if (!empty($_FILES['pic']['name'])) {
                $target_dir = FCPATH . '/uploads/customer_image/';
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                //old image unlink
                $old_img = get_data_by_id('pic', 'customers', 'customer_id', $data['customer_id']);
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
                $this->customersModel->update($data['customer_id'], $data);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('agent/customer_update/' . $data['customer_id'].'?active=photo');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('agent/customer_update/' . $data['customer_id'].'?active=photo');
            }


        }
    }





}