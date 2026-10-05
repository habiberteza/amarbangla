<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Settings update</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Settings update</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Settings update</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-12">
                                <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                            </div>
                            <div class="card card-primary card-outline card-outline-tabs">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link <?php echo (!empty($_GET) && ($_GET['active'] == 'general'))?'active':'';?> <?php echo(empty($_GET))?'active':'';?>" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Registraion Info</a>
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
                                                    <form action="<?php echo base_url('super_admin/settings_update_action'); ?>" method="post">
                                                        <div class="form-group">
                                                            <label for="varchar">Name</label>
                                                            <input type="text" class="form-control" name="name" id="name" placeholder="name" value="<?php echo $admin->name; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="varchar">Email </label>
                                                            <input type="email" class="form-control" name="email" id="email" placeholder="email" value="<?php echo $admin->email; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="longtext">Password </label>
                                                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="longtext">Confirm Password</label>
                                                            <input type="password" class="form-control" name="con_password" id="password" placeholder="Confirm Password" value="" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="enum">Status</label>
                                                            <select class="form-control" name="status" id="status">
                                                                <?php print globalStatus($admin->status); ?>
                                                            </select>
                                                        </div>

                                                        <input type="hidden" name="user_id" value="<?php echo $admin->user_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?php echo site_url('super_admin/settings') ?>" class="btn btn-danger">Cancel</a>

                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'personal'))?'active show':'';?>" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form action="<?php echo base_url('super_admin/settings_personal_update'); ?>" method="post">
                                                        <div class="form-group">
                                                            <label for="varchar">Companey Name </label>
                                                            <input type="text" class="form-control" name="ComName" id="ComName" placeholder="Companey Name" value="<?php echo $admin->ComName; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="varchar">Country </label>
                                                            <input type="text" class="form-control" name="country" id="country" placeholder="Country" value="<?php echo $admin->country; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="varchar">Mobile </label>
                                                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" value="<?php echo $admin->mobile; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="varchar">Address </label>
                                                            <input type="text" class="form-control" name="address" id="address" placeholder="Address" value="<?php echo $admin->address; ?>" />
                                                        </div>

                                                        <input type="hidden" name="user_id" value="<?php echo $admin->user_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?php echo site_url('super_admin/settings') ?>" class="btn btn-danger">Cancel</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade <?php echo(!empty($_GET) && ($_GET['active'] == 'photo'))?'active show':'';?>" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form action="<?php echo base_url('super_admin/settings_photo_update'); ?>" method="post" enctype="multipart/form-data">
                                                        <div class="form-group">
                                                            <?php echo image_view('uploads/admin_image','',$admin->pic,'no_image.jpg','w-25','') ?><br>
                                                            <label for="longtext">Profile Image</label>
                                                            <input type="file" class="form-control" name="pic" id="pic" />
                                                            <span class="help-block"><b>Max. file size 1024KB and (width=160px) x (height=160px)</b></span>
                                                        </div>

                                                        <input type="hidden" name="user_id" value="<?php echo $admin->user_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?php echo site_url('super_admin/settings') ?>" class="btn btn-danger">Cancel</a>
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
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->