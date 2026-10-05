<?php
$name = get_data_by_id('name', 'loan_provider', 'loan_pro_id', $loan_provider);
$balance = get_data_by_id('balance', 'loan_provider', 'loan_pro_id', $loan_provider);

?>
<div class="row">
    <div class="col-md-12">
        <h3> Account Holder: <?= $name ?></h3>
        <table class="table table-bordered table-striped bg-success">
            <tr>
                <td>Total Get:</td>
                <td><?= showWithCurrencySymbol(get_total('ledger_loan', 'amount', 'Dr.', 'loan_pro_id', $loan_provider)) ?></td>
                <td>Total Pay:</td>
                <td><?= showWithCurrencySymbol(get_total('ledger_loan', 'amount', 'Cr.', 'loan_pro_id', $loan_provider)) ?></td>
                <td>Balance:</td>
                <td><?= showWithCurrencySymbol($balance) ?></td>
            </tr>
        </table>

    </div>
</div>
<table id="example1" class="table table-striped projects">
    <thead>
    <tr>
        <th> # </th>
        <th>Date</th>
        <th>Particulars</th>
        <th>Trangaction Id</th>
        <th>Debit</th>
        <th>Credit</th>
        <th>Balance</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=1; foreach ($ledgerLoan as $row){
        $particulars = ($row->particulars == NULL) ? "Pay due" : $row->particulars;
        $amountCr = ($row->trangaction_type != "Cr.") ? "---" : showWithCurrencySymbol($row->amount);
        $amountDr = ($row->trangaction_type != "Dr.") ? "---" : showWithCurrencySymbol($row->amount);
        ?>
        <tr>
            <td width="20"> <?= $i++;?> </td>
            <td><?= $row->createdDtm ?></td>
            <td><?= $particulars ?></td>
            <td>TRNS_<?= $row->trans_id ?></td>
            <td><?= $amountDr ?></td>
            <td><?= $amountCr ?></td>
            <td><?= showWithCurrencySymbol($row->rest_balance) ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>