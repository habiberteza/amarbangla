<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Agent commission</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Agent commission</li>
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
                            <a href="<?php echo base_url('super_admin/agent');?>" class="btn btn-xs btn-danger float-right">Back</a>
                            <h3 class="card-title">Agent commission list</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Shop Name</th>
                                    <th>Total Sale</th>
                                    <th>Total Commission</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1; foreach($shop as $val ){ ?>
                                    <tr>
                                        <td><?php echo $i++;?></td>
                                        <td><?php echo $val->name;?></td>
                                        <td><?php echo showWithCurrencySymbol(shop_id_by_total_sale($val->sch_id));?></td>
                                        <td><?php echo showWithCurrencySymbol(shop_id_by_total_sale_commission($val->sch_id));?></td>
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