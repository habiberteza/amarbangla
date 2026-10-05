<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Transaction</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Transaction Create</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Transaction Create</h3>
                <a href="<?= base_url('shop_admin/transaction'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">

                <div class="row">
                    <div class="col-md-12">
                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                    </div>
                    <div class="col-md-12">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'supplier'))?'active':'';?> <?= (empty($_GET))?'active':'';?>" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Supplier</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'account'))?'active':'';?> " id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Account Holder</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'fund'))?'active':'';?>" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Fund Transfer</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'expense'))?'active':'';?>" id="custom-tabs-four-vat-tab" data-toggle="pill" href="#custom-tabs-four-vat" role="tab" aria-controls="custom-tabs-four-vat" aria-selected="false">Expense</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'other'))?'active':'';?>" id="custom-tabs-four-address-tab" data-toggle="pill" href="#custom-tabs-four-address" role="tab" aria-controls="custom-tabs-four-address" aria-selected="false">Other Sales</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'employee'))?'active':'';?>" id="custom-tabs-four-customer-tab" data-toggle="pill" href="#custom-tabs-four-customer" role="tab" aria-controls="custom-tabs-four-customer" aria-selected="false">Employee Salary</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'vat'))?'active':'';?>" id="custom-tabs-four-vat-pay-tab" data-toggle="pill" href="#custom-tabs-four-vat-pay" role="tab" aria-controls="custom-tabs-four-customer" aria-selected="false">Vat Pay</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'sale'))?'active':'';?>" id="custom-tabs-four-sale-commission-pay-tab" data-toggle="pill" href="#custom-tabs-four-sale-commission-pay" role="tab" aria-controls="custom-tabs-four-customer" aria-selected="false">Sale commission pay</a>
                                </li>



                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'supplier'))?'active show':'';?> <?= (empty($_GET))?'active show':'';?> " id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <form action="<?= base_url('shop_admin/transaction_supplier_action') ?>" method="post">

                                                <div class="form-group">
                                                    <label for="int">Supplier</label>
                                                    <select class="form-control select2 " onchange="supplTransView(this.value)"  name="supplier_id" id="supplier_id">
                                                        <option selected="selected"  value="">Please Select</option>
                                                        <?= getAllListInOption('supplier_id','supplier_id','name','suppliers'); ?>
                                                    </select>

                                                </div>
                                                <div class="form-group">
                                                    <label for="particulars">Particulars</label>
                                                    <textarea class="form-control" rows="3" name="particulars" id="particulars" placeholder="Particulars"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="enum">Transaction Type </label>
                                                    <select class="form-control" name="trangaction_type" onchange="changepaymenttype(this.value,'supp')" id="trangaction_type" required>
                                                        <option value="">Please Select</option>
                                                        <option value="1">খরচ (Cr.)</option>
                                                        <option value="2">জমা (Dr.)</option>
                                                    </select>
                                                </div>

                                                <div class="form-group" id="payment_supp">
                                                    <label for="payment_type">Payment Type </label>
                                                    <select class="form-control" onchange="checkBank(this.value,'supp')" name="payment_type" required>
                                                        <option value="" >Please Select</option>
                                                        <option value="1"  >Bank</option>
                                                        <option value="3"  >Chaque</option>
                                                        <option value="2" >Cash</option>
                                                    </select>
                                                </div>

                                                <div class="form-group" id="amount_supp">
                                                    <label for="int">Amount </label>
                                                    <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" required />
                                                </div>

                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                        <div class="col-md-8">
                                            <div id="suppData"></div>
                                        </div>

                                    </div>

                                </div>

                                <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'account'))?'active show':'';?>" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <form action="<?= base_url('shop_admin/transaction_account_holder_action') ?>" method="post">
                                                <div class="form-group">
                                                    <label for="int">Account Holder </label>
                                                    <select class="form-control select2 " onchange="lonProvTransView(this.value)" name="loan_pro_id">
                                                        <option selected="selected"  value="">Please Select</option>
                                                        <?= getAllListInOption('loan_pro_id','loan_pro_id','name','loan_provider'); ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="particulars">Particulars </label>
                                                    <textarea class="form-control" rows="3" name="particulars" id="particulars" placeholder="Particulars"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="enum">Transaction Type </label>
                                                    <select class="form-control" name="trangaction_type" onchange="changepaymenttype(this.value,'account')" id="trangaction_type">
                                                        <option value="">Please Select</option>
                                                        <option value="1">খরচ (Cr.)</option>
                                                        <option value="2">জমা (Dr.)</option>
                                                    </select>
                                                </div>
                                                <div class="form-group" id="payment_account">
                                                    <label for="payment_type">Payment Type </label>
                                                    <select class="form-control" onchange="checkBank(this.value,'account')" name="payment_type">
                                                        <option value="" >Please Select</option>
                                                        <option value="1"  >Chaque/Bank</option>
                                                        <option value="2" >Cash</option>
                                                    </select>
                                                </div>

                                                <div class="form-group " id="amount_account">
                                                    <label for="int">Amount </label>
                                                    <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" required />
                                                </div>

                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                        <div class="col-md-8">
                                            <div id="lonProvData"></div>
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'fund'))?'active show':'';?>" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <form action="<?= base_url('shop_admin/transaction_bank_action') ?>" method="post">
                                                <div class="form-group">
                                                    <label for="int">Bank</label>
                                                    <select class="form-control" onchange="bankTransView(this.value)" name="bank_id" id="bank_id" required>
                                                        <option value="">Please select</option>
                                                        <?= getTwoValueInOption('bank_id','bank_id','name','account_no','bank'); ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="int">To Bank</label>
                                                    <select class="form-control" name="bank_id2" required>
                                                        <option value="">Please select</option>
                                                        <?= getTwoValueInOption('bank_id','bank_id','name','account_no','bank'); ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="particulars">Particulars </label>
                                                    <textarea class="form-control" rows="3" name="particulars" id="particulars" placeholder="Particulars"></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="int">Amount </label>
                                                    <input type="text" class="form-control" onchange="checkAvailableBankAmount(this.value)" name="amount" id="amount9090" placeholder="Amount" required />
                                                    <div id="Bank_valid"></div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update</button>

                                            </form>
                                        </div>
                                        <div class="col-md-8">
                                            <div id="bankData"></div>
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'expense'))?'active show':'';?>" id="custom-tabs-four-vat" role="tabpanel" aria-labelledby="custom-tabs-four-vat-tab">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <form action="<?= base_url('shop_admin/transaction_expense_action'); ?>" method="post">
                                                <div class="form-group">
                                                    <label for="particulars">Memo Number </label>
                                                    <input type="text" class="form-control" name="memo_number" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="particulars">Particulars </label>
                                                    <textarea class="form-control" rows="3" name="particulars" id="particulars" placeholder="Particulars"></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="payment_type">Payment Type </label>
                                                    <select class="form-control" onchange="checkBank(this.value,'expen')" required name="payment_type">
                                                        <option value="" >Please Select</option>
                                                        <option value="1"  >Chaque/Bank</option>
                                                        <option value="2" >Cash</option>
                                                    </select>
                                                </div>

                                                <div class="form-group"  id="amount_expen">
                                                    <label for="int">Amount </label>
                                                    <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" required />
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                        <div class="col-md-8">

                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'other'))?'active show':'';?>" id="custom-tabs-four-address" role="tabpanel" aria-labelledby="custom-tabs-four-address-tab">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <form action="<?= base_url('shop_admin/transaction_others_action'); ?>" method="post">

                                                <div class="form-group">
                                                    <label for="particulars">Particulars</label>
                                                    <textarea class="form-control" rows="3" name="particulars" id="particulars" placeholder="Particulars" required ></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="int">Amount </label>
                                                    <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" required />
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'employee'))?'active show':'';?>" id="custom-tabs-four-customer" role="tabpanel" aria-labelledby="custom-tabs-four-customer-tab">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <form action="<?= base_url('shop_admin/transaction_employee_action'); ?>" method="post">

                                                <div class="form-group">
                                                    <label for="int">Employee </label>
                                                    <select class="form-control select2 " onchange="employeeSearch(this.value)" name="employee_id" required>
                                                        <option selected="selected"  value="">Please Select</option>
                                                        <?= getAllListInOption('employee_id','employee_id','name','employee'); ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="int">Salary </label>
                                                    <input type="text" class="form-control" name="salary"  id="salary" placeholder="Salary" readonly>

                                                </div>

                                                <div class="form-group">
                                                    <label for="payment_type">Payment Type </label>
                                                    <select class="form-control" onchange="checkBank(this.value,'emplo')" required name="payment_type">
                                                        <option value="" >Please Select</option>
                                                        <option value="1"  >Chaque/Bank</option>
                                                        <option value="2" >Cash</option>
                                                    </select>
                                                </div>

                                                <div class="form-group" id="amount_emplo">
                                                    <label for="int">Amount </label>
                                                    <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" required />
                                                </div>

                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                        <div class="col-md-8">
                                            <div id="ledger_employee"></div>
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'vat'))?'active show':'';?>" id="custom-tabs-four-vat-pay" role="tabpanel" aria-labelledby="custom-tabs-four-customer-tab">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <form action="<?= base_url('shop_admin/transaction_vat_action'); ?>" method="post">

                                                <div class="form-group">
                                                    <label for="int">Vat Register </label>
                                                    <select class="form-control select2 " onchange="vatLedgerView(this.value)"  name="vat_id">
                                                        <option selected="selected"  value="">Please Select</option>
                                                        <?= getAllListInOption('vat_id','vat_id','name','vat_register'); ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="particulars">Particulars </label>
                                                    <textarea class="form-control" rows="3" name="particulars" id="particulars" placeholder="Particulars" required ></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="payment_type">Payment Type </label>
                                                    <select class="form-control" onchange="checkBank(this.value,'vat')" name="payment_type">
                                                        <option value="" >Please Select</option>
                                                        <option value="1"  >Chaque/Bank</option>
                                                        <option value="2" >Cash</option>
                                                    </select>
                                                </div>

                                                <div class="form-group" id="amount_vat">
                                                    <label for="int">Amount </label>
                                                    <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" required />
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update</button>

                                            </form>
                                        </div>

                                        <div class="col-md-8">
                                            <div id="vatledger"></div>
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'sale'))?'active show':'';?>" id="custom-tabs-four-sale-commission-pay" role="tabpanel" aria-labelledby="custom-tabs-four-customer-tab">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <form action="<?= base_url('shop_admin/transaction_sale_commission_action'); ?>" method="post">
                                                <div class="form-group">
                                                    <label for="enum">Transaction Type </label>
                                                    <select class="form-control" name="trangaction_type" id="trangaction_type">
                                                        <option value="2">Cr</option>
                                                    </select>
                                                </div>
                                                <div class="form-group"  id="dataexpense">
                                                    <label for="int">Amount </label>
                                                    <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" required />
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                        <div class="col-md-8"></div>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- /.card-body -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= $this->endSection() ?>


<?= $this->section('java_script') ?>
<script>
    function supplTransView(Id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/transaction_supplier') ?>",
            data: {suppId: Id},
            success: function(html){
                $("#suppData").html(html).show();
            }
        });
    }

    function changepaymenttype(Id,ext){
        var ext_data = "'"+ext+"'";
        if (Id == 2) {
            var view = '<label for="payment_type">Payment Type </label><select class="form-control" onchange="checkBank(this.value,'+ext_data+')" name="payment_type" required><option value="" >Please Select</option><option value="1"  >Bank</option><option value="3"  >Chaque</option><option value="2" >Cash</option></select>'

            $("#payment_"+ext).html(view).show();
        }else{
            view ='<label for="payment_type">Payment Type </label><select class="form-control" onchange="checkBank(this.value,'+ext_data+')" name="payment_type"><option value="" >Please Select</option><option value="1"  >Chaque/Bank</option><option value="2" >Cash</option></select>';

            $("#payment_"+ext).html(view).show();
        }
    }
    function checkBank(id,ext){
        if (id == 1) {
            var view ='<div class="form-group"><label for="varchar">Bank </label><select class="form-control" name="bank_id"><option  required>Please select</option value=""><?= getTwoValueInOption('bank_id','bank_id','name','account_no','bank'); ?></select></div><div class="form-group" id="chaque"><label for="int">Amount </label><input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" required /> </div>';
            $("#amount_"+ext).html(view).show();
        }

        if (id == 3) {
            var view2 ='<div class="form-group" ><label>Cheque</label><input type="text" class="form-control" name="chequeNo" id="chequeNo" placeholder="Input Cheque No "></div><div class="form-group" ><label>Cheque Amount</label><input type="text" onchange="cheque()" class="form-control chequeAmount" name="chequeAmount" id="chequeAmount" placeholder="Input Cheque Amount " required ><b id="cheque_valid"></b></div>';
            $("#amount_"+ext).html(view2).show();
        }

        if (id == 2) {
            var view3 ='<div class="form-group" ><label for="int">Amount </label><input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" required /> </div>';
            $("#amount_"+ext).html(view3).show();

        }
    }

    function lonProvTransView(Id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/transaction_account_holder') ?>",
            data: {lonProvId: Id},
            success: function(html){
                $("#lonProvData").html(html).show();
            }
        });
    }

    function bankTransView(Id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/transaction_bank') ?>",
            data: {bankId: Id},
            success: function(html){
                $("#bankData").html(html).show();
            }
        });
    }

    function checkAvailableBankAmount(amount){
        var bankId = $('#bank_id').val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/transaction_bank_balance') ?>",
            dataType: "text",
            data: {balance: amount,bank_id: bankId},
            success: function(msg){
                $('#Bank_valid').html(msg);
            }
        });
    }

    function employeeSearch(Id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/transaction_search_employee_salary') ?>",
            data: {id: Id},
            dataType: 'json',
            success: function(data){
                $("#salary").val(data.salary).show();
                $("#ledger_employee").html(data.view).show();
            }
        });
    }

    function vatLedgerView(vatId){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/transaction_vat_ledger') ?>",
            data: {vatId:vatId},
            success: function(val){
                $("#vatledger").html(val).show();
            }
        });
    }

</script>
<?= $this->endSection() ?>
