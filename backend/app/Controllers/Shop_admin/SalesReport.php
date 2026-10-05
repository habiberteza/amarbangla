<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\InvoiceitemModel;
use App\Models\SupcommiinvoiceModel;


class SalesReport extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $invoiceitemModel;
    protected $supcommiinvoiceModel;
    private $module_name = 'Sales_report';
    public function __construct()
    {
        $this->invoiceitemModel = new InvoiceitemModel();
        $this->supcommiinvoiceModel = new SupcommiinvoiceModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){

        //All invoice item show list (start)
        $sale = $this->invoiceitemModel->where('sch_id', Auth()->sch_id)->orderBy('inv_item', 'DESC')->findAll(10);
        //All invoice item show list (start)


        //All sale profite Show in invoice item table (atrat)
        $saleprofit = $this->invoiceitemModel->selectSum('profit')->where('sch_id', Auth()->sch_id)->first()->profit;
        //All sale profite Show in invoice item table (end)

        $totalsale = $this->supcommiinvoiceModel->selectSum('amount')->where('sch_id', Auth()->sch_id)->first()->amount;
        $totalsalecom = $this->supcommiinvoiceModel->selectSum('commission')->where('sch_id', Auth()->sch_id)->first()->commission;


        // montly commision genaret
        $inv = $this->supcommiinvoiceModel->select('createdDtm')->selectSum('amount')->selectSum('commission')->where('sch_id', Auth()->sch_id)->groupBy('MONTH(createdDtm),YEAR(createdDtm)')->findAll();

        $data = [
            'montsale' => $inv,
            'sale' => $sale,
            'saleprofit' => $saleprofit,
            'saletotal' => $totalsale,
            'totalsalecommision' => $totalsalecom,
        ];


        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Sales_report/index',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }
    public function search(){

        $st_date = $this->request->getPost('st_date');
        $en_date = $this->request->getPost('en_date');

        $data['sale'] = $this->invoiceitemModel->where('sch_id', Auth()->sch_id)->where('createdDtm >=', $st_date . ' 00:00:00')->where('createdDtm <=', $en_date . ' 23:59:59')->findAll();

        $data['saleprofit'] = $this->invoiceitemModel->selectSum('profit')->where('sch_id', Auth()->sch_id)->where('createdDtm >=', $st_date)->where('createdDtm <=', $en_date)->first()->profit;
        $data['st_date']= $st_date;
        $data['en_date']= $en_date;

        echo view('Shop_admin/Sales_report/report',$data);

    }







}