<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Settings</li>
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
                <h3 class="card-title">Settings </h3>
                <a href="<?= base_url('shop_admin/get_product'); ?>" class="btn btn-xs btn-primary float-right">Get Products</a>
<!--                <a href="--><?php //= base_url('shop_admin/settings_database_backup'); ?><!--" class="btn btn-xs btn-warning mr-2 float-right">Database Backup</a>-->
            </div>
            <div class="card-body p-3">
                <table  class="table table-striped projects text-capitalize">
                    <thead>
                    <tr>
                        <th> Name</th>
                        <th>Mobile</th>
                        <th>Logo</th>
                        <th>Profile Image</th>
                        <th>Banner Image</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td><?php echo $row->name ?></td>
                            <td><?php echo showWithPhoneNummberCountryCode($row->mobile) ?></td>
                            <td width="200"> <?= image_view('uploads/schools', '', $row->logo, 'no_image.jpg', 'w-100', '');?> </td>
                            <td width="120"> <?= image_view('uploads/schools', '', $row->image, 'no_image.jpg', 'w-100', '');?> </td>

                            <td width="200"><?= image_view('uploads/schools', '', $row->banner, 'no_image.jpg', 'w-100', '');?></td>
                            <td><?php echo statusView($row->status); ?></td>
                            <td width="120">
                                <a href="<?= base_url('shop_admin/settings_update/' . $row->sch_id); ?>" class="btn btn-xs btn-info ">Update</a>
                            </td>
                        </tr>


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
