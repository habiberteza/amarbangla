<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Logo</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Logo</li>
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
                            <h3 class="card-title">Logo</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                                </div>

                                <div class="col-md-6">
                                    <center><h4>Left Banner update</h4></center>
                                    <hr>
                                    <form action="<?php echo site_url('super_admin/website_settings/banner_action_left') ?>" method="POST"  enctype="multipart/form-data">
                                        <div class="form-group">
                                            <?php  echo image_view('uploads/website_image','',$banner_left->value,'no_image.jpg','w-50',''); ?>

                                        </div>

                                        <div class="form-group">
                                            <label class="control-label ">Upload Image</label>
                                            <div class="row" id="coba2"></div>

                                        </div>
                                        <small><b>Size: 270x355 </b></small><br>
                                        <button type="submit" class="btn btn-primary">Update</button>



                                    </form>

                                </div>
                                <div class="col-md-6">

                                    <center><h4>Right Banner update</h4></center>
                                    <hr>
                                    <form action="<?php echo site_url('super_admin/website_settings/banner_action_right') ?>" method="POST"  enctype="multipart/form-data">

                                        <div class="form-group">

                                            <?php  echo image_view('uploads/website_image','',$banner_right->value,'no_image.jpg','w-50',''); ?>

                                        </div>

                                        <div class="form-group">
                                            <label class="control-label ">Upload Image</label>
                                            <div class="row" id="coba3"></div>

                                        </div>
                                        <small><b>Size: 270x355 </b></small><br>
                                        <button type="submit" class="btn btn-primary">Update</button>

                                    </form>
                                </div>
                                <div class="col-md-6">

                                    <center><h4>Top Banner update</h4></center>
                                    <hr>
                                    <form action="<?php echo site_url('super_admin/website_settings/banner_action_top') ?>" method="POST"  enctype="multipart/form-data">

                                        <div class="form-group">
                                            <?php  echo image_view('uploads/website_image','',$banner_top->value,'no_image.jpg','w-100',''); ?>

                                        </div>


                                        <div class="form-group">
                                            <label class="control-label ">Upload Image</label>
                                            <div class="row" id="coba4"></div>
                                        </div>
                                        <small><b>Size: 495x171 </b></small><br>
                                        <button type="submit" class="btn btn-primary">Update</button>


                                    </form>
                                </div>
                                <div class="col-md-6">

                                    <center><h4>Top Bottom Banner update</h4></center>
                                    <hr>
                                    <form action="<?php echo site_url('super_admin/website_settings/banner_action_bottom') ?>" method="POST"  enctype="multipart/form-data">
                                        <div class="form-group">

                                            <?php  echo image_view('uploads/website_image','',$banner_top2->value,'no_image.jpg','w-100',''); ?>

                                        </div>
                                        <div class="form-group">
                                            <label class="control-label ">Upload Image</label>
                                            <div class="row" id="coba5"></div>
                                        </div>
                                        <small><b>Size: 495x171 </b></small><br>
                                        <button type="submit" class="btn btn-primary">Update</button>


                                    </form>
                                </div>
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