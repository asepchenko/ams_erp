<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route("crm.dashboard.index") }}" class="brand-link">
        <img src="{{ asset('img/amswhitenew.png') }}" alt="Logo" class="brand-image">
        <span class="brand-text font-weight-light">CRM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar text-sm">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-flat nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route("crm.dashboard.index") }}" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                    </a>
                </li>
                @can('crm_app_access')
                    <li class="nav-item has-treeview">
                        <a class="nav-link nav-dropdown-toggle">
                            <i class="nav-icon fas fa-user-friends">
                            </i>
                            <p>
                                <span>Customer</span>
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route("crm.customerstore.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-address-card"></i>
                                <p>
                                    Customer Store List
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("approval.file.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-address-book"></i>
                                <p>
                                    Customer List
                                </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                <li class="nav-item has-treeview">
                        <a class="nav-link nav-dropdown-toggle">
                            <i class="nav-icon fas fa-mobile-alt">
                            </i>
                            <p>
                                <span>Member Mobile App</span>
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route("crm.mobile-member.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-user-check"></i>
                                <p>
                                    Active Member List
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("crm.mobile-promo.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>
                                    Promo List
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("crm.mobile-category.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-folder"></i>
                                <p>
                                    Category List
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("crm.mobile-product.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-tshirt"></i>
                                <p>
                                    Product List
                                </p>
                                </a>
                            </li>
                        </ul>
                    </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>