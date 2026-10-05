<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Purchase view</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Purchase view</li>
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
                <h3 class="card-title">Purchase view</h3>
                <a href="<?= base_url('shop_admin/purchase'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-12 text-center border-bottom pb-3">
                        <h5 class="m-0">Supplier : <?php echo get_data_by_id('name','suppliers','supplier_id',$purchase->supplier_id); ?></h5>
                        <small class="pull-right">Balance : <?php echo showWithCurrencySymbol(get_data_by_id('balance','suppliers','supplier_id',$purchase->supplier_id)); ?></small>
                    </div>
                    <div class="col-md-8 mt-4">
                        <center><h5 class="box-title">Purchase Detail</h5></center>
                        <table id="example2" class="table table-striped projects ">
                            <thead>
                            <tr>
                                <th> # </th>
                                <th>Product</th>
                                <th>Quantity </th>
                                <th>Purchase Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1; foreach ($purchaseItem as $val){ ?>
                                <tr>
                                    <td width="20"> <?= $i++;?> </td>
                                    <td><?= get_data_by_id('name','products','prod_id',$val->prod_id); ?></td>
                                    <td><?= $val->quantity; ?></td>
                                    <td><?= showWithCurrencySymbol($val->purchase_price); ?></td>
                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4 mt-4">
                        <center><h5 class="box-title">Payment Detail</h5></center>
                        <div class="row" style="padding: 10px; border:1px dashed #D0D3D8;">
                            <div class="col-md-6">
                                <label>Total</label>
                            </div>
                            <div class="col-md-6" style="border-left: 1px dashed #D0D3D8;">
                                <p><?php echo showWithCurrencySymbol(get_data_by_id('amount','purchase','purchase_id', $purchaseId)); ?></p>
                            </div>
                        </div>

                        <?php
                            $cash = get_data_by_id('nagad_paid','purchase','purchase_id', $purchaseId);
                            if (!empty($cash)) {
                        ?>
                            <div class="row" style="padding: 10px; border:1px dashed #D0D3D8;">
                                <div class="col-md-6">
                                    <label>Cash Pay</label>
                                </div>
                                <div class="col-md-6" style="border-left: 1px dashed #D0D3D8;">
                                    <p><?= showWithCurrencySymbol($cash); ?></p>
                                </div>
                            </div>

                        <?php } ?>

                        <?php
                            $bankAmount = get_data_by_id('bank_paid','purchase','purchase_id', $purchaseId);
                            if (!empty($bankAmount)) {
                        ?>
                            <div class="row" style="padding: 10px; border:1px dashed #D0D3D8;">
                                <div class="col-md-6">
                                    <label>Bank Pay</label>
                                </div>
                                <div class="col-md-6" style="border-left: 1px dashed #D0D3D8;">
                                    <p><?= showWithCurrencySymbol($bankAmount); ?></p>
                                </div>
                            </div>
                        <?php } ?>

                        <?php
                            $due = get_data_by_id('due','purchase','purchase_id', $purchaseId);
                            if (!empty($due)) {
                        ?>
                            <div class="row" style="padding: 10px; border:1px dashed #D0D3D8;">
                                <div class="col-md-6">
                                    <label>Total Due</label>
                                </div>
                                <div class="col-md-6" style="border-left: 1px dashed #D0D3D8;">
                                    <p><?= showWithCurrencySymbol($due); ?></p>
                                </div>
                            </div>
                        <?php } ?>
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
