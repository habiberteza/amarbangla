<?php

namespace App\Controllers\Super_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\LedgersuperModel;
use App\Models\ShopsModel;

class Ledger extends BaseController
{
    protected $validation;
    protected $session;
    protected $ledgersuperModel;
    protected $shopsModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->ledgersuperModel = new LedgersuperModel();
        $this->shopsModel = new ShopsModel();
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
            $shop = $this->request->getGet('shop');
            $st_date = $this->request->getGet('st_date');
            $en_date = $this->request->getGet('en_date');
            $perPage = (int) ($this->request->getGet('per_page') ?? 25);
            if ($perPage <= 0 || $perPage > 200) $perPage = 25;

            $builder = $this->ledgersuperModel->orderBy('sup_ledg_id', 'DESC');
            if (!empty($shop)) {
                $builder = $builder->where('sch_id', $shop);
            }
            if (!empty($st_date)) {
                $builder = $builder->where('createdDtm >=', $st_date . ' 00:00:00');
            }
            if (!empty($en_date)) {
                $builder = $builder->where('createdDtm <=', $en_date . ' 23:59:59');
            }

            $data['ledger'] = $builder->paginate($perPage);
            $data['pager'] = $this->ledgersuperModel->pager;
            $data['selected_shop'] = $shop;
            $data['st_date'] = $st_date;
            $data['en_date'] = $en_date;
            $data['per_page'] = $perPage;
            $data['shops'] = $this->shopsModel->where('deleted IS NULL')->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Ledger/index',$data);
            echo view('Super_admin/footer');
        }
    }

    public function ledger_filter(){
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {
            $shop = $this->request->getPost('shop');
            $st_date = $this->request->getPost('st_date');
            $en_date = $this->request->getPost('en_date');

            if ((!empty($shop)) || (!empty($st_date))) {
                if (!empty($shop)){
                    if (!empty($st_date)){
                        $data['ledger'] = $this->ledgersuperModel->where('sch_id',$shop)->where('createdDtm >=', $st_date . ' 00:00:00')->where('createdDtm <=', $en_date . ' 23:59:59')->findAll();
                    }else{
                        $data['ledger'] = $this->ledgersuperModel->where('sch_id',$shop)->findAll();
                    }
                }else {
                    $data['ledger'] = $this->ledgersuperModel->where('createdDtm >=', $st_date . ' 00:00:00')->where('createdDtm <=', $en_date . ' 23:59:59')->findAll();
                }

                $data['shops'] = $this->shopsModel->where('deleted IS NULL')->findAll();
                $data['sch_id'] = $shop;
                $data['st_date'] = $st_date;
                $data['en_date'] = $en_date;

                echo view('Super_admin/header');
                echo view('Super_admin/sidebar');
                echo view('Super_admin/Ledger/result', $data);
                echo view('Super_admin/footer');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Sorry! Something is wrong. <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/ledger');
            }
        }
    }







}
