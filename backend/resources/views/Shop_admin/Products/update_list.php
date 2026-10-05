<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Products</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Products</li>
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
                <h3 class="card-title">Products price update</h3>
                <a href="<?= base_url('shop_admin/products'); ?>" class="btn btn-xs btn-danger w-25 float-right no-print">Back</a>
            </div>
            <div class="card-body p-3">
                <form action="<?= base_url('shop_admin/products_price_update_super_action')?>" method="post">
                    <div class="row mb-4">
                        <div class="col-md-12" id="message">
                            <?= isset(newSession()->message) ? newSession()->message :''; ?>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control input-sm" name="keyword" id="keyword" onchange="product_up_show(this.value)">
                                <option value="0">Please Select Category</option>
                                <?php foreach ($category as $row) { ?>
                                    <optgroup label="---------------------------------">
                                        <option value="<?= $row->prod_cat_id; ?>" id="head" style="font-weight: bolder; margin-left: -10px;"><?= $row->product_category; ?></option>
                                        <?= subCatSaleInOption($row->prod_cat_id); ?>
                                    </optgroup>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-8 " style="text-align: right;">
                            <button type="submit" class="btn btn-primary" >Super price update</button>
                        </div>
                    </div>
                    <table id="example2" class="table table-striped projects ">
                        <thead>
                        <tr>
                            <th>Check</th>
                            <th>Name</th>
                            <th class="hi-ime2">Image</th>
                            <th>Sale Price</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="product">
                        <?php foreach ($products_data as $products) { ?>
                            <tr >
                                <td>
                                    <?php if (!empty($products->demo_id)) { ?>
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" name="productId[]" id="checkBox_<?= $products->prod_id ?>" onclick="proPriceUpdateSuper(this.value,'<?= $products->prod_id ?>')" value="<?= $products->prod_id ?>" <?= ($products->show_global_price == '1')?'checked':'';?> >
                                        </div>
                                    <?php } ?>
                                </td>
                                <td><?= $products->name ?></td>
                                <td class="hi-im"><?= singleImage_by_productId($products->prod_id, '70'); ?></td>
                                <td id="val_<?= $products->prod_id ?>">
                                    <input type="text" class="form-control" id="price_<?= $products->prod_id ?>" value="<?= $products->selling_price ?>" <?= ($products->show_global_price == '1')?'readonly':'';?> >
                                    <p id="pp_<?= $products->prod_id ?>"><?php if ($products->purchase_price > $products->selling_price){ echo '<span style="color:red;">ক্রয় মূল্য থেকে বিক্রয়মূলক কম হয়ে গেছে।</span>';} ?></p>
                                </td>
                                <td id="proRow_<?= $products->prod_id ?>">
                                    <?php if ($products->show_global_price == '0'){ ?>
                                        <?php echo '<button class="btn btn-xs btn-warning" type="button" onclick="update_price(' . $products->prod_id . ')"  >Update</button>'; ?>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
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
    function product_up_show(id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/products_search_price_update') ?>",
            dataType: "text",
            data: {catId: id },
            success: function(data){
                $('#product').html(data);
                $(".dataTables_info").css("display", "none");
                $(".dataTables_paginate").css("display", "none");

            }
        });
    }

    function update_price(id){
        var price = $('#price_'+id).val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/products_price_update_action') ?>",
            dataType: "text",
            data: {pro_id: id, price: price,},
            success: function(msg){
                $('#message').fadeIn();
                $('#message').html('<div class="alert alert-success alert-dismissible" role="alert">Price Update Success </div>').delay(200).fadeOut('slow') ;
                $('#pp_'+id).html(msg);
            }
        });

    }
</script>
<?= $this->endSection() ?>
