<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shops category update</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Shops category update</li>
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
                            <h3 class="card-title">Shops category update</h3>
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
                                            <a class="nav-link <?php echo (!empty($_GET) && ($_GET['active'] == 'general'))?'active':'';?> <?php echo(empty($_GET))?'active':'';?>" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Registration Info</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link <?php echo(!empty($_GET) && ($_GET['active'] == 'personal'))?'active':'';?>" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Others</a>
                                        </li>

                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'general'))?'active show':'';?> <?php echo(empty($_GET))?'active show':'';?> " id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form action="<?php echo base_url('super_admin/shop_category_update_action'); ?>" method="post">

                                                        <div class="form-group">
                                                            <label for="varchar">Category </label>
                                                            <input type="text" class="form-control" name="name" id="name" placeholder="Shop category name" value="<?php echo $shopcategory->name; ?>" >
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="varchar">Parent Category</label>
                                                            <select class="form-control" name="parent_cat_id">
                                                                <option value="">Please Select</option>
                                                                <?php echo shopSubCatListOption($shopcategory->parent_cat_id); ?>
                                                            </select>
                                                        </div>

                                                        <input type="hidden" name="shop_cat_id" value="<?php echo $shopcategory->shop_cat_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?php echo site_url('super_admin/shop_category') ?>" class="btn btn-danger">Cancel</a>

                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'personal'))?'active show':'';?>" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form action="<?php echo base_url('super_admin/shop_category_others_action'); ?>" method="post" enctype="multipart/form-data">

                                                        <div class="form-group">
                                                            <label for="varchar">Show Home </label>
                                                            <select class="form-control" name="show_home" >
                                                                <?php echo globalStatus($shopcategory->show_home)?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="varchar">Title </label>
                                                            <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?php echo $shopcategory->title; ?>" required />
                                                        </div>

                                                        <div class="form-group"><label for="varchar">Image  </label>
                                                            <input type="file" class="form-control" name="image" >
                                                        </div>


                                                        <input type="hidden" name="shop_cat_id" value="<?php echo $shopcategory->shop_cat_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?php echo site_url('super_admin/shop_category') ?>" class="btn btn-danger">Cancel</a>
                                                    </form>
                                                </div>
                                                <div class="col-md-6">

                                                    <?php echo image_view('uploads/schools', '', $shopcategory->image, 'no_image.jpg', 'w-25', '');?>
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