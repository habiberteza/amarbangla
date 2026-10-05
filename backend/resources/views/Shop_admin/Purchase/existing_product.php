<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Purchase existing product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Purchase product</li>
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
                <h3 class="card-title">Purchase existing product</h3>
            </div>
            <div class="card-body p-3">
                <form action="<?= base_url('shop_admin/purchase_existing_action') ?>" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <?= isset(newSession()->message) ? newSession()->message :''; ?>
                        </div>
                        <div class="col-md-12 mt-4 mb-5">
                            <table id="example1" class="table table-striped projects">
                                <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Purchase Price</th>
                                    <th>Category</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($product as $row){ ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="returnchecked[]" class="datatables" id="checkedProd" value="<?= $row->prod_id;?>" >
                                            <input type="hidden" name="prod_id[]" value="<?= $row->prod_id; ?>">
                                        </td>
                                        <td><?= $row->name?></td>
                                        <td><input type="number" class="quantity form-control" id="quantity" name="quantity[]" placeholder="Quantity" value="1" ></td>
                                        <td><input type="text" class="purchase_price form-control" id="searchColumn" name="purchase_price[]" value="<?= $row->purchase_price; ?>" readonly ></td>
                                        <td><?= get_data_by_id('product_category','product_category', 'prod_cat_id',$row->prod_cat_id); ?></td>
                                    </tr>
                                <?php } ?>

                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12 row" id="box_form">
                            <div class="col-md-8" >
                                <p class="lead">Payment Type:</p>
                                <img src="<?php print base_url(); ?>/assets/dist/img/credit/cash.jpeg" alt="Cash">
                                <img src="<?php print base_url(); ?>/assets/dist/img/credit/bank.png" alt="Bank">

                                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg
                                    dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                                </p>
                            </div>
                            <div class="col-md-4 row" >
                                <div class="col-md-6">
                                    <label for="int">Total Amount</label>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" name="totalPrice"  id="totalAmount" readonly >
                                </div>
                                <div class="col-md-6">
                                    <label for="int">Cash</label>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" onchange="checkShopsBalance(this.value)" class="cash form-control" name="cash"  id="cash" ><b id="Balance_valid"></b>
                                </div>
                                <div class="col-md-6">
                                    <label for="int">Bank</label>
                                </div>
                                <div class="form-group col-md-6">
                                    <select  class="form-control" name="bank_id" id="bank_id" >
                                        <option value="">Select Bank</option>
                                        <?= getTwoValueInOption('','bank_id','name','account_no','bank'); ?>
                                    </select><br>
                                    <input type="text" onchange="checkBankBalance(this.value)" class="bank form-control" name="bank" id="bank" >
                                    <b id="Bank_valid"></b>
                                </div>
                                <div class="col-md-6">
                                    <label for="int">Due</label>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" name="due" id="totalDueAmount" readonly >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="box_form">
                            <input type="hidden" name="purchase_id" value="<?= newSession()->purchaseId?>">
                            <input type="hidden" name="supplier_id" value="<?= newSession()->supplierId?>">

                            <button type="submit" class="btn btn-primary">Create</button>
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
    // These script is for product purchase Exixting Itame -- Start ---
    function calculateExixPrice() {
        var totalPrice = 0;
        // var n = $(":checkbox:checked").length;
        var n = $("input.datatables").length;
        var prod_id = $("input[name^='prod_id']");
        var qnt = $("input[name^='quantity']");
        var pur_price = $("input[name^='purchase_price']");

        for(i=0;i<n;i++){
            if($('input.datatables')[i].checked) {
                quantity =  qnt[i].value;
                purchase_price = pur_price[i].value;
                itemPrice = purchase_price * quantity;
                totalPrice = totalPrice + itemPrice;
            }
        }
        $("#totalAmount").val(totalPrice);
        $("#totalDueAmount").val(totalPrice);

    }

    $(document).on( 'input', '.datatables', function(){ calculateExixPrice(); } );
    $(document).on( 'input', '.quantity', function(){ calculateExixPrice(); } );
    $(document).on( 'input', '.purchase_price', function(){ calculateExixPrice(); } );

    function duetotal(){
        var sub ;
        var totalAmount = $('#totalAmount').val();
        var cash = $("#cash").val();
        var bank = $("#bank").val();
        sub = (totalAmount-cash)-bank;

        $("#totalDueAmount").val(sub);
    }
    $(document).on( 'input', '.cash', function(){ duetotal(); } );
    $(document).on( 'input', '.bank', function(){ duetotal(); } );
    // These script is for product purchase Exixting Itame -- End ---


    function checkShopsBalance(cash){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/purchase_check_shop_balance') ?>",
            dataType: "text",
            data: {cash: cash},
            success: function(msg){
                $('#Balance_valid').html(msg);
            }
        });
    }

    function checkBankBalance(amount){
        var bankId = $('#bank_id').val();
        if (bankId == 0) {
            $('#Bank_valid').html('<span style="color:red">Please Select Bank</span>');
            $("#bank").val('');
        }else{
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('shop_admin/purchase_check_bank_balance') ?>",
                dataType: "text",
                data: {balance: amount,bank_id: bankId},
                success: function(msg){
                    $('#Bank_valid').html(msg);
                }
            });
        }

    }

</script>
<?= $this->endSection() ?>
