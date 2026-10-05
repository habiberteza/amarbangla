
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