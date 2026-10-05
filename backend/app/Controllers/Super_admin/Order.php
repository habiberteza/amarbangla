<?php

namespace App\Controllers\Super_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\DeliveryModel;
use App\Models\GlobaladdressModel;
use App\Models\InvoiceitemModel;
use App\Models\InvoiceModel;
use App\Models\LedgerModel;
use App\Models\PackageModel;
use App\Models\ShopsModel;

class Order extends BaseController
{
    protected $validation;
    protected $session;
    protected $invoiceModel;
    protected $invoiceitemModel;
    protected $ledgerModel;
    protected $shopsModel;
    protected $deliveryModel;
    protected $packageModel;
    protected $globaladdressModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
        $this->invoiceitemModel = new InvoiceitemModel();
        $this->shopsModel = new ShopsModel();
        $this->deliveryModel = new DeliveryModel();
        $this->packageModel = new PackageModel();
        $this->ledgerModel = new LedgerModel();
        $this->globaladdressModel = new GlobaladdressModel();
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
            $status = $this->request->getGet('status');
            $search = trim($this->request->getGet('search') ?? '');
            $perPage = (int) ($this->request->getGet('per_page') ?? 25);
            if ($perPage <= 0 || $perPage > 200) $perPage = 25;

            $builder = $this->invoiceModel->orderBy('invoice_id', 'DESC');
            if (!empty($shop)) {
                $builder = $builder->where('sch_id', $shop);
            }
            if ($status !== null && $status !== '') {
                $builder = $builder->where('status', $status);
            }
            if (!empty($search)) {
                $builder = $builder->where('invoice_id', $search);
            }

            $data['order'] = $builder->paginate($perPage);
            $data['pager'] = $this->invoiceModel->pager;
            $data['selected_shop'] = $shop;
            $data['selected_status'] = $status;
            $data['search'] = $search;
            $data['per_page'] = $perPage;
            $data['shope'] = $this->shopsModel->where('deleted IS NULL')->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Order/index',$data);
            echo view('Super_admin/footer');
        }
    }
    public function invoice($invoice_id) {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {


            $data['shopsName'] = 'Amer bangla bazar';
            $data['invoiceId'] = $invoice_id;

            $data['invoiceItame'] = $this->invoiceitemModel->where('invoice_id',$invoice_id)->findAll();


            //due calculation (start)
            $invoiceDue = $this->invoiceModel->select('due')->where('invoice_id',$invoice_id)->first()->due;


            $rest_balance_query = $this->ledgerModel->select('rest_balance')->where('invoice_id',$invoice_id)->where('trangaction_type','Cr.')->first();

            if (!empty($rest_balance_query)) {
                $rest_balance = $rest_balance_query->rest_balance;
            }else {
                $rest_balance = 0;
            }


            $amount_query = $this->ledgerModel->select('amount')->where('invoice_id',$invoice_id)->where('trangaction_type','Cr.')->first();
            if (!empty($amount_query)) {
                $amount = $amount_query->amount;
            }else {
                $amount = 0;
            }



            $data['oldDue'] = $rest_balance - $amount;
            $data['totalDue'] = $invoiceDue;
            //due calculation (end)

            $data['customerId'] = get_data_by_id('customer_id','invoice','invoice_id',$invoice_id);

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Order/invoice',$data);
            echo view('Super_admin/footer');
        }
    }

    public function order_filter_status($status){
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['order'] = $this->invoiceModel->where('status',$status)->findAll();
            $data['shope'] = $this->shopsModel->where('deleted IS NULL')->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Order/result',$data);
            echo view('Super_admin/footer');
        }
    }

    public function order_filter($status){
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['order'] = $this->deliveryModel->join('invoice','delivery.invoice_id = invoice.invoice_id')->where('delivery.status',$status)->findAll();
            $data['shope'] = $this->shopsModel->where('deleted IS NULL')->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Order/result',$data);
            echo view('Super_admin/footer');
        }
    }

    public function order_filter_not_accepted(){
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['order'] = $this->invoiceModel->where("not exists (select * from delivery where delivery.invoice_id = invoice.invoice_id)",null,false)->where('invoice.status !=','3')->findAll();
            $data['shope'] = $this->shopsModel->where('deleted IS NULL')->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Order/result',$data);
            echo view('Super_admin/footer');
        }
    }

    public function order_filter_shop(){
        $shopId = $this->request->getPost('sch_id');

        $data = '';
        $status = '';
        $i = 1;

        $order = $this->packageModel->join('invoice','invoice.invoice_id = package.invoice_id')->where('package.sch_id', $shopId)->findAll();

        foreach($order as $or){
            $link = site_url('super_admin/order_invoice/'.$or->invoice_id);
            $orLink = site_url('super_admin/customer_order_list/'.$or->customer_id);
            $deliverystatus = deliverystatus($or->invoice_id);
            if (!empty($deliverystatus)) {
                foreach ($deliverystatus as $row) {
                    $detail = 'Delivery By Admin';
                    if (!empty($row->delivery_boy_id)) {
                        $deliveryBoyData = get_all_row_by_id('delivery_boy', 'delivery_boy_id', $row->delivery_boy_id);
                        $name = $deliveryBoyData->name;
                        $phone = $deliveryBoyData->mobile;
                        $detail = $name . '<br>' . showWithPhoneNummberCountryCode($phone);
                    }
                    if ($row->status == 0) {
                        $status = '<span class="label bg-info p-1">Accepted</span><br>' .$detail. '<br>';
                    }
                    if ($row->status == 1) {
                        $status = '<span class="label bg-success p-1">Complete</span><br>' . $detail . '<br>';
                    }
                }
            }else{
                $status = '<span class="label bg-warning p-1">Not Accepted</span>';
            }

            $data .= '<tr>
            <td>'.$i++ .'</td>
            <td><a href="'.$link.'">View Invoice</a></td>
            <td>'. showWithCurrencySymbol($or->final_amount).'</td>
            <td>'.invoiceDateFormat($or->createdDtm).'</td> 
            <td>'.getinvoiceStatusNew($or->invoice_id).'</td>
            <td>'.$status.'</td> 
            <td><a href="'.$orLink.'" class="btn btn-xs btn-success" target="_blank">Order list</a></td> 
        </tr>';
        }



        print $data;
    }

    public function order_filter_invoice(){
        $division1 = $this->request->getPost('division');
        $zila1 = $this->request->getPost('district');
        $upazila1 = $this->request->getPost('upazila');
        $pourashava1 = $this->request->getPost('pourashava');
        $ward1 = $this->request->getPost('ward');
        $status = $this->request->getPost('status');


        $division = empty($division1) ? '1=1' : array('global_address.division' => $division1);
        $district = empty($zila1) ? '1=1' : array('global_address.zila' => $zila1);
        $upazila = empty($upazila1) ? '1=1' : array('global_address.upazila' => $upazila1);
        if (!empty($pourashava1)) {
            if ($pourashava1 === '1' || $pourashava1 === 'Pourashava') {
                $pourashava = "global_address.pourashava IN ('1', 'Pourashava')";
            } elseif ($pourashava1 === '2' || $pourashava1 === 'Union') {
                $pourashava = "global_address.pourashava IN ('2', 'Union')";
            } elseif ($pourashava1 === '0' || $pourashava1 === 'City Corporation') {
                $pourashava = "global_address.pourashava IN ('0', 'City Corporation')";
            } else {
                $pourashava = array('global_address.pourashava' => $pourashava1);
            }
        } else {
            $pourashava = '1=1';
        }
        $ward = empty($ward1) ? '1=1' : array('global_address.ward' => $ward1);
        $status = empty($status) ? '1=1' : array('invoice.status' => $status);

        $view = '';
        $j=1;

        $inv = $this->globaladdressModel->join('invoice','invoice.global_address_id = global_address.global_address_id')->where($division)->where($district)->where($upazila)->where($pourashava)->where($ward)->where($status);
        foreach($inv->findAll() as $row){
            $invUrl = site_url('super_admin/order_invoice/' . $row->invoice_id);
            $orUrl = site_url('super_admin/customer_order_list/'.$row->customer_id);
            $view .='<tr>
                <td>'.$j++.'</td>
                <td><a href="'.$invUrl.'">View Invoice</a></td>
                <td>'.showWithCurrencySymbol($row->final_amount).'</td>
                <td>'.invoiceDateFormat($row->createdDtm).'</td>
                <td>'.getinvoiceStatusNew($row->invoice_id).'</td>
                <td>';
            $deliverystatus = deliverystatus($row->invoice_id);
            if (!empty($deliverystatus)) {
                foreach ($deliverystatus as $val) {
                    $detail = 'Delivery By Admin';
                    if (!empty($val->delivery_boy_id)) {
                        $deliveryBoyData = get_all_row_by_id('delivery_boy', 'delivery_boy_id', $val->delivery_boy_id);
                        $name = $deliveryBoyData->name;
                        $phone = $deliveryBoyData->mobile;
                        $detail = $name . '<br>' . showWithPhoneNummberCountryCode($phone);
                    }

                    if ($val->status == 0) {
                        $view .= '<span class="label bg-info p-1">Accepted</span><br>' .$detail. '<br>';
                    }
                    if ($val->status == 1) {
                        $view .= '<span class="label bg-success p-1">Complete</span><br>' . $detail . '<br>';

                    }
                }
            }else{
                $view .= '<span class="label bg-warning p-1">Not Accepted</span>';
            }

            $view .='</td>
                <td><a href="'.$orUrl.'" class="btn btn-xs btn-success" target="_blank">Order list</a></td>
            </tr>';
        }


        print $view;
    }




}
