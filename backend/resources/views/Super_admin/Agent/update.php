<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Agent update</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Agent update</li>
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
                            <h3 class="card-title">Agent update</h3>
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
                                            <a class="nav-link <?php echo(!empty($_GET) && ($_GET['active'] == 'personal'))?'active':'';?>" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Address</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link <?php echo(!empty($_GET) && ($_GET['active'] == 'user'))?'active':'';?>" id="custom-tabs-four-settings-tab" data-toggle="pill" href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings" aria-selected="false">Area</a>
                                        </li>

                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'general'))?'active show':'';?> <?php echo(empty($_GET))?'active show':'';?> " id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form action="<?php echo base_url('super_admin/agent_update'); ?>" method="post">

                                                        <div class="form-group">
                                                            <label for="varchar">Name </label>
                                                            <input type="text" class="form-control" name="agent_name" id="agent_name" placeholder="Name" value="<?php echo $agent->agent_name; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="int">Phone </label>
                                                            <input type="number" class="form-control" name="mobile" id="mobile" placeholder="Phone" value="<?php echo $agent->mobile; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="int">Email </label>
                                                            <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $agent->email; ?>" />
                                                        </div>
                                                        <div class="form-group has-feedback">
                                                            <label for="int">Password </label>
                                                            <input type="password" class="form-control"  placeholder="Password" name="password" />
                                                        </div>
                                                        <div class="form-group has-feedback">
                                                            <label for="int">Confirm Password </label>
                                                            <input type="password" class="form-control" placeholder="Conform Password" name="con_password" />
                                                        </div>

                                                        <div class="form-group has-feedback">
                                                            <label for="int">Status </label>
                                                            <select name="status" id="status" class="form-control">
                                                                <?php echo globalStatus($agent->status);?>
                                                            </select>
                                                        </div>

                                                        <input type="hidden" name="agent_id" value="<?php echo $agent->agent_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?php echo site_url('super_admin/agent') ?>" class="btn btn-danger">Cancel</a>

                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'personal'))?'active show':'';?>" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form action="<?php echo base_url('super_admin/agent_address_update'); ?>" method="post">

                                                        <div class="form-group">
                                                            <label for="varchar">Division </label>
                                                            <select class="form-control" name="division" onchange="viewdistrict(this.value)" required>
                                                                <option value="">Please Select</option>
                                                                <?php echo (!empty($address))?divisionView($address->division):divisionView(); ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">District </label>
                                                            <select class="form-control" name="district"  onchange="viewupazila(this.value)" id="district"  required>
                                                                <option value="">Please Select</option>
                                                                <?php echo (!empty($address))?districtselect($address->zila,$address->division):districtselect(); ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Upazila </label>
                                                            <select class="form-control" name="upazila" id="upazila" required>
                                                                <option value="">Please Select</option>
                                                                <?php echo (!empty($address))?upazilaselect($address->upazila,$address->zila):upazilaselect(); ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Pourashava/Union </label>
                                                            <select class="form-control" name="pourashava" required>
                                                                <option value="" >Please Select</option>
                                                                <?php echo (!empty($address))?pourashavaUnion($address->pourashava):pourashavaUnion(); ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Ward </label>
                                                            <select class="form-control" name="ward" required>
                                                                <option value="" >Please Select</option>
                                                                <?php echo (!empty($address))?wardView($address->ward):wardView(); ?>
                                                            </select>
                                                        </div>

                                                        <input type="hidden" name="agent_id" value="<?php echo $agent->agent_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?php echo site_url('super_admin/agent') ?>" class="btn btn-danger">Cancel</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade <?php echo(!empty($_GET) && ($_GET['active'] == 'user'))?'active show':'';?>" id="custom-tabs-four-settings" role="tabpanel" aria-labelledby="custom-tabs-four-settings-tab">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <form action="<?php echo base_url('super_admin/agent_area_update'); ?>" method="post">

                                                        <div class="form-group">
                                                            <label for="varchar">Division </label>
                                                            <select class="form-control" name="division" onchange="viewdistrict(this.value)" required>
                                                                <option value="">Please Select</option>
                                                                <?php echo divisionView() ; ?>
                                                            </select>


                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">District </label>
                                                            <select class="form-control" name="district"  onchange="viewupazila(this.value)" id="district2" required>
                                                                <option value="">Please Select</option>
                                                                <?php echo districtselect() ; ?>
                                                            </select>


                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Upazila </label>
                                                            <select class="form-control" name="upazila" id="upazila2" required>
                                                                <option value="">Please Select</option>
                                                                <?php echo upazilaselect() ; ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Pourashava/Union </label>
                                                            <select class="form-control" name="pourashava" required>
                                                                <option value="">Please Select</option>
                                                                <?php echo pourashavaUnion() ; ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="varchar">Ward </label>
                                                            <select class="form-control" name="ward" required>
                                                                <option value="">Please Select</option>
                                                                <?php echo wardView() ; ?>
                                                            </select>
                                                        </div>

                                                        <input type="hidden" name="agent_id" value="<?php echo $agent->agent_id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?php echo site_url('super_admin/agent') ?>" class="btn btn-danger">Cancel</a>
                                                    </form>
                                                </div>

                                                <div class="col-md-8">
                                                    <table class="table table-bordered table-striped" id="example3">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Division</th>
                                                                <th>District</th>
                                                                <th>Upazila</th>
                                                                <th>Pourashava/Union</th>
                                                                <th>Ward</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php $i=1; foreach($area as $val){ $address = global_address_id_by_address($val->global_address_id); ?>
                                                            <tr>
                                                                <td><?php echo $i++; ?></td>
                                                                <td><?php echo divisionname($address->division);?></td>
                                                                <td><?php echo districtname($address->zila);?></td>
                                                                <td><?php echo upazilaname($address->upazila);?></td>
                                                                <td><?php echo pourashavaUnionName($address->pourashava);?></td>
                                                                <td><?php echo getwardname($address->ward);?></td>
                                                                <td>
                                                                    <?php echo anchor(site_url('super_admin/delete_area/'.$val->agent_permitted_area_id.'/'.$val->agent_id),'Delete', 'class="btn btn-xs btn-danger" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
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