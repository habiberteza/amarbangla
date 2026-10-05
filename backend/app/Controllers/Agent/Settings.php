<?php

namespace App\Controllers\Agent;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\AgentModel;
use App\Models\GensettingsagentModel;
use App\Models\PackageModel;

class Settings extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $crop;
    protected $agentModel;
    protected $gensettingsagentModel;


    public function __construct()
    {
        $this->agentModel = new AgentModel();
        $this->gensettingsagentModel = new GensettingsagentModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
        $this->crop = \Config\Services::image();
    }


    public function index() {
        $setting = $this->gensettingsagentModel->where('agent_id',Auth_agent()->agent_id)->where('label','order_management_numbers')->first();
        $data['order_management_numbers'] = $setting ? $setting->value : '';
        echo view('Agent/Settings/index',$data);
    }
    public function update_action(){
        $data['agent_id'] = Auth_agent()->agent_id;
        $data['agent_name'] = $this->request->getPost('agent_name');
        $data['email'] = $this->request->getPost('email');
        $data['mobile'] = $this->request->getPost('mobile');
        $data['password'] = SHA1($this->request->getPost('password'));
        $data['con_password'] = SHA1($this->request->getPost('con_password'));
        $data['pass'] = $this->request->getPost('password');

        $order_management_numbers = $this->request->getPost('order_management_numbers');

        $this->validation->setRules([
            'agent_name' => ['label' => 'name', 'rules' => 'required'],
            'email' => ['label' => 'email', 'rules' => 'required'],
            'mobile' => ['label' => 'mobile', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required|max_length[155]'],
            'con_password' => ['label' => 'Con password', 'rules' => 'required|matches[password]'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/settings');
        } else {
            $this->agentModel->update($data['agent_id'],$data);

            $this->gensettingsagentModel->set('value', $order_management_numbers)->where('agent_id',Auth_agent()->agent_id)->where('label','order_management_numbers')->update();

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('agent/settings');

        }
    }







}