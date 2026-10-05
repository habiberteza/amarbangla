<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Invoice</h1>
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
                <div class="col-12">


                    <!-- Main content -->
                    <div class="invoice p-3 mb-3">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <img src="<?= base_url('uploads/schools/logo-amar.jpg'); ?>" class="" width="200" alt="<?= $shopsName; ?>">
                                    <small class="float-right">Date: <?= invoiceDateFormat($createdDtm);?></small>
                                </h4>
                            </div>
                            <!-- /.col -->
                        </div>


                        <!-- Table row -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=0;
                                    foreach ($invoiceItem as $row) { ?>
                                        <tr>
                                            <td><?php echo ++$i;?></td>
                                            <td><?php echo singleImage_by_productId($row->prod_id, 70, 0, 'check-out-img') ?></td>
                                            <td><?php
                                                $catId =  get_data_by_id('prod_cat_id','products','prod_id',$row->prod_id);
                                                $parent_pro_cat = get_data_by_id('parent_pro_cat','product_category','prod_cat_id',$catId);
                                                $category = get_data_by_id('product_category','product_category','prod_cat_id',$parent_pro_cat);
                                                $subCategory = get_data_by_id('product_category','product_category','prod_cat_id',$catId);
                                                $productName =  get_data_by_id('name','products','prod_id',$row->prod_id);

                                                echo $productName.'<br> <small>('.$category.' > '.$subCategory .')</small>';
                                                ?></td>
                                            <td><?php echo showWithCurrencySymbol($row->price);?></td>
                                            <td><?php echo $row->quantity;?></td>
                                            <td><?php echo showWithCurrencySymbol($row->total_price);?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
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
                                            Name:</b> <?php echo get_data_by_id('customer_name','customers','customer_id',$customerId); ?>
                                        <br><b>Phone:</b> +880 <?php echo get_data_by_id('mobile','customers','customer_id',$customerId); ?>

                                        <?php
                                        $inv_global_address_id = get_data_by_id('global_address_id','invoice','invoice_id',$invoiceId);
                                        $address = global_address_id_by_address($inv_global_address_id);

                                        ?>

                                        <br><b>Division:</b> <?php echo divisionname($address->division); ?>
                                        <br><b>District:</b> <?php echo districtname($address->zila); ?>
                                        <br><b>Upazila:</b> <?php echo upazilaname($address->upazila); ?>
                                        <br><b>Pourashava/Union:</b> <?php echo pourashavaUnionName($address->pourashava); ?>
                                        <br><b>Ward:</b> <?php echo getwardname($address->ward); ?>
                                        <br><b>Home Address:</b> <?php echo get_data_by_id('address','invoice','invoice_id',$invoiceId); ?>
                                    </p>
                                <?php } ?>
                            </div>
                            <!-- /.col -->
                            <div class="col-6">
                                <div class="table-responsive" >
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <th style="width:50%">Subtotal:</th>
                                            <td><?php echo showWithCurrencySymbol($subtotal);?></td>
                                        </tr>

                                        <tr>
                                            <?php $delivery_type = get_data_by_id('delivery_type','invoice','invoice_id',$invoiceId);?>
                                            <th>Delivery charge: <br> <small>( <?php echo $delivery_type; ?> )</th>
                                            <td><input type="hidden" name="package_id" id="package_id" value="<?php echo $package ;?>" ><input type="text" name="delCharge" id="delCharge" value="<?php echo $delCharge?>" >

                                                <?php if ($status == 0) { ?>
                                                    <button type="button" id="upbtn" onclick="delivUpdate()" class="btn btn-xs btn-success" style="height: 27px;margin-bottom: 5px;" ><i class="fas fa-sync-alt"></i></button>
                                                <?php }?>



                                                <br><b id="succCharge" style="color: green;" ></b></td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td><?php echo showWithCurrencySymbol($finalAmount);?></td>
                                        </tr>

                                        <tr>
                                            <th>Total Due:</th>
                                            <td><?php echo showWithCurrencySymbol($dueAmount);?></td>
                                        </tr>
                                        </tbody></table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- this row will not appear when printing -->
                        <div class="row no-print">
                            <div class="col-12">
<!--                                <button type="button" onclick="print_page()" class="btn btn-primary float-right ml-3"><i class="fas fa-print"></i> Print</button>-->

                                <a href="<?= base_url('shop_admin/invoice_a4_print/'.$package)?>"  class="btn btn-primary float-right ml-3"><i class="fas fa-print"></i> A4-Print</a>
                                <a href="<?= base_url('shop_admin/invoice_pos_print/'.$package)?>"  class="btn btn-primary float-right ml-3"><i class="fas fa-print"></i> POS-Print</a>


                                <?php if ($status == 0){?>
                                <a href="<?= base_url('shop_admin/invoice_package_action/' . $package); ?>" class="btn btn-warning float-right"><i class="fas fa-edit"></i> Paid </a>
                                <?php } ?>
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
    function print_page(){
        window.print();
    }
</script>
<?= $this->endSection() ?>
