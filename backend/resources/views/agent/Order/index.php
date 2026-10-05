<?= $this->extend('Agent/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('agent/dashboard')?>">Home</a></li>
                        <li class="breadcrumb-item active">Order</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header row">
                            <div class="col-md-12">
                                <h3 class="card-title">Order list</h3>
                            </div>


                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Shop</th>
                                    <th>Invoice Id</th>
                                    <th>Total</th>
                                    <th>Create date</th>
                                    <th>Status</th>
                                    <th>Delivery Status</th>
                                </tr>
                                </thead>
                                <tbody id="shopOrder">
                                <?php  foreach ($order as $view){ ?>
                                    <tr>
                                        <td><?= $view->invoice_id;?></td>
                                        <td><?= get_data_by_id('name','shops','sch_id',$view->sch_id).'<br>'.get_data_by_id('mobile','shops','sch_id',$view->sch_id)  ?></td>
                                        <td><a href="<?= site_url('agent/invoice/' . $view->invoice_id) ?>">View Invoice</a></td>
                                        <td><?php echo showWithCurrencySymbol($view->price +$view->delivery_charge); ?></td>
                                        <td>
                                            <?php echo invoiceDateFormat($view->createdDtm); ?>
                                        </td>
                                        <td><?php print getinvoiceStatusNew($view->invoice_id); ?></td>

                                        <td>
                                            <?php
                                            if (!empty(deliverystatus($view->invoice_id))) {
                                                foreach (deliverystatus($view->invoice_id) as $row) {
                                                    $detail = 'Delivery By Admin';
                                                    if (!empty($row->delivery_boy_id)) {
                                                        $name = get_data_by_id('name', 'delivery_boy', 'delivery_boy_id', $row->delivery_boy_id);
                                                        $phone = get_data_by_id('mobile', 'delivery_boy', 'delivery_boy_id', $row->delivery_boy_id);
                                                        $detail = $name . '<br>' . showWithPhoneNummberCountryCode($phone);
                                                    }

                                                    if ($row->status == 0) {
                                                        echo '<span class="label bg-info p-1">Accepted</span><br>' .$detail. '<br>';
                                                    }
                                                    if ($row->status == 1) {
                                                        echo '<span class="label bg-success p-1">Complete</span><br>' . $detail . '<br>';

                                                    }
                                                }
                                            }else{
                                                echo '<span class="label bg-warning p-1">Not Accepted</span>';
                                            }
                                            ?>
                                        </td>

                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- /.content-wrapper -->
<?= $this->endSection() ?>


<?= $this->section('java_script') ?>
<script>

</script>
<?= $this->endSection() ?>
