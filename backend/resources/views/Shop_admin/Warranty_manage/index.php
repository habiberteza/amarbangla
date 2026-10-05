<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Warranty Manage</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Warranty Manage</li>
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
                <h3 class="card-title">Warranty Manage List</h3>
                <a href="<?= base_url('shop_admin/warranty_manage_create'); ?>" class="btn btn-xs btn-primary w-25 float-right">Create</a>
            </div>
            <div class="card-body p-3">
                <table id="example1" class="table table-striped projects">
                    <thead>
                    <tr>
                        <th> # </th>
                        <th>Product Name</th>
                        <th>Receive Date</th>
                        <th>Delivery Date</th>
                        <th>Customer Address</th>
                        <th>Customer Name</th>
                        <th>Mobile</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($warrantyManage as $val){ ?>
                        <tr>
                            <td width="20"> <?= $i++;?> </td>
                            <td><?= $val->product_name ?></td>
                            <td><?= $val->receive_date ?></td>
                            <td><?= $val->delivery_date ?></td>
                            <td><?= $val->customer_address ?></td>
                            <td><?= $val->customer_name ?></td>
                            <td><?= showWithPhoneNummberCountryCode($val->mobile) ?></td>
                            <td width="120">
                                <a href="<?= base_url('shop_admin/warranty_manage_update/' . $val->warranty_id); ?>" class="btn btn-xs btn-warning ">Update</a>
                                <a href="<?= base_url('shop_admin/warranty_manage_delete/' . $val->warranty_id); ?>" onclick="javasciprt: return confirm('Are You Sure ?')" class="btn btn-xs btn-danger">Delete</a>
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
