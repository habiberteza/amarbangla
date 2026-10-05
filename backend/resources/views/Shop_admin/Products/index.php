<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Products</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Products</li>
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
                <h3 class="card-title">Products List</h3>
            </div>
            <div class="card-body p-3">
                <form action="<?= base_url('shop_admin/products_barcode') ?>" method="post">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control input-sm" name="keyword" id="keyword"
                                onchange="product_show(this.value)">
                            <option value="0">Please Select Category</option>
                            <?php foreach ($productcategory as $row) { ?>
                                <optgroup label="---------------------------------">
                                    <option value="<?php echo $row->prod_cat_id; ?>"
                                            id="head"><?php echo $row->product_category; ?></option>
                                    <?php echo subCatSaleInOption($row->prod_cat_id); ?>
                                </optgroup>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-8 " style="text-align: right;">
                        <a class="btn btn-xs btn-danger pull-right"  href="<?= base_url('shop_admin/products_short_list'); ?>"><i class="fa fa-fw fa-tasks"></i> Short List</a>
                        <button type="submit" class="btn btn-xs btn-primary pull-right"><i class="fa fa-barcode"></i> Barcode Generate </button>

                        <a class="btn btn-xs btn-info pull-right" href="<?= base_url('shop_admin/products_print_list'); ?>"><i class="fa fa-fw fa-tasks"></i> Products Print List</a>
                        <a class="btn btn-xs btn-success pull-right"  href="<?= base_url('shop_admin/products_price_update'); ?>"><i class="fa fa-fw fa-tasks"></i> Price update</a>
                    </div>
                </div>
                <table id="example1" class="table table-striped projects ">
                    <thead>
                    <tr>
                        <th> # </th>
                        <th>Barcode Qty </th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Purchase Price</th>
                        <th>Supplier</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($product as $val){ ?>
                        <tr>
                            <td width="20"> <?= $i++;?> </td>
                            <td><input type="number" name="barcodeqty[<?= $val->prod_id; ?>]" style="width: 40px;" value="0"></td>
                            <td><?= $val->name; ?></td>
                            <td><?= get_data_by_id('product_category', 'product_category', 'prod_cat_id', $val->prod_cat_id); ?></td>
                            <td><?= $val->quantity ?></td>
                            <td><?= showWithCurrencySymbol($val->purchase_price) ?></td>
                            <td><?= get_data_by_id('name', 'suppliers', 'supplier_id', $val->supplier_id) ?></td>
                            <td><?= singleImage_by_productId($val->prod_id,'90');?></td>
                            <?php
                                if ($val->status == 1) {
                                    $status_icon = '<i class="fa fa-toggle-on aria-hidden="true" style="font-size: 29px;color: green;"></i>';
                                    $updatestatus = 0;
                                } else {
                                    $status_icon = '<i class="fa fa-toggle-off aria-hidden="true" style="font-size: 29px;color: gray;"></i>';
                                    $updatestatus = 1;
                                }
                            ?>
                            <td width="140">
                                <a href="<?= base_url('shop_admin/products_status_update/' . $val->prod_id.'/'.$updatestatus); ?>" class="btn btn-xs  "><?= $status_icon;?></a>
                                <a href="<?= base_url('shop_admin/products_update/' . $val->prod_id); ?>" class="btn btn-xs btn-warning ">Update</a>
                                <a href="<?= base_url('shop_admin/products_delete/' . $val->prod_id); ?>" onclick="javasciprt: return confirm('Are You Sure ?')" class="btn btn-xs btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
                </form>
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
