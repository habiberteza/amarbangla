<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shops Commission</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Shops Commission list</li>
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
                            <h3 class="card-title">Shops Commission list</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-12">
                                <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                            </div>
                            <div class="col-md-12" style="margin-bottom: 20px;">
                                <form action="<?php echo site_url('super_admin/shops_commission_address_search'); ?>" method="post">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="varchar">Division </label>
                                            <select class="form-control" name="division" onchange="viewdistrict(this.value)" >
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
                                            <label for="varchar">Status</label>
                                            <select class="form-control" name="status">
                                                <option value="">Please Select</option>
                                                <option value="1">Paid</option>
                                                <option value="2">Unpaid</option>
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

                            <div class="col-md-12 mb-3">
                                <form action="<?php echo base_url('super_admin/shops_commission')?>" method="get">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 mb-2">
                                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by shop name..." value="<?php echo esc($search ?? ''); ?>">
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                                                <option value="25" <?php echo ($per_page ?? 25) == 25 ? 'selected' : ''; ?>>25 / page</option>
                                                <option value="50" <?php echo ($per_page ?? 25) == 50 ? 'selected' : ''; ?>>50 / page</option>
                                                <option value="100" <?php echo ($per_page ?? 25) == 100 ? 'selected' : ''; ?>>100 / page</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i> Search</button>
                                            <a href="<?php echo base_url('super_admin/shops_commission'); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-sync"></i> Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                            <table id="shops_comm_table" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Commission</th>
                                    <th>Due</th>
                                    <th>Pay</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  foreach ($shops as $val){ ?>
                                    <tr>
                                        <td><?php echo $val->sch_id;?></td>
                                        <td><?php echo esc($val->name);?></td>
                                        <td><?php echo showWithCurrencySymbol($val->commision); ?></td>
                                        <td><?php echo showWithCurrencySymbol($val->due_commision); ?></td>
                                        <td><?php echo showWithCurrencySymbol($val->pay_commision); ?></td>
                                        <td width="260px">
                                            <a href="<?php echo site_url('super_admin/shops_commission_unpaid_list/' . $val->sch_id) ?>" class="btn btn-xs btn-info">Unpaid List</a>

                                            <a href="<?php echo site_url('super_admin/shops_commission_paid_list/' . $val->sch_id) ?>" class="btn btn-xs btn-success">Paid List</a>

                                            <a href="<?php echo site_url('super_admin/shops_commission_pay_list/' . $val->sch_id) ?>" class="btn btn-xs btn-primary">Commission Pay List</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Commission</th>
                                    <th>Due</th>
                                    <th>Pay</th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                            </table>
                            </div>
                            <?php if (isset($pager)): ?>
                                <div class="mt-3 d-flex justify-content-center">
                                    <?= $pager->links(); ?>
                                </div>
                            <?php endif; ?>
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