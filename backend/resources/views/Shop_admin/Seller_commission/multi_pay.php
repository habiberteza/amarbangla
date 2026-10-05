<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Seller Commission</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Seller Commission</li>
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
                <h3 class="card-title">Seller Commission List</h3>
                <a href="<?= base_url('shop_admin/seller_commission')?>" class="btn btn-xs w-25 btn-danger float-right">Back</a>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-12">
                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                    </div>
                </div>
                <div class="showDatafirst">
                    <form action="<?= base_url('shop_admin/seller_commission_pay_action')?>" method="post">
                        <table class="table table-bordered table-striped "  id="example1"  >
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>
                                <input type="checkbox" id="selectall" name="selectall" autocomplete="off"  onclick="eventCheckBox()">
                                <span>Select All</span>
                            </th>
                            <th>InvoiceId</th>
                            <th>Seller</th>
                            <th>Commission</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody><?php $i=''; foreach ($commissionData as $row) { ?>
                            <tr>
                                <td width="10px"><?= ++$i ?></td>
                                <td><input type="checkbox" value="<?php echo $row->invoice_id ?>" name="invoiceId[]" ></td>
                                <td><?= $row->invoice_id ?></td>
                                <td><?= get_data_by_id('name','seller','seller_id',$row->seller_id) ?></td>
                                <td><?= showWithCurrencySymbol($row->commission) ?></td>
                                <td><?php if ($row->com_status == 0) { ?>
                                        <?php echo '<span class="bg-warning p-1">pending</span>'; ?>
                                    <?php }else{ ?>
                                        <?php echo '<span class="bg-success p-1">paid</span>'; ?>
                                    <?php } ?></td>
                                <td width="180px">
                                    <?php
                                    if ($row->com_status == 0) {
                                        echo anchor(site_url('shop_admin/seller_commission_pay/'.$row->package_id.'/'.$row->seller_id),'Pay Invoice', 'class="btn btn-xs btn-info" onclick="javasciprt: return confirm(\'Are You Sure Pay This ?\')"');
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>

                    </table>
                        <small class="float-right" ><button type="submit" class="btn btn-primary mt-2">Pay</button></small>
                    </form>
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
    function commissionShowFirst(id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/seller_commission_search') ?>",
            dataType: "text",
            data: {sellerId: id,},
            success: function(data){
                $('.showDatafirst').html(data);
                $("#example1").DataTable({
                    "responsive": true, "lengthChange": true, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }
        });
    }
    function eventCheckBox() {
        let checkboxs = document.getElementsByTagName("input");
        for(let i = 1; i < checkboxs.length ; i++) {
            checkboxs[i].checked = !checkboxs[i].checked;
        }
    }
</script>
<?= $this->endSection() ?>
