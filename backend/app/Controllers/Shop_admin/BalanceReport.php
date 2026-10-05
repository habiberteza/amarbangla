<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\BankModel;
use App\Models\CommissionModel;
use App\Models\EmployeeModel;
use App\Models\LedgerexpenseModel;
use App\Models\LedgerothersalesModel;
use App\Models\LoanproviderModel;
use App\Models\PackageModel;
use App\Models\ProductsModel;
use App\Models\ReturnsaleModel;
use App\Models\SuppecommisionModel;
use App\Models\SuppliersModel;
use App\Models\VatregisterModel;

class BalanceReport extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $bankModel;
    protected $loanproviderModel;
    protected $packageModel;
    protected $productsModel;
    protected $ledgerothersalesModel;
    protected $employeeModel;
    protected $suppliersModel;
    protected $ledgerexpenseModel;
    protected $suppecommisionModel;
    protected $returnsaleModel;
    protected $vatregisterModel;
    protected $commissionModel;
    private $module_name = 'Balance_report';
    public function __construct()
    {
        $this->bankModel = new BankModel();
        $this->loanproviderModel = new LoanproviderModel();
        $this->packageModel = new PackageModel();
        $this->productsModel = new ProductsModel();
        $this->ledgerothersalesModel = new LedgerothersalesModel();
        $this->employeeModel = new EmployeeModel();
        $this->suppliersModel = new SuppliersModel();
        $this->ledgerexpenseModel = new LedgerexpenseModel();
        $this->suppecommisionModel = new SuppecommisionModel();
        $this->returnsaleModel = new ReturnsaleModel();
        $this->vatregisterModel = new VatregisterModel();
        $this->commissionModel = new CommissionModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){

        //shops cash search(start)
        $cash = Auth()->cash;
        //shops cash search(start)

        //total bank amount calculate(start)
        $bankCash = $this->bankModel->selectSum('balance')->where('sch_id',Auth()->sch_id)->first()->balance;
        //total bank amount calculate(end)

        //total Lone provider balance calculate(start)
        $loanPro = $this->loanproviderModel->where('sch_id',Auth()->sch_id)->findAll();

        $loanProGetCash = 0;
        foreach ($loanPro as $result) {
            if ($result->balance > 0) {
                $loanProGetCash += $result->balance;
            }
        }
        //total Lone provider balance calculate(end)


        //total invoice profit calculate (start)
        $invoiceCash = $this->packageModel->selectSum('profit')->where('sch_id',Auth()->sch_id)->first()->profit;

        $product = $this->productsModel->where('sch_id',Auth()->sch_id)->where('purchase_type','0')->findAll();
        $totalProdPrice = 0;
        foreach ($product as $row) {
            $totalProdPrice += $row->quantity * $row->purchase_price;
        }
        //total invoice profit calculate (end)


        //total other sale amount calculate (start)
        $otherSaleCash = $this->ledgerothersalesModel->selectSum('amount')->where('sch_id',Auth()->sch_id)->first()->amount;
        //total other sale amount calculate (end)


        //Total balance calculate (start)
        $totalGetCash = $loanProGetCash;
        $totalCash = $cash + $bankCash + $totalGetCash + $totalProdPrice;
        //Total balance calculate (end)


        //total employee Balance (start)
        $employeeBalan = $this->employeeModel->selectSum('balance')->where('sch_id',Auth()->sch_id)->first()->balance;
        //total employee Balance (end)


        //total supplier due balance calculate (start)
        $suppl = $this->suppliersModel->where('sch_id',Auth()->sch_id)->findAll();
        $suppCash = 0;
        foreach ($suppl as $result) {
            if ($result->balance < 0) {
                $suppCash -= $result->balance;
            }
        }
        //total supplier due balance calculate (end)


        //total Lone due Cash calculate (start)
        $loanProCash = 0;
        foreach ($loanPro as $result) {
            if ($result->balance < 0) {
                $loanProCash -= $result->balance;
            }
        }
        $totalDueCash = $suppCash + $loanProCash;
        //total Lone due Cash calculate (end)


        //total expense amount calculate (start)
        $expenseCash = $this->ledgerexpenseModel->selectSum('amount')->where('sch_id',Auth()->sch_id)->first()->amount;
        //total expense amount calculate (end)


        // supper admin commission pay(start)
        $supperCommPay = $this->suppecommisionModel->where('sch_id',Auth()->sch_id)->first()->pay_commision;
        // supper admin commission pay(end)


        //total return sale return profit(start)
        $returnProfit = $this->returnsaleModel->selectSum('rtn_profit')->where('sch_id',Auth()->sch_id)->first()->rtn_profit;
        //total return sale return profit(start)

        // Total vat earning (start)
        $vatEarning = $this->vatregisterModel->where('sch_id',Auth()->sch_id)->first()->balance;
        // Total vat earning (end)


        $deliCharEarn = $this->packageModel->selectSum('delivery_charge')->where('sch_id',Auth()->sch_id)->where('status', '1')->first()->delivery_charge;


        $deliPay = $this->commissionModel->selectSum('commission.commission')->join('delivery', 'delivery.delivery_boy_id = commission.delivery_boy_id')->where('delivery.package_id = commission.package_id')->where('delivery.sch_id = ' , Auth()->sch_id)->where('delivery.status =', '1')->where('commission.com_status', '1')->first();

        $deliCharpay = (empty($deliPay)) ? 0 : $deliPay->commission;


        //Total due balance calculate (start)
        $totalDue = $totalDueCash;
        $totalBalance = $totalCash - $totalDue;
        //Total due balance calculate (end)


        $selCom = $this->commissionModel->selectSum('commission.commission')->join('package', 'package.package_id = commission.package_id')->where('sch_id = ' , Auth()->sch_id)->where('commission.com_status', '1')->where('commission.seller_id !=', NULL)->where('commission.delivery_boy_id IS NULL')->groupBy('commission.seller_id')->first();

        $sellercommision = (empty($selCom)) ? 0 : $selCom->commission;

        $delCom = $this->commissionModel->selectSum('commission.commission')->where('com_status', '1')->join('package', 'package.package_id = commission.package_id')->where('sch_id = ' . Auth()->sch_id)->where('commission.com_status', '1')->where('commission.delivery_boy_id !=', NULL)->where('commission.seller_id IS NULL')->groupBy('commission.delivery_boy_id')->first();

        $deliveryboycommision = (empty($delCom)) ? 0 : $delCom->commission;


        $expense = $expenseCash + $supperCommPay;

        $data = [
            'cash' => $cash,
            'bankCash' => $bankCash,
            'totalCash' => $totalCash,
            'invoiceCash' => $invoiceCash,
            'otherSaleCash' => $otherSaleCash,
            'totalProdPrice' => $totalProdPrice,
            'totalDue' => $totalDue,
            'totalGetCash' => $totalGetCash,
            'expenseCash' => $expense,
            'totalDueCash' => $totalDueCash,
            'loanProGetCash' => $loanProGetCash,
            'totalBalance' => $totalBalance,
            'employeeBalan' => $employeeBalan,
            'returnProfit' => $returnProfit,
            'vatEarning' => $vatEarning,
            'commisionseller' => $sellercommision,
            'commisiondeliveryboy' => $deliveryboycommision,
            'deliCharge' => $deliCharEarn,
            'deliChargePay' => $deliCharpay,
        ];




        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Balance_report/index',$data);
        }else{
            echo view('Shop_admin/no_permission');
        }
    }








}