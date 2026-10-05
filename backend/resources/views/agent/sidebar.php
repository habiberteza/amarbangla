<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo base_url('agent/dashboard') ?>" class="brand-link">

        <span class="brand-text font-weight-light"><?= Auth_agent()->agent_name; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <?php //echo image_view('uploads/schools', '', Auth_agent()->image, 'no_image.jpg', 'img-circle elevation-2', ''); ?>
            </div>
            <div class="info">
                <a href="<?php echo base_url('agent/dashboard') ?>" class="d-block">
                    <?= Auth_agent()->agent_name; ?>
                </a>
            </div>
        </div>



        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item <?= (menu_active_or_inactive(['agent/dashboard'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?= base_url('agent/dashboard');?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Dashboard </p>
                    </a>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['agent/super_commission'])== true)?'menu-open':''; ?> "> <a href="<?= base_url('agent/super_commission');?>" class="nav-link"> <i class="nav-icon fas fa-book"></i> <p> Shop commission </p> </a></li>
                <li class="nav-item <?= (menu_active_or_inactive(['agent/customers'])== true)?'menu-open':''; ?> "> <a href="<?= base_url('agent/customers');?>" class="nav-link"> <i class="nav-icon fas fa-book"></i> <p> Customers </p> </a></li>
                <li class="nav-item <?= (menu_active_or_inactive(['agent/order'])== true)?'menu-open':''; ?> "> <a href="<?= base_url('agent/order');?>" class="nav-link"> <i class="nav-icon fas fa-book"></i> <p> All Order </p> </a></li>
                <li class="nav-item <?= (menu_active_or_inactive(['agent/calling_request'])== true)?'menu-open':''; ?> "> <a href="<?= base_url('agent/calling_request');?>" class="nav-link"> <i class="nav-icon fas fa-book"></i> <p> Calling Request </p> </a></li>
                <li class="nav-item <?= (menu_active_or_inactive(['agent/sellers'])== true)?'menu-open':''; ?> "> <a href="<?= base_url('agent/sellers');?>" class="nav-link"> <i class="nav-icon fas fa-book"></i> <p> Seller </p> </a></li>
                <li class="nav-item <?= (menu_active_or_inactive(['agent/delivery_boy'])== true)?'menu-open':''; ?> "> <a href="<?= base_url('agent/delivery_boy');?>" class="nav-link"> <i class="nav-icon fas fa-book"></i> <p> Delivery Boy </p> </a></li>
                <li class="nav-item <?= (menu_active_or_inactive(['agent/campaign'])== true)?'menu-open':''; ?> "> <a href="<?= base_url('agent/campaign');?>" class="nav-link"> <i class="nav-icon fas fa-book"></i> <p> Campaign </p> </a></li>
                <li class="nav-item <?= (menu_active_or_inactive(['agent/group_post'])== true)?'menu-open':''; ?> "> <a href="<?= base_url('agent/group_post');?>" class="nav-link"> <i class="nav-icon fas fa-book"></i> <p> Group Post </p> </a></li>
                <li class="nav-item <?= (menu_active_or_inactive(['agent/settings'])== true)?'menu-open':''; ?> "> <a href="<?= base_url('agent/settings');?>" class="nav-link"> <i class="nav-icon fas fa-book"></i> <p> Settings </p> </a></li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>