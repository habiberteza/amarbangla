<?= $this->extend('Agent/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="min-height: 1604.44px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Invoice</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Invoice</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content" id="printDiv">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Main content -->
                    <div class="invoice p-3 mb-3">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <?php echo image_view('uploads/schools', '', 'amarBangla.jpg', 'no_image.jpg', '', '');?>
                                    <small class="float-right">Date: <?php echo invoiceDateFormat(get_data_by_id('createdDtm', 'invoice', 'invoice_id', $invoiceId)); ?></small>
                                </h4>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                <b>Invoice</b> Inv_<?php echo $invoiceId ?>
                                <p>Placed on <?php echo invoiceDateFormat(get_data_by_id('createdDtm', 'invoice', 'invoice_id', $invoiceId)); ?></p>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">

                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                <span class="float-right"><?php echo getinvoiceStatusNew($invoiceId); ?></span>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- Table row -->
                        <div class="row">
                            <?php

                            $package = invoice_id_by_package($invoiceId);
                            foreach ($package as $row) {
                                $product = package_id_by_product($row->package_id);
                                ?>
                                <div class="col-md-12 col-xs-12"
                                     style="border:1px solid #e4e0e0; margin-top: 10px; text-transform: capitalize; ">
                                    <h4 style="border-bottom:1px solid #e4e0e0; ">

                                        <small style="float: right;"><?php print deliverystatus3($row->package_id); ?></small>

                                        <small style="float: right; padding-right: 10px;">
                                            <?php echo getPackageStatus($row->package_id); ?>
                                        </small>
                                        <?php $agentId = get_data_by_id('agent_id', 'shops', 'sch_id', $row->sch_id); ?>
                                        <?php echo get_data_by_id('name', 'shops', 'sch_id', $row->sch_id); ?><br>
                                        <small><?php echo get_data_by_id('mobile', 'shops', 'sch_id', $row->sch_id); ?></small><br>
                                        <?php if(!empty($agentId)){?>
                                            <small class="no-print">Agent Name: <?php echo get_data_by_id('agent_name', 'agent', 'agent_id', $agentId); ?></small><br>
                                            <small class="no-print">Agent Phone: <?php echo get_data_by_id('mobile', 'agent', 'agent_id', $agentId); ?></small><br>
                                        <?php } ?>
                                        <small>delivery charge : <?php echo showWithCurrencySymbol($row->delivery_charge); ?></small>


                                    </h4>

                                    <?php //$product = package_id_by_product($row->package_id);
                                    foreach ($product as $pro) {
                                        $suplier_id = get_data_by_id('supplier_id', 'products', 'prod_id', $pro->prod_id);
                                        $supName = get_data_by_id('name', 'suppliers', 'supplier_id', $suplier_id);
                                        $supPhone = get_data_by_id('phone', 'suppliers', 'supplier_id', $suplier_id);
                                        $productPurchase = get_data_by_id('purchase_price', 'products', 'prod_id', $pro->prod_id);

                                        $productName = get_data_by_id('name', 'products', 'prod_id', $pro->prod_id);
                                        $proPrice = get_data_by_id('selling_price', 'products', 'prod_id', $pro->prod_id);
                                        //$proPrice =  get_data_by_id('selling_price','products','prod_id',$pro->prod_id);
                                        ?>
                                        <div class="row">
                                            <div class="col-md-2 col-xs-2">
                                                <?php echo singleImage_by_productId($pro->prod_id, 70, 0, 'check-out-img') ?>
                                            </div>
                                            <div class="col-md-10 col-xs-10">
                                                <p style="margin-top: 15px; margin-bottom: 0px;"><?php echo $productName ?><br>
                                                    <?php echo showWithCurrencySymbol($pro->price) ?><br>
                                                    x<?php echo $pro->quantity ?>
                                                </p>
                                                <small class="no-print">
                                                    Suplier: <?php echo $supName ?> <?php echo $supPhone ?><br>
                                                    Purchase Price: <?php echo showWithCurrencySymbol($productPurchase); ?>
                                                </small>
                                            </div>
                                        </div>
                                        <!-- <span style="float:right;">delevery</span> -->
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                        <!-- /.row -->

                        <div class="row">
                            <!-- accepted payments column -->
                            <div class="col-6">
                                <p class="lead">Payment Methods:</p>
                                <p><b>Cash on delivery</b></p>
                                <p>Customer delivery address</p>
                                <?php if (!empty($customerId)) { ?>
                                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;"><b>
                                            Name:</b> <?php echo get_data_by_id('customer_name', 'customers', 'customer_id', $customerId); ?>
                                        <br><b>Phone:</b>
                                        +880 <?php echo get_data_by_id('mobile', 'customers', 'customer_id', $customerId); ?>

                                        <?php
                                        $cus_global_address_id = get_data_by_id('global_address_id', 'invoice', 'invoice_id', $invoiceId);
                                        $address = global_address_id_by_address($cus_global_address_id);

                                        ?>

                                        <br><b>Division:</b> <?php echo divisionname($address->division); ?>
                                        <br><b>District:</b> <?php echo districtname($address->zila); ?>
                                        <br><b>Upazila:</b> <?php echo upazilaname($address->upazila); ?>
                                        <br><b>Pourashava/Union:</b> <?php echo pourashavaUnionName($address->pourashava); ?>
                                        <br><b>Ward:</b> <?php echo getwardname($address->ward); ?>
                                        <br><b>Home
                                            Address:</b> <?php echo get_data_by_id('address', 'invoice', 'invoice_id', $invoiceId); ?>
                                    </p>
                                <?php } ?>
                            </div>
                            <!-- /.col -->
                            <div class="col-6">
                                <p class="lead">Amount Due <?php echo invoiceDateFormat(get_data_by_id('createdDtm', 'invoice', 'invoice_id', $invoiceId)); ?></p>

                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <th style="width:50%">Subtotal:</th>
                                            <td><?php echo showWithCurrencySymbol(get_data_by_id('amount', 'invoice', 'invoice_id', $invoiceId)); ?></td>
                                        </tr>

                                        <tr>
                                            <th>Delivery charge:</th>
                                            <td><?php echo showWithCurrencySymbol(get_data_by_id('delivery_charge', 'invoice', 'invoice_id', $invoiceId)); ?>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td><?php echo showWithCurrencySymbol(get_data_by_id('final_amount', 'invoice', 'invoice_id', $invoiceId)); ?></td>
                                        </tr>
                                        <?php //}?>
                                        <?php

                                        $nagadPay = get_data_by_id('nagad_paid', 'invoice', 'invoice_id', $invoiceId);
                                        if ($nagadPay != 0) {
                                            echo '<tr>
		                <th>Cash Pay:</th>
		                <td>' . showWithCurrencySymbol($nagadPay) . '</td>
		              </tr>';
                                        }

                                        $bankPay = get_data_by_id('bank_paid', 'invoice', 'invoice_id', $invoiceId);
                                        if ($bankPay != 0) {
                                            echo '<tr>
		                <th>Bank Pay:</th>
		                <td>' . showWithCurrencySymbol($bankPay) . '</td>
		              </tr>';
                                        }

                                        $chaquePay = get_data_by_id('chaque_paid', 'invoice', 'invoice_id', $invoiceId);
                                        if ($chaquePay != 0) {
                                            echo '<tr>
		                <th>Cheque Pay:</th>
		                <td>' . showWithCurrencySymbol($chaquePay) . '</td>
		              </tr>';
                                        }

                                        ?>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- this row will not appear when printing -->
                        <div class="row no-print">
                            <div class="col-12">
                                <button onclick="printDiv()" class="btn btn-default float-right"><i class="fas fa-print"></i> Print</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.invoice -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= $this->endSection() ?>


<?= $this->section('java_script') ?>
<script>

</script>
<?= $this->endSection() ?>
