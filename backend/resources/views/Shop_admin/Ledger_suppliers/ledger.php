<?php
$name = get_data_by_id('name', 'suppliers', 'supplier_id', $supplier_id);
$balance = get_data_by_id('balance', 'suppliers', 'supplier_id', $supplier_id);

?>
<div class="row">
    <div class="col-md-12">
        <h3> Supplier: <?= $name ?></h3>
        <table class="table table-bordered table-striped bg-success" >
            <tr>
                <td>Total Get:</td>
                <td> <?= showWithCurrencySymbol(get_total('ledger_suppliers', 'amount', 'Dr.', 'supplier_id', $supplier_id)) ?></td>

                <td>Total Pay:</td>
                <td> <?= showWithCurrencySymbol(get_total('ledger_suppliers', 'amount', 'Cr.', 'supplier_id', $supplier_id)) ?></td>

                <td>Due Balance:</td>
                <td> <?= showWithCurrencySymbol($balance) ?></td>
            </tr>
        </table>;

    </div>
</div>
<table id="example1" class="table table-striped projects">
    <thead>
    <tr>
        <th> # </th>
        <th>Date</th>
        <th>Particulars</th>
        <th>Memo</th>
        <th>Debit</th>
        <th>Credit</th>
        <th>Balance</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=1; foreach ($ledgerSupplier as $row){
        $particulars = ($row->particulars == NULL) ? "Pay due" : $row->particulars;
        $amountCr = ($row->trangaction_type != "Cr.") ? "---" : showWithCurrencySymbol($row->amount);
        $amountDr = ($row->trangaction_type != "Dr.") ? "---" : showWithCurrencySymbol($row->amount);
        $transId = ($row->trans_id == NULL) ? "---" : $row->trans_id;
        $purchaseId = ($row->purchase_id == NULL) ? 'TRNS_' . $row->trans_id  : 'PURS_' . $row->purchase_id;
        ?>
        <tr>
            <td width="20"> <?= $i++;?> </td>
            <td><?= $row->createdDtm ?></td>
            <td><?= $particulars ?></td>
            <td><?= $purchaseId ?></td>
            <td><?= $amountDr ?></td>
            <td><?= $amountCr ?></td>
            <td><?= showWithCurrencySymbol($row->rest_balance) ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>