<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Mobile Slider</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Mobile Slider</li>
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
                            <h3 class="card-title">Mobile Slider</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <center><h4>Slider 1 update</h4></center>
                                    <hr>
                                    <form action="<?php echo site_url('super_admin/website_settings/mobile_slider_action') ?>" method="POST"  enctype="multipart/form-data">
                                        <div class="form-group">
                                            <?php echo  image_view('uploads/website_image','',$slider_1_mob,'no_imagebn.jpg','w-100','') ?>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label ">Upload Image</label>
                                            <div class="row" id="coba2"></div>
                                        </div>
                                        <input type="hidden" name="label" value="slider_1_mob">
                                        <small><b>Size: 390x100 </b></small><br>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </form>

                                </div>
                                <div class="col-md-6">

                                    <center><h4>Slider 2 update</h4></center>
                                    <hr>
                                    <form action="<?php echo site_url('super_admin/website_settings/mobile_slider_action') ?>" method="POST"  enctype="multipart/form-data">

                                        <div class="form-group">
                                            <?php echo  image_view('uploads/website_image','',$slider_2_mob,'no_imagebn.jpg','w-100','') ?>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label ">Upload Image</label>
                                            <div class="row" id="coba3"></div>
                                        </div>
                                        <input type="hidden" name="label" value="slider_2_mob">
                                        <small><b>Size: 390x100 </b></small><br>
                                        <button type="submit" class="btn btn-primary">Update</button>

                                    </form>
                                </div>

                                <div class="col-md-6">

                                    <center><h4>Slider 3 update</h4></center>
                                    <hr>
                                    <form action="<?php echo site_url('super_admin/website_settings/mobile_slider_action') ?>" method="POST"  enctype="multipart/form-data">

                                        <div class="form-group">
                                            <?php echo  image_view('uploads/website_image','',$slider_3_mob,'no_imagebn.jpg','w-100','') ?>
                                        </div>


                                        <div class="form-group">
                                            <label class="control-label ">Upload Image</label>
                                            <div class="row"  id="coba4"></div>
                                        </div>
                                        <input type="hidden" name="label" value="slider_3_mob">
                                        <small><b>Size: 390x100 </b></small><br>
                                        <button type="submit" class="btn btn-primary">Update</button>


                                    </form>
                                </div>

                                <div class="col-md-6"></div>

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