<?php

namespace App\Controllers\Super_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\CommissionModel;
use App\Models\DeliveryboyModel;
use App\Models\DeliveryModel;
use App\Models\GlobaladdressModel;
use App\Models\InvoiceModel;
use App\Models\LedgerdeliveryboyModel;
use App\Models\PackageModel;

class Deliveryboy extends BaseController
{
    protected $validation;
    protected $session;
    protected $deliveryboyModel;
    protected $globaladdressModel;
    protected $commissionModel;
    protected $ledgerdeliveryboyModel;
    protected $invoiceModel;
    protected $packageModel;
    protected $deliveryModel;

    protected $crop;
    protected $permission;

    public function __construct()
    {
        $this->deliveryboyModel = new DeliveryboyModel();
        $this->globaladdressModel = new GlobaladdressModel();
        $this->ledgerdeliveryboyModel = new LedgerdeliveryboyModel();
        $this->invoiceModel = new InvoiceModel();
        $this->commissionModel = new CommissionModel();
        $this->packageModel = new PackageModel();
        $this->deliveryModel = new DeliveryModel();
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

            $builder = $this->deliveryboyModel->where('deleted IS NULL')->orderBy('delivery_boy_id', 'DESC');
            if (!empty($search)) {
                $builder = $builder->where(function($b) use ($search) {
                    $b->like('name', $search)
                      ->orLike('mobile', $search);
                });
            }

            $data['deliveryboy'] = $builder->paginate($perPage);
            $data['pager'] = $this->deliveryboyModel->pager;
            $data['search'] = $search;
            $data['per_page'] = $perPage;

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Deliveryboy/index',$data);
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
            echo view('Super_admin/Deliveryboy/create');
            echo view('Super_admin/footer');
        }
    }

    public function create_action(){
        $supuserId = $this->session->userIdSuper;

        $data['name'] = $this->request->getPost('name');
        $data['mobile'] = $this->request->getPost('mobile');
        $data['password'] = SHA1($this->request->getPost('password'));
        $data['con_password'] = SHA1($this->request->getPost('con_password'));
        $data['pass'] = $this->request->getPost('password');
        $data['role_id'] = '3';
        $data['balance'] = '0';
        $data['createdBy'] = $supuserId;

        $this->validation->setRules([
            'name' => ['label' => 'name', 'rules' => 'required'],
            'mobile' => ['label' => 'mobile', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required|max_length[155]'],
            'con_password' => ['label' => 'Con password', 'rules' => 'required|matches[password]'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/delivery_boy_create');
        } else {
            $check = checkUniqueField($data['mobile'], 'mobile', 'delivery_boy');
            if ($check == true) {

                $this->deliveryboyModel->insert($data);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Create Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/delivery_boy_create');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">This number already exists! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/delivery_boy_create');
            }

        }
    }


    public function update($delivery_boy_id) {
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['delivery'] = $this->deliveryboyModel->where('delivery_boy_id',$delivery_boy_id)->first();
            $data['address'] = $this->globaladdressModel ->where('global_address_id',$data['delivery']->global_address_id)->first();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Deliveryboy/update',$data);
            echo view('Super_admin/footer');
        }
    }

    public function update_action()
    {
        $supuserId = $this->session->userIdSuper;

        $data['delivery_boy_id'] = $this->request->getPost('delivery_boy_id');
        $data['name'] = $this->request->getPost('name');
        $data['mobile'] = $this->request->getPost('mobile');
        $data['pass'] = $this->request->getPost('password');
        $data['password'] = SHA1($this->request->getPost('password'));
        $data['con_password'] = SHA1($this->request->getPost('con_password'));

        $this->validation->setRules([
            'name' => ['label' => 'name', 'rules' => 'required'],
            'mobile' => ['label' => 'mobile', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required'],
            'con_password' => ['label' => 'Con password', 'rules' => 'required|matches[password]'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/delivery_boy_update/' . $data['delivery_boy_id'].'?active=general');
        } else {

            $this->deliveryboyModel->update($data['delivery_boy_id'], $data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/delivery_boy_update/' . $data['delivery_boy_id'].'?active=general');

        }
    }

    public function personal_update()
    {
        $supuserId = $this->session->userIdSuper;

        $data['delivery_boy_id'] = $this->request->getPost('delivery_boy_id');
        $data['father_name'] = $this->request->getPost('father_name');
        $data['mother_name'] = $this->request->getPost('mother_name');
        $data['age'] = $this->request->getPost('age');
        $data['status'] = $this->request->getPost('status');
        $data['agent_id'] = $this->request->getPost('agent_id');

        $this->validation->setRules([
            'delivery_boy_id' => ['label' => 'delivery_boy_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/delivery_boy_update/' . $data['delivery_boy_id'].'?active=personal');
        } else {

            $this->deliveryboyModel->update($data['delivery_boy_id'], $data);

            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/delivery_boy_update/' . $data['delivery_boy_id'].'?active=personal');

        }
    }
    public function address_update()
    {
        $supuserId = $this->session->userIdSuper;

        $dataUp['delivery_boy_id'] = $this->request->getPost('delivery_boy_id');
        $data['division'] = $this->request->getPost('division');
        $data['zila'] = $this->request->getPost('district');
        $data['upazila'] = $this->request->getPost('upazila');
        $data['pourashava'] = $this->request->getPost('pourashava');
        $data['ward'] = $this->request->getPost('ward');

        $this->validation->setRules([
            'delivery_boy_id' => ['label' => 'delivery_boy_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($dataUp) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/delivery_boy_update/' . $dataUp['delivery_boy_id'].'?active=user');
        } else {
            $address = $this->globaladdressModel->where($data)->first();
            if (!empty($address)) {
                $dataUp['global_address_id'] = $address->global_address_id;
                $this->deliveryboyModel->update($dataUp['delivery_boy_id'], $dataUp);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/delivery_boy_update/' . $dataUp['delivery_boy_id'] . '?active=user');
            }else{
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Global address not match! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/delivery_boy_update/' . $dataUp['delivery_boy_id'].'?active=user');
            }

        }
    }

    public function photo_update() {
        $data['delivery_boy_id'] = $this->request->getPost('delivery_boy_id');
        $this->validation->setRules([
            'delivery_boy_id' => ['label' => 'delivery_boy_id', 'rules' => 'required'],
        ]);

        if ($this->validation->run($data) == FALSE) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">' . $this->validation->listErrors() . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/delivery_boy_update/' . $data['delivery_boy_id'] . '?active=photo');
        } else {
            if (!empty($_FILES['pic']['name'])) {
                $target_dir = FCPATH . '/uploads/delivery_boy_image/';
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777);
                }

                //old image unlink
                $old_img = get_data_by_id('pic', 'delivery_boy', 'delivery_boy_id', $data['delivery_boy_id']);
                if (!empty($old_img)) {
                    unlink($target_dir . '' . $old_img);
                }

                //new image uplode
                $pic = $this->request->getFile('pic');
                $namePic = $pic->getRandomName();
                $pic->move($target_dir, $namePic);
                $pro_nameimg = 'delivery_' . $pic->getName();
                $this->crop->withFile($target_dir . '' . $namePic)->fit(300, 300, 'center')->save($target_dir . '' . $pro_nameimg);
                unlink($target_dir . '' . $namePic);
                $data['pic'] = $pro_nameimg;

                $this->deliveryboyModel->update($data['delivery_boy_id'], $data);

                $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Data update successfully <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/delivery_boy_update/' . $data['delivery_boy_id'] . '?active=photo');
            } else {
                $this->session->setFlashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert">Please select any image!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return redirect()->to('super_admin/delivery_boy_update/' . $data['delivery_boy_id'] . '?active=photo');
            }
        }
    }

    public function delete($delivery_boy_id) {

        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {
            $supuserId = $this->session->userIdSuper;
            $data['delivery_boy_id'] = $delivery_boy_id;
            $data['deleted'] = $supuserId;
            $data['deletedRole'] = $supuserId;

            $this->deliveryboyModel->update($data['delivery_boy_id'], $data);
            $this->session->setFlashdata('message', '<div class="alert alert-success alert-dismissible" role="alert">Delete Record Success <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            return redirect()->to('super_admin/delivery_boy');

        }
    }

    public function ledger($delivery_boy_id){
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['ledger'] = $this->ledgerdeliveryboyModel->where('delivery_boy_id',$delivery_boy_id)->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Deliveryboy/ledger',$data);
            echo view('Super_admin/footer');
        }
    }
    public function commission($delivery_boy_id){
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['shops'] = $this->commissionModel->join('package', 'package.invoice_id = commission.invoice_id')->where('commission.delivery_boy_id = ' . $delivery_boy_id)->groupBy('package.sch_id')->findAll();

            $data['commission'] = $this->commissionModel->where('delivery_boy_id',$delivery_boy_id)->findAll();

            $data['deliveryBoyId'] = $delivery_boy_id;

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Deliveryboy/commission',$data);
            echo view('Super_admin/footer');
        }
    }

    public function delivery_boy_order($delivery_boy_id){
        $isLoggedInSuperAdmin = $this->session->isLoggedInSuperAdmin;
        if (!isset($isLoggedInSuperAdmin) || $isLoggedInSuperAdmin != TRUE) {
            return redirect()->to(site_url("super_admin"));
        } else {

            $data['orderData'] = $this->deliveryModel->where('delivery_boy_id', $delivery_boy_id)->findAll();

            echo view('Super_admin/header');
            echo view('Super_admin/sidebar');
            echo view('Super_admin/Deliveryboy/order',$data);
            echo view('Super_admin/footer');
        }
    }

    public function commission_data(){
        $shopID = $this->request->getPost('sch_id');
        $deliveryBoyId = $this->request->getPost('delivery_boy_id');

        $commi = $this->commissionModel->join('package', 'commission.invoice_id = package.invoice_id')->where('package.sch_id =' . $shopID)->where('commission.delivery_boy_id = ' . $deliveryBoyId)->findAll();

        $view = '';
        $i = 0;
        foreach ($commi as $row) {

            $view .= '<tr><td>' . ++$i . '</td>
                <td>' . invoiceDateFormat($row->createdDtm) . '</td>
                <td>' . $row->invoice_id . '</td>
                <td>' . showWithCurrencySymbol($row->commission) . '</td>
                <td>';
            if ($row->com_status == 0) {
                $view .= '<span class="label bg-warning">pending</span>';
            } else {
                $view .= '<span class="label bg-success">paid</span>';
            }
            $view .= '</td></tr>';
        }

        print $view;
    }
    public function commission_data_total(){
        $shopID = $this->request->getPost('sch_id');
        $deliveryBoyId = $this->request->getPost('delivery_boy_id');

        $shopName = get_data_by_id('name', 'shops', 'sch_id', $shopID);

        $duecom = $this->commissionModel->selectSum('commission')->join('package', 'commission.invoice_id = package.invoice_id')->where('package.sch_id =' . $shopID)->where('commission.delivery_boy_id = ' . $deliveryBoyId)->where('commission.com_status', '0')->first()->commission;

        $reccom = $this->commissionModel->selectSum('commission')->join('package', 'commission.invoice_id = package.invoice_id')->where('package.sch_id =' . $shopID)->where('commission.delivery_boy_id = ' . $deliveryBoyId)->where('commission.com_status', '1')->first()->commission;

        $view = '<div class="col-md-12" style="padding: 40px;font-size: 20px; background-color: #f39c13;"><div class="col-md-4"><b>Shop Name</b> ' . $shopName . '</div>
                <div class="col-md-4"><b>Due Commision</b> ' . showWithCurrencySymbol($duecom) . '</div>
                <div class="col-md-4"><b>Received commision</b> ' . showWithCurrencySymbol($reccom) . '</div></div>';

        print $view;
    }

    public function commission_all(){
        $deliveryBoyId = $this->request->getPost('delivery_boy_id');
        $shope = $this->commissionModel->join('package', 'package.invoice_id = commission.invoice_id')->where('commission.delivery_boy_id = ' . $deliveryBoyId)->groupBy('package.sch_id')->findAll();
        $rowview = '';
        $totaodue = 0;
        $totaorecv = 0;
        $rowview .= '<div class="col-md-12" >
                    <div id="shopcommDetail " class="row">
                        <div class="col-md-8">
                            <table class="table table-bordered table-striped " id="TFtable">
                                <thead>
                                    <tr>
                                        <th>Shop Name</th>
                                        <th>Due Commision</th>
                                        <th>Received Commision</th>
                                    </tr>
                                </thead>
                                <tbody>';


        foreach ($shope as $view) {
            $totaodue += get_due_commision_delivery_boy($view->sch_id, $deliveryBoyId);
            $totaorecv += get_received_commision_delivery_boy($view->sch_id, $deliveryBoyId);

            $rowview .= '<tr>
                                        <td>' . get_data_by_id('name', 'shops', 'sch_id', $view->sch_id) . '
                                        </td>
                                        <td>
                                            ' . showWithCurrencySymbol(get_due_commision_delivery_boy($view->sch_id, $deliveryBoyId)) . '
                                        </td><td>' . showWithCurrencySymbol(get_received_commision_delivery_boy($view->sch_id, $deliveryBoyId)) . '
                                        </td>
                                    </tr>';
        }
        $rowview .= '</tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-bordered table-striped " id="TFtable">
                                <tr>
                                    <td>Total Due</td>
                                    <td>' . showWithCurrencySymbol($totaodue) . '</td>
                                </tr>
                                <tr>
                                    <td>Total received</td>
                                    <td>' . showWithCurrencySymbol($totaorecv) . '</td>
                                </tr>
                            </table>                            
                        </div>
                    </div>                    
                </div>';
        print $rowview;
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
                return redirect()->to('super_admin/delivery_boy');
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


                $deliveryboy = $this->globaladdressModel->join('delivery_boy','delivery_boy.global_address_id = global_address.global_address_id')->where($division)->where($district)->where($upazila)->where($pourashava)->where($ward)->where('delivery_boy.deleted IS NULL')->findAll();

                $data['deliveryboy'] = $deliveryboy;

                echo view('Super_admin/header');
                echo view('Super_admin/sidebar');
                echo view('Super_admin/Deliveryboy/result', $data);
                echo view('Super_admin/footer');
            }
        }
    }

}
