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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Invoice form</h3>
            </div>
            <div class="card-body p-3">
                <form action="<?= base_url('shop_admin/invoice_package_create_action')?>" method="post">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-bordered table-striped" >
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=0; $j=0; $k=0; $l=0; $m = 0; $n = 0; foreach ($invoice_data as $row) { ?>
                                    <tr>

                                        <td>
                                            <?php echo get_data_by_id('name','products','prod_id',$row->prod_id);?>
                                            <input type="hidden" class="form-control " name="productId[]"  value="<?php echo $row->prod_id ;?>" >
                                        </td>
                                        <td width="80">
                                            <?php echo$row->quantity ;?>
                                            <input type="hidden" class="form-control " name="qty[]" value="<?php echo $row->quantity;?>" >
                                        </td>
                                        <td width="100">
                                            <?php echo showWithCurrencySymbol($row->price) ;?>
                                            <input type="hidden" class="form-control upprice" name="price[]" value="<?php echo $row->price ;?>" >


                                            <input type="hidden" readonly class="form-control subtotal" name="subtotal[]" id="subt_<?php print $m++; ?>" value="<?php echo $row->total_price ?>">
                                            <input type="hidden"  name="suballtotal[]" id="subtl2_<?php print $k++; ?>" value="<?php echo $row->total_price ;?>" >
                                            <span id="subtl_<?php print $l++; ?>">
                                                <span id="subtl_<?php print $j++; ?>">
                                                 <?php //echo number_format($row['subtotal']) ;?>
                                                </span>
                                            </span>

                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4 row">
                            <div class="col-md-12" style="border:1px dashed #D0D3D8 ;  padding: 10px;">
                                <label>Customer</label>
                                <?php $customerId = get_data_by_id('customer_id','invoice','invoice_id',$invoiceId)  ?>
                                <input type="hidden" class="form-control" name="customer_id" id="customer_id" placeholder="Name" value="<?php echo $customerId  ?>"  />
                                <input type="text" class="form-control" name="name" readonly placeholder="Name" value="<?php echo get_data_by_id('customer_name','customers','customer_id',$customerId)  ?>"  />
                            </div>

                            <div class="col-md-12" style="padding:5px;">
                                <label>Delivery Charge</label>
                                <input type="text" class="form-control"  value="<?php echo $delCharge ?>">

                            </div>

                            <div class="col-md-12" style="padding:5px;">
                                <label>Grand Total</label>
                                <input type="hidden" class="form-control" name="grandtotal2" readonly id="grandtotal2" value="<?php echo $total ?>">

                                <input type="text" class="form-control" name="grandtotal" readonly id="grandtotal" value="<?php echo $total ?>">

                            </div>

                            <div class="col-md-12 row" style=" padding:5px;">
                                <label>Payment</label>
                                <div class="col-md-12" style="border:1px dashed #D0D3D8 ; padding:5px;">
                                    <label>Cash</label>
                                    <input type="text" class="form-control nagod" name="nagod" id="nagod" placeholder="Input Cash Amount">
                                </div>

                                <div class="col-md-6" style="border:1px dashed #D0D3D8 ; padding:5px;">
                                    <label>Bank</label>
                                    <select  class="form-control" name="bank_id" id="bank_id" >
                                        <option value="">Select Bank</option>
                                        <?php echo getTwoValueInOption('bank_id','bank_id','name','account_no','bank'); ?>
                                    </select>
                                </div>
                                <div class="col-md-6" style="border:1px dashed #D0D3D8 ; padding:5px;">
                                    <label>Bank Amount</label>
                                    <input type="text" onclick="checkBankId()" class="form-control bankAmount" name="bankAmount" id="bankAmount"  placeholder="input Bank Amount">
                                    <b id="Bank_valid"></b>
                                </div>

                                <input type="hidden" onchange="cheque()" class="form-control chequeAmount" name="chequeAmount" id="chequeAmount" placeholder="Input Cheque Amount ">


                                <div class="col-md-6" style="border:1px dashed #D0D3D8 ;padding:5px;">
                                    <label>Total Amount </label>
                                    <input type="text" class="form-control " name="grandtotallast" readonly id="grandtotallast" value="<?php echo $total?>">

                                </div>
                                <div class="col-md-6" style="border:1px dashed #D0D3D8; padding:5px;">
                                    <label>Total Due</label>
                                    <input type="text" class="form-control" name="grandtotaldue" readonly id="grandtotaldue" value="<?php echo $total?>">
                                </div>

                                <div class="col-md-12 mt-3" >

                                    <button style="float: right;" id="btn" type="submit" class="btn btn-primary">Create</button>
                                    <b id="mess"></b>
                                </div>
                            </div>
                            <input type="hidden" class="form-control" name="package_id" id="package_id" placeholder="Name" value="<?php echo $packageId  ?>"  />



                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= $this->endSection() ?>


<?= $this->section('java_script') ?>
<script>
    function checkBankId(){
        var bankId = $('#bank_id').val();

        if (bankId == 0) {
            $('#Bank_valid').html('<span style="color:red">Please Select Bank</span>');
            $("#bankAmount").val('');
        }else{
            $('#Bank_valid').html('<span style="color:#238A09">Bank Selected </span>');
        }
    }

    function cheque(){
        var chequeNo = $('#chequeNo').val();
        if (chequeNo == 0) {
            $('#cheque_valid').html('<span style="color:red">Please input Cheque no..</span>');
            $("#chequeAmount").val('');
        }else{
            $('#cheque_valid').html('<span style="color:#238A09">Cheque no inputed </span>');
        }
    }

</script>
<?= $this->endSection() ?>
