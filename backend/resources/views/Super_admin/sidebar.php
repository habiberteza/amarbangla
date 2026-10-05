<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo base_url('super_admin/shops')?>" class="brand-link">
<!--        <img src="--><?php //echo base_url()?><!--assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">-->
        <span class="brand-text font-weight-light">Amar Bangla</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <?php echo image_view('uploads/admin_image', '', profile_image_super(), 'no_image.jpg', 'img-circle elevation-2', '');?>
            </div>
            <div class="info">
                <a href="<?php echo base_url('super_admin/shops')?>" class="d-block"><?php echo newSession()->sup_name;?></a>
            </div>
        </div>



        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/shops'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/shops')?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Shops </p>
                    </a>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/shop_category'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/shop_category')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p> Shop Category </p>
                    </a>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/license'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/license')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p> License </p>
                    </a>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/settings'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/settings')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p> Settings </p>
                    </a>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/shops_commission'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/shops_commission')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p> Shops commission </p>
                    </a>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/ledger'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/ledger')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p> Ledger </p>
                    </a>
                </li>
                <?php
                $urlarrayCus = menu_active_or_inactive(['super_admin/customer_type','super_admin/customer']);
                ?>

                <li class="nav-item <?= ($urlarrayCus == true)?'menu-is-opening menu-open':''; ?>">
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Customers
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview" <?= ($urlarrayCus == true)?'style="display: block;"':'style="display: none;"'; ?>>
                        <li class="nav-item">
                            <a href="<?php echo base_url('super_admin/customer_type')?>" class="nav-link <?= (menu_active_or_inactive(['super_admin/customer_type'])== true)?'active':''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Customers Type</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('super_admin/customer')?>" class="nav-link <?= (menu_active_or_inactive(['super_admin/customer'])== true)?'active':''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Customer List</p>
                            </a>
                        </li>

                    </ul>
                </li>


                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/order'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/order')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p> Orders </p>
                    </a>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/calling_request'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/calling_request')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p> Calling request </p>
                    </a>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/sellers'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/sellers')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p> Seller </p>
                    </a>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/delivery_boy'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/delivery_boy')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p> Delivery Boy </p>
                    </a>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/agent'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/agent')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p> Agent </p>
                    </a>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/global_address'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/global_address')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Global Address </p>
                    </a>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/demo_product_category'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/demo_product_category')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Product category</p>
                    </a>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/demo_product'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/demo_product')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Product</p>
                    </a>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/product_color'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/product_color')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Product Color</p>
                    </a>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/campaign'])== true)?'menu-is-opening menu-open':''; ?> ">
                    <a href="<?php echo base_url('super_admin/campaign')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Campaign</p>
                    </a>
                </li>
                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/group_post'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/group_post')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Group Post</p>
                    </a>
                </li>
                <?php
                $urlarraySettings = menu_active_or_inactive(['super_admin/website_settings/logo','super_admin/website_settings/banner','super_admin/website_settings/banner_product','super_admin/website_settings/slider','super_admin/website_settings/footer','super_admin/website_settings/mobile_slider','super_admin/website_settings/home_banner']);
                ?>
                <li class="nav-item <?= ($urlarraySettings == true)?'menu-is-opening menu-open':''; ?>" >
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Website settings
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" <?= ($urlarraySettings == true)?'style="display: block;"':'style="display: none;"'; ?>>
                        <li class="nav-item ">
                            <a href="<?php echo base_url('super_admin/website_settings/logo')?>" class="nav-link <?= (menu_active_or_inactive(['super_admin/website_settings/logo'])== true)?'active':''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Logo</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('super_admin/website_settings/banner')?>" class="nav-link <?= (menu_active_or_inactive(['super_admin/website_settings/banner'])== true)?'active':''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Banner</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('super_admin/website_settings/banner_product')?>" class="nav-link <?= (menu_active_or_inactive(['super_admin/website_settings/banner_product'])== true)?'active':''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Banner product page</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('super_admin/website_settings/slider')?>" class="nav-link <?= (menu_active_or_inactive(['super_admin/website_settings/slider'])== true)?'active':''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Slider</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo base_url('super_admin/website_settings/footer')?>" class="nav-link <?= (menu_active_or_inactive(['super_admin/website_settings/footer'])== true)?'active':''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Footer</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo base_url('super_admin/website_settings/mobile_slider')?>" class="nav-link <?= (menu_active_or_inactive(['super_admin/website_settings/mobile_slider'])== true)?'active':''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mobile Slider</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo base_url('super_admin/website_settings/home_banner')?>" class="nav-link <?= (menu_active_or_inactive(['super_admin/website_settings/home_banner'])== true)?'active':''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Home banner</p>
                            </a>
                        </li>



                    </ul>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/sms_panel'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/sms_panel')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Sms Panel</p>
                    </a>
                </li>

                <li class="nav-item <?= (menu_active_or_inactive(['super_admin/general_settings'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('super_admin/general_settings')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>General settings</p>
                    </a>
                </li>


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>