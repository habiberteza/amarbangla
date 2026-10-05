<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Stock Report</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Stock Report</li>
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
                <h3 class="card-title">Stock Report List</h3>
            </div>
            <div class="card-body p-3">
                <div class="row mb-3 " >
                    <div class="col-md-5" >
                        <label for="int">Store name</label>
                        <select class="form-control select2 " onchange="storePro(this.value)" id="store_id" >
                            <option selected="selected"  value="">Please Select</option>
                            <?php echo getAllListInOption('store_id','store_id','name','stores'); ?>
                        </select>
                    </div>


                </div>

                <div id="product" ></div>

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
    function storePro(storeId)
    {
        //alert(storeId);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/stock_report_search') ?>",
            data: {storeId:storeId},
            success: function(val){
                $("#product").html(val).show();
                $("#example1").DataTable({
                    "responsive": true, "lengthChange": true, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }
        });
    }
</script>
<?= $this->endSection() ?>
