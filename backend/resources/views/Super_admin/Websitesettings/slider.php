<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Slider</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Slider</li>
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
                            <h3 class="card-title">Slider</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <center><h4>Slider 1 update</h4></center>
                                    <hr>
                                    <form id="slider_1" action="<?php echo site_url('super_admin/website_settings/slider_action') ?>" method="POST"  enctype="multipart/form-data">
                                        <div class="form-group">
                                            <?php echo  image_view('uploads/website_image','',$slider_1,'no_imagebn.jpg','w-100','') ?>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label ">Upload Image</label>
                                            <div class="row" id="coba2"></div>
                                        </div>
                                        <input type="hidden" name="label" value="slider_1">
                                        <small><b>Size: 870x475 </b></small><br>
                                        <button type="submit" class="btn btn-primary"  >Update</button>

                                    </form>

                                </div>
                                <div class="col-md-6">

                                    <center><h4>Slider 2 update</h4></center>
                                    <hr>
                                    <form action="<?php echo site_url('super_admin/website_settings/slider_action') ?>" method="POST"  enctype="multipart/form-data">

                                        <div class="form-group">
                                            <?php echo  image_view('uploads/website_image','',$slider_2,'no_imagebn.jpg','w-100','') ?>

                                        </div>

                                        <div class="form-group">
                                            <label class="control-label ">Upload Image</label>
                                            <div class="row" id="coba3"></div>
                                        </div>
                                        <input type="hidden" name="label" value="slider_2">
                                        <small><b>Size: 870x475 </b></small><br>
                                        <button type="submit" class="btn btn-primary">Update</button>

                                    </form>
                                </div>

                                <div class="col-md-6">

                                    <center><h4>Slider 3 update</h4></center>
                                    <hr>
                                    <form action="<?php echo site_url('super_admin/website_settings/slider_action') ?>" method="POST"  enctype="multipart/form-data">

                                        <div class="form-group">
                                            <?php echo  image_view('uploads/website_image','',$slider_3,'no_imagebn.jpg','w-100','') ?>

                                        </div>


                                        <div class="form-group">
                                            <label class="control-label ">Upload Image</label>
                                            <div class="row" id="coba4"></div>
                                        </div>
                                        <input type="hidden" name="label" value="slider_3">
                                        <small><b>Size: 870x475 </b></small><br>
                                        <button type="submit" class="btn btn-primary">Update</button>

                                    </form>
                                </div>

                                <div class="col-md-6"> </div>
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