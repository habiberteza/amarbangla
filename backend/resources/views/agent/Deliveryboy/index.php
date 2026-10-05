<?= $this->extend('Agent/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Delivery boy</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('agent/dashboard')?>">Home</a></li>
                        <li class="breadcrumb-item active">Delivery boy</li>
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
                        <div class="card-header">
                            <a href="<?php echo base_url('agent/delivery_boy_create');?>" class="btn btn-xs btn-primary w-25 float-right">Create</a>
                            <h3 class="card-title">Delivery boy list</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-12">
                                <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                            </div>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  foreach ($deliveryboy as $val){ ?>
                                    <tr>
                                        <td width="10"><?php echo $val->delivery_boy_id;?></td>
                                        <td><?php echo $val->name ?></td>
                                        <td><?php echo $val->mobile ?></td>
                                        <td><?php echo showWithCurrencySymbol($val->balance) ?></td>
                                        <td width="250px">
                                            <a href="<?php echo base_url('agent/delivery_boy_order/'.$val->delivery_boy_id);?>" class="btn btn-xs btn-info ">Order</a>
                                            <a href="<?php echo base_url('agent/delivery_boy_commission/'.$val->delivery_boy_id);?>" class="btn btn-xs btn-primary ">Commission</a>
                                            <a href="<?php echo base_url('agent/delivery_boy_ledger/'.$val->delivery_boy_id);?>" class="btn btn-xs btn-success ">Ledger</a>
                                            <a href="<?php echo base_url('agent/delivery_boy_update/'.$val->delivery_boy_id);?>" class="btn btn-xs btn-warning ">Update</a>
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
<?= $this->endSection() ?>


<?= $this->section('java_script') ?>
    <script></script>
<?= $this->endSection() ?>