
<div class="row">
    <div class="col-md-12">
        <h3> Store Name: <b><?= $name ?></b></h3>
        <span>
            <table class="table table-bordered table-striped bg-success " >
                <tr>
                    <td>Storage Inventory Prices:</td>
                    <td><?= showWithCurrencySymbol($purchasePrice) ?></td>

                    <td>Storage Inventory Quantity:</td>
                    <td><?= $quantity ?></td>
                </tr>
            </table>
        </span>
    </div>
</div>
<table id="example1" class="table table-striped projects">
    <thead>
    <tr>
        <th> # </th>
        <th>Name</th>
        <th>Product Category</th>
        <th>Quantity</th>
        <th>Purchase Price</th>
        <th>Selling Price</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=1; foreach ($product as $row){ ?>
        <tr>
            <td width="20"> <?= $i++;?> </td>
            <td><?= $row->name ?></td>
            <td><?= get_data_by_id('product_category', 'product_category', 'prod_cat_id', $row->prod_cat_id) ?></td>
            <td><?= $row->quantity ?></td>
            <td><?= showWithCurrencySymbol($row->purchase_price) ?></td>
            <td><?= showWithCurrencySymbol($row->selling_price) ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>