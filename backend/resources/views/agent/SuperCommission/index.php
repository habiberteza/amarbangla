<?= $this->extend('Agent/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Super Commission</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('agent/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Super Commission</li>
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
                <h3 class="card-title">Super Commission List</h3>
            </div>
            <div class="card-body p-3">
                <table id="example1" class="table table-striped projects">
                    <thead>
                    <tr>
                        <th> # </th>
                        <th>Name</th>
                        <th>Commission</th>
                        <th>Due</th>
                        <th>Pay</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($commission as $row){ ?>
                        <tr>
                            <td width="20"> <?= $i++;?> </td>
                            <td><?php echo $row->name ?></td>
                            <td><?php echo showWithCurrencySymbol(get_data_by_id('commision','supper_commision','sch_id',$row->sch_id)); ?></td>
                            <td><?php echo showWithCurrencySymbol(get_data_by_id('due_commision','supper_commision','sch_id',$row->sch_id)); ?></td>
                            <td><?php echo showWithCurrencySymbol(get_data_by_id('pay_commision','supper_commision','sch_id',$row->sch_id)); ?></td>
                            <td width="180">
                                <a href="<?= base_url('agent/unpaid_list/' . $row->sch_id); ?>" class="btn btn-xs btn-info ">Unpaid List</a>
                                <a href="<?= base_url('agent/paid_list/' . $row->sch_id); ?>" class="btn btn-xs btn-success ">Paid List</a>
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
