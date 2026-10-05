<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Role</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Role</li>
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
                <h3 class="card-title">Role Update</h3>
                <a href="<?= base_url('shop_admin/role'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-12">
                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                    </div>
                    <div class="col-md-6">
                        <form action="<?= base_url('shop_admin/role_update_action') ?>" method="post">
                            <div class="form-group">
                                <label for="varchar">Role Name</label>
                                <input type="text" class="form-control" name="role" id="role" placeholder="Role" value="<?= $role->role;?>" required />
                            </div>
                            <div class="form-group">
                                <label for="varchar">Permission</label>
                                <ol>
                                    <?php
                                    $myRole = json_decode($role->permission);
                                    foreach ($permission as $key => $value) { ?>
                                        <li><?php echo $key; ?>
                                            <?php foreach ($value as $k=>$v) {
                                                if(isset($myRole->$key->$k)) {
                                                    $isChecked = ($myRole->$key->$k == 1) ? 'checked="checked"' : '';
                                                }else{
                                                    $isChecked = '';
                                                }
                                            ?>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" <?= $isChecked; ?> name="permission[<?= $key; ?>][<?= $k; ?>]" value="1" >  <?= $k ?></label>
                                                </div>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                </ol>
                            </div>
                            <input type="hidden"  name="role_id"  value="<?= $role->role_id;?>" required />
                            <button type="submit" class="btn btn-primary">Update</button>
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
