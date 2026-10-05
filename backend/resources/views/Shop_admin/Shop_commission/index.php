<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Shop Commission</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Shop Commission</li>
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
                <h3 class="card-title">Shop Commission List</h3>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-12 text-center border-bottom pb-3">

                        <a href="<?php echo site_url('shop_admin/shop_commission_paid') ?>" class="btn btn-success" style="margin-right: 20px;" >Paid</a>

                        <a href="<?php echo site_url('shop_admin/shop_commission_unpaid') ?>" class="btn btn-warning " style="margin-right: 20px;" >Unpaid</a>

                        <a href="<?php echo site_url('shop_admin/shop_commission_multipay') ?>" class="btn btn-primary " >Multipay</a>
                    </div>

                    <div class="col-md-12">
                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                    </div>

                    <div class="col-md-8 mt-4">
                        <table class="table table-bordered table-striped" id="example2">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>InvoiceId</th>
                            <th>Percent</th>
                            <th>Commission</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; foreach ($supCommision as $val) { ?>
                            <tr>
                                <td><?= $i++?></td>
                                <td><?= $val->invoice_id;?></td>
                                <td><?= $val->percent;?> %</td>
                                <td><?= $val->commission;?></td>
                                <td>
                                    <?php
                                        if ($val->status == 0) {echo '<span class="bg-warning p-1">Unpaid</span>';}
                                        if ($val->status == 1) {echo '<span class="bg-success p-1">Paid</span>';}
                                        if ($val->status == 2) {echo '<span class="bg-info p-1">Pending</span>';}
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        if ($val->status == 0) {echo '<a href="'.site_url('shop_admin/shop_commission_pay_action/'.$val->invoice_id).'" class="btn btn-info btn-xs">Paid</a>';}
                                        if ($val->status == 1) {echo '<span class="bg-success p-1">Paid</span>';}
                                        if ($val->status == 2) {echo '<span class="bg-info p-1">Pending</span>';}
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th>No</th>
                            <th>InvoiceId</th>
                            <th>Percent</th>
                            <th>Commission</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>

                    </table>
                    </div>

                    <div class="col-md-4">
                        <div class="shadow p-3 mb-5 bg-body rounded">
                            <center><h3 class="box-title"><i class="fa fa-fw fa-line-chart"></i> Total sale commission </h3></center>
                            <table class="table table-bordered table-striped" >
                                <thead>
                                <tr>
                                    <th>Total Sale</th>
                                    <th>Total Sale commission </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?php echo showWithCurrencySymbol($totalsale);?></td>
                                    <td><?php echo showWithCurrencySymbol($totalcomm);?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="shadow p-3 mb-5 bg-body rounded">
                            <center><h3 class="box-title"><i class="fa fa-fw fa-line-chart"></i> Total Due/Pay</h3></center>
                            <table class="table table-bordered table-striped" >
                                <thead>
                                <tr>
                                    <th>Total Due</th>
                                    <th>Total Pay </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?php echo showWithCurrencySymbol($totalDue);?></td>
                                    <td><?php echo showWithCurrencySymbol($totalPay);?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="shadow p-3 mb-5 bg-body rounded">
                            <center><h3 class="box-title"><i class="fa fa-fw fa-line-chart"></i> Total sale commission monthly </h3></center>
                            <table class="table table-bordered table-striped" >
                                <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Sale</th>
                                    <th>Commission </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($montsale as $row) { ?>
                                    <tr>
                                        <td><?php echo monthDateFormat($row->createdDtm);?></td>
                                        <td><?php echo showWithCurrencySymbol($row->amount);?></td>
                                        <td><?php echo showWithCurrencySymbol($row->commission);?></td>
                                    </tr>
                                <?php } ?>

                                </tbody>
                            </table>
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
</script>
<?= $this->endSection() ?>
