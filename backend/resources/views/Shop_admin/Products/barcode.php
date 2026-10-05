<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Products barcode</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Products barcode</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header ">
                <h3 class="card-title">Products barcode</h3>
                <a href="<?= base_url('shop_admin/products'); ?>" class="btn btn-xs btn-danger w-25 float-right no-print">Back</a>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-12" style="padding: 20px; ">
                        <?php
                        if (is_array($barcodeqty)) {
                            foreach ($barcodeqty as $key => $row) {
                                if ($barcodeqty[$key] > 0) {
                                    echo "<div class='row'>";
                                    for ($i = 1; $i <= $barcodeqty[$key]; $i++) {
                                        echo '<div style="padding:10px; float:left;"><img src="data:image/png;base64,' . base64_encode($generator->getBarcode((string)$key, $barcodeType)) . '" width="' . $barcodeSize . '"><br><center><small>Price:' . get_data_by_id('selling_price', 'products', 'prod_id', $key) . '</small></center></div>';
                                    }
                                    echo "</div><hr />";
                                }
                            }
                        } else { ?>
                            <div style="padding:10px; float:left;">Please select any product to generate barcode.</div>
                        <?php  } ?>
                    </div>

                    <div class="row no-print">
                        <div class="col-md-12" style="margin-top: 20px;">
                            <div class="print_line btn btn-primary pull-right" onclick="print(document);"><i class="fa fa-print"></i> Print Now</div>
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

</script>
<?= $this->endSection() ?>
