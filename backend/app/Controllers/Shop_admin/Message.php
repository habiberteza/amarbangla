<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\MessageModel;

class Message extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $messageModel;
    private $module_name = 'Message';
    public function __construct()
    {
        $this->messageModel = new MessageModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){


        $data['result'] = $this->messageModel->where('sch_id',Auth()->sch_id)->where('deleted IS NULL')->orderBy('message_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Message/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }
    public function view($message_id){


        $upData['message_id'] = $message_id;
        $upData['red_status'] = '1';
        $upData['updatedBy'] = Auth()->user_id;
        $this->messageModel->update($upData['message_id'], $upData);

        $data['message'] = $this->messageModel->where('message_id',$message_id)->first();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['create']) and $permi['create'] == 1) {
            echo view('Shop_admin/Message/view',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }

    }








}