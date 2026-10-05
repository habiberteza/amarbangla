<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">User</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">User</li>
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
                <h3 class="card-title">User List</h3>
                <a href="<?= base_url('shop_admin/user_create'); ?>" class="btn btn-xs btn-primary w-25 float-right">Create</a>
            </div>
            <div class="card-body p-3">
                <table id="example1" class="table table-striped projects">
                    <thead>
                    <tr>
                        <th> # </th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($user as $val){ ?>
                        <tr>
                            <td width="20"> <?= $i++;?> </td>
                            <td><?php echo $val->name ?></td>
                            <td><?php echo showWithPhoneNummberCountryCode($val->mobile) ?></td>
                            <td><?php echo get_data_by_id('role','roles','role_id',$val->role_id) ?></td>
                            <td><?php echo ($val->status == 1)?'<button class="btn btn-xs btn-info">Active</button>':'<button class="btn btn-xs btn-danger">Inactive</button>'; ?></td>
                            <td width="120">
                                <?php if (is_default($val->user_id,'user_id', 'users') != 1) { ?>
                                <a href="<?= base_url('shop_admin/user_update/' . $val->user_id); ?>" class="btn btn-xs btn-warning ">Update</a>
                                <a href="<?= base_url('shop_admin/user_delete/' . $val->user_id); ?>" onclick="javasciprt: return confirm('Are You Sure ?')" class="btn btn-xs btn-danger">Delete</a>
                                <?php } ?>
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
