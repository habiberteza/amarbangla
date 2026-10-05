<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 row">
                    <div class="col-md-4">
                        <h1 class="m-0">Invoice</h1>
                    </div>
                    <div class="col-md-8">
                        <input type="checkbox" id="checkbox_1" name="office" value="1" onclick="show_hide_row('office')">
                        <label for="checkbox_1"> Office copy</label>

                        <input type="checkbox" id="checkbox_2" name="customer" value="1" onclick="show_hide_row('customer')" checked>
                        <label for="checkbox_2"> Customer copy</label>

                        <input type="checkbox" id="checkbox_3" name="store" value="1" onclick="show_hide_row('store')">
                        <label for="checkbox_3"> Store copy</label>
                    </div>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Invoice</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                    <!-- Main content -->
                    <div class=" invoice p-3 mb-3 ">
                        <!-- Office Copy -->
                        <div class="row " style="width: 275px; display: none;" id="office" >
                            <div class="col-md-12" >

                                <!-- title row -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="page-header">
                                            <small class="float-right" style="font-size: 10px;" ><b>Date:</b> <?php echo invoiceDateFormat($createdDtm);?><br><b>Phone:</b> +880 <?php echo $shopsPhone; ?><br><b>Office copy</b></small>
                                            <img src="<?= base_url('uploads/schools/logo-amar.jpg'); ?>" class="" width="70" alt="<?php print $shopsName; ?>">
                                        </div>

                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- info row -->
                                <div class="row invoice-info" style="border-top: 1px dotted; font-size: 10px;">
                                    <div class="col-sm-4 invoice-col">
                                        From
                                        <address >
                                            <strong><?php print $shopsName; ?></strong>

                                        </address>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4 invoice-col">
                                        To
                                        <address>
                                            <?php ?>
                                            <strong><?php
                                                $customerId = get_data_by_id('customer_id','invoice','invoice_id',$invoiceId);
                                                echo ($customerId == 0 ) ? get_data_by_id('customer_name','invoice','invoice_id',$invoiceId) : get_data_by_id('customer_name','customers','customer_id',$customerId);

                                                ?></strong>
                                        </address>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4 invoice-col">
                                        <b>Invoice Id :</b> Inv_<?php echo $invoiceId?>
                                    </div>
                                </div>
                                <!-- /.row -->

                                <!-- Table row -->
                                <div class="row">
                                    <div class="col-md-12" style="border-top: 1px dotted;">
                                        <table style="width: 100%; text-transform: capitalize;">
                                            <thead>
                                            <tr style="font-size: 10px;">
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i=0;
                                            foreach ($invoiceItem as $row) { ?>
                                                <tr style="font-size: 9px;">
                                                    <td><?php
                                                        $catId =  get_data_by_id('prod_cat_id','products','prod_id',$row->prod_id);
                                                        $parent_pro_cat = get_data_by_id('parent_pro_cat','product_category','prod_cat_id',$catId);
                                                        $category = get_data_by_id('product_category','product_category','prod_cat_id',$parent_pro_cat);
                                                        $subCategory = get_data_by_id('product_category','product_category','prod_cat_id',$catId);
                                                        $productName =  get_data_by_id('name','products','prod_id',$row->prod_id);

                                                        echo $productName.'<br> <small>('.$category.' > '.$subCategory .')</small>';
                                                        ?></td>
                                                    <td><?php echo showWithCurrencySymbolInvoice($row->price);?></td>
                                                    <td><?php echo $row->quantity;?></td>
                                                    <td><?php echo showWithCurrencySymbolInvoice($row->total_price);?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <div class="row">

                                    <!-- /.col -->
                                    <div class="col-md-12" style="border-top: 1px dotted; font-size: 10px;">

                                        <table style="width: 100%;  ">
                                            <tbody>
                                            <tr>
                                                <th style="width:50%">Subtotal:</th>
                                                <td><?php echo showWithCurrencySymbolInvoice($subtotal);?></td>
                                            </tr>


                                            <tr>
                                                <th>Delivery charge</th>
                                                <td><?php echo showWithCurrencySymbolInvoice($delCharge);?> </td>
                                            </tr>
                                            <tr>
                                                <th>Total:</th>
                                                <td><?php echo showWithCurrencySymbolInvoice($finalAmount);?></td>
                                            </tr>

                                            <tr>
                                                <th>Total Due:</th>
                                                <td><?php echo showWithCurrencySymbolInvoice($dueAmount);?></td>
                                            </tr>
                                            </tbody></table>
                                    </div>


                                    <div class="col-md-12" style="border-top: 1px dotted; font-size: 10px;">
                                        <?php if (!empty($customerId)) { ?>

                                            <p><b>Customer delivery address</b><br>
                                                <b>Name:</b> <?php echo get_data_by_id('customer_name','customers','customer_id',$customerId); ?><br>
                                                <b>Phone:</b> +880 <?php echo get_data_by_id('mobile','customers','customer_id',$customerId); ?><br>

                                                <?php
                                                $inv_global_address_id = get_data_by_id('global_address_id','invoice','invoice_id',$invoiceId);
                                                $address = global_address_id_by_address($inv_global_address_id);

                                                ?>
                                                <!-- <br><b>Division:</b> <?php //echo divisionname(get_data_by_id('division','cus_address','customer_id',$customerId)); ?> -->
                                                <b>District:</b> <?php echo districtname($address->zila); ?>
                                                <b>Upazila:</b> <?php echo upazilaname($address->upazila); ?> <br>
                                                <b>Pourashava/Union:</b> <?php echo pourashavaUnionName($address->pourashava); ?>
                                                <br><b>Ward:</b> <?php echo getwardname($address->ward); ?>
                                                <b>Home Address:</b> <?php echo get_data_by_id('address','invoice','invoice_id',$invoiceId); ?><br>
                                                <b>Status:</b> paid<br>
                                                <b>Signature:</b> ...............................
                                            </p>
                                        <?php } ?>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                            </div>
                        </div>

                        <!-- Customer Copy -->
                        <div class="row" style="width: 275px; " id="customer">
                            <div class="col-md-12" >

                                <!-- title row -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="page-header">
                                            <small class="float-right" style="font-size: 10px;" ><b>Date:</b> <?php echo invoiceDateFormat($createdDtm);?><br><b>Phone:</b> +880 <?php echo $shopsPhone; ?><br><b>Customer copy</b></small>
                                            <img src="<?= base_url('uploads/schools/logo-amar.jpg'); ?>" class="" width="70" alt="<?php print $shopsName; ?>">


                                        </div>

                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- info row -->
                                <div class="row invoice-info" style="border-top: 1px dotted; font-size: 10px;">
                                    <div class="col-sm-4 invoice-col">
                                        From
                                        <address >
                                            <strong><?php print $shopsName; ?></strong>

                                        </address>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4 invoice-col">
                                        To
                                        <address>
                                            <?php ?>
                                            <strong><?php
                                                $customerId = get_data_by_id('customer_id','invoice','invoice_id',$invoiceId);
                                                echo ($customerId == 0 ) ? get_data_by_id('customer_name','invoice','invoice_id',$invoiceId) : get_data_by_id('customer_name','customers','customer_id',$customerId);

                                                ?></strong>
                                        </address>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4 invoice-col">
                                        <b>Invoice Id :</b> Inv_<?php echo $invoiceId?>
                                    </div>
                                </div>
                                <!-- /.row -->

                                <!-- Table row -->
                                <div class="row">
                                    <div class="col-md-12" style="border-top: 1px dotted;">
                                        <table style="width: 100%; text-transform: capitalize;">
                                            <thead>
                                            <tr style="font-size: 10px;">
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i=0;
                                            foreach ($invoiceItem as $row) { ?>
                                                <tr style="font-size: 9px;">
                                                    <td><?php
                                                        $catId =  get_data_by_id('prod_cat_id','products','prod_id',$row->prod_id);
                                                        $parent_pro_cat = get_data_by_id('parent_pro_cat','product_category','prod_cat_id',$catId);
                                                        $category = get_data_by_id('product_category','product_category','prod_cat_id',$parent_pro_cat);
                                                        $subCategory = get_data_by_id('product_category','product_category','prod_cat_id',$catId);
                                                        $productName =  get_data_by_id('name','products','prod_id',$row->prod_id);

                                                        echo $productName.'<br> <small>('.$category.' > '.$subCategory .')</small>';
                                                        ?></td>
                                                    <td><?php echo showWithCurrencySymbolInvoice($row->price);?></td>
                                                    <td><?php echo $row->quantity;?></td>
                                                    <td><?php echo showWithCurrencySymbolInvoice($row->total_price);?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <div class="row">

                                    <!-- /.col -->
                                    <div class="col-md-12" style="border-top: 1px dotted; font-size: 10px;">

                                        <table style="width: 100%;  ">
                                            <tbody>
                                            <tr>
                                                <th style="width:50%">Subtotal:</th>
                                                <td><?php echo showWithCurrencySymbolInvoice($subtotal);?></td>
                                            </tr>

                                            <tr>
                                                <th>Delivery charge</th>
                                                <td><?php echo showWithCurrencySymbolInvoice($delCharge);?> </td>
                                            </tr>
                                            <tr>
                                                <th>Total:</th>
                                                <td><?php echo showWithCurrencySymbolInvoice($finalAmount);?></td>
                                            </tr>

                                            </tbody></table>
                                    </div>


                                    <div class="col-md-12" style="border-top: 1px dotted; font-size: 10px;">
                                        <?php if (!empty($customerId)) { ?>

                                            <p><b>Customer delivery address</b><br>
                                                <b>Name:</b> <?php echo get_data_by_id('customer_name','customers','customer_id',$customerId); ?><br>
                                                <b>Phone:</b> +880 <?php echo get_data_by_id('mobile','customers','customer_id',$customerId); ?><br>

                                                <?php
                                                $inv_global_address_id = get_data_by_id('global_address_id','invoice','invoice_id',$invoiceId);
                                                $address = global_address_id_by_address($inv_global_address_id);
                                                ?>

                                                <b>District:</b> <?php echo districtname($address->zila); ?>
                                                <b>Upazila:</b> <?php echo upazilaname($address->upazila); ?> <br>
                                                <b>Pourashava/Union:</b> <?php echo pourashavaUnionName($address->pourashava); ?>
                                                <br><b>Ward:</b> <?php echo getwardname($address->ward); ?>
                                                <b>Home Address:</b> <?php echo get_data_by_id('address','invoice','invoice_id',$invoiceId); ?><br>
                                                <b>Status:</b> paid<br>
                                            </p>
                                        <?php } ?>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                            </div>
                        </div>
                        <!-- Store Copy -->
                        <div class="row" style="width: 275px; display: none;" id="store">
                            <div class="col-md-12" >

                                <!-- title row -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="page-header">
                                            <small class="float-right" style="font-size: 10px;" ><b>Date:</b> <?php echo invoiceDateFormat($createdDtm);?><br><b>Phone:</b> +880 <?php echo $shopsPhone; ?><br><b>Store copy</b></small>
                                            <img src="<?= base_url('uploads/schools/logo-amar.jpg'); ?>" class="" width="70" alt="<?php print $shopsName; ?>">


                                        </div>

                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- info row -->
                                <div class="row invoice-info" style="border-top: 1px dotted; font-size: 10px;">
                                    <div class="col-sm-4 invoice-col">
                                        From
                                        <address >
                                            <strong><?php print $shopsName; ?></strong>

                                        </address>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4 invoice-col">
                                        To
                                        <address>
                                            <?php ?>
                                            <strong><?php
                                                $customerId = get_data_by_id('customer_id','invoice','invoice_id',$invoiceId);
                                                echo ($customerId == 0 ) ? get_data_by_id('customer_name','invoice','invoice_id',$invoiceId) : get_data_by_id('customer_name','customers','customer_id',$customerId);

                                                ?></strong>
                                        </address>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4 invoice-col">
                                        <b>Invoice Id :</b> Inv_<?php echo $invoiceId?>
                                    </div>
                                </div>
                                <!-- /.row -->

                                <!-- Table row -->
                                <div class="row">
                                    <div class="col-md-12" style="border-top: 1px dotted;">
                                        <table style="width: 100%; text-transform: capitalize;">
                                            <thead>
                                            <tr style="font-size: 10px;">
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i=0;
                                            foreach ($invoiceItem as $row) { ?>
                                                <tr style="font-size: 9px;">
                                                    <td><?php
                                                        $catId =  get_data_by_id('prod_cat_id','products','prod_id',$row->prod_id);
                                                        $parent_pro_cat = get_data_by_id('parent_pro_cat','product_category','prod_cat_id',$catId);
                                                        $category = get_data_by_id('product_category','product_category','prod_cat_id',$parent_pro_cat);
                                                        $subCategory = get_data_by_id('product_category','product_category','prod_cat_id',$catId);
                                                        $productName =  get_data_by_id('name','products','prod_id',$row->prod_id);

                                                        echo $productName.'<br> <small>('.$category.' > '.$subCategory .')</small>';
                                                        ?></td>
                                                    <td><?php echo showWithCurrencySymbolInvoice($row->price);?></td>
                                                    <td><?php echo $row->quantity;?></td>
                                                    <td><?php echo showWithCurrencySymbolInvoice($row->total_price);?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <div class="row">

                                    <!-- /.col -->
                                    <div class="col-md-12" style="border-top: 1px dotted; font-size: 10px;">

                                        <table style="width: 100%;  ">
                                            <tbody>
                                            <tr>

                                                <th style="width:50%">Total:</th>
                                                <td><?php echo showWithCurrencySymbolInvoice(get_data_by_id('amount','invoice','invoice_id',$invoiceId));?></td>
                                            </tr>
                                            <?php

                                            $nagadPay = get_data_by_id('nagad_paid','invoice','invoice_id',$invoiceId);
                                            if ($nagadPay != 0) {
                                                echo '<tr>
				                <th>Cash Pay:</th>
				                <td>'.showWithCurrencySymbolInvoice($nagadPay).'</td>
				              </tr>';
                                            }

                                            $bankPay = get_data_by_id('bank_paid','invoice','invoice_id',$invoiceId);
                                            if ($bankPay != 0) {
                                                echo '<tr>
				                <th>Bank Pay:</th>
				                <td>'.showWithCurrencySymbolInvoice($bankPay).'</td>
				              </tr>';
                                            }

                                            $chaquePay = get_data_by_id('chaque_paid','invoice','invoice_id',$invoiceId);
                                            if ($chaquePay != 0) {
                                                echo '<tr>
				                <th>Cheque Pay:</th>
				                <td>'.showWithCurrencySymbolInvoice($chaquePay).'</td>
				              </tr>';
                                            }

                                            ?>
                                            </tbody></table>
                                    </div>


                                    <div class="col-md-12" style="border-top: 1px dotted; font-size: 10px;"></div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                            </div>
                        </div>

                    </div>
                    <!-- /.invoice -->
                <div class="col-12">
                    <div class="row no-print">
                        <div class="col-12">
                            <button type="button" onclick="print_page()" class="btn btn-primary float-right ml-3"><i class="fas fa-print"></i> Print</button>
                            <a href="<?= base_url('shop_admin/invoice_view/'.$package)?>" class="btn btn-danger float-right ml-3"> Back</a>
                        </div>
                    </div>
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
    function print_page(){
        window.print();
    }

    function show_hide_row(label){
        if ($('input[name="' + label + '"]').is(':checked')) {
            $("#" + label).css('display', 'block');
        } else {
            $("#" + label).css('display', 'none');
        }
    }
</script>
<?= $this->endSection() ?>
