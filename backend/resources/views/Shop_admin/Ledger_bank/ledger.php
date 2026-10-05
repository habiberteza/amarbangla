<?php
$name = get_data_by_id('name', 'bank', 'bank_id', $bank_id);
$account = get_data_by_id('account_no', 'bank', 'bank_id', $bank_id);
$balance = get_data_by_id('balance', 'bank', 'bank_id', $bank_id);

?>
<div class="row">
    <div class="col-md-12">
        <h3> Bank: <?= $name . ' -- ' . $account ?></h3>
        <span>
            <table class="table table-bordered table-striped bg-success " >
                <tr>
                    <td>Total Deposit:</td>
                    <td><?= showWithCurrencySymbol(get_total('ledger_bank', 'amount', 'Dr.', 'bank_id', $bank_id)) ?></td>

                    <td>Total Withdraw:</td>
                    <td><?= showWithCurrencySymbol(get_total('ledger_bank', 'amount', 'Cr.', 'bank_id', $bank_id)) ?></td>

                    <td>Balance:</td>
                    <td><?= showWithCurrencySymbol($balance) ?></td>
                </tr>
            </table>
        </span>
    </div>
</div>
<table id="example1" class="table table-striped projects">
    <thead>
    <tr>
        <th> # </th>
        <th>Date</th>
        <th>Particulars</th>
        <th>Tran Id</th>
        <th>purc Id</th>
        <th>inv Id</th>
        <th>Debit</th>
        <th>Credit</th>
        <th>Balance</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=1; foreach ($ledgerBank as $row){
        $particulars = ($row->particulars == NULL) ? "Pay due" : $row->particulars;
        $amountCr = ($row->trangaction_type != "Cr.") ? "---" : showWithCurrencySymbol($row->amount);
        $amountDr = ($row->trangaction_type != "Dr.") ? "---" : showWithCurrencySymbol($row->amount);
        $transId = ($row->trans_id == NULL) ? "---" : $row->trans_id;
        $purchaseId = ($row->purchase_id == NULL) ? "---" : $row->purchase_id;
        $invoiceId = ($row->invoice_id == 0) ? "---" : $row->invoice_id;
    ?>
        <tr>
            <td width="20"> <?= $i++;?> </td>
            <td><?= $row->createdDtm ?></td>
            <td><?= $particulars ?></td>
            <td><?= $transId ?></td>
            <td><?= $purchaseId ?></td>
            <td><?= $invoiceId ?></td>
            <td><?= $amountDr ?></td>
            <td><?= $amountCr ?></td>
            <td><?= showWithCurrencySymbol($row->rest_balance) ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>