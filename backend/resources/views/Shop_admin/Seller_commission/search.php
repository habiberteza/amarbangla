<table class="table table-bordered table-striped showDatafirst"  id="example1"  >
    <thead>
    <tr>
        <th>No</th>
        <th>InvoiceId</th>
        <th>Seller</th>
        <th>Commission</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody><?php $i=''; foreach ($seller as $row) { ?>
        <tr>
            <td width="10px"><?= ++$i ?></td>
            <td><?= $row->invoice_id ?></td>
            <td><?= get_data_by_id('name','seller','seller_id',$row->seller_id) ?></td>
            <td><?= showWithCurrencySymbol($row->commission) ?></td>
            <td><?php if ($row->com_status == 0) { ?>
                    <?php echo '<span class="bg-warning p-1">pending</span>'; ?>
                <?php }else{ ?>
                    <?php echo '<span class="bg-success p-1">paid</span>'; ?>
                <?php } ?></td>
            <td width="180px">
                <?php
                if ($row->com_status == 0) {
                    echo anchor(site_url('shop_admin/seller_commission_pay/'.$row->package_id.'/'.$row->seller_id),'Pay Invoice', 'class="btn btn-xs btn-info" onclick="javasciprt: return confirm(\'Are You Sure Pay This ?\')"');
                }
                ?>
            </td>
        </tr>
        <?php
    }
    ?>
    </tbody>

</table>