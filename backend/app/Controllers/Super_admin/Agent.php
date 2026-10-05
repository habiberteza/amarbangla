<?php

namespace App\Controllers\Super_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\AgentModel;
use App\Models\AgentpermittedareaModel;
use App\Models\GensettingsagentModel;
use App\Models\GlobaladdressModel;
use App\Models\ShopsModel;

class Agent extends BaseController
{
    protected $validation;
    protected $session;
    protected $agentModel;
    protected $globaladdressModel;
    protected $agentpermittedareaModel;
    protected $shopsModel;
    protected $gensettingsagentModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->agentModel = new AgentModel();
        $this->globaladdressModel = new GlobaladdressModel();
        $this->agentpermittedareaModel = new AgentpermittedareaModel();
        $this->shopsModel = new ShopsModel();
        $this->gensettingsagentModel = new GensettingsagentModel();
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

            $builder = $this->agentModel->where('deleted IS NULL')->orderBy('agent_id', 'DESC');
            if (!empty($search)) {
                $builder = $builder->where(function($b) use ($search) {
                    $b->like('agent_name', $search)
                      ->orLike('mobile', $search);
                });
            }

            $data['agent'] = $builder->paginate($perPage);
            $data['pager'] = $this->agentModel->pager;
            $data['search'] = $search;
            $data['per_page'] = $perPage;

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Agent/index',$data);
            echo view('Super_admin/footer');
        }
    }

    public function create() {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Agent/create');
            echo view('Super_admin/footer');
        }
    }

    public function create_action(){

        $supuserId = $this->session->userIdSuper;

        $data['agent_name'] = $this->request->getPost('agent_name');
        $data['email'] = $this->request->getPost('email');
        $data['mobile'] = $this->request->getPost('mobile');
        $data['password'] = SHA1($this->request->getPost('password'));
        $data['con_password'] = SHA1($this->request->getPost('con_password'));
        $data['pass'] = $this->request->getPost('password');
        $data['createdBy'] = $supuserId;

        $this->validation->setRules([
            'agent_name' => ['label' => 'name', 'rules' => 'required'],
            'email' => ['label' => 'email', 'rules' => 'required'],
            'mobile' => ['label' => 'mobile', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required|max_length[155]'],
            'con_password' => ['label' => 'Con password', 'rules' => 'required|matches[password]'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/agent_create');
        } else {
            $check = checkUniqueField($data['mobile'], 'mobile', 'agent');
            if ($check == true) {
                $this->agentModel->insert($data);
                $agentId = $this->agentModel->getInsertID();

                $this->gensettingsagentModel->set('agent_id',$agentId)->set('label','order_management_numbers')->insert();

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/agent_create');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">This number already exists! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/agent_create');
            }

        }
    }



    public function update($agent_id) {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['agent'] = $this->agentModel->where('agent_id',$agent_id)->first();
            $data['address'] = $this->globaladdressModel->where('global_address_id',$data['agent']->global_address_id)->first();
            $data['area'] = $this->agentpermittedareaModel->where('agent_id',$agent_id)->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Agent/update',$data);
            echo view('Super_admin/footer');
        }
    }

    public function update_action()
    {
        $supuserId = $this->session->userIdSuper;

        $data['agent_id'] = $this->request->getPost('agent_id');
        $data['agent_name'] = $this->request->getPost('agent_name');
        $data['email'] = $this->request->getPost('email');
        $data['mobile'] = $this->request->getPost('mobile');
        $data['password'] = SHA1($this->request->getPost('password'));
        $data['con_password'] = SHA1($this->request->getPost('con_password'));
        $data['pass'] = $this->request->getPost('password');
        $data['status'] = $this->request->getPost('status');

        $this->validation->setRules([
            'agent_name' => ['label' => 'name', 'rules' => 'required'],
            'email' => ['label' => 'email', 'rules' => 'required'],
            'mobile' => ['label' => 'mobile', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required|max_length[155]'],
            'con_password' => ['label' => 'Con password', 'rules' => 'required|matches[password]'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/agent_update/' . $data['agent_id'].'?active=general');
        } else {

            $this->agentModel->update($data['agent_id'], $data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/agent_update/' . $data['agent_id'].'?active=general');

        }
    }

    public function address_update(){
        $supuserId = $this->session->userIdSuper;

        $dataUp['agent_id'] = $this->request->getPost('agent_id');
        $data['division'] = $this->request->getPost('division');
        $data['zila'] = $this->request->getPost('district');
        $data['upazila'] = $this->request->getPost('upazila');
        $data['pourashava'] = $this->request->getPost('pourashava');
        $data['ward'] = $this->request->getPost('ward');

        $this->validation->setRules([
            'agent_id' => ['label' => 'agent_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($dataUp) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/agent_update/' . $dataUp['agent_id'].'?active=personal');
        } else {
            $address = $this->globaladdressModel->where($data)->first();
            if (!empty($address)) {
                $dataUp['global_address_id'] = $address->global_address_id;

                $this->agentModel->update($dataUp['agent_id'], $dataUp);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/agent_update/' . $dataUp['agent_id'] . '?active=personal');
            } else {
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Global address not match! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/agent_update/' . $dataUp['agent_id'] . '?active=personal');
            }
        }
    }

    public function area_update(){
        $supuserId = $this->session->userIdSuper;

        $dataUp['agent_id'] = $this->request->getPost('agent_id');
        $data['division'] = $this->request->getPost('division');
        $data['zila'] = $this->request->getPost('district');
        $data['upazila'] = $this->request->getPost('upazila');
        $data['pourashava'] = $this->request->getPost('pourashava');
        $data['ward'] = $this->request->getPost('ward');

        $this->validation->setRules([
            'agent_id' => ['label' => 'agent_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($dataUp) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/agent_update/' . $dataUp['agent_id'].'?active=user');
        } else {
            $address = $this->globaladdressModel->where($data)->first();
            if (!empty($address)) {

                $area = $this->agentpermittedareaModel->where('global_address_id',$address->global_address_id)->first();
                if (empty($area)) {
                    $dataUp['global_address_id'] = $address->global_address_id;
                    $dataUp['createdBy'] = $supuserId;

                    $this->agentpermittedareaModel->insert($dataUp);

                    $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data insert successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    return redirect()->to('super_admin/agent_update/' . $dataUp['agent_id'] . '?active=user');
                }else{
                    $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Global address already exists! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    return redirect()->to('super_admin/agent_update/' . $dataUp['agent_id'] . '?active=user');
                }

            } else {
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Global address not match! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/agent_update/' . $dataUp['agent_id'] . '?active=user');
            }
        }
    }

    public function delete($agent_id) {

        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {
            $supuserId = $this->session->userIdSuper;
            $data['agent_id'] = $agent_id;
            $data['deleted'] = $supuserId;
            $data['deletedRole'] = $supuserId;

            $this->agentModel->update($data['agent_id'], $data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/customer_type');

        }
    }
    public function delete_area($agent_permitted_area_id,$agent_id) {

        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {
            $supuserId = $this->session->userIdSuper;
            $data['agent_permitted_area_id'] = $agent_permitted_area_id;
            $this->agentpermittedareaModel->delete($data['agent_permitted_area_id']);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/agent_update/'.$agent_id.'?active=user');

        }
    }



    public function commission($agent_id) {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['shop'] = $this->shopsModel->where('agent_id',$agent_id)->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Agent/commission',$data);
            echo view('Super_admin/footer');
        }
    }

    public function filter(){
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
                return redirect()->to('super_admin/agent');
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


                $agent = $this->globaladdressModel->join('agent','agent.global_address_id = global_address.global_address_id')->where($division)->where($district)->where($upazila)->where($pourashava)->where($ward)->where('agent.deleted IS NULL')->findAll();

                $data['agent'] = $agent;

                echo view('Super_admin/header');
                echo view('Super_admin/sidebar');
                echo view('Super_admin/Agent/result', $data);
                echo view('Super_admin/footer');
            }
        }
    }

    public function login($agent_id){
        $result = $this->agentModel->where('agent_id', $agent_id)->first();

        if (!empty($result)) {
            $sessionArray = array(
                'agentId' => $result->agent_id,
                'agentName' => $result->agent_name,
                'isLoggedInAgent' => TRUE
            );
            $this->session->set($sessionArray);
            return redirect()->to(site_url("agent/dashboard"));
        }else{
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Agent not exist! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/agent');
        }
    }





}
