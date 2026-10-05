<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Suppliers</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Suppliers Products</li>
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
                <h3 class="card-title">Supplier Products List</h3>
                <a href="<?= base_url('shop_admin/suppliers'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">
                <table id="example1" class="table table-striped projects">
                    <thead>
                    <tr>
                        <th> # </th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Purchase Price</th>
                        <th>Total Price </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($purchase as $val){ foreach (get_all_result_by_id('purchase_item','purchase_id',$val->purchase_id) as $row){ ?>
                        <tr>
                            <td width="20"> <?= $i++;?> </td>
                            <td><?= get_data_by_id('name','products','prod_id',$row->prod_id); ?></td>
                            <td><?= $row->quantity ?></td>
                            <td><?= showWithCurrencySymbol($row->purchase_price) ?></td>
                            <td><?= showWithCurrencySymbol($row->total_price) ?></td>
                        </tr>
                    <?php } } ?>
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
