<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Home Banner</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Home Banner</li>
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
                            <h3 class="card-title">Home Banner</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <center><h4>Main Banner update</h4></center>
                                    <hr>
                                    <form action="<?php echo site_url('super_admin/website_settings/home_banner_action') ?>" method="POST"  enctype="multipart/form-data">
                                        <div class="form-group">
                                            <?php echo image_view('uploads/website_image','',$home_banner,'slider_mo.jpg','w-100','') ?>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label ">Upload Image</label>
                                                <div class="row" id="coba2"></div>
                                        </div>
                                        <small><b>Size: 315x100 </b></small><br>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <center><h4>Banner one update</h4></center>
                                    <hr>
                                    <form action="<?php echo site_url('super_admin/website_settings/home_banner_small_action') ?>" method="POST"  enctype="multipart/form-data">
                                        <div class="form-group">
                                            <?php echo image_view('uploads/website_image','',$home_banner_2,'no_image.jpg','','') ?>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label ">Upload Image</label>
                                            <div class="row" id="coba3"></div>
                                        </div>
                                        <input type="hidden" name="label" value="home_banner_2">
                                        <small><b>Size: 90x90 </b></small><br>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <center><h4>Banner two update</h4></center>
                                    <hr>
                                    <form action="<?php echo site_url('super_admin/website_settings/home_banner_small_action') ?>" method="POST"  enctype="multipart/form-data">
                                        <div class="form-group">
                                            <?php echo image_view('uploads/website_image','',$home_banner_3,'no_image.jpg','','') ?>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label ">Upload Image</label>
                                            <div class="row"  id="coba4"></div>
                                        </div>
                                        <input type="hidden" name="label" value="home_banner_3">
                                        <small><b>Size: 90x90 </b></small><br>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <center><h4>Banner three update</h4></center>
                                    <hr>
                                    <form action="<?php echo site_url('super_admin/website_settings/home_banner_small_action') ?>" method="POST"  enctype="multipart/form-data">
                                        <div class="form-group">
                                            <?php echo image_view('uploads/website_image','',$home_banner_4,'no_image.jpg','','') ?>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label ">Upload Image</label>
                                            <div class="row" id="coba5"></div>
                                        </div>
                                        <input type="hidden" name="label" value="home_banner_4">
                                        <small><b>Size: 90x90 </b></small><br>
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