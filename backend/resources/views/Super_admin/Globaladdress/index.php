<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Global Address</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Global Address</li>
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
                            <a href="<?php echo base_url('super_admin/global_address_create');?>" class="btn btn-xs btn-primary w-25 float-right">Create</a>
                            <h3 class="card-title">Global Address list</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-12 mb-3">
                                <form action="<?php echo site_url('super_admin/global_address'); ?>" method="get">
                                    <div class="row align-items-end">
                                        <div class="col-md-3 mb-2">
                                            <label for="varchar">Division </label>
                                            <select class="form-control form-control-sm" name="division" onchange="viewdistrict(this.value)">
                                                <option value="">All Divisions</option>
                                                <?php echo divisionView(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label for="varchar">District </label>
                                            <select class="form-control form-control-sm" name="district" onchange="viewupazila(this.value)" id="district" >
                                                <option value="">All Districts</option>
                                                <?php echo districtselect(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label for="varchar">Upazila </label>
                                            <select class="form-control form-control-sm" name="upazila" id="upazila" onchange="checkCity(this.value)" >
                                                <option value="">All Upazilas</option>
                                                <?php echo upazilaselect(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-1 mb-2">
                                            <label>Per Page</label>
                                            <select name="per_page" class="form-control form-control-sm">
                                                <option value="25" <?php echo (isset($per_page) && $per_page == 25) ? 'selected' : ''; ?>>25</option>
                                                <option value="50" <?php echo (isset($per_page) && $per_page == 50) ? 'selected' : ''; ?>>50</option>
                                                <option value="100" <?php echo (isset($per_page) && $per_page == 100) ? 'selected' : ''; ?>>100</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2 d-flex">
                                            <button type="submit" class="btn btn-sm btn-primary mr-1"><i class="fas fa-search"></i> Filter</button>
                                            <a href="<?php echo site_url('super_admin/global_address'); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-undo"></i> Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                            <table id="global_address_table" class="table table-bordered table-striped table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Division</th>
                                        <th>Zila</th>
                                        <th>Upazila</th>
                                        <th>Pourashava</th>
                                        <th>Ward</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($address_data)) { $i = 1; foreach ($address_data as $address) { ?>
                                    <tr>
                                        <td width="80px"><?php echo $address->global_address_id; ?></td>
                                        <td><?php echo divisionname($address->division) ?></td>
                                        <td><?php echo districtname($address->zila) ?></td>
                                        <td><?php echo upazilaname($address->upazila) ?></td>
                                        <td><?php echo pourashavaUnionName($address->pourashava) ?></td>
                                        <td><?php echo getwardname($address->ward) ?></td>
                                        <td width="160px">
                                            <a href="<?php echo base_url('super_admin/global_address_update/'.$address->global_address_id);?>" class="btn btn-xs btn-warning ">Update</a>
                                            <a href="<?php echo base_url('super_admin/global_address_delete/'.$address->global_address_id);?>" onclick="javasciprt: return confirm('Are You Sure ?')" class="btn btn-xs btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                    <?php } } else { ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted"><i class="fas fa-map-marker-alt fa-2x d-block mb-2"></i> No addresses found.</td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            </div>
                            <?php if (!empty($pager)) { ?>
                                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                                    <div class="text-muted small mb-2">
                                        Showing page <?php echo $pager->getCurrentPage(); ?> of <?php echo $pager->getPageCount(); ?> (Total <?php echo number_format($pager->getTotal()); ?> addresses)
                                    </div>
                                    <div>
                                        <?= $pager->links(); ?>
                                    </div>
                                </div>
                            <?php } ?>
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