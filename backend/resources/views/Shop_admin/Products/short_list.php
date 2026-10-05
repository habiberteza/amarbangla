<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Products short list</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Products short list</li>
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
                <h3 class="card-title">Products short list</h3>
                <a href="<?= base_url('shop_admin/products'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">

                <div class="row mb-4">
                    <div class="col-md-12">
                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                    </div>

                </div>
                <table id="example1" class="table table-striped projects ">
                    <thead>
                    <tr>
                        <th> # </th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Purchase Price</th>
                        <th>Supplier</th>
                        <th>Prod Category </th>
                        <th>Purchase Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($product as $val){ ?>
                        <tr>
                            <td width="20"> <?= $i++;?> </td>
                            <td><?= $val->name; ?></td>
                            <td><?= $val->quantity; ?></td>
                            <td><?= showWithCurrencySymbol($val->purchase_price); ?></td>
                            <td><?= get_data_by_id('name', 'suppliers', 'supplier_id', $val->supplier_id); ?></td>
                            <td><?= get_data_by_id('product_category', 'product_category', 'prod_cat_id', $val->prod_cat_id); ?></td>
                            <td><?= $val->purchase_date; ?></td>
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
