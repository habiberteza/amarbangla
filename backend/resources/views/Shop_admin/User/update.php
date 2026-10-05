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
                        <li class="breadcrumb-item active">User Update</li>
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
                <h3 class="card-title">User Update</h3>
                <a href="<?= base_url('shop_admin/user'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">

                <div class="row">
                    <div class="col-md-12">
                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                    </div>

                </div>

                <div class="card card-primary card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link <?php echo (!empty($_GET) && ($_GET['active'] == 'general'))?'active':'';?> <?php echo(empty($_GET))?'active':'';?>" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Registration Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo(!empty($_GET) && ($_GET['active'] == 'personal'))?'active':'';?>" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Personal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo(!empty($_GET) && ($_GET['active'] == 'photo'))?'active':'';?> " id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Photo</a>
                            </li>


                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'general'))?'active show':'';?> <?php echo(empty($_GET))?'active show':'';?> " id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="<?php echo base_url('shop_admin/user_update_action'); ?>" method="post">

                                            <div class="form-group">
                                                <label for="varchar">Name </label>
                                                <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?= $user->name; ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="varchar">Email </label>
                                                <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?= $user->email; ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="varchar">Password </label>
                                                <input type="password" class="form-control" name="password" id="password" placeholder="Password"  required />
                                            </div>
                                            <div class="form-group">
                                                <label for="longtext">Confirm Password </label>
                                                <input type="password" class="form-control" name="con_password" id="con_password" placeholder="Confirm Password" required />
                                            </div>
                                            <div class="form-group">
                                                <label for="enum">Status </label>
                                                <select class="form-control" name="status" id="status">
                                                    <?= globalStatus($user->status); ?>
                                                </select>
                                            </div>

                                            <input type="hidden" name="user_id" value="<?= $user->user_id; ?>" >
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'personal'))?'active show':'';?>" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="<?php echo base_url('shop_admin/user_personal_action'); ?>" method="post">

                                            <div class="form-group">
                                                <label for="varchar">Phone </label>
                                                <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Phone" value="<?= $user->mobile; ?>" />
                                            </div>

                                            <div class="form-group">
                                                <label for="varchar">Address </label>
                                                <input type="text" class="form-control" name="address" id="address" placeholder="Address" value="<?= $user->address; ?>" />
                                            </div>

                                            <div class="form-group">
                                                <label for="enum">Role </label>
                                                <select class="form-control" name="role_id" id="role_id">
                                                    <?php echo getRoleIdListInOption($user->role_id,'role_id','role','roles'); ?>
                                                </select>
                                            </div>


                                            <input type="hidden" name="user_id" value="<?= $user->user_id; ?>" >
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade <?php echo(!empty($_GET) && ($_GET['active'] == 'photo'))?'active show':'';?>" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="<?php echo base_url('shop_admin/user_photo_action'); ?>" method="post" enctype="multipart/form-data">

                                            <div class="form-group">
                                                <?php echo image_view('uploads/users_image', '', $user->pic, 'no_image.jpg', 'w-25', '');?><br>
                                                <label for="varchar">Photo </label>
                                                <input type="file" class="form-control" name="pic" id="pic" />
                                                <span class="help-block"><b>Max. file size 1024KB and (width=300px) x (height=300px)</b></span>
                                            </div>

                                            <input type="hidden" name="user_id" value="<?= $user->user_id; ?>" >
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /.card -->
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
