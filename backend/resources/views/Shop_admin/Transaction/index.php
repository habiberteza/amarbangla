<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Transaction</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Transaction</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Transaction List</h3>
                <a href="<?= base_url('shop_admin/transaction_create'); ?>" class="btn btn-xs btn-primary w-25 float-right">Create</a>
            </div>
            <div class="card-body p-3">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'supplier'))?'active':'';?> <?= (empty($_GET))?'active':'';?>" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Supplier</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'account'))?'active':'';?> " id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Account Holder</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'fundTransfer'))?'active':'';?>" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Fund Transfer</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'expense'))?'active':'';?>" id="custom-tabs-four-vat-tab" data-toggle="pill" href="#custom-tabs-four-vat" role="tab" aria-controls="custom-tabs-four-vat" aria-selected="false">Expense</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'other'))?'active':'';?>" id="custom-tabs-four-address-tab" data-toggle="pill" href="#custom-tabs-four-address" role="tab" aria-controls="custom-tabs-four-address" aria-selected="false">Other Sales</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'employee'))?'active':'';?>" id="custom-tabs-four-customer-tab" data-toggle="pill" href="#custom-tabs-four-customer" role="tab" aria-controls="custom-tabs-four-customer" aria-selected="false">Employee Salary</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'customer'))?'active':'';?>" id="custom-tabs-four-vat-pay-tab" data-toggle="pill" href="#custom-tabs-four-vat-pay" role="tab" aria-controls="custom-tabs-four-customer" aria-selected="false">Vat Pay</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'customer'))?'active':'';?>" id="custom-tabs-four-sale-commission-pay-tab" data-toggle="pill" href="#custom-tabs-four-sale-commission-pay" role="tab" aria-controls="custom-tabs-four-customer" aria-selected="false">Sale commission pay</a>
                        </li>



                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'supplier'))?'active show':'';?> <?= (empty($_GET))?'active show':'';?> " id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-header">
                                        <h3 class="box-title">Supplier Transaction</h3>
                                    </div>
                                    <table class="table table-bordered table-striped supplier" id="example2">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Supplier</th>
                                            <th>Transaction Type</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=0; foreach ($transaction_data as $row) {
                                            if ($row->supplier_id != NULL) { ?>
                                                <tr>
                                                    <td><?= ++$i;?></td>
                                                    <td><?= get_data_by_id('name','suppliers','supplier_id',$row->supplier_id);?></td>
                                                    <td><?= $row->trangaction_type;?></td>
                                                    <td><?= showWithCurrencySymbol($row->amount);?></td>
                                                </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'account'))?'active show':'';?>" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-header">
                                        <h3 class="box-title">Account Holder</h3>
                                    </div>
                                    <table class="table table-bordered table-striped " id="account">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Account Holder</th>
                                                <th>Transaction Type</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=0; foreach ($transaction_data as $row) {
                                            if ($row->loan_pro_id != NULL) { ?>
                                                <tr>
                                                    <td><?= ++$i;?></td>
                                                    <td><?= get_data_by_id('name','loan_provider','loan_pro_id',$row->loan_pro_id);?></td>
                                                    <td><?= $row->trangaction_type;?></td>
                                                    <td><?= showWithCurrencySymbol($row->amount);?></td>

                                                </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'fundTransfer'))?'active show':'';?>" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-header">
                                        <h3 class="box-title">Fund Transfer</h3>
                                    </div>
                                    <table class="table table-bordered table-striped " id="fundTransfer">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Bank</th>
                                            <th>Transaction Type</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=0; foreach ($transaction_data as $row) {
                                            if ($row->bank_id != NULL) { ?>
                                                <tr>
                                                    <td><?= ++$i;?></td>
                                                    <td><?= get_data_by_id('name','bank','bank_id',$row->bank_id);?></td>
                                                    <td><?= $row->trangaction_type;?></td>
                                                    <td><?= showWithCurrencySymbol($row->amount);?></td>
                                                </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'expense'))?'active show':'';?>" id="custom-tabs-four-vat" role="tabpanel" aria-labelledby="custom-tabs-four-vat-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-header">
                                        <h3 class="box-title">Expense Transaction</h3>
                                    </div>
                                    <table class="table table-bordered table-striped " id="expense">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Expense</th>
                                            <th>Transaction Type</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=0; foreach ($transaction_data as $row) {
                                            if ($row->loan_pro_id == NULL && $row->customer_id == NULL && $row->supplier_id == NULL && $row->bank_id == NULL && $row->lc_id == NULL && $row->employee_id == NULL && $row->trangaction_type == 'Cr.') { ?>
                                                <tr>
                                                    <td><?= ++$i;?></td>
                                                    <td>Expense</td>
                                                    <td><?= $row->trangaction_type;?></td>
                                                    <td><?= showWithCurrencySymbol($row->amount);?></td>
                                                </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'other'))?'active show':'';?>" id="custom-tabs-four-address" role="tabpanel" aria-labelledby="custom-tabs-four-address-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-header">
                                        <h3 class="box-title">Other Sales Transaction</h3>
                                    </div>
                                    <table class="table table-bordered table-striped " id="otherSales">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Other Sales</th>
                                            <th>Transaction Type</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=0; foreach ($transaction_data as $row) {
                                            if ($row->loan_pro_id == NULL && $row->customer_id == NULL && $row->supplier_id == NULL && $row->bank_id == NULL && $row->lc_id == NULL && $row->trangaction_type == 'Dr.') { ?>
                                                <tr>
                                                    <td><?= ++$i;?></td>
                                                    <td>Other Sales</td>
                                                    <td><?= $row->trangaction_type;?></td>
                                                    <td><?= showWithCurrencySymbol($row->amount);?></td>
                                                </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'employee'))?'active show':'';?>" id="custom-tabs-four-customer" role="tabpanel" aria-labelledby="custom-tabs-four-customer-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-header">
                                        <h3 class="box-title">Employee Salary </h3>
                                    </div>
                                    <table class="table table-bordered table-striped " id="employeeSalary">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Transaction Type</th>
                                            <th>Salary</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=0; foreach ($transaction_data as $row) {
                                            if ($row->employee_id != NULL ) { ?>
                                                <tr>
                                                    <td><?= ++$i;?></td>
                                                    <td><?= get_data_by_id('name','employee','employee_id',$row->employee_id);?></td>
                                                    <td><?= $row->trangaction_type;?></td>
                                                    <td><?= showWithCurrencySymbol($row->amount);?></td>
                                                </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'customer'))?'active show':'';?>" id="custom-tabs-four-vat-pay" role="tabpanel" aria-labelledby="custom-tabs-four-customer-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-header">
                                        <h3 class="box-title">Vat Pay</h3>
                                    </div>
                                    <table class="table table-bordered table-striped " id="vatPay">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Vat Register Name</th>
                                            <th>Transaction Type</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=0; foreach ($transaction_data as $row) {
                                            if ($row->vat_id != NULL) { ?>
                                                <tr>
                                                    <td><?= ++$i;?></td>
                                                    <td><?= get_data_by_id('name','vat_register','vat_id',$row->vat_id);?></td>
                                                    <td><?= $row->trangaction_type;?></td>
                                                    <td><?= showWithCurrencySymbol($row->amount);?></td>

                                                </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'customer'))?'active show':'';?>" id="custom-tabs-four-sale-commission-pay" role="tabpanel" aria-labelledby="custom-tabs-four-customer-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-header">
                                        <h3 class="box-title">Sale commission pay</h3>
                                    </div>
                                    <table class="table table-bordered table-striped " id="saleCommission">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Transaction Type</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $t = 1; foreach ($supComiTrns as $commi) { ?>
                                            <tr>
                                                <td><?= $t++?></td>
                                                <td><?= $commi->trangaction_type;?></td>
                                                <td><?= showWithCurrencySymbol($commi->amount);?></td>
                                                <td><?php
                                                    if ($commi->status == 0) {
                                                        echo '<span class="label label-warning">pending</span>';
                                                    }else{
                                                        echo '<span class="label label-success">complete</span>';
                                                    }
                                                    ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
            <!-- /.card-body -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= $this->endSection() ?>


<?= $this->section('java_script') ?>
<script>
    $(function () {
        $("#account").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false, "targets": 'no-sort', "bSort": false,
        });
        $("#fundTransfer").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false, "targets": 'no-sort', "bSort": false,
        });
        $("#expense").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false, "targets": 'no-sort', "bSort": false,
        });
        $("#otherSales").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false, "targets": 'no-sort', "bSort": false,
        });
        $("#employeeSalary").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false, "targets": 'no-sort', "bSort": false,
        });
        $("#vatPay").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false, "targets": 'no-sort', "bSort": false,
        });
        $("#saleCommission").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false, "targets": 'no-sort', "bSort": false,
        });
    });
</script>
<?= $this->endSection() ?>
