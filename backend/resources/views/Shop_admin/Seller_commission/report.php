<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Seller Commission Report</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Seller Commission Report</li>
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
                <h3 class="card-title">Seller Commission Report</h3>
            </div>
            <div class="card-body p-3">
                <div class="row mb-3 ">
                    <div class="col-md-4">
                        <select class="form-control select2" name="seller" id="seller" onchange="commission(this.value)" >
                            <option value="0">Select Seller</option>
                            <?php foreach ($seller as $row) { ?>
                                <option value="<?= $row->seller_id?>"><?php echo get_data_by_id('name','seller','seller_id',$row->seller_id) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-8"> </div>
                </div>
                <div id="showData"></div>
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
    function commission(id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/seller_commission_report_search') ?>",
            dataType: "text",
            data: {sellerId: id,},
            success: function(data){
                $('#showData').html(data);
            }
        });
    }
</script>
<?= $this->endSection() ?>
