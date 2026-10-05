<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Delivery boy</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Delivery boy</li>
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
                            <a href="<?php echo base_url('super_admin/delivery_boy_create');?>" class="btn btn-xs btn-primary w-25 float-right">Create</a>
                            <h3 class="card-title">Delivery boy list</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-12">
                                <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                            </div>
                            <div class="col-md-12">
                                <form action="<?php echo base_url('super_admin/delivery_boy_filter')?>" method="post">
                                    <div class="row">

                                        <div class="form-group col-md-2">
                                            <label for="varchar">Division </label>
                                            <select class="form-control" name="division" onchange="viewdistrict(this.value)" required >
                                                <option value="">Please Select</option>
                                                <?php echo divisionView(); ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="varchar">District</label>
                                            <select class="form-control" name="district" onchange="viewupazila(this.value)" id="district" >
                                                <option value="">Please Select</option>
                                                <?php echo districtselect(); ?>
                                            </select>


                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="varchar">Upazila </label>
                                            <select class="form-control" name="upazila" id="upazila" >
                                                <option value="">Please Select</option>
                                                <?php echo upazilaselect(); ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="varchar">Pourashava/Union</label>
                                            <select class="form-control" name="pourashava">
                                                <option value="">Please Select</option>
                                                <?php echo pourashavaUnion(); ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="varchar">Ward</label>
                                            <select class="form-control" name="ward">
                                                <option value="">Please Select</option>
                                                <?php echo wardView(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <button type="submit" class="btn btn-primary "  style="margin-top: 30px; width:100%;">Filter </button>
                                        </div>

                                    </div>
                                </form>
                            </div>

                            <div class="row mb-3 align-items-end">
                                <div class="col-md-5">
                                    <form action="<?php echo base_url('super_admin/delivery_boy'); ?>" method="get" class="d-flex">
                                        <input type="text" name="search" class="form-control form-control-sm mr-2" placeholder="Search by name or mobile..." value="<?php echo esc($search ?? ''); ?>">
                                        <button type="submit" class="btn btn-sm btn-primary mr-1"><i class="fas fa-search"></i> Search</button>
                                        <a href="<?php echo base_url('super_admin/delivery_boy'); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-undo"></i> Reset</a>
                                    </form>
                                </div>
                                <div class="col-md-7 text-right">
                                    <form action="<?php echo base_url('super_admin/delivery_boy'); ?>" method="get" class="d-inline-flex align-items-center">
                                        <?php if (!empty($search)) { ?><input type="hidden" name="search" value="<?php echo esc($search); ?>"><?php } ?>
                                        <label class="mr-2 mb-0 small">Per Page:</label>
                                        <select name="per_page" class="form-control form-control-sm mr-2" style="width: auto;" onchange="this.form.submit()">
                                            <option value="25" <?php echo (isset($per_page) && $per_page == 25) ? 'selected' : ''; ?>>25</option>
                                            <option value="50" <?php echo (isset($per_page) && $per_page == 50) ? 'selected' : ''; ?>>50</option>
                                            <option value="100" <?php echo (isset($per_page) && $per_page == 100) ? 'selected' : ''; ?>>100</option>
                                        </select>
                                    </form>
                                </div>
                            </div>

                            <div class="table-responsive">
                            <table id="deliveryboy_table" class="table table-bordered table-striped table-hover">
                                <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($deliveryboy)) { foreach ($deliveryboy as $val){ ?>
                                    <tr>
                                        <td width="10"><?php echo $val->delivery_boy_id;?></td>
                                        <td><strong><?php echo esc($val->name); ?></strong></td>
                                        <td><?php echo esc($val->mobile); ?></td>
                                        <td><?php echo showWithCurrencySymbol($val->balance) ?></td>
                                        <td width="300px">
                                            <a href="<?php echo base_url('super_admin/delivery_boy_order/'.$val->delivery_boy_id);?>" class="btn btn-xs btn-info ">Order</a>
                                            <a href="<?php echo base_url('super_admin/delivery_boy_commission/'.$val->delivery_boy_id);?>" class="btn btn-xs btn-primary ">Commission</a>
                                            <a href="<?php echo base_url('super_admin/delivery_boy_ledger/'.$val->delivery_boy_id);?>" class="btn btn-xs btn-success ">Ledger</a>
                                            <a href="<?php echo base_url('super_admin/delivery_boy_update/'.$val->delivery_boy_id);?>" class="btn btn-xs btn-warning ">Update</a>
                                            <a href="<?php echo base_url('super_admin/delivery_boy_delete/'.$val->delivery_boy_id);?>" onclick="javasciprt: return confirm('Are You Sure ?')" class="btn btn-xs btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } } else { ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted"><i class="fas fa-motorcycle fa-2x d-block mb-2"></i> No delivery boys found.</td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            </div>
                            <?php if (!empty($pager)) { ?>
                                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                                    <div class="text-muted small mb-2">
                                        Showing page <?= $pager->getCurrentPage(); ?> of <?= $pager->getPageCount(); ?> (Total <?= number_format($pager->getTotal()); ?> delivery personnel)
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