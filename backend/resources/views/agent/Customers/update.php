<?= $this->extend('Agent/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Customer update</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('agent/dashboard')?>">Home</a></li>
                        <li class="breadcrumb-item active">Customer update</li>
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
                            <h3 class="card-title">Customer update</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-12">
                                <?=  isset(newSession()->message) ? newSession()->message :''; ?>
                            </div>
                            <div class="card card-primary card-outline card-outline-tabs">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'general'))?'active':'';?> <?=(empty($_GET))?'active':'';?>" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Registraion Info</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'personal'))?'active':'';?>" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Personal</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'user'))?'active':'';?>" id="custom-tabs-four-settings-tab" data-toggle="pill" href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings" aria-selected="false">Address</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'photo'))?'active':'';?> " id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Photo</a>
                                        </li>


                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'general'))?'active show':'';?> <?= (empty($_GET))?'active show':'';?> " id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form action="<?= base_url('agent/customer_update_action'); ?>" method="post">

                                                        <div class="form-group">
                                                            <label for="varchar">CustomerName</label>
                                                            <input type="text" class="form-control" name="customer_name" id="CustomerName" placeholder="CustomerName" value="<?= $category->customer_name; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="int">Mobile </label>
                                                            <input type="text" class="form-control" name="mobile" id="Mobile" placeholder="Mobile" value="<?= $category->mobile; ?>" />
                                                        </div>
                                                        <div class="form-group has-feedback">
                                                            <label for="int">Password</label>
                                                            <input type="password" class="form-control" placeholder="Password" name="password" required  />
                                                        </div>
                                                        <div class="form-group has-feedback">
                                                            <label for="int">Confirm Password </label>
                                                            <input type="password" class="form-control" placeholder="Conform Password" name="con_password" required  />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="varchar">User Type</label>
                                                            <select class="form-control" name="cus_type_id" >
                                                                <?= getListInOption($category->cus_type_id,'cus_type_id','type_name', 'customer_type')?>
                                                            </select>
                                                        </div>

                                                        <input type="hidden" name="customer_id" value="<?php echo $category->customer_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?= site_url('agent/customers') ?>" class="btn btn-danger">Cancel</a>

                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'personal'))?'active show':'';?>" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form action="<?= base_url('agent/customer_personal_update'); ?>" method="post">

                                                        <div class="form-group">
                                                            <label for="varchar">FatherName</label>
                                                            <input type="text" class="form-control" name="father_name" id="FatherName" placeholder="FatherName" value="<?= $category->father_name; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="varchar">MotherName</label>
                                                            <input type="text" class="form-control" name="mother_name" id="MotherName" placeholder="MotherName" value="<?= $category->mother_name; ?>" />
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Age</label>
                                                            <input type="text" class="form-control" name="age" id="Age" placeholder="Age" value="<?= $category->age; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="int">NID</label>
                                                            <input type="text" class="form-control" name="nid" id="NID" placeholder="NID" value="<?= $category->nid; ?>" />
                                                        </div>


                                                        <input type="hidden" name="customer_id" value="<?= $category->customer_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?= site_url('agent/customers') ?>" class="btn btn-danger">Cancel</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'user'))?'active show':'';?>" id="custom-tabs-four-settings" role="tabpanel" aria-labelledby="custom-tabs-four-settings-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form action="<?= base_url('agent/customer_address_update'); ?>" method="post">
                                                        <div class="form-group">
                                                            <label for="varchar">Division </label>
                                                            <select class="form-control" name="division" onchange="viewdistrict(this.value)">
                                                                <option>Please Select</option>
                                                                <?php echo (!empty($address))?divisionView($address->division):divisionView(); ?>
                                                            </select>


                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">District </label>
                                                            <select class="form-control" name="district" onchange="viewupazila(this.value)" id="district">
                                                                <option>Please Select</option>
                                                                <?php echo (!empty($address))?districtselect($address->zila,$address->division):districtselect(); ?>
                                                            </select>

                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Upazila </label>
                                                            <select class="form-control" name="upazila" id="upazila">
                                                                <option>Please Select</option>
                                                                <?php echo (!empty($address))?upazilaselect($address->upazila,$address->zila):upazilaselect(); ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Pourashava/Union </label> <span style="color: red;">*</span>

                                                            <select class="form-control" name="pourashava">
                                                                <option>Please Select</option>
                                                                <?php echo (!empty($address))?pourashavaUnion($address->pourashava):pourashavaUnion(); ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Ward </label>
                                                            <span style="color: red;">*</span>
                                                            <select class="form-control" name="ward" required>
                                                                <option value="">Please Select</option>
                                                                <?php echo (!empty($address))?wardView($address->ward):wardView(); ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">House Address </label>
                                                            <textarea name="address" class="form-control"><?= $category->address ?></textarea>
                                                        </div>

                                                        <input type="hidden" name="customer_id" value="<?= $category->customer_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?= site_url('agent/customers') ?>" class="btn btn-danger">Cancel</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'photo'))?'active show':'';?>" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form action="<?= base_url('agent/customer_photo_update'); ?>" method="post" enctype="multipart/form-data">

                                                        <div class="form-group">
                                                            <?= image_view('uploads/customer_image', '', $category->pic, 'no_image.jpg', 'w-25', '');?><br>
                                                            <label for="varchar">Photo </label>
                                                            <input type="file" class="form-control" name="pic" id="pic" />
                                                            <span class="help-block"><b>Max. file size 1024KB and (width=300px) x (height=300px)</b></span>
                                                        </div>

                                                        <input type="hidden" name="customer_id" value="<?= $category->customer_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?= site_url('agent/customers') ?>" class="btn btn-danger">Cancel</a>
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
<?= $this->endSection() ?>


<?= $this->section('java_script') ?>
<script>

</script>
<?= $this->endSection() ?>
