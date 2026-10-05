<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Settings Update</li>
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
                <h3 class="card-title">Settings Update</h3>
                <a href="<?= base_url('shop_admin/settings'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
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
                                <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'general'))?'active':'';?> <?= (empty($_GET))?'active':'';?>" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Registration Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'photo'))?'active':'';?> " id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Photo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'personal'))?'active':'';?>" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">General Settings</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'vat'))?'active':'';?>" id="custom-tabs-four-vat-tab" data-toggle="pill" href="#custom-tabs-four-vat" role="tab" aria-controls="custom-tabs-four-vat" aria-selected="false">Vat Register</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'address'))?'active':'';?>" id="custom-tabs-four-address-tab" data-toggle="pill" href="#custom-tabs-four-address" role="tab" aria-controls="custom-tabs-four-address" aria-selected="false">Address</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'customer'))?'active':'';?>" id="custom-tabs-four-customer-tab" data-toggle="pill" href="#custom-tabs-four-customer" role="tab" aria-controls="custom-tabs-four-customer" aria-selected="false">Customer Settings</a>
                            </li>



                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'general'))?'active show':'';?> <?= (empty($_GET))?'active show':'';?> " id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="<?php echo base_url('shop_admin/settings_update_action'); ?>" method="post">

                                            <div class="form-group">
                                                <label for="varchar">Name </label>
                                                <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $row->name; ?>"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="longtext">Email </label>
                                                <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $row->email; ?>"/>
                                            </div>

                                            <div class="form-group">
                                                <label for="longtext">Mobile </label>
                                                <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile"  value="<?php echo $row->mobile; ?>"/>
                                            </div>

                                            <div class="form-group">
                                                <label for="comment">Comment </label>
                                                <textarea class="form-control" rows="3" name="comment" id="comment" placeholder="Comment"><?php echo $row->comment; ?></textarea>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'photo'))?'active show':'';?>" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="<?php echo base_url('shop_admin/settings_photo_action'); ?>" method="post" enctype="multipart/form-data">



                                            <div class="form-group">
                                                <?= image_view('uploads/schools', '', $row->logo, 'no_image.jpg', '', '');?><br>
                                                <label for="longtext">Logo </label>
                                                <input type="file" class="form-control" name="logo" id="logo"/>
                                                <span class="help-block"><b>Max. file size 1024KB and (width=350px) x (height=100px)</b></span>
                                            </div>

                                            <div class="form-group">
                                                <?= image_view('uploads/schools', '', $row->image, 'no_image.jpg', '', '');?><br>
                                                <label for="longtext">Profile Image </label>
                                                <input type="file" class="form-control" name="image" id="profile_image"/>
                                                <span class="help-block"><b>Max. file size 1024KB and (width=160px) x (height=160px)</b></span>
                                            </div>

                                            <div class="form-group">
                                                <?= image_view('uploads/schools', '', $row->banner, 'no_image.jpg', '', '');?><br>
                                                <label for="longtext">Banner Image </label>
                                                <input type="file" class="form-control" name="banner" id="banner"/>
                                                <span class="help-block"><b>Max. file size 1024KB and (width=302px) x (height=129px)</b></span>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'personal'))?'active show':'';?>" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="<?php echo base_url('shop_admin/settings_general_action'); ?>" method="post">

                                            <?php
                                            $checkpr = get_data_by_id('priority','shops','sch_id',Auth()->sch_id);
                                            foreach ($gensattData as $geninfo) {
                                                if (($geninfo->label == 'express_shop_delivery_charge') || ($geninfo->label == 'express_shop_fast_delivery_charge') || ($geninfo->label == 'regular_shop_delivery_charge')) {
                                            }else{ ?>
                                                <div class="form-group">
                                                    <label for="longtext" class="text-capitalize"><?= str_replace("_"," ",$geninfo->label); ?></label>
                                                    <input type="hidden" name="id[]" value="<?= $geninfo->settings_id; ?>">
                                                    <input type="text" class="form-control" name="value[]" id="" value="<?= $geninfo->value; ?>"/>

                                                </div>
                                            <?php } } ?>

                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'vat'))?'active show':'';?>" id="custom-tabs-four-vat" role="tabpanel" aria-labelledby="custom-tabs-four-vat-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="<?php echo base_url('shop_admin/settings_vat_action'); ?>" method="post">
                                            <div class="form-group">
                                                <label for="varchar">Name </label>
                                                <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?= $vatRegister->name; ?> " required/>
                                            </div>
                                            <div class="form-group">
                                                <label for="varchar">Vat Register No </label>
                                                <input type="text" class="form-control" name="vat_register_no" id="vat_register_no" placeholder="Vat Register No" value="<?= $vatRegister->vat_register_no; ?>" required/>
                                            </div>
                                            <input type="hidden" name="vat_id" value="<?= $vatRegister->vat_id; ?>"/>

                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'address'))?'active show':'';?>" id="custom-tabs-four-address" role="tabpanel" aria-labelledby="custom-tabs-four-address-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="<?= base_url('shop_admin/settings_address_action'); ?>" method="post">
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
                                                <input name="address" class="form-control" value="<?= $row->address ?>">
                                            </div>


                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'customer'))?'active show':'';?>" id="custom-tabs-four-customer" role="tabpanel" aria-labelledby="custom-tabs-four-customer-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="<?php echo base_url('shop_admin/settings_customer_action'); ?>" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <?= image_view('uploads/customer_dashboard', '', $banner, 'no_image.jpg', 'w-50', '');?><br>
                                                <label for="longtext">Banner </label>
                                                <input type="file" class="form-control" name="banner" id="banner"/>
                                            </div>

                                            <div class="form-group">
                                                <label for="longtext">Video Url </label>
                                                <input type="text" class="form-control" name="video" id="video" placeholder="Input video embed url" value="<?php echo $video ?>"/>
                                                <span class="help-block"><b>Video embed url</b></span>
                                            </div>

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
