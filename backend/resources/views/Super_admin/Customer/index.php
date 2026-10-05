<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Customer Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Customer</li>
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

                    <div class="card card-outline card-primary">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-users mr-1"></i> Customer List 
                                <span class="badge badge-info ml-2"><?php echo isset($pager) ? number_format($pager->getTotal()) : count($customer); ?> Total</span>
                            </h3>
                            <div class="card-tools ml-auto">
                                <a href="<?php echo base_url('super_admin/customer_create'); ?>"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus mr-1"></i> Create Customer
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-12 px-0">
                                <?php echo isset(newSession()->message) ? newSession()->message : ''; ?>
                            </div>

                            <!-- Fast Instant Search & Per-Page Controls -->
                            <div class="bg-light p-3 rounded mb-3 border">
                                <form action="<?php echo base_url('super_admin/customer') ?>" method="get" class="row align-items-end">
                                    <div class="form-group col-md-5 mb-md-0">
                                        <label for="search" class="font-weight-bold text-secondary">Search Customer</label>
                                        <div class="input-group">
                                            <input type="text" name="search" id="search" class="form-control" 
                                                   placeholder="Search by Name, Mobile or ID..." 
                                                   value="<?php echo htmlspecialchars($search ?? ''); ?>">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fas fa-search mr-1"></i> Search
                                                </button>
                                                <?php if (!empty($search)): ?>
                                                    <a href="<?php echo base_url('super_admin/customer'); ?>" class="btn btn-outline-secondary" title="Clear Search">
                                                        <i class="fas fa-times"></i> Clear
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-2 mb-md-0">
                                        <label for="per_page" class="font-weight-bold text-secondary">Per Page</label>
                                        <select name="per_page" id="per_page" class="form-control" onchange="this.form.submit()">
                                            <option value="25" <?php echo ($perPage ?? 25) == 25 ? 'selected' : ''; ?>>25 items</option>
                                            <option value="50" <?php echo ($perPage ?? 25) == 50 ? 'selected' : ''; ?>>50 items</option>
                                            <option value="100" <?php echo ($perPage ?? 25) == 100 ? 'selected' : ''; ?>>100 items</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-5 mb-md-0 text-md-right">
                                        <button class="btn btn-outline-info" type="button" data-toggle="collapse" data-target="#locationFilterCollapse" aria-expanded="false" aria-controls="locationFilterCollapse">
                                            <i class="fas fa-map-marker-alt mr-1"></i> Filter by Location <i class="fas fa-chevron-down ml-1"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Location Filter (Collapsible) -->
                            <div class="collapse mb-3" id="locationFilterCollapse">
                                <div class="card card-body bg-light border">
                                    <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-filter mr-1"></i> Filter by Geographic Location</h6>
                                    <form action="<?php echo base_url('super_admin/customer_filter') ?>" method="post">
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <label for="division">Division</label>
                                                <select class="form-control" name="division" onchange="viewdistrict(this.value)" required>
                                                    <option value="">Please Select</option>
                                                    <?php echo divisionView(); ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="district">District</label>
                                                <select class="form-control" name="district" onchange="viewupazila(this.value)" id="district">
                                                    <option value="">Please Select</option>
                                                    <?php echo districtselect(); ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="upazila">Upazila</label>
                                                <select class="form-control" name="upazila" id="upazila">
                                                    <option value="">Please Select</option>
                                                    <?php echo upazilaselect(); ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="pourashava">Pourashava/Union</label>
                                                <select class="form-control" name="pourashava">
                                                    <option value="">Please Select</option>
                                                    <?php echo pourashavaUnion(); ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="ward">Ward</label>
                                                <select class="form-control" name="ward">
                                                    <option value="">Please Select</option>
                                                    <?php echo wardView(); ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2 d-flex align-items-end">
                                                <button type="submit" class="btn btn-primary btn-block">Filter Location</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Fast Server-Rendered Customer Table -->
                            <div class="table-responsive">
                                <table id="customer_table" class="table table-bordered table-striped table-hover mb-0">
                                    <thead class="thead-light">
                                    <tr>
                                        <th style="width: 70px;">ID</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Created Date</th>
                                        <th style="width: 220px;" class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($customer)): ?>
                                        <?php foreach ($customer as $val): ?>
                                            <tr>
                                                <td class="font-weight-bold text-muted"><?php echo $val->customer_id; ?></td>
                                                <td class="font-weight-bold text-dark"><?php echo htmlspecialchars($val->customer_name ?? ''); ?></td>
                                                <td>
                                                    <span class="badge badge-light border px-2 py-1 font-weight-normal text-dark" style="font-size: 0.9rem;">
                                                        <i class="fas fa-phone-alt text-success mr-1"></i>
                                                        <?php echo showWithPhoneNummberCountryCode($val->mobile); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo invoiceDateFormat($val->createdDtm); ?></td>
                                                <td class="text-center">
                                                    <a href="<?php echo base_url('super_admin/customer_order_list/' . $val->customer_id); ?>"
                                                       class="btn btn-xs btn-success mr-1">
                                                        <i class="fas fa-shopping-bag mr-1"></i> Orders
                                                    </a>
                                                    <a href="<?php echo base_url('super_admin/customer_update/' . $val->customer_id); ?>"
                                                       class="btn btn-xs btn-warning text-dark mr-1">
                                                        <i class="fas fa-edit mr-1"></i> Update
                                                    </a>
                                                    <a href="<?php echo base_url('super_admin/customer_delete/' . $val->customer_id); ?>"
                                                       onclick="return confirm('Are you sure you want to delete this customer?')"
                                                       class="btn btn-xs btn-danger">
                                                        <i class="fas fa-trash mr-1"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                                No customers found matching your criteria.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Created Date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Fast Server-Side Pagination Bar -->
                            <?php if (isset($pager)): ?>
                                <div class="row align-items-center mt-3 pt-2 border-top">
                                    <div class="col-md-6 text-muted mb-2 mb-md-0">
                                        <?php if ($pager->getTotal() > 0): ?>
                                            Showing <span class="font-weight-bold"><?php echo (($pager->getCurrentPage() - 1) * $pager->getPerPage()) + 1; ?></span>
                                            to <span class="font-weight-bold"><?php echo min($pager->getCurrentPage() * $pager->getPerPage(), $pager->getTotal()); ?></span>
                                            of <span class="font-weight-bold"><?php echo number_format($pager->getTotal()); ?></span> customers
                                        <?php else: ?>
                                            0 customers found
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-md-end justify-content-center">
                                        <?php echo $pager->links(); ?>
                                    </div>
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