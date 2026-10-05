<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shops update</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Shop update</li>
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
                            <h3 class="card-title">Shops update</h3>
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
                                        <li class="nav-item">
                                            <a class="nav-link <?php echo(!empty($_GET) && ($_GET['active'] == 'user'))?'active':'';?>" id="custom-tabs-four-settings-tab" data-toggle="pill" href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings" aria-selected="false">User Update</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'address'))?'active':'';?>" id="custom-tabs-four-address-tab" data-toggle="pill" href="#custom-tabs-four-address" role="tab" aria-controls="custom-tabs-four-address" aria-selected="false">Address</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link <?php echo(!empty($_GET) && ($_GET['active'] == 'shop_category'))?'active':'';?>" id="custom-tabs-four-shopcategory-tab" data-toggle="pill" href="#custom-tabs-four-shopcategory" role="tab" aria-controls="custom-tabs-four-shopcategory" aria-selected="false">Shop category</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'general'))?'active show':'';?> <?php echo(empty($_GET))?'active show':'';?> " id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form action="<?php echo base_url('super_admin/shops_general_update'); ?>" method="post">
                                                        <div class="form-group">
                                                            <label for="varchar">Name </label>
                                                            <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $shops->name;?>" >
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="int">Email</label>
                                                            <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $shops->email;?>" >
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="enum">Status </label>
                                                            <select class="form-control" name="status" id="status">
                                                                <?php print globalStatus($shops->status); ?>
                                                            </select>
                                                        </div>

                                                        <input type="hidden" name="sch_id" value="<?php echo $shops->sch_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Create</button>
                                                        <a href="<?php echo site_url('super_admin/shops') ?>" class="btn btn-danger">Cancel</a>

                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'personal'))?'active show':'';?>" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form action="<?php echo base_url('super_admin/shops_personal_update'); ?>" method="post">

                                                        <div class="form-group">
                                                            <label for="varchar">Mobile </label>
                                                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" value="<?php echo $shops->mobile; ?>" >
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Address </label>
                                                            <input type="text" class="form-control" name="address" id="address" placeholder="Address" value="<?php echo $shops->address; ?>" >
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Comment </label>
                                                            <input type="text" class="form-control" name="comment" id="comment" placeholder="Comment" value="<?php echo $shops->comment; ?>" >
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Sale commission </label>
                                                            <input type="text" class="form-control" name="sup_comm" id="sup_comm" placeholder="commission" value="<?php echo $shops->sup_comm; ?>" >
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="varchar">Agent </label>
                                                            <select name="agent_id" class="form-control" id="agent_id">
                                                                <option value="">Please select</option>
                                                                <?php echo getListInOption($shops->agent_id, 'agent_id', 'agent_name', 'agent');?>
                                                            </select>
                                                        </div>


                                                        <input type="hidden" name="sch_id" value="<?php echo $shops->sch_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?php echo site_url('super_admin/shops') ?>" class="btn btn-danger">Cancel</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade <?php echo(!empty($_GET) && ($_GET['active'] == 'photo'))?'active show':'';?>" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form action="<?php echo base_url('super_admin/shops_photo_update'); ?>" method="post" enctype="multipart/form-data">

                                                        <div class="form-group">
                                                            <?php echo image_view('uploads/schools', '', $shops->logo, 'no_image.jpg', 'w-25', '');?><br>
                                                            <label for="longtext">Logo</label>
                                                            <input type="file" class="form-control" name="logo" id="logo" />
                                                            <span class="help-block"><b>Max. file size 1024KB and (width=350px) x (height=100px)</b></span>
                                                        </div>

                                                        <div class="form-group">
                                                            <?php echo image_view('uploads/schools', '', $shops->image, 'no_image.jpg', 'w-25', '');?><br>
                                                            <label for="longtext">ProfileImage</label>
                                                            <input type="file" class="form-control" name="profile_image"
                                                                   id="profile_image" />
                                                            <span class="help-block"><b>Max. file size 1024KB and
                                                                    (width=160px) x (height=160px)</b></span>
                                                        </div>

                                                        <input type="hidden" name="sch_id" value="<?php echo $shops->sch_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?php echo site_url('super_admin/shops') ?>" class="btn btn-danger">Cancel</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade <?php echo(!empty($_GET) && ($_GET['active'] == 'user'))?'active show':'';?>" id="custom-tabs-four-settings" role="tabpanel" aria-labelledby="custom-tabs-four-settings-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form action="<?php echo base_url('super_admin/shops_user_update'); ?>" method="post">

                                                            <div class="form-group">
                                                                <label for="varchar">Email </label>
                                                                <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $user->email; ?>" required >
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="varchar">Password </label>
                                                                <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="" required >
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="longtext">Confirm Password </label>
                                                                <input type="password" class="form-control" name="con_password" id="password" placeholder="Confirm Password" value="" required >
                                                            </div>


                                                        <input type="hidden" name="sch_id" value="<?php echo $shops->sch_id; ?>" >
                                                        <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?php echo site_url('super_admin/shops') ?>" class="btn btn-danger">Cancel</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'address'))?'active show':'';?>" id="custom-tabs-four-address" role="tabpanel" aria-labelledby="custom-tabs-four-address-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form action="<?= base_url('super_admin/shops_address_action'); ?>" method="post">
                                                        <div class="form-group">
                                                            <label for="varchar">Division </label>
                                                            <select class="form-control" name="division" onchange="viewdistrict(this.value)">
                                                                <option>Please Select</option>
                                                                <?= divisionView($division); ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">District  </label>
                                                            <select class="form-control" name="district" onchange="viewupazila(this.value)" id="district" required>
                                                                <option value="">Please Select</option>
                                                                <?= districtselect($zila, $division); ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Upazila  </label>
                                                            <select class="form-control" name="upazila" id="upazila" onchange="checkCity(this.value)" required>
                                                                <option value="">Please Select</option>
                                                                <?= upazilaselect($upazila, $zila); ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group" id="pourashava" >
                                                            <label for="varchar">Pourashava/Union  </label>
                                                            <select class="form-control" name="pourashava" id="reuq">
                                                                <option value="">Please Select</option>
                                                                <?= pourashavaUnion($pourashava); ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Ward </label>
                                                            <select class="form-control" name="ward" required>
                                                                <option value="">Please Select</option>
                                                                <?= wardView($ward); ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">House Address </label>
                                                            <input name="address" class="form-control" value="<?= $shops->address ?>">
                                                        </div>


                                                        <input type="hidden" name="sch_id" value="<?php echo $shops->sch_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?php echo site_url('super_admin/shops') ?>" class="btn btn-danger">Cancel</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade <?php echo(!empty($_GET) && ($_GET['active'] == 'shop_category'))?'active show':'';?>" id="custom-tabs-four-shopcategory" role="tabpanel" aria-labelledby="custom-tabs-four-shopcategory-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form action="<?php echo base_url('super_admin/shops_category_update'); ?>" method="post">

                                                        <div class="form-group">
                                                            <label for="varchar">Category </label>

                                                            <select class="form-control" onchange="shopSubCategory(this.value)" name="parent_cat_id" id="parent_cat_id" required>
                                                                <option value="">Please Select</option>
                                                                <?php  echo shopCateListInOption($shops->shop_cat_id);?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">SubCategory </label>
                                                            <select class="form-control" name="sub_cat_id" id="subCat" required>
                                                                <option value="">Please Select</option>
                                                                <?php echo shopSubCatListInOption($shops->shop_cat_id);?>
                                                            </select>
                                                        </div>


                                                        <div class="form-group">
                                                            <label for="varchar">Shop type </label>
                                                            <select class="form-control" name="type">
                                                                <option value="">Please select</option>
                                                                <?php echo shopType($shops->type);?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Priority type </label>
                                                            <select class="form-control" name="priority">
                                                                <option value="">Please select</option>
                                                                <?php echo priorityType($shops->priority);?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Home feature </label>
                                                            <select class="form-control" name="home_feature">
                                                                <option value="">Please select</option>
                                                                <?php echo home_feature($shops->home_feature);?>
                                                            </select>
                                                        </div>

                                                        <input type="hidden" name="sch_id" value="<?php echo $shops->sch_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?php echo site_url('super_admin/shops') ?>" class="btn btn-danger">Cancel</a>
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