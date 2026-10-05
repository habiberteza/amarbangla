<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Order</li>
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
                        <div class="card-header row">
                            <div class="col-md-3">
                                <h3 class="card-title">Order list</h3>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo site_url('super_admin/order_filter_status/2') ?>" class="btn btn-primary btn-xs">Pending</a>
                                <a href="<?php echo site_url('super_admin/order_filter_not_accepted/') ?>" class="btn btn-warning btn-xs">Not Accepted</a>
                                <a href="<?php echo site_url('super_admin/order_filter/0') ?>" class="btn btn-info btn-xs">Accepted</a>
                                <a href="<?php echo site_url('super_admin/order_filter/1') ?>"  class="btn btn-success btn-xs">Complete</a>
                                <a href="<?php echo site_url('super_admin/order_filter_status/3') ?>" class="btn btn-danger btn-xs">Cancel</a>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control select2" name="shop" onchange="allshoporder(this.value)">
                                    <option value="0">Shop by Order filter</option>
                                    <?php foreach ($shope as $view) { ?>
                                        <option value="<?php echo $view->sch_id ?>"><?php echo $view->name ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="varchar">Division </label>
                                    <select class="form-control" name="division" onchange="viewdistrict(this.value)">
                                        <option value="">Please Select</option>
                                        <?php echo divisionView() ; ?>
                                    </select>


                                </div>

                                <div class="form-group col-md-4">
                                    <label for="varchar">District</label>
                                    <select class="form-control" name="district" onchange="viewupazila(this.value)"
                                            id="district" required>
                                        <option value="">Please Select</option>
                                        <?php echo districtselect() ; ?>
                                    </select>


                                </div>

                                <div class="form-group col-md-4">
                                    <label for="varchar">Upazila </label>
                                    <select class="form-control" name="upazila" id="upazila" required>
                                        <option value="">Please Select</option>
                                        <?php echo upazilaselect() ; ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="varchar">Pourashava/Union</label>
                                    <select class="form-control" name="pourashava">
                                        <option value="">Please Select</option>
                                        <?php echo pourashavaUnion() ; ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="varchar">Ward </label>
                                    <select class="form-control" name="ward">
                                        <option value="">Please Select</option>
                                        <?php echo wardView() ; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="varchar">Status </label>
                                    <select class="form-control" name="status" >
                                        <option value="">Please select</option>
                                        <option value="2">Pending</option>
                                        <option value="3">Cancel</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <button type="button" class="btn btn-primary " onclick="filter_invoice()" style="margin-top: 30px; width:100%;">Filter</button>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-end">
                                <div class="col-md-4">
                                    <form action="<?php echo base_url('super_admin/order'); ?>" method="get" class="d-flex">
                                        <input type="text" name="search" class="form-control form-control-sm mr-2" placeholder="Search Invoice ID..." value="<?php echo esc($search ?? ''); ?>">
                                        <button type="submit" class="btn btn-sm btn-primary mr-1"><i class="fas fa-search"></i></button>
                                        <a href="<?php echo base_url('super_admin/order'); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-undo"></i></a>
                                    </form>
                                </div>
                                <div class="col-md-8 text-right">
                                    <form action="<?php echo base_url('super_admin/order'); ?>" method="get" class="d-inline-flex align-items-center">
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
                            <table id="order_table" class="table table-bordered table-striped table-hover">
                                <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Invoice Id</th>
                                    <th>Total</th>
                                    <th>Create date</th>
                                    <th>Status</th>
                                    <th>Delivery Status</th>
                                    <th>Customer order list</th>
                                </tr>
                                </thead>
                                <tbody id="shopOrder">
                                <?php if (!empty($order)) { foreach ($order as $view){ ?>
                                    <tr>
                                        <td><?php echo $view->invoice_id;?></td>
                                        <td><a href="<?php echo site_url('super_admin/order_invoice/' . $view->invoice_id) ?>" class="btn btn-xs btn-outline-primary font-weight-bold">#<?php echo $view->invoice_id; ?></a></td>
                                        <td><strong><?php echo showWithCurrencySymbol($view->final_amount); ?></strong></td>
                                        <td>
                                            <?php echo invoiceDateFormat($view->createdDtm); ?>
                                        </td>
                                        <td><?php echo getinvoiceStatusNew($view->invoice_id); ?></td>

                                        <td>
                                            <?php
                                             $deliverystatus = deliverystatus($view->invoice_id);
                                             if (!empty($deliverystatus)) {
                                                 foreach ($deliverystatus as $row) {
                                                     $detail = 'Delivery By Admin';
                                                     if (!empty($row->delivery_boy_id)) {
                                                         $deliveryBoyData = get_all_row_by_id('delivery_boy', 'delivery_boy_id', $row->delivery_boy_id);
                                                         $name = $deliveryBoyData->name;
                                                         $phone = $deliveryBoyData->mobile;
                                                         $detail = $name . '<br>' . showWithPhoneNummberCountryCode($phone);
                                                     }

                                                     if ($row->status == 0) {
                                                         echo '<span class="badge badge-info p-1">Accepted</span><br>' .$detail. '<br>';
                                                     }
                                                     if ($row->status == 1) {
                                                         echo '<span class="badge badge-success p-1">Complete</span><br>' . $detail . '<br>';

                                                     }
                                                 }
                                             }else{
                                                 echo '<span class="badge badge-warning p-1">Not Accepted</span>';
                                             }
                                             ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo site_url('super_admin/customer_order_list/'.$view->customer_id)?>" class="btn btn-xs btn-success" target="_blank">Order list</a>
                                        </td>

                                    </tr>
                                <?php } } else { ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted"><i class="fas fa-shopping-cart fa-2x d-block mb-2"></i> No orders found.</td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            </div>
                            <?php if (!empty($pager)) { ?>
                                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                                    <div class="text-muted small mb-2">
                                        Showing page <?php echo $pager->getCurrentPage(); ?> of <?php echo $pager->getPageCount(); ?> (Total <?php echo number_format($pager->getTotal()); ?> orders)
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