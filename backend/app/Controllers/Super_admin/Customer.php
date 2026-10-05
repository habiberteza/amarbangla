<?php

namespace App\Controllers\Super_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\CustomersModel;
use App\Models\CustomertypeModel;
use App\Models\GlobaladdressModel;
use App\Models\InvoiceModel;

class Customer extends BaseController
{
    protected $validation;
    protected $session;
    protected $customertypeModel;
    protected $customersModel;
    protected $globaladdressModel;
    protected $invoiceModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->customertypeModel = new CustomertypeModel();
        $this->customersModel = new CustomersModel();
        $this->globaladdressModel = new GlobaladdressModel();
        $this->invoiceModel = new InvoiceModel();
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
            $perPage = (int) ($this->request->getGet('per_page') ?: 25);
            if ($perPage < 10) $perPage = 10;
            if ($perPage > 200) $perPage = 200;

            $search = trim($this->request->getGet('search') ?? '');

            $builder = $this->customersModel->where('deleted IS NULL');

            if (!empty($search)) {
                $builder = $builder->where(function($sub) use ($search) {
                    $sub->like('customer_name', $search)
                        ->orLike('mobile', $search);
                    if (is_numeric($search)) {
                        $sub->orWhere('customer_id', (int) $search);
                    }
                });
            }

            $builder->orderBy('customer_id', 'DESC');

            $data['customer'] = $builder->paginate($perPage);
            $data['pager'] = $this->customersModel->pager;
            $data['search'] = $search;
            $data['perPage'] = $perPage;

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Customer/index', $data);
            echo view('Super_admin/footer');
        }
    }

    public function create() {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {
            $data['customers_type'] = $this->customertypeModel->where('deleted IS NULL')->findAll();
            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Customer/create',$data);
            echo view('Super_admin/footer');
        }
    }

    public function create_action(){
        $supuserId = $this->session->userIdSuper;

        $data['customer_name'] = $this->request->getPost('customer_name');
        $data['mobile'] = $this->request->getPost('mobile');
        $data['password'] = SHA1($this->request->getPost('password'));
        $data['con_password'] = SHA1($this->request->getPost('con_password'));
        $data['cus_type_id'] = $this->request->getPost('cus_type_id');
        $data['balance'] = '0';
        $data['createdBy'] = $supuserId;
        $data['createdDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'customer_name' => ['label' => 'name', 'rules' => 'required'],
            'mobile' => ['label' => 'mobile', 'rules' => 'required'],
            'cus_type_id' => ['label' => 'cus_type_id', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required|max_length[155]'],
            'con_password' => ['label' => 'Con password', 'rules' => 'required|matches[password]'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/customer_create');
        } else {
            $check = checkUniqueField($data['mobile'], 'mobile', 'customers');
            if ($check == true) {

                $this->customersModel->insert($data);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/customer_create');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">This user already exists!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/customer_create');
            }

        }
    }



    public function update($customer_id) {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['category'] = $this->customersModel->where('customer_id',$customer_id)->first();
            $data['address'] = $this->globaladdressModel->where('global_address_id',$data['category']->global_address_id)->first();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Customer/update',$data);
            echo view('Super_admin/footer');
        }
    }

    public function update_action()
    {
        $supuserId = $this->session->userIdSuper;

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
            return redirect()->to('super_admin/customer_update/' . $data['customer_id'].'?active=general');
        } else {

            $this->customersModel->update($data['customer_id'], $data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/customer_update/' . $data['customer_id'].'?active=general');

        }
    }

    public function personal_update()
    {
        $supuserId = $this->session->userIdSuper;

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
            return redirect()->to('super_admin/customer_update/' . $data['customer_id'].'?active=personal');
        } else {

            $this->customersModel->update($data['customer_id'], $data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/customer_update/' . $data['customer_id'].'?active=personal');

        }
    }
    public function address_update()
    {
        $supuserId = $this->session->userIdSuper;

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
            return redirect()->to('super_admin/customer_update/' . $customer_id.'?active=user');
        } else {

            $glAddress = $this->globaladdressModel->where($data)->first();
            if (!empty($glAddress)) {
                $updata['customer_id'] = $customer_id;
                $updata['global_address_id'] = $glAddress->global_address_id;
                $updata['address'] = $address;
                $this->customersModel->update($updata['customer_id'],$updata);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/customer_update/' . $customer_id . '?active=user');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Global address not match!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/customer_update/' . $customer_id.'?active=user');
            }

        }
    }
    public function photo_update()
    {
        $supuserId = $this->session->userIdSuper;

        $data['customer_id'] = $this->request->getPost('customer_id');
        $this->validation->setRules([
            'customer_id' => ['label' => 'customer_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/customer_update/' . $data['customer_id'].'?active=photo');
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
                return redirect()->to('super_admin/customer_update/' . $data['customer_id'].'?active=photo');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/customer_update/' . $data['customer_id'].'?active=photo');
            }



        }
    }

    public function delete($customer_id) {

        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {
            $supuserId = $this->session->userIdSuper;
            $data['customer_id'] = $customer_id;
            $data['deleted'] = $supuserId;
            $data['deletedRole'] = $supuserId;

            $this->customersModel->update($data['customer_id'], $data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/customer');

        }
    }

    public function order_list($customer_id) {

        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {
            $supuserId = $this->session->userIdSuper;
            $data['order'] = $this->invoiceModel->where('customer_id',$customer_id)->findAll();
            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Customer/order_list',$data);
            echo view('Super_admin/footer');
        }
    }
    public function filter() {

        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {
            $supuserId = $this->session->userIdSuper;

            $data['division'] = $this->request->getPost('division');
            $data['zila'] = $this->request->getPost('district');
            $data['upazila'] = $this->request->getPost('upazila');
            $data['pourashava'] = $this->request->getPost('pourashava');
            $data['ward'] = $this->request->getPost('ward');
            $this->validation->setRules([
                'division' => ['label' => 'division', 'rules' => 'required'],
            ]);

            if ($this->validation->run($data) == FALSE) {
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/customer');
            } else {

                $division = empty($this->request->getPost('division')) ? '1=1' : array('global_address.division' => $this->request->getPost('division'));
                $district = empty($this->request->getPost('district')) ? '1=1' : array('global_address.zila' => $this->request->getPost('district'));
                $upazila = empty($this->request->getPost('upazila')) ? '1=1' : array('global_address.upazila' => $this->request->getPost('upazila'));
                $pVal = $this->request->getPost('pourashava');
                if (!empty($pVal)) {
                    if ($pVal === '1' || $pVal === 'Pourashava') {
                        $pourashava = "global_address.pourashava IN ('1', 'Pourashava')";
                    } elseif ($pVal === '2' || $pVal === 'Union') {
                        $pourashava = "global_address.pourashava IN ('2', 'Union')";
                    } elseif ($pVal === '0' || $pVal === 'City Corporation') {
                        $pourashava = "global_address.pourashava IN ('0', 'City Corporation')";
                    } else {
                        $pourashava = array('global_address.pourashava' => $pVal);
                    }
                } else {
                    $pourashava = '1=1';
                }
                $ward = empty($this->request->getPost('ward')) ? '1=1' : array('global_address.ward' => $this->request->getPost('ward'));


                $customer = $this->globaladdressModel->join('customers','customers.global_address_id = global_address.global_address_id')->where($division)->where($district)->where($upazila)->where($pourashava)->where($ward)->where('customers.deleted', null)->findAll();

                $data['customer'] = $customer;

                echo view('Super_admin/header');
                echo view('Super_admin/sidebar');
                echo view('Super_admin/Customer/result', $data);
                echo view('Super_admin/footer');
            }
        }
    }



}
