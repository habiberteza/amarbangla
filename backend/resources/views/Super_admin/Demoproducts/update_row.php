

    <td><?php echo $result->id ?></td>
    <td><?php echo $result->name ?></td>

    <td><?php echo demo_singleImage_by_productId($result->id,'70','0','');?> </td>
    <td><?php echo get_data_by_id('product_category', 'demo_category', 'cat_id', $result->prod_cat_id) ?></td>
    <td>
        <p onclick="updateFunction('<?= $result->id ?>', 'purchase_price', '<?= $result->purchase_price ?>', 'pur_view_<?= $result->id ?>', 'pur_price_update_<?= $result->id ?>','update_row_<?= $result->id;?>')"><?php echo showWithCurrencySymbol($result->purchase_price) ?></p>
        <p id="pur_view_<?= $result->id;?>"></p>
    </td>
    <td>
        <p onclick="updateFunction('<?= $result->id ?>', 'selling_price', '<?= $result->selling_price ?>', 'sel_view_<?= $result->id ?>', 'sell_price_update_<?= $result->id ?>','update_row_<?= $result->id;?>')"><?php echo showWithCurrencySymbol($result->selling_price) ?></p>
        <p id="sel_view_<?= $result->id;?>"></p></td>

    <td>
        <a href="<?php echo site_url('super_admin/demo_product_update/' . $result->id) ?>" class="btn btn-xs btn-info ">Edit</a>
        <a href="<?php echo site_url('super_admin/demo_product_delete/' . $result->id) ?>" onclick="javasciprt: return confirm('Are You Sure ?')" class="btn btn-xs btn-danger">Delete</a>
    </td>
