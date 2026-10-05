<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Purchase product</h1>
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
                <h3 class="card-title">Purchase product</h3>
            </div>
            <div class="card-body p-3">
                <form action="<?= base_url('shop_admin/purchase_action') ?>" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <?= isset(newSession()->message) ? newSession()->message :''; ?>
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" class="form-control" name="purchase_id" value="<?= newSession()->purchaseId ; ?>" readonly >
                            <input type="hidden" class="form-control" name="supplier_id" value="<?= newSession()->supplierId ; ?>" readonly >
                        </div>
                        <div class="col-md-12 row">
                            <div class="form-group col-md-3">
                                <label for="int">Category </label>
                                <select class="form-control" onchange="showSubCategory(this.value)" name="category" id="category" >
                                    <option value="">Please Select</option>
                                    <?php echo getCatListInOption('prod_cat_id','prod_cat_id','product_category','product_category'); ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="int">Sub Category </label>
                                <select class="form-control"  name="sub_category" id="subCat"  >
                                    <option value="">Please Select</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="varchar">Name </label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Name"   >
                            </div>


                            <div class="form-group col-md-3">
                                <label for="int">Unit </label>
                                <select class="form-control" name="unit" >
                                    <?php echo selectOptions($selected = 1, unitArray()); ?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="int">Purchase Price</label>
                                <input type="text" class="form-control purchase_price" name="price" id="price" placeholder="Purchase Price" >
                            </div>
                            <div class="form-group col-md-3">
                                <label for="int">Selling Price </label>
                                <input type="text" class="form-control selling_price" name="selling_price" id="selling_price" placeholder="Selling Price" >
                            </div>
                            <div class="form-group col-md-3">
                                <label for="int">Quantity </label>
                                <input type="number" class="form-control quantity"  name="qty" placeholder="Quantity" value="1" >
                            </div>
                            <div class="form-group col-md-3 " style="margin-top: 30px;" >
                                <button onclick="addCart()" type="button" class="form-control btn btn-info btn-xs" >Add Cart</button>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <table id="example2" class="table table-striped projects">
                                <thead>
                                <tr>
                                    <th> # </th>
                                    <th>Name</th>
                                    <th>Unit</th>
                                    <th>Purchase Price</th>
                                    <th>Selling Price</th>
                                    <th>Quantity</th>
                                    <th>Sub Total</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1; foreach ($cart as $row){ ?>
                                    <tr>
                                        <td><input type="hidden" class="form-control" name="prod_cat_id[]" id="prod_cat_id[]"  value="<?= $row['cat_id'] ;?>"  /><?= $i++ ;?></td>
                                        <td><input type="hidden" class="form-control" name="name[]" id="name"  value="<?= $row['name'] ;?>"  /><?= $row['name'] ;?> </td>
                                        <td><input type="hidden" class="form-control" name="unit[]" id="unit"  value="<?= $row['unit'] ;?>"  /><?= showUnitName($row['unit']);?> </td>
                                        <td><input type="hidden" class="form-control purchase_price" name="purchase_price[]" id="purchase_price" value="<?= $row['price'] ;?>" /><?= showWithCurrencySymbol($row['price']) ;?></td>
                                        <td><input type="hidden" class="form-control selling_price" name="selling_price[]" id="selling_price"  value="<?= $row['salePrice'] ;?>" /><?= showWithCurrencySymbol($row['salePrice']) ;?></td>
                                        <td><input type="hidden" class="form-control quantity"  name="quantity[]" placeholder="Quantity" value="<?= $row['qty'] ;?>" /><?= $row['qty'] ;?></td>
                                        <td><input type="hidden" class="form-control"  name="total_price[]" value="<?= $row['subtotal'] ;?>" /><?= showWithCurrencySymbol($row['subtotal']) ;?></td>
                                        <td width="120px">
                                            <button class="btn btn-xs btn-danger" type="button" id="<?= $row['rowid']?>" onclick="remove_cart(this)">Cancel</button>
                                        </td>
                                    </tr>
                                <?php } ?>

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="8"><button style="float: right; margin-right: 40px;" onclick="clearCart()" class="btn btn-info btn-xs" type="button" >Clear All</button></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="col-md-12 row mt-4">
                            <div class="col-md-8" >
                                <p class="lead">Payment Type:</p>
                                <img src="<?php print base_url(); ?>/assets/dist/img/credit/cash.jpeg" alt="Cash">
                                <img src="<?php print base_url(); ?>/assets/dist/img/credit/bank.png" alt="Bank">

                                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg
                                    dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                                </p>
                            </div>
                            <div class="col-md-4" >
                                <div class="col-xs-6">
                                    <label for="int">Total Amount</label>
                                </div>
                                <div class="form-group col-xs-6">
                                    <input type="text" class="form-control" name="totalPrice"  id="totalPrice" value="<?= Cart()->total()?>" readonly >
                                </div>
                                <div class="col-xs-6">
                                    <label for="int">Cash</label>
                                </div>
                                <div class="form-group col-xs-6">
                                    <input type="text" onchange="checkShopsBalance(this.value)" class="cash form-control" name="cash"  id="cash" ><b id="Balance_valid"></b>
                                </div>
                                <div class="col-xs-6">
                                    <label for="int">Bank</label>
                                </div>
                                <div class="form-group col-xs-6">
                                    <select  class="form-control" name="bank_id" id="bank_id" >
                                        <option value="">Select Bank</option>
                                        <?php echo getTwoValueInOption('','bank_id','name','account_no','bank'); ?>
                                    </select><br>
                                    <input type="text" onchange="checkBankBalance(this.value)" class="bank form-control" name="bank" id="bank" >
                                    <b id="Bank_valid"></b>
                                </div>
                                <div class="col-xs-6">
                                    <label for="int">Due</label>
                                </div>
                                <div class="form-group col-xs-6">
                                    <input type="text" class="form-control" name="due" id="totaldue" readonly  value="<?= Cart()->total();?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
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
    function showSubCategory(id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/get_sub_category') ?>",
            dataType: "text",
            data: {category_id: id},
            success: function(data){
                $('#subCat').html(data);
            }
        });
    }

    function addCart(){
        var category = $('[name=category]').val();
        var subCatId = $('[name=sub_category]').val();
        var name = $('[name=name]').val();
        var unit = $('[name=unit]').val();
        var price = $('[name=price]').val();
        var salePrice = $('[name=selling_price]').val();
        var qty = $('[name=qty]').val();

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/purchase_add_to_cart') ?>",
            dataType: "text",
            data: {subCatId: subCatId,name:name,price:price,salePrice:salePrice,qty:qty,category:category,unit:unit},
            success: function(msg){
                location.reload();
            }
        });
    }

    function remove_cart(rowid){
        var rowid = $(rowid).attr("id");
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/purchase_remove_cart') ?>",
            dataType: "text",
            data: {id:rowid},
            success: function(msg){
                location.reload();
            }
        });
    }

    function clearCart(){
        $.ajax({
            type: "get",
            url: "<?php echo site_url('shop_admin/purchase_clear_cart') ?>",
            dataType: "text",
            success: function(msg){
                location.reload();
            }
        });
    }

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

    function totalDue(){
        var sub ;
        var totalPrice = $('#totalPrice').val();
        var cash = $("#cash").val();
        var bank = $("#bank").val();
        sub = (totalPrice-cash)-bank;
        $("#totaldue").val(sub);
        $("#totalAmountDue").val(sub);
    }
    $(document).on( 'input', '.cash', function(){ totalDue(); } );
    $(document).on( 'input', '.bank', function(){ totalDue(); } );

</script>
<?= $this->endSection() ?>
