<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Message</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Message</li>
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
                <h3 class="card-title">Message List</h3>
            </div>
            <div class="card-body p-3">
                <table id="example1" class="table table-striped projects text-capitalize">
                    <thead>
                    <tr>
                        <th> # </th>
                        <th>Sender</th>
                        <th>Sender Type</th>
                        <th>Receiver</th>
                        <th>Message</th>
                        <th>Red Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($result as $val){ $status = ($val->red_status == 0)?'<span class="label label-warning">Unread</span>':'<span class="label label-success">Read</span>'; ?>
                        <tr>
                            <td width="20"> <?= $i++;?> </td>
                            <td><?php echo massageName($val->sender_type,$val->sender_id)?></td>
                            <td><?php echo massageType($val->sender_type) ?></td>
                            <td><?php echo massageName($val->receiver_type,$val->receiver_id) ?></td>
                            <td><?php echo $val->massage ?></td>
                            <td><?php echo $status ?></td>
                            <td width="120">
                                <a href="<?= base_url('shop_admin/message_view/' . $val->message_id); ?>" class="btn btn-xs btn-warning ">View</a>
                            </td>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
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
