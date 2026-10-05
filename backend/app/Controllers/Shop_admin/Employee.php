<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\EmployeeModel;

class Employee extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;

    protected $employeeModel;
    private $module_name = 'Employee';
    public function __construct()
    {
        $this->employeeModel = new EmployeeModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){
        $data['employee'] = $this->employeeModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('employee_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Employee/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function create(){
        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/Employee/create');
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function create_action(){

        $data['sch_id'] = Auth()->sch_id;
        $data['name'] = $this->request->getPost('name');
        $data['salary'] = $this->request->getPost('salary');
        $data['age'] = $this->request->getPost('age');
        $data['createdBy'] = Auth()->user_id;
        $data['createdDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'name' => ['label' => 'Name', 'rules' => 'required'],
            'salary' => ['label' => 'Salary', 'rules' => 'required'],
            'age' => ['label' => 'Age', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/employee_create');
        } else {
            $this->employeeModel->insert($data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Add Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/employee_create');

        }
    }
    public function update($employee_id){
        $data['employee'] = $this->employeeModel->where('employee_id',$employee_id)->first();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Employee/update', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }

    }
    public function update_action(){
        $data['employee_id'] = $this->request->getPost('employee_id');
        $data['name'] = $this->request->getPost('name');
        $data['salary'] = $this->request->getPost('salary');
        $data['age'] = $this->request->getPost('age');
        $data['updatedBy'] = Auth()->user_id;
        $data['updatedDtm'] = date('Y-m-d h:i:s');

        $this->validation->setRules([
            'name' => ['label' => 'Name', 'rules' => 'required'],
            'salary' => ['label' => 'Salary', 'rules' => 'required'],
            'age' => ['label' => 'Age', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/employee_update/'.$data['employee_id']);
        } else {
            $this->employeeModel->update($data['employee_id'],$data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Update Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('shop_admin/employee_update/'.$data['employee_id']);
        }
    }
    public function delete($employee_id){
        $data['employee_id'] = $employee_id;
        $data['deleted'] = Auth()->user_id;
        $data['deletedRole'] = Auth()->role_id;

        $this->employeeModel->update($data['employee_id'],$data);
        $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('shop_admin/employee');
    }








}