<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Delivery Boy Commission</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Delivery Boy Commission</li>
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
                <h3 class="card-title">Delivery Boy Commission List</h3>
            </div>
            <div class="card-body p-3">
                <div class="row mb-3 ">
                    <div class="col-md-4">
                        <select class="form-control select2"  onchange="commissionShowFirst(this.value)" >
                            <option value="0">Please Select</option>
                            <?php foreach ($delivery as $row) { ?>
                                <option value="<?= $row->delivery_boy_id?>"><?php echo get_data_by_id('name','delivery_boy','delivery_boy_id',$row->delivery_boy_id) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <a href="<?= base_url('shop_admin/delivery_boy_commission_multi_pay')?>" class="btn btn-primary float-right">Multiple payments</a>
                    </div>
                </div>
                <div class="showDatafirst">
                    <table class="table table-bordered table-striped "  id="example1"  >
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>InvoiceId</th>
                            <th>Delivery Boy</th>
                            <th>Commission</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody><?php $i=''; foreach ($deliveryCommission as $row) { ?>
                            <tr>
                                <td width="10px"><?= ++$i ?></td>
                                <td><?= $row->invoice_id ?></td>
                                <td><?= get_data_by_id('name','delivery_boy','delivery_boy_id',$row->delivery_boy_id) ?></td>
                                <td><?= showWithCurrencySymbol($row->commission) ?></td>
                                <td><?php if ($row->com_status == 0) { ?>
                                        <?= '<span class="bg-warning p-1">pending</span>'; ?>
                                    <?php }else{ ?>
                                        <?= '<span class="bg-success p-1">paid</span>'; ?>
                                    <?php } ?></td>
                                <td width="180px">
                                    <?php
                                    if ($row->com_status == 0) {
                                        echo anchor(site_url('delivery_boy_commission_pay/'.$row->package_id.'/'.$row->delivery_boy_id),'Pay Invoice', 'class="btn btn-xs btn-info" onclick="javasciprt: return confirm(\'Are You Sure Pay This ?\')"');
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>

                    </table>
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

    function commissionShowDel(id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/delivery_boy_commission_search') ?>",
            dataType: "text",
            data: {delivery_boy_id: id,},
            success: function(data){
                $('#showDatafirst').html(data);
                $("#example1").DataTable({
                    "responsive": true, "lengthChange": true, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }
        });
    }
</script>
<?= $this->endSection() ?>
