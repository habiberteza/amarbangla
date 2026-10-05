
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
    <?php $i=1; foreach ($ledgerNagod as $row){
        $particulars = ($row->particulars == NULL) ? "Payment" : $row->particulars;
        $amountCr = ($row->trangaction_type != "Cr.") ? "---" : showWithCurrencySymbol($row->amount);
        $amountDr =($row->trangaction_type != "Dr.")?"---":showWithCurrencySymbol($row->amount);
        ?>
        <tr>
            <td width="20"> <?= $i++;?> </td>
            <td><?php echo $row->createdDtm ?></td>
            <td><?php echo $particulars ?></td>
            <td><?php echo $row->trans_id ?></td>
            <td><?php echo $amountDr ?></td>
            <td><?php echo $amountCr ?></td>
            <td><?php echo showWithCurrencySymbol($row->rest_balance) ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>