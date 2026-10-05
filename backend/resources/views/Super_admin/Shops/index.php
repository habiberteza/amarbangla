<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shops</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Shop list</li>
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
                            <a href="<?php echo base_url('super_admin/shops_create');?>" class="btn btn-xs btn-primary w-25 float-right">Create</a>
                            <h3 class="card-title">Shops list</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-12" style="margin-bottom: 20px;">
                                <form action="<?php echo site_url('super_admin/shops_search'); ?>" method="post">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="varchar">Division </label>
                                            <select class="form-control" name="division" onchange="viewdistrict(this.value)" required>
                                                <option value="">Please Select</option>
                                                <?php echo divisionView(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="varchar">District </label>
                                            <select class="form-control" name="district" onchange="viewupazila(this.value)" id="district">
                                                <option value="">Please Select</option>
                                                <?php echo districtselect(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="varchar">Upazila </label>
                                            <select class="form-control" name="upazila" id="upazila"
                                                    onchange="checkCity(this.value)">
                                                <option value="">Please Select</option>
                                                <?php echo upazilaselect(); ?>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="varchar">Pourashava/Union
                                            </label>
                                            <select class="form-control" name="pourashava">
                                                <option value="">Please Select</option>
                                                <?php echo pourashavaUnion(); ?>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="varchar">Ward</label>
                                            <select class="form-control" name="ward">
                                                <option value="">Please Select</option>
                                                <?php echo wardView(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary" style="margin-top: 30px;">
                                                Search
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Balance</th>
                                        <th>Logo</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php  foreach ($shops as $val){ ?>
                                        <tr>
                                            <td><?php echo $val->sch_id;?></td>
                                            <td><?php echo $val->name;?></td>
                                            <td><?php echo showWithCurrencySymbol($val->cash) ?></td>
                                            <td width="120px"><?php echo image_view('uploads/schools', '', $val->logo, 'no_image.jpg', 'w-100', '');?></td>
                                            <td width="120px"><?php echo image_view('uploads/schools', '', $val->image, 'no_image.jpg', 'w-100', '');?></td>
                                            <td><?php echo statusView($val->status);?></td>
                                            <td width="160px">
                                                <a href="<?php echo base_url('super_admin/shops_view/'.$val->sch_id);?>" class="btn btn-xs btn-info">View</a>
                                                <a href="<?php echo base_url('super_admin/shops_update/'.$val->sch_id);?>" class="btn btn-xs btn-warning ">Update</a>
<!--                                                <a href="--><?php //echo base_url('super_admin/shops_delete/'.$val->sch_id);?><!--" class="btn btn-xs btn-danger">Delete</a>-->
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Balance</th>
                                        <th>Logo</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
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