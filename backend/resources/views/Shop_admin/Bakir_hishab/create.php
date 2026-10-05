<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">বাকির হিসাব</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">বাকির হিসাব</li>
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
                <h3 class="card-title">বাকির হিসাব</h3>
                <a href="<?= base_url('shop_admin/bakir_hishab'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">

                <div class="row">
                    <div class="col-md-12">
                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                    </div>
                    <div class="col-md-6">
                        <form action="<?= base_url('shop_admin/bakir_hishab_create_action'); ?>" method="post">
                            <div class="form-group">
                                <label for="int">Account Holder </label>
                                <select class="form-control select2  " name="loan_pro_id" onchange="lonProvTransView(this.value)" >
                                    <option selected="selected" value="">Please Select </option>
                                    <?php echo getAllListInOption('loan_pro_id', 'loan_pro_id', 'name', 'loan_provider'); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="particulars">Particulars  </label>
                                <textarea class="form-control" rows="3" name="particulars" id="particulars" placeholder="Particulars"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="enum">Transaction Type </label>
                                <select class="form-control" name="trangaction_type" onchange="changepaymenttype(this.value,'loPo')" id="trangaction_type">
                                    <option value="">Please Select</option>
                                    <option value="1">ধার দিলাম (Cr.)</option>
                                    <option value="2">জমা (Dr.)</option>
                                </select>
                            </div>
                            <div class="form-group" id="payment_loPo">
                                <label for="payment_type">Payment Type </label>
                                <select class="form-control"  onchange="checkBank(this.value)" name="payment_type">
                                    <option value="">Please Select</option>
                                    <option value="1">Chaque/Bank</option>
                                    <option value="2">Cash</option>
                                </select>
                            </div>

                            <div class="form-group " id="amount_loPo">
                                <label for="int">Amount </label>
                                <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" required >
                            </div>

                            <div class="form-group" >
                                <label for="int">Image </label>
                                <input type="file" class="form-control" name="image" >
                            </div>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>

                    </div>
                    <div class="col-md-6">
                        <div id="lonProvData"></div>
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
            var view ='<div class="form-group"><label for="varchar">Bank </label><select class="form-control" name="bank_id"><option  required>Please select</option value=""><?= getTwoValueInOption('bank_id','bank_id','name','account_no','bank'); ?></select></div><div class="form-group" id="chaque"><label for="int">Amount </label><input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" required > </div>';
            $("#amount_"+ext).html(view).show();
        }

        if (id == 3) {
            var view2 ='<div class="form-group" ><label>Cheque</label><input type="text" class="form-control" name="chequeNo" id="chequeNo" placeholder="Input Cheque No "></div><div class="form-group" ><label>Cheque Amount</label><input type="text" onchange="cheque()" class="form-control chequeAmount" name="chequeAmount" id="chequeAmount" placeholder="Input Cheque Amount " required ><b id="cheque_valid"></b></div>';
            $("#amount_"+ext).html(view2).show();
        }

        if (id == 2) {
            var view3 ='<div class="form-group" ><label for="int">Amount </label><input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" required > </div>';
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
</script>
<?= $this->endSection() ?>
