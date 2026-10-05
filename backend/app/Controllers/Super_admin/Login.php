<?php

namespace App\Controllers\Super_admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Login extends BaseController
{
    protected $validation;
    protected $session;
    protected $adminModel;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->adminModel = new AdminModel();
    }
    public function index() {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {

            echo view('Super_admin/Login/login');

        }else {
            return redirect()->to(site_url("super_admin/shops"));
        }
    }


    public function login_action(){
        $this->validation->setRule('email', 'Email', 'required|valid_email|max_length[128]|trim');
        $this->validation->setRule('password', 'Password', 'required|max_length[32]');

        if ($this->validation->withRequest($this->request)->run() == FALSE) {

            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">All field is required <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to(site_url('super_admin'));
        } else {

            $email = strtolower($this->request->getPost('email'));
            $password = $this->request->getPost('password');


            $result = $this->loginMe($email, $password);

            if (!empty($result)) {

                // Remember me (Remembering the user email and password) Start
                if (!empty($this->request->getPost("remember"))) {

                    setcookie('login_email_super_admin', $email, time() + (86400 * 30), "/");
                    setcookie('login_password_super_admin', $password, time() + (86400 * 30), "/");

                } else {
                    if (isset($_COOKIE['login_email_super_admin'])) {
                        setcookie('login_email_super_admin', '', 0, "/");
                    }
                    if (isset($_COOKIE['login_password_super_admin'])) {
                        setcookie('login_password_super_admin', '', 0, "/");
                    }
                }
                // Remember me (Remembering the user email and password) End



                $sessionArray = array(
                    'userIdSuper' => $result->user_id,
                    'sup_name' => $result->name,
                    'isLoggedInSuperAdmin' => TRUE
                );

                $this->session->set($sessionArray);

                return redirect()->to(site_url("super_admin/shops"));

            } else {
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Email or password mismatch <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to(site_url("super_admin"));
            }
        }
    }


    private function loginMe($email, $password)
    {
        $user = $this->adminModel->where('email', $email)->first();

        if (!empty($user)) {
            if (SHA1($password) == $user->password) {
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    public function logout()
    {

        unset($_SESSION['userIdSuper']);
        unset($_SESSION['sup_name']);
        unset($_SESSION['isLoggedInSuperAdmin']);

//        $this->session->destroy();
        return redirect()->to('super_admin');
    }


}
