<?= $this->extend('Agent/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Seller order</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('agent/dashboard')?>">Home</a></li>
                        <li class="breadcrumb-item active">Seller order</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Seller order</h3>
                            <a href="<?= base_url('agent/sellers'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Shop</th>
                                    <th>Invoice Id </th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody id="shopOrder">
                                    <?php $i = 1;foreach ($order as $view) { $schId = get_data_by_id('sch_id','package','invoice_id',$view->invoice_id) ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><?php echo get_data_by_id('name', 'shops', 'sch_id', $schId); ?></td>
                                        <td><?php echo $view->invoice_id; ?></td>
                                        <td><?php echo showWithCurrencySymbol($view->final_amount); ?></td>
                                        <td><?php
                                            if ($view->status == 0) {
                                                echo '<span class="label bg-primary">Unpaid</span>';
                                            }
                                            if ($view->status == 1) {
                                                echo '<span class="label bg-success">Paid</span>';
                                            }
                                            if ($view->status == 2) {
                                                echo '<span class="label bg-warning">Pandding</span>';
                                            }
                                            if ($view->status == 3) {
                                                echo '<span class="label bg-danger">Cancel</span>';
                                            } ?>
                                        </td>
                                        <?php }?>
                                </tbody>
                            </table>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= $this->endSection() ?>


<?= $this->section('java_script') ?>
    <script>

    </script>
<?= $this->endSection() ?>