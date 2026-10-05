<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Get Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Get Product</li>
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
                <h3 class="card-title">Get Product</h3>
                <a href="<?= base_url('shop_admin/settings'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-12">
                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                    </div>
                </div>

                <div class="card card-primary card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link <?= (!empty($_GET['active']) && ($_GET['active'] == 'account'))?'active':'';?> <?= (empty($_GET['active']))?'active':'';?>" href="<?= base_url('shop_admin/get_product?active=account')?>" >Account Holder</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (!empty($_GET['active']) && ($_GET['active'] == 'suppliers'))?'active':'';?> " href="<?= base_url('shop_admin/get_product?active=suppliers')?>" >Suppliers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (!empty($_GET['active']) && ($_GET['active'] == 'bank'))?'active':'';?>" href="<?= base_url('shop_admin/get_product?active=bank')?>" >Bank</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link <?= (!empty($_GET['active']) && ($_GET['active'] == 'cash'))?'active':'';?>" href="<?= base_url('shop_admin/get_product?active=cash')?>" >Cash</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link <?= (!empty($_GET['active']) && ($_GET['active'] == 'product'))?'active':'';?>"   href="<?= base_url('shop_admin/get_product?active=product')?>"  >Product</a>
                            </li>


                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade <?= (!empty($_GET['active']) && ($_GET['active'] == 'account'))?'active show':'';?> <?= (empty($_GET['active']))?'active show':'';?> " id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="<?php echo base_url('shop_admin/get_product_account_holder'); ?>" method="post">
                                            <div class="form-group">
                                                <label for="int">Account Holder </label>
                                                <select class="form-control select2 " style=" width: 100%;"  name="loan_pro_id" required>
                                                    <option selected="selected"  value="">Please Select</option>
                                                    <?php echo getAllListInOption('loan_pro_id','loan_pro_id','name','loan_provider'); ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="particulars">Particulars </label>
                                                <textarea class="form-control" rows="3" name="particulars" id="particulars" required></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="enum">Transaction Type </label>
                                                <select class="form-control" name="trangaction_type" id="trangaction_type" required>
                                                    <option value="2">জমা (Dr.)</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="varchar">Amount </label>
                                                <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" value="" required />
                                            </div>

                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane fade <?= (!empty($_GET['active']) && ($_GET['active'] == 'suppliers'))?'active show':'';?>" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="<?php echo base_url('shop_admin/get_product_suppliers'); ?>" method="post" enctype="multipart/form-data">

                                            <div class="form-group">
                                                <label for="int">Suppliers </label>
                                                <select class="form-control select2 " style=" width: 100%;"   name="supplier_id" required>
                                                    <option selected="selected"  value="">Please Select</option>
                                                    <?php echo getAllListInOption('supplier_id','supplier_id','name','suppliers'); ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="particulars">Particulars </label>
                                                <textarea class="form-control" rows="3" name="particulars" id="particulars" placeholder="Particulars" required></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="enum">Transaction Type </label>
                                                <select class="form-control" name="trangaction_type" id="trangaction_type" required>
                                                    <option value="2">জমা (Dr.)</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="varchar">Amount</label>
                                                <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" value="" required />
                                            </div>



                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade <?= (!empty($_GET['active']) && ($_GET['active'] == 'bank'))?'active show':'';?>" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="<?php echo base_url('shop_admin/get_product_bank'); ?>" method="post">
                                            <div class="form-group">
                                                <label for="int">Bank </label>
                                                <select class="form-control select2 " style=" width: 100%;"  name="bank_id" >
                                                    <option selected="selected"  value="">Please Select</option>
                                                    <?php echo getTwoValueInOption('bank_id','bank_id','name','account_no','bank'); ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="particulars">Particulars </label>
                                                <textarea class="form-control" rows="3" name="particulars" id="particulars" placeholder="Particulars" required></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="enum">Transaction Type </label>
                                                <select class="form-control" name="trangaction_type"  id="trangaction_type" required>
                                                    <option value="2">জমা (Dr.)</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="varchar">Amount </label>
                                                <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" value="" required />
                                            </div>


                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade <?= (!empty($_GET['active']) && ($_GET['active'] == 'cash'))?'active show':'';?>" id="custom-tabs-four-vat" role="tabpanel" aria-labelledby="custom-tabs-four-vat-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="<?php echo base_url('shop_admin/get_product_cash'); ?>" method="post">
                                            <div class="form-group">
                                                <label for="particulars">Particulars </label>
                                                <textarea class="form-control" rows="3" name="particulars" id="particulars" placeholder="Particulars"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="enum">Transaction Type </label>
                                                <select class="form-control" name="trangaction_type" onchange="changepaymenttype(this.value)" id="trangaction_type">

                                                    <option value="2">জমা (Dr.)</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="varchar">Amount </label>
                                                <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" value="" required />
                                            </div>

                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade <?= (!empty($_GET['active']) && ($_GET['active'] == 'product'))?'active show':'';?>" id="custom-tabs-four-address" role="tabpanel" aria-labelledby="custom-tabs-four-address-tab">
                                <div class="row">

                                    <div class="form-group col-md-6">
                                        <label for="int">Product Filter</label>
                                        <input type="text" class="form-control" oninput="_productNameSearch(this.value)" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="int">Category Filter</label>
                                        <select class="form-control" style="text-transform:capitalize;"
                                                onchange="_prodSearch(this.value)">
                                            <option value="0">Please select</option>
                                            <?php foreach ($category as $cat) { ?>
                                                <optgroup label="---------------------------------">
                                                    <option value="<?php echo $cat->cat_id; ?>"
                                                            style="font-weight: bold; "><?php echo $cat->product_category; ?></option>
                                                    <?php echo subCatDemoOption($cat->cat_id) ?>
                                                </optgroup>
                                            <?php } ?>
                                        </select>
                                    </div>


                                    <div class="col-md-12 mt-5">
                                        <div id="product" class="row">

                                            <?php $i = 1; foreach ($product as $row) { ?>
                                                <div class="col-md-4 text-capitalize info-box border mt-2 ">
                                                    <form id="dataForm_<?php echo $i;?>" method="post" action="<?php echo site_url('shop_admin/get_product_update') ?>">
                                                        <div class="pro-bor">
                                                            <div style="width: 100%;float: left;">
                                                                <div style="width: 20%;float: left;">
                                                                    <?php 
                                                                    $json = (array) json_decode(html_entity_decode((string) ($row->picture ?? '')));
                                                                    $img = $json[0] ?? (reset($json) ?: '');
                                                                    ?>

                                                                    <?= image_view('uploads/demo_product_image', $row->id, '70_'.$img, 'noImage.jpg','', '')?>
                                                                </div>
                                                                <div style="width: 80%;float: left; padding-left: 20px;">
                                                                    <?php echo $row->name; ?><br>
                                                                    <span class="cat-css"><?php echo get_data_by_id('product_category', 'demo_category', 'cat_id', $row->prod_cat_id); ?></span>
                                                                </div>
                                                            </div>

                                                            <div style="width: 100%;float: left; padding: 2px;">
                                                                <div style="width: 50%;float: left;padding-right: 2px;">
                                                                    <input type="number" class="form-control input-si"
                                                                           name="purchase_price" value="<?php echo $row->purchase_price; ?>"
                                                                           placeholder="Purchase price" required>
                                                                </div>
                                                                <div style="width: 50%;float: left; padding-left: 2px;">
                                                                    <input type="number" class="form-control input-si"
                                                                           name="selling_price"
                                                                           placeholder="Selling price" value="<?php echo $row->selling_price; ?>" required>
                                                                </div>
                                                            </div>
                                                            <div style="width: 100%;float: left; padding: 2px;">
                                                                <input type="number" class="form-control input-si"
                                                                       name="qty" placeholder="Quantity" id="" value="<?php echo $row->quantity; ?>" required>

                                                                <input type="hidden" name="prod_cat_id"
                                                                       value="<?php echo $row->prod_cat_id; ?>">
                                                                <input type="hidden" name="name"
                                                                       value="<?php echo $row->name; ?>">
                                                                <input type="hidden" name="unit"
                                                                       value="<?php echo $row->unit; ?>">
                                                                <input type="hidden" name="size"
                                                                       value="<?php echo $row->size; ?>">
                                                                <input type="hidden" name="pro_id"
                                                                       value="<?php echo $row->id; ?>">
                                                            </div>
                                                            <div style="width: 100%;float: left; padding: 2px;">
                                                                <select class="form-control select2 select2-hidden-accessible input-si" style=" width: 100%;" name="supplier_id" required >
                                                                    <option selected="selected" value="">Please Select</option>
                                                                    <?php echo getAllListInOption('supplier_id', 'supplier_id', 'name', 'suppliers'); ?>
                                                                </select>
                                                            </div>

                                                            <div style="width: 100%;float: left; padding: 2px;">
                                                                <?php if (get_product_complete($row->name) == 1 ){ ?>
                                                                    <button type="submit" onclick="get_product('dataForm_<?php echo $i;?>','submit-btn_<?php echo $i;?>')" class=" btn-block class-btn " id="submit-btn_<?php echo $i;?>">Import Product</button>
                                                                <?php }else{ ?>
                                                                    <button  class=" btn-block class-btn "  disabled style="background-color: green;color:white;" >Complete</button>
                                                                <?php } ?>

                                                            </div>

                                                        </div>
                                                    </form>

                                                </div>
                                            <?php $i++; } ?>

                                        </div>

                                    </div>
                                    <div class="col-md-12">
                                        <?= $pager; ?>
                                    </div>

                                </div>


                            </div>



                        </div>
                    </div>
                    <!-- /.card -->
                </div>



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
    function get_product(formId,btn){
        var form = $('#' + formId);
        var url = $('#' + formId).attr('action');

        $('#' + formId).on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: url,
                type: 'post',
                data: form.serialize(),
                beforeSend: function () {
                    $('#' + btn).html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success: function (response) {
                    if (response == 1) {
                        $('#' + btn).html('Complete');
                        $('#' + btn).attr("disabled", true);
                        $('#' + btn).css('background-color', 'green');
                        $('#' + btn).css('color', 'white');
                        $('.select2-selection').css("border-color", "black");
                        $('.select2-selection').css("background-color", "");
                        $(form)[0].reset();
                    }else{
                        $('#' + btn).html('Something went wrong');
                        $('#' + btn).css('color', 'red');
                    }
                }
            });
        });
    }

    function _productNameSearch(id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/get_product_product_show_key_search') ?>",
            dataType: "text",
            data: {proId:id},
            success: function(data){
                $('#product').html(data);
                $('.pagination').hide();
                $('.select2').select2({ tags: true });
            }
        });
    }

    function _prodSearch(id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/get_product_product_show_by_category') ?>",
            dataType: "text",
            data: {cat_id:id},
            success: function(data){
                $('#product').html(data);
                $('.pagination').hide();
                $('.select2').select2({ tags: true });
            }
        });
    }
</script>
<?= $this->endSection() ?>
