<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Footer</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Footer</li>
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
                            <h3 class="card-title">Footer</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form action="<?php echo site_url('super_admin/website_settings/footer_action') ?>" method="POST"  enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                                    </div>
                                    <div class="col-md-6" style="border-right: 1px solid;">
                                        <center><h4>Address detail</h4></center>
                                        <hr>
                                        <div class="form-group">
                                            <label class="control-label ">Email</label>
                                            <input type="email" name="email" class="form-control" value="<?php echo $email?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label ">Phone</label>
                                            <input type="text" name="phone" class="form-control" value="<?php echo $phone?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label ">Address</label>
                                            <textarea name="address" class="form-control" width="100%" rows="4" cols="50"><?php echo $address?></textarea>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <center><h4>Social settings</h4></center>
                                        <hr>
                                        <div class="form-group">
                                            <label class="control-label ">Facebook</label>
                                            <input type="text" name="facebook" class="form-control" value="<?php echo $facebook?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label ">Twitter</label>
                                            <input type="text" name="twitter" class="form-control" value="<?php echo $twitter?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label ">Youtube </label>
                                            <input type="text" name="youtube" class="form-control" value="<?php echo $youtube?>">
                                        </div>
                                    </div>
                                    <div  class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </form>
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