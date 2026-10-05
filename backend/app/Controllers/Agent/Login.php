<?php

namespace App\Controllers\Agent;

use App\Controllers\BaseController;
use App\Models\AgentModel;


class Login extends BaseController
{
    protected $validation;
    protected $session;
    protected $agentModel;
    protected $email;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->email = \Config\Services::email();
        $this->agentModel = new AgentModel();
    }

    public function index(){
        return view('Agent/Login/login');
    }

    public function login_action()
    {
        $this->validation->setRule('email', 'Email', 'required|valid_email|max_length[128]|trim');
        $this->validation->setRule('password', 'Password', 'required|max_length[32]');

        if ($this->validation->withRequest($this->request)->run() == FALSE) {

            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">All field is required <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');

            return redirect()->to(site_url('agent/login'));
        } else {

            $email = strtolower($this->request->getPost('email'));
            $password = $this->request->getPost('password');

            $result = $this->loginMe($email, $password);

            if (!empty($result)) {

                if (!empty($this->request->getPost("remember"))) {

                    setcookie('login_email_agent', $email, time() + (86400 * 30), "/");

                    setcookie('login_password_agent', $password, time() + (86400 * 30), "/");

                } else {

                    if (isset($_COOKIE['login_email_agent'])) {

                        setcookie('login_email_agent', '', 0, "/");

                    }

                    if (isset($_COOKIE['login_password_agent'])) {

                        setcookie('login_password_agent', '', 0, "/");

                    }
                }

                $sessionArray = array(
                    'agentId' => $result->agent_id,
                    'agentName' => $result->agent_name,
                    'isLoggedInAgent' => TRUE
                );
                $this->session->set($sessionArray);
                // $this->session->set_userdata($sessionArray);
                return redirect()->to(site_url("agent/dashboard"));



            } else {

                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Email or password mismatch <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');

                return redirect()->to(site_url("agent/login"));

            }

        }
    }


    private function loginMe($email, $password)
    {
        $user = $this->agentModel->where('email', $email)->first();

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

        unset($_SESSION['userId']);
        unset($_SESSION['shopId']);
        unset($_SESSION['role']);
        unset($_SESSION['name']);
        unset($_SESSION['isLoggedInAgent']);

        //        $this->session->destroy();
        return redirect()->to('agent/login');
    }


    public function forgotPassword()
    {
        $isLoggedInShopAdmin = $this->session->isLoggedInShopAdmin;

        if (!isset($isLoggedInShopAdmin) || $isLoggedInShopAdmin != TRUE) {

            echo view('agent/Login/ForgotPassword');

        } else {
            // return redirect()->to(site_url("super_admin/shops"));
        }

    }

    public function reset_link()
    {

        $email = $this->request->getPost('email');

        $result = $this->userModel->where('email', $email)->countAllResults();

        if ($result > 0) {
            $tokan = rand(1000, 9999);

            $_SESSION['tokan'] = $tokan;
            $_SESSION['email'] = $email;

            $message = "Please Click On Password Reset Link <br> <a href='" . base_url('login/reset?tokan=') . $tokan . "'>Reset Password</a>";

            //print $message;
            // $this->email->from('example@gmail.com', 'example');
            // $this->email->to($email);
            // $this->email->subject('Reset Password Link');
            // $this->email->message($message);
            // if (!$this->email->send()) {
            //     show_error($this->email->print_debugger());
            // } else {
            //     echo 'Reset password link sent successfully!';
            // }

            return redirect()->to(site_url("shop_admin/Login/otp"));
        } else {
            // $this->session->set_flashdata('message', '<div style="margin-top: 12px" class="alert alert-danger" id="message">Your Email Address Could Not Be Found</div>');

            return redirect()->to(site_url("shop_admin/forgot_password"));
        }

    }

    public function otp()
    {
        // echo $_SESSION['tokan'];

        if (!empty($_SESSION['tokan'])) {
            echo ($_SESSION['tokan']);
            return view('Shop_admin/Login/otp');
        } else {
            return redirect()->to(site_url("shop_admin/forgot_password"));
        }

    }

    public function otp_action()
    {
        $opt = $this->request->getPost('otp');
        if (!empty($_SESSION['tokan'])) {
            if ($_SESSION['tokan'] == $opt) {
                $_SESSION['isReset'] = true;
                return redirect()->to(site_url("shop_admin/reset_password"));

            } else {
                return redirect()->to(site_url("shop_admin/otp"));
            }

        } else {
            return redirect()->to(site_url("shop_admin/forgot_password"));
        }
    }

    public function reset_password()
    {
        if (!empty($_SESSION['tokan']) && !empty($_SESSION['isReset'])) {

            return view("Shop_admin/Login/Reset_password");
        } else {

            return redirect()->to(site_url("shop_admin/otp"));
        }
    }

    public function reset_password_action()
    {

        $this->validation->setRule('password', 'Password', 'required|max_length[30]');
        $this->validation->setRule('cpassword', 'Confirm Password', 'required|matches[password]|max_length[30]');

        if ($this->validation->withRequest($this->request)->run() == FALSE) {
            return redirect()->to(site_url('Shop_admin/Reset_password'));
        } else {
            $data['password'] = SHA1($this->request->getPost('password'));

            $updateId = $this->userModel->update($_SESSION['email'], $data);

            if ($updateId) {

                unset($_SESSION['forgot_email']);
                unset($_SESSION['otp']);
                unset($_SESSION['forgetPassword']);

                return view("Shop_admin/Login/login");

            } else {
                return view("Shop_admin/Reset_password");
            }
        }

    }


}





