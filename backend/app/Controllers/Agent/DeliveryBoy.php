<?php

namespace App\Controllers\Agent;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\CommissionModel;
use App\Models\DeliveryboyModel;
use App\Models\DeliveryModel;
use App\Models\GlobaladdressModel;
use App\Models\InvoiceModel;
use App\Models\LedgerdeliveryboyModel;
use App\Models\PackageModel;

class DeliveryBoy extends BaseController
{
    protected $validation;
    protected $session;
    protected $deliveryboyModel;
    protected $globaladdressModel;
    protected $commissionModel;
    protected $ledgerdeliveryboyModel;
    protected $invoiceModel;
    protected $packageModel;
    protected $deliveryModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->deliveryboyModel = new DeliveryboyModel();
        $this->globaladdressModel = new GlobaladdressModel();
        $this->ledgerdeliveryboyModel = new LedgerdeliveryboyModel();
        $this->invoiceModel = new InvoiceModel();
        $this->commissionModel = new CommissionModel();
        $this->packageModel = new PackageModel();
        $this->deliveryModel = new DeliveryModel();
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->permission = new Permission();
        $this->crop = \Config\Services::image();
    }
    public function index() {
        $data['deliveryboy'] = $this->deliveryboyModel->where('agent_id',Auth_agent()->agent_id)->where('deleted IS NULL')->findAll();
        echo view('Agent/Deliveryboy/index',$data);
    }

    public function create() {
        echo view('Agent/Deliveryboy/create');
    }

    public function create_action(){
        $data['name'] = $this->request->getPost('name');
        $data['mobile'] = $this->request->getPost('mobile');
        $data['password'] = SHA1($this->request->getPost('password'));
        $data['con_password'] = SHA1($this->request->getPost('con_password'));
        $data['pass'] = $this->request->getPost('password');
        $data['agent_id'] = Auth_agent()->agent_id;
        $data['role_id'] = '3';
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
            return redirect()->to('agent/delivery_boy_create');
        } else {
            $check = checkUniqueField($data['mobile'], 'mobile', 'delivery_boy');
            if ($check == true) {

                $this->deliveryboyModel->insert($data);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('agent/delivery_boy_create');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">This number already exists! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('agent/delivery_boy_create');
            }

        }
    }


    public function update($delivery_boy_id) {
        $data['delivery'] = $this->deliveryboyModel->where('delivery_boy_id',$delivery_boy_id)->first();
        $data['address'] = $this->globaladdressModel ->where('global_address_id',$data['delivery']->global_address_id)->first();
        echo view('Agent/Deliveryboy/update',$data);
    }

    public function update_action(){
        $data['delivery_boy_id'] = $this->request->getPost('delivery_boy_id');
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
            return redirect()->to('agent/delivery_boy_update/' . $data['delivery_boy_id'].'?active=general');
        } else {
            $this->deliveryboyModel->update($data['delivery_boy_id'], $data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/delivery_boy_update/' . $data['delivery_boy_id'].'?active=general');

        }
    }

    public function personal_update(){

        $data['delivery_boy_id'] = $this->request->getPost('delivery_boy_id');
        $data['father_name'] = $this->request->getPost('father_name');
        $data['mother_name'] = $this->request->getPost('mother_name');
        $data['age'] = $this->request->getPost('age');
        $data['status'] = $this->request->getPost('status');
        $data['agent_id'] = $this->request->getPost('agent_id');

        $this->validation->setRules([
            'delivery_boy_id' => ['label' => 'delivery_boy_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/delivery_boy_update/' . $data['delivery_boy_id'].'?active=personal');
        } else {
            $this->deliveryboyModel->update($data['delivery_boy_id'], $data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/delivery_boy_update/' . $data['delivery_boy_id'].'?active=personal');

        }
    }
    public function address_update(){

        $dataUp['delivery_boy_id'] = $this->request->getPost('delivery_boy_id');
        $data['division'] = $this->request->getPost('division');
        $data['zila'] = $this->request->getPost('district');
        $data['upazila'] = $this->request->getPost('upazila');
        $data['pourashava'] = $this->request->getPost('pourashava');
        $data['ward'] = $this->request->getPost('ward');

        $this->validation->setRules([
            'delivery_boy_id' => ['label' => 'delivery_boy_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($dataUp) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/delivery_boy_update/' . $dataUp['delivery_boy_id'].'?active=user');
        } else {
            $address = $this->globaladdressModel->where($data)->first();
            if (!empty($address)) {
                $dataUp['global_address_id'] = $address->global_address_id;
                $this->deliveryboyModel->update($dataUp['delivery_boy_id'], $dataUp);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('agent/delivery_boy_update/' . $dataUp['delivery_boy_id'] . '?active=user');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Global address not match! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('agent/delivery_boy_update/' . $dataUp['delivery_boy_id'].'?active=user');
            }

        }
    }

    public function photo_update() {
        $data['delivery_boy_id'] = $this->request->getPost('delivery_boy_id');
        $this->validation->setRules([
            'delivery_boy_id' => ['label' => 'delivery_boy_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/delivery_boy_update/' . $data['delivery_boy_id'] . '?active=photo');
        } else {
            if (!empty($_FILES['pic']['name'])) {
                $target_dir = FCPATH . '/uploads/delivery_boy_image/';
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                //old image unlink
                $old_img = get_data_by_id('pic', 'delivery_boy', 'delivery_boy_id', $data['delivery_boy_id']);
                if (!empty($old_img)) {
                    unlink($target_dir . '' . $old_img);
                }

                //new image uplode
                $pic = $this->request->getFile('pic');
                $namePic = $pic->getRandomName();
                $pic->move($target_dir, $namePic);
                $pro_nameimg = 'delivery_' . $pic->getName();
                $this->crop->withFile($target_dir . '' . $namePic)->fit(300, 300, 'center')->save($target_dir . '' . $pro_nameimg);
                unlink($target_dir . '' . $namePic);
                $data['pic'] = $pro_nameimg;

                $this->deliveryboyModel->update($data['delivery_boy_id'], $data);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('agent/delivery_boy_update/' . $data['delivery_boy_id'] . '?active=photo');
            } else {
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('agent/delivery_boy_update/' . $data['delivery_boy_id'] . '?active=photo');
            }
        }
    }



    public function ledger($delivery_boy_id){
        $data['ledger'] = $this->ledgerdeliveryboyModel->where('delivery_boy_id',$delivery_boy_id)->findAll();
        echo view('Agent/Deliveryboy/ledger',$data);
    }
    public function commission($delivery_boy_id){
        $data['shops'] = $this->commissionModel->join('package', 'package.invoice_id = commission.invoice_id')->where('commission.delivery_boy_id = ' . $delivery_boy_id)->groupBy('package.sch_id')->findAll();
        $data['commission'] = $this->commissionModel->where('delivery_boy_id',$delivery_boy_id)->findAll();
        $data['deliveryBoyId'] = $delivery_boy_id;

        echo view('Agent/Deliveryboy/commission',$data);
    }

    public function delivery_boy_order($delivery_boy_id){
        $data['orderData'] = $this->deliveryModel->where('delivery_boy_id', $delivery_boy_id)->findAll();
        echo view('Agent/Deliveryboy/order',$data);
    }


}
