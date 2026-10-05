<p class="pull-right mb-3">Last Balance <?= showWithCurrencySymbol($restBalance) ?></p>

<table id="example3" class="table table-striped projects ">
    <thead>
    <tr>
        <th>Date</th>
        <th>Bank</th>
        <th>Particulars</th>
        <th>Debit</th>
        <th>Credit</th>
        <th>Balance</th>
    </tr>
    </thead>
    <tbody>
    <?php
        $i=1; foreach ($bankLedger as $row){
        $particulars = ($row->particulars == NULL) ? "Pay due" : $row->particulars;
        $amountCr = ($row->trangaction_type != "Cr.") ? "---" : showWithCurrencySymbol($row->amount);
        $amountDr = ($row->trangaction_type != "Dr.") ? "---" : showWithCurrencySymbol($row->amount);
    ?>
        <tr>
            <td><?= bdDateFormat($row->createdDtm); ?></td>
            <td><?= get_data_by_id('name', 'bank', 'bank_id', $row->bank_id); ?></td>
            <td><?= $particulars; ?></td>
            <td><?= $amountDr; ?></td>
            <td><?= $amountCr; ?></td>
            <td><?= showWithCurrencySymbol($row->rest_balance); ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>