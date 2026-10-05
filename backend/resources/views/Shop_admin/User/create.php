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
                        <li class="breadcrumb-item active">User Create</li>
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
                <h3 class="card-title">User Create</h3>
                <a href="<?= base_url('shop_admin/user'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">

                <div class="row">
                    <div class="col-md-12">
                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                    </div>
                    <div class="col-md-6">
                        <form action="<?= base_url('shop_admin/user_create_action'); ?>" method="post">
                            <div class="form-group">
                                <label for="varchar">Name </label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Name" required />
                            </div>
                            <div class="form-group">
                                <label for="varchar">Email </label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="Email" required />
                            </div>
                            <div class="form-group">
                                <label for="varchar">Password </label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required />
                            </div>
                            <div class="form-group">
                                <label for="longtext">Confirm Password </label>
                                <input type="password" class="form-control" name="con_password" id="con_password" placeholder="Confirm Password" required />
                            </div>

                            <div class="form-group">
                                <label for="enum">Role</label>
                                <select class="form-control" name="role_id" id="role_id" required >
                                    <option value="">Please Select</option>
                                    <?php echo getRoleIdListInOption('','role_id','role','roles'); ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="enum">Status </label>
                                <select class="form-control" name="status" id="status" required >
                                    <?php print globalStatus('0'); ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>

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
