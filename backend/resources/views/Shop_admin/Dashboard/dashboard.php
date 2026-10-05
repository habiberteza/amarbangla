<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard')?>">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12" id="message"></div>
                <div class="col-md-12 alert alert-secondary text-center" role="alert">

                    <h4 class=" alert-heading">Welcome To <?= Auth()->shopName; ?>
                        <br> Shop Id: #<?= Auth()->sch_id;?></h4>
                    <hr>
                    <p class="mb-0">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" <?php echo Auth()->opening_status == 1 ? 'checked' : '' ?> onchange="opening_status_update()" name="status" class="custom-control-input" value="0" id="customSwitch_6">
                            <label class="custom-control-label" for="customSwitch_6"></label>
                            <label> Opening status</label>
                        </div>
                    </div>
                    </p>
                </div>
            </div>
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>বাকির হিসাব</h3>

                            <p>বাকির হিসাব </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="<?= base_url('shop_admin/bakir_hishab');?>" class="small-box-footer">বাকির হিসাব<i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Order<sup style="font-size: 20px"></sup></h3>

                            <p>Order </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="<?= base_url('shop_admin/invoice');?>" class="small-box-footer">Order <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>Seller Commission</h3>

                            <p>Seller Commission</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="<?= base_url('shop_admin/seller_commission');?>" class="small-box-footer">Seller Commission <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>Delivery Boy Commission</h3>

                            <p>Delivery Boy Commission</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="<?= base_url('shop_admin/delivery_boy_commission');?>" class="small-box-footer">Delivery Boy Commission <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>Shop Commission</h3>

                            <p>Shop Commission </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="<?= base_url('shop_admin/shop_commission');?>" class="small-box-footer">Shop Commission<i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Supplier Ledger<sup style="font-size: 20px"></sup></h3>

                            <p>Supplier Ledger </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="<?= base_url('shop_admin/ledger_suppliers');?>" class="small-box-footer">Supplier Ledger <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>Bank Ledger</h3>

                            <p>Bank Ledger</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="<?= base_url('shop_admin/ledger_bank');?>" class="small-box-footer">Loan Ledger <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>Loan Ledger</h3>

                            <p>Loan Ledger</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="<?= base_url('shop_admin/ledger_loan');?>" class="small-box-footer">Loan Ledger <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>Get product</h3>

                            <p>Get product </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="<?= base_url('shop_admin/get_product');?>" class="small-box-footer">Get product<i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">

                    <div class="row">
                        <div class="col-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">TOTAL BANK AMOUNT</span>
                                    <span class="info-box-number">
                                        <?php echo showWithCurrencySymbol($totalBankBal) ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-4">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><i
                                        class="fas fa-thumbs-up"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">TOTAL CASH AMOUNT</span>
                                    <span class="info-box-number">
                                        <?php echo admin_cash() ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-4 ">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i
                                        class="fas fa-users"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">GET TOTAL AMOUNT</span>
                                    <span class="info-box-number">
                                        <?php echo showWithCurrencySymbol($totalGet) ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-4 ">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i
                                        class="fas fa-shopping-cart"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">DUE TOTAL AMOUNT</span>
                                    <span class="info-box-number">
                                        <?php echo showWithCurrencySymbol($totalDue) ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-4 ">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i
                                        class="fas fa-users"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Total Product</span>
                                    <span class="info-box-number">
                                        <?php echo $totalProduct ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-4 ">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i
                                        class="fas fa-users"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">TOTAL CUSTOMER</span>
                                    <span class="info-box-number"> </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    </div>
                </section>
                <!-- /.Left col -->

            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= $this->endSection() ?>


<?= $this->section('java_script') ?>
<script>
    function opening_status_update() {
        const base_url = "<?= base_url("shop_admin/opening_status")?>";
        $.ajax({
            type: "POST",
            url: base_url,
            dataType: "text",
            data: {},
            success: function (data) {
                $('#message').fadeIn();
                $('#message').html(data).delay(200).fadeOut('slow');
            }
        });
    }
</script>
<?= $this->endSection() ?>
