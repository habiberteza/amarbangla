<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sms panel</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Sms panel</li>
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
                            <h3 class="card-title">Sms panel list</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-12" id="message">
                                <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                            </div>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Shop</th>
                                        <th>Sms quantity</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($sms as $val) { ?>
                                    <tr>
                                        <td width="80px"><?php echo $val->sms_request_id ?></td>
                                        <td><?php echo get_data_by_id('name','shops','sch_id',$val->sch_id); ?></td>
                                        <td><?php echo $val->sms_qty ?></td>
                                        <td width="180px">
                                            <?php if ($val->status !=1){ ?>
                                                <select class="form-control" name="status" onchange="statusChange(this.value,'<?php echo $val->sms_request_id ?>')">
                                                    <?php echo smsGlobalStatus($val->status);?>
                                                </select>
                                            <?php }else{ ?>
                                                <?php echo smsStatusView($val->status);?>
                                            <?php } ?>

                                        </td>

                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Shop</th>
                                        <th>Sms quantity</th>
                                        <th>Status</th>
                                    </tr>
                                </tfoot>
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