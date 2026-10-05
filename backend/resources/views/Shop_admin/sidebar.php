<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo base_url('shop_admin/dashboard') ?>" class="brand-link">

        <span class="brand-text font-weight-light"><?= Auth()->shopName; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <?php echo image_view('uploads/schools', '', Auth()->image, 'no_image.jpg', 'img-circle elevation-2', ''); ?>
            </div>
            <div class="info">
                <a href="<?php echo base_url('super_admin/shops') ?>" class="d-block">
                    <?= Auth()->userName; ?>
                </a>
            </div>
        </div>



        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item <?= (menu_active_or_inactive(['shop_admin/dashboard'])== true)?'menu-is-opening menu-open':''; ?>">
                    <a href="<?php echo base_url('shop_admin/dashboard');?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Dashboard </p>
                    </a>
                </li>

                <?php
                $urlarrayBank = menu_active_or_inactive(['shop_admin/bank','shop_admin/bank_deposit','shop_admin/bank_withdraw','shop_admin/chaque']);
                $modArrayBank = ['Bank','Bank_deposit','Bank_withdraw','Chaque'];
                $menuAccessBank = all_menu_permission_check($modArrayBank,Auth()->role_id); if ($menuAccessBank == true){
                ?>
                    <li class="nav-item treeview <?= ($urlarrayBank == true)?'menu-is-opening menu-open':''; ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p> Bank <i class="fas fa-angle-left right"></i> </p>
                        </a>
                        <ul class="nav nav-treeview" <?= ($urlarrayBank == true)?'style="display: block;"':'style="display: none;"'; ?>>
                            <?= add_main_ajax_based_menu_with_permission('Bank', 'shop_admin/bank', Auth()->role_id, 'far fa-circle nav-icon', 'Bank'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Bank Deposit', 'shop_admin/bank_deposit', Auth()->role_id, 'far fa-circle nav-icon', 'Bank_deposit'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Bank Withdraw', 'shop_admin/bank_withdraw', Auth()->role_id, 'far fa-circle nav-icon', 'Bank_withdraw'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Chaque', 'shop_admin/chaque', Auth()->role_id, 'far fa-circle nav-icon', 'Chaque'); ?>
                        </ul>
                    </li>
                <?php }
                $modArraySup = ['Suppliers'];
                $menuAccessSup = all_menu_permission_check($modArraySup,Auth()->role_id); if ($menuAccessSup == true){
                ?>
                <li class="nav-item <?= (menu_active_or_inactive(['shop_admin/suppliers'])== true)?'menu-open':''; ?>">
                    <a href="<?php echo base_url('shop_admin/suppliers');?>" class="nav-link">
                        <i class="nav-icon fas fa-user-alt"></i>
                        <p> Suppliers </p>
                    </a>
                </li>
                <?php }
                $urlArrayPurchase = menu_active_or_inactive(['shop_admin/purchase','shop_admin/return_purchase']);
                $modArrayPurchase = ['Purchase','Return_purchase'];
                $menuAccessPurchase = all_menu_permission_check($modArrayPurchase,Auth()->role_id); if ($menuAccessPurchase == true){
                ?>
                <li class="nav-item treeview <?= ($urlArrayPurchase == true)?'menu-is-opening menu-open':''; ?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p> Purchase <i class="fas fa-angle-left right"></i> </p>
                    </a>
                    <ul class="nav nav-treeview" <?= ($urlArrayPurchase == true)?'style="display: block;"':'style="display: none;"'; ?>>
                        <?= add_main_ajax_based_menu_with_permission('Purchase', 'shop_admin/purchase', Auth()->role_id, 'far fa-circle nav-icon', 'Purchase'); ?>
                        <?php  //echo add_main_ajax_based_menu_with_permission('Return Purchase', 'shop_admin/return_purchase', Auth()->role_id, 'far fa-circle nav-icon', 'Return_purchase'); ?>
                    </ul>
                </li>
                <?php }
                $urlArrayTransaction = menu_active_or_inactive(['shop_admin/transaction','shop_admin/transaction_create']);
                $modArrayTransaction = ['Transaction'];
                $menuAccessTransaction = all_menu_permission_check($modArrayTransaction,Auth()->role_id); if ($menuAccessTransaction == true){
                ?>
                <li class="nav-item treeview <?= ($urlArrayTransaction == true)?'menu-is-opening menu-open':''; ?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p> Transaction <i class="fas fa-angle-left right"></i> </p>
                    </a>
                    <ul class="nav nav-treeview" <?= ($urlArrayTransaction == true)?'style="display: block;"':'style="display: none;"'; ?>>
                        <?= add_main_ajax_based_menu_with_permission('Transaction Create', 'shop_admin/transaction_create', Auth()->role_id, 'far fa-circle nav-icon', 'Transaction'); ?>
                        <?= add_main_ajax_based_menu_with_permission('Transaction', 'shop_admin/transaction', Auth()->role_id, 'far fa-circle nav-icon', 'Transaction'); ?>
                    </ul>
                </li>
                <?php }
                $urlArrayStock = menu_active_or_inactive(['shop_admin/stores','shop_admin/products','shop_admin/product_category','shop_admin/brand']);
                $modArrayStock = ['Stores','Products','Product_category','Brand'];
                $menuAccessStock = all_menu_permission_check($modArrayStock,Auth()->role_id); if ($menuAccessStock == true){
                ?>
                    <li class="nav-item treeview <?= ($urlArrayStock == true)?'menu-is-opening menu-open':''; ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p> Stock <i class="fas fa-angle-left right"></i> </p>
                        </a>
                        <ul class="nav nav-treeview" <?= ($urlArrayStock == true)?'style="display: block;"':'style="display: none;"'; ?>>
                            <?= add_main_ajax_based_menu_with_permission('Stores', 'shop_admin/stores', Auth()->role_id, 'far fa-circle nav-icon', 'Stores'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Products', 'shop_admin/products', Auth()->role_id, 'far fa-circle nav-icon', 'Products'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Product Category', 'shop_admin/product_category', Auth()->role_id, 'far fa-circle nav-icon', 'Product_category'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Product Brand', 'shop_admin/brand', Auth()->role_id, 'far fa-circle nav-icon', 'Brand'); ?>
                        </ul>
                    </li>
                <?php }
                $urlArraySales = menu_active_or_inactive(['shop_admin/sales','shop_admin/return_sale']);
                $modArraySales = ['Sales','Return_sale'];
                $menuAccessSales = all_menu_permission_check($modArraySales,Auth()->role_id); if ($menuAccessSales == true){
                ?>
<!--                    <li class="nav-item treeview --><?php //= ($urlArraySales == true)?'menu-is-opening menu-open':''; ?><!--">-->
<!--                        <a href="#" class="nav-link">-->
<!--                            <i class="nav-icon fas fa-book"></i>-->
<!--                            <p> Sales <i class="fas fa-angle-left right"></i> </p>-->
<!--                        </a>-->
<!--                        <ul class="nav nav-treeview" --><?php //= ($urlArraySales == true)?'style="display: block;"':'style="display: none;"'; ?><!-- >-->
                            <?php //echo add_main_ajax_based_menu_with_permission('Sales', 'shop_admin/sales', Auth()->role_id, 'far fa-circle nav-icon', 'Sales'); ?>
                            <?php //echo add_main_ajax_based_menu_with_permission('Return Sale', 'shop_admin/return_sale', Auth()->role_id, 'far fa-circle nav-icon', 'Return_sale'); ?>
<!--                        </ul>-->
<!--                    </li>-->
                <?php }
                $urlArrayLedger = menu_active_or_inactive(['shop_admin/ledger_bank','shop_admin/ledger_loan','shop_admin/ledger_nagodan','shop_admin/ledger_suppliers','shop_admin/ledger_vat']);
                $modArrayLedger = ['Ledger_bank','Ledger_loan','Ledger_nagodan','Ledger_suppliers','Ledger_vat'];
                $menuAccessLedger = all_menu_permission_check($modArrayLedger,Auth()->role_id); if ($menuAccessLedger == true){
                ?>
                    <li class="nav-item treeview <?= ($urlArrayLedger == true)?'menu-is-opening menu-open':''; ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p> Ledger <i class="fas fa-angle-left right"></i> </p>
                        </a>
                        <ul class="nav nav-treeview" <?= ($urlArrayLedger == true)?'style="display: block;"':'style="display: none;"'; ?>>
                            <?= add_main_ajax_based_menu_with_permission('Ledger Bank', 'shop_admin/ledger_bank', Auth()->role_id, 'far fa-circle nav-icon', 'Ledger_bank'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Ledger Account Holder', 'shop_admin/ledger_loan', Auth()->role_id, 'far fa-circle nav-icon', 'Ledger_loan'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Ledger Cash', 'shop_admin/ledger_nagodan', Auth()->role_id, 'far fa-circle nav-icon', 'Ledger_nagodan'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Ledger Suppliers', 'shop_admin/ledger_suppliers', Auth()->role_id, 'far fa-circle nav-icon', 'Ledger_suppliers'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Ledger Vat', 'shop_admin/ledger_vat', Auth()->role_id, 'far fa-circle nav-icon', 'Ledger_vat'); ?>
                        </ul>
                    </li>
                <?php }
                $modArraySup = ['Loan_provider'];
                $menuAccessSup = all_menu_permission_check($modArraySup,Auth()->role_id); if ($menuAccessSup == true){
                ?>
                <li class="nav-item <?= (menu_active_or_inactive(['shop_admin/loan_provider'])== true)?'menu-open':''; ?>">
                    <a href="<?php echo base_url('shop_admin/loan_provider');?>" class="nav-link">
                        <i class="nav-icon fas fa-user-alt"></i>
                        <p> Account Holder </p>
                    </a>
                </li>
                <?php }

                $urlArrayEmployee = menu_active_or_inactive(['shop_admin/employee','shop_admin/ledger_employee']);
                $modArrayEmployee = ['Employee','Ledger_employee'];
                $menuAccessEmployee = all_menu_permission_check($modArrayEmployee,Auth()->role_id); if ($menuAccessEmployee == true){
                ?>
                    <li class="nav-item treeview <?= ($urlArrayEmployee == true)?'menu-is-opening menu-open':''; ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p> Employee <i class="fas fa-angle-left right"></i> </p>
                        </a>
                        <ul class="nav nav-treeview" <?= ($urlArrayEmployee == true)?'style="display: block;"':'style="display: none;"'; ?>>
                            <?= add_main_ajax_based_menu_with_permission('Employee', 'shop_admin/employee', Auth()->role_id, 'far fa-circle nav-icon', 'Employee'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Salary', 'shop_admin/ledger_employee', Auth()->role_id, 'far fa-circle nav-icon', 'Ledger_employee'); ?>
                        </ul>
                    </li>
                <?php }
                $urlArrayInvoice = menu_active_or_inactive(['shop_admin/invoice','shop_admin/balance_report','shop_admin/stock_report','shop_admin/sales_report','shop_admin/purchase_report','shop_admin/seller_commission','shop_admin/seller_commission_report','shop_admin/delivery_boy_commission','shop_admin/delivery_boy_commission_report','shop_admin/shop_commission']);
                $modArrayInvoice = ['Invoice','Balance_report','Stock_report','Sales_report','Purchase_report','Seller_commission','Delivery_boy_commission','Shop_commission'];
                $menuAccessInvoice = all_menu_permission_check($modArrayInvoice,Auth()->role_id); if ($menuAccessInvoice == true){
                    ?>
                    <li class="nav-item treeview <?= ($urlArrayInvoice == true)?'menu-is-opening menu-open':''; ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p> Report <i class="fas fa-angle-left right"></i> </p>
                        </a>
                        <ul class="nav nav-treeview" <?= ($urlArrayInvoice == true)?'style="display: block;"':'style="display: none;"'; ?>>
                            <?= add_main_ajax_based_menu_with_permission('Invoice', 'shop_admin/invoice', Auth()->role_id, 'far fa-circle nav-icon', 'Invoice'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Balance Report', 'shop_admin/balance_report', Auth()->role_id, 'far fa-circle nav-icon', 'Balance_report'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Stock Report', 'shop_admin/stock_report', Auth()->role_id, 'far fa-circle nav-icon', 'Stock_report'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Sales Report', 'shop_admin/sales_report', Auth()->role_id, 'far fa-circle nav-icon', 'Sales_report'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Purchase Report', 'shop_admin/purchase_report', Auth()->role_id, 'far fa-circle nav-icon', 'Purchase_report'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Seller Commission', 'shop_admin/seller_commission', Auth()->role_id, 'far fa-circle nav-icon', 'Seller_commission'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Seller Commission Report', 'shop_admin/seller_commission_report', Auth()->role_id, 'far fa-circle nav-icon', 'Seller_commission'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Delivery Boy Commission', 'shop_admin/delivery_boy_commission', Auth()->role_id, 'far fa-circle nav-icon', 'Delivery_boy_commission'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Delivery Boy Commission Report', 'shop_admin/delivery_boy_commission_report', Auth()->role_id, 'far fa-circle nav-icon', 'Delivery_boy_commission'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Shop Commission', 'shop_admin/shop_commission', Auth()->role_id, 'far fa-circle nav-icon', 'Shop_commission'); ?>
                        </ul>
                    </li>
                <?php }
                $modArraySup = ['Daily_book'];
                $menuAccessSup = all_menu_permission_check($modArraySup,Auth()->role_id); if ($menuAccessSup == true){
                ?>
                    <li class="nav-item <?= (menu_active_or_inactive(['shop_admin/daily_book'])== true)?'menu-open':''; ?>">
                        <a href="<?php echo base_url('shop_admin/daily_book');?>" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p> Daily Book </p>
                        </a>
                    </li>
                <?php }
                $urlArrayEmployee = menu_active_or_inactive(['shop_admin/user','shop_admin/role']);
                $modArrayEmployee = ['User','Role'];
                $menuAccessEmployee = all_menu_permission_check($modArrayEmployee,Auth()->role_id); if ($menuAccessEmployee == true){
                    ?>
                    <li class="nav-item treeview <?= ($urlArrayEmployee == true)?'menu-is-opening menu-open':''; ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p> User <i class="fas fa-angle-left right"></i> </p>
                        </a>
                        <ul class="nav nav-treeview" <?= ($urlArrayEmployee == true)?'style="display: block;"':'style="display: none;"'; ?>>
                            <?= add_main_ajax_based_menu_with_permission('User', 'shop_admin/user', Auth()->role_id, 'far fa-circle nav-icon', 'User'); ?>
                            <?= add_main_ajax_based_menu_with_permission('User Role', 'shop_admin/role', Auth()->role_id, 'far fa-circle nav-icon', 'Role'); ?>
                        </ul>
                    </li>
                <?php }
                $modArraySup = ['Campaign'];
                $menuAccessSup = all_menu_permission_check($modArraySup,Auth()->role_id); if ($menuAccessSup == true){
                    ?>
                    <li class="nav-item <?= (menu_active_or_inactive(['shop_admin/campaign'])== true)?'menu-open':''; ?>">
                        <a href="<?php echo base_url('shop_admin/campaign');?>" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p> Campaign </p>
                        </a>
                    </li>
                <?php }
                $urlArrayEmployee = menu_active_or_inactive(['shop_admin/my_post','shop_admin/group_post']);
                $modArrayEmployee = ['My_post','Group_post'];
                $menuAccessEmployee = all_menu_permission_check($modArrayEmployee,Auth()->role_id); if ($menuAccessEmployee == true){
                    ?>
                    <li class="nav-item treeview <?= ($urlArrayEmployee == true)?'menu-is-opening menu-open':''; ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p> Group Post <i class="fas fa-angle-left right"></i> </p>
                        </a>
                        <ul class="nav nav-treeview" <?= ($urlArrayEmployee == true)?'style="display: block;"':'style="display: none;"'; ?>>
                            <?= add_main_ajax_based_menu_with_permission('My Post', 'shop_admin/my_post', Auth()->role_id, 'far fa-circle nav-icon', 'My_post'); ?>
                            <?= add_main_ajax_based_menu_with_permission('Group Post', 'shop_admin/group_post', Auth()->role_id, 'far fa-circle nav-icon', 'Group_post'); ?>
                        </ul>
                    </li>
                <?php }
                $modArraySup = ['Message'];
                $menuAccessSup = all_menu_permission_check($modArraySup,Auth()->role_id); if ($menuAccessSup == true){
                    ?>
                    <li class="nav-item <?= (menu_active_or_inactive(['shop_admin/message'])== true)?'menu-open':''; ?>">
                        <a href="<?php echo base_url('shop_admin/message');?>" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p> Message </p>
                        </a>
                    </li>
                <?php }
                $modArraySup = ['Settings'];
                $menuAccessSup = all_menu_permission_check($modArraySup,Auth()->role_id); if ($menuAccessSup == true){
                    ?>
                    <li class="nav-item <?= (menu_active_or_inactive(['shop_admin/settings'])== true)?'menu-open':''; ?>">
                        <a href="<?php echo base_url('shop_admin/settings');?>" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p> Settings </p>
                        </a>
                    </li>
                <?php }
                    $modArraySup = ['Sms_panel'];
                    $menuAccessSup = all_menu_permission_check($modArraySup,Auth()->role_id); if ($menuAccessSup == true){
                ?>
                    <li class="nav-item <?= (menu_active_or_inactive(['shop_admin/sms_panel'])== true)?'menu-open':''; ?>">
                        <a href="<?php echo base_url('shop_admin/sms_panel');?>" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p> Sms panel </p>
                        </a>
                    </li>
                <?php }
                $modArraySup = ['Warranty_manage'];
                $menuAccessSup = all_menu_permission_check($modArraySup,Auth()->role_id); if ($menuAccessSup == true){
                ?>
                <li class="nav-item <?= (menu_active_or_inactive(['shop_admin/warranty_manage'])== true)?'menu-open':''; ?>">
                    <a href="<?php echo base_url('shop_admin/warranty_manage');?>" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p> Warranty manage </p>
                    </a>
                </li>
                <?php } ?>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>