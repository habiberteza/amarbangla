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
    <?php
        $invoiceData = get_all_row_by_id('invoice', 'invoice_id', $invoiceId);
    ?>
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
                                    <small class="float-right">Date: <?php echo invoiceDateFormat($invoiceData->createdDtm); ?></small>
                                </h4>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                <b>Invoice</b> Inv_<?php echo $invoiceId ?>
                                <p>Placed on <?php echo invoiceDateFormat($invoiceData->createdDtm); ?></p>
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
                                        <?php $shopData = get_all_row_by_id('shops', 'sch_id', $row->sch_id);?>
                                        <?php $agentId = $shopData->agent_id; ?>
                                        <?php echo $shopData->name; ?><br>
                                        <small><?php echo $shopData->mobile; ?></small><br>
                                        <?php if(!empty($agentId)){?>
                                            <?php $agentData = get_all_row_by_id('agent', 'agent_id', $agentId);?>
                                            <small class="no-print">Agent Name: <?php echo $agentData->agent_name; ?></small><br>
                                            <small class="no-print">Agent Phone: <?php echo $agentData->mobile; ?></small><br>
                                        <?php } ?>
                                        <small>delivery charge : <?php echo showWithCurrencySymbol($row->delivery_charge); ?></small>


                                    </h4>

                                    <?php $product = package_id_by_product($row->package_id);
                                        foreach ($product as $pro) {
                                            $productData = get_all_row_by_id('products', 'prod_id', $pro->prod_id);
                                            $productPurchase = $productData->purchase_price;
                                            $productName = $productData->name;
                                            $proPrice = $productData->selling_price;

                                            $supplierData = get_all_row_by_id('suppliers', 'supplier_id', $productData->supplier_id);
                                            $supName = $supplierData->name;
                                            $supPhone = $supplierData->phone;

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
                                                    Supplier: <?php echo $supName ?> <?php echo $supPhone ?><br>
                                                    Purchase Price: <?php echo showWithCurrencySymbol($productPurchase); ?>
                                                </small>
                                            </div>
                                        </div>
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
                                    <?php $customerData = get_all_row_by_id('customers', 'customer_id', $customerId);?>
                                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;"><b>
                                            Name:</b> <?php echo $customerData->customer_name; ?>
                                        <br><b>Phone:</b>
                                        +880 <?php echo $customerData->mobile; ?>

                                    <?php
                                        $cus_global_address_id = $invoiceData->global_address_id;
                                        $address = global_address_id_by_address($cus_global_address_id);
                                    ?>

                                        <br><b>Division:</b> <?php echo divisionname($address->division); ?>
                                        <br><b>District:</b> <?php echo districtname($address->zila); ?>
                                        <br><b>Upazila:</b> <?php echo upazilaname($address->upazila); ?>
                                        <br><b>Pourashava/Union:</b> <?php echo pourashavaUnionName($address->pourashava); ?>
                                        <br><b>Ward:</b> <?php echo getwardname($address->ward); ?>
                                        <br><b>Home
                                            Address:</b> <?php echo $invoiceData->address; ?>
                                    </p>
                                <?php } ?>
                            </div>
                            <!-- /.col -->
                            <div class="col-6">
                                <p class="lead">Amount Due <?php echo invoiceDateFormat($invoiceData->createdDtm); ?></p>

                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <th style="width:50%">Subtotal:</th>
                                            <td><?php echo showWithCurrencySymbol($invoiceData->amount); ?></td>
                                        </tr>

                                        <tr>
                                            <th>Delivery charge:</th>
                                            <td><?php echo showWithCurrencySymbol($invoiceData->delivery_charge); ?>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td><?php echo showWithCurrencySymbol($invoiceData->final_amount); ?></td>
                                        </tr>
                                        <?php //}?>
                                        <?php

                                        $nagadPay = $invoiceData->nagad_paid;
                                        if ($nagadPay != 0) {
                                            echo '<tr>
		                <th>Cash Pay:</th>
		                <td>' . showWithCurrencySymbol($nagadPay) . '</td>
		              </tr>';
                                        }

                                        $bankPay = $invoiceData->bank_paid;
                                        if ($bankPay != 0) {
                                            echo '<tr>
		                <th>Bank Pay:</th>
		                <td>' . showWithCurrencySymbol($bankPay) . '</td>
		              </tr>';
                                        }

                                        $chaquePay = $invoiceData->chaque_paid;
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