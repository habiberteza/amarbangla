<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Return Purchase</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Return Purchase</li>
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
                <h3 class="card-title">Return Purchase List</h3>
                <a href="<?= base_url('shop_admin/return_purchase_create'); ?>" class="btn btn-xs btn-primary w-25 float-right">Return</a>
            </div>
            <div class="card-body p-3">
                <div class="col-md-12">
                    <?= isset(newSession()->message) ? newSession()->message :''; ?>
                </div>
                <table id="example1" class="table table-striped projects">
                    <thead>
                    <tr>
                        <th> # </th>
                        <th>Date</th>
                        <th>Supplier</th>
                        <th>Total Amount</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($returnPurchase as $val){ ?>
                        <tr>
                            <td width="20"> <?= $i++;?> </td>
                            <td><?= invoiceDateFormat($val->createdDtm) ?></td>
                            <td><?= get_data_by_id('name', 'suppliers', 'supplier_id', $val->supplier_id) ?></td>
                            <td><?= showWithCurrencySymbol($val->amount) ?></td>
                            <td width="120">
                                <a href="<?= base_url('shop_admin/return_purchase_view/' . $val->rtn_purchase_id); ?>" class="btn btn-xs btn-info ">View</a>
                            </td>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
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
