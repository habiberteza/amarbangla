<table id="example1" class="table table-striped projects">
    <thead>
    <tr>
        <th> # </th>
        <th>Date</th>
        <th>Particulars</th>
        <th>Employee</th>
        <th>Trangaction Id</th>
        <th>Salary</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=1; foreach ($ledgerEmployee as $row){
        $particulars = ($row->particulars == NULL) ? "Transaction" : $row->particulars;
        $employeeId = get_data_by_id('name', 'employee', "employee_id", $row->employee_id);
        $amountCr = ($row->trangaction_type != "Cr.") ? "---" : showWithCurrencySymbol($row->amount);
        $amountDr = ($row->trangaction_type != "Dr.") ? "---" : showWithCurrencySymbol($row->amount);
    ?>
        <tr>
            <td width="20"> <?= $i++;?> </td>
            <td><?= $row->createdDtm ?></td>
            <td><?= $particulars?></td>
            <td><?= $employeeId ?></td>
            <td><?= $row->trans_id ?></td>
            <td><?= $amountDr ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>