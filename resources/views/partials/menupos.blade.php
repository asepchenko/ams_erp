<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route("pos.dashboard.index") }}" class="brand-link">
        <img src="{{ asset('img/amswhitenew.png') }}" alt="Logo" class="brand-image">
        <span class="brand-text font-weight-light">POS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar text-sm">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-flat nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route("pos.dashboard.index") }}" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                    </a>
                </li>
                <!--<li class="nav-item">
                    <a href="{{ route("admin.selisih.index") }}" class="nav-link">
                    <i class="nav-icon fas fa-envelope-open"></i>
                    <p>
                        Selisih SO
                    </p>
                    </a>
                </li>-->
                @can('pos_report_sales_access')
                    <li class="nav-item has-treeview">
                        <a class="nav-link nav-dropdown-toggle">
                            <i class="nav-icon fas fa-coins">

                            </i>
                            <p>
                                <span>Sales</span>
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('pos_daily_report_sales_all_access')
                            <li class="nav-item">
                                <a href="{{ route("pos.dailyreportall.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>
                                    Daily Sales Report All
                                </p>
                                </a>
                            </li>
                            @endcan

                            @can('pos_daily_report_sales_gh_access')
                            <li class="nav-item">
                                <a href="{{ route("pos.dailyreportgh.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>
                                    Daily Sales Report by GH
                                </p>
                                </a>
                            </li>
                            @endcan
                            <li class="nav-item">
                                <a href="{{ route("pos.sales-detail.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>
                                    Sales Detail
                                </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('pos_master_data_access')
                    <li class="nav-item has-treeview">
                        <a class="nav-link nav-dropdown-toggle">
                            <i class="nav-icon fas fa-table">

                            </i>
                            <p>
                                <span>Master Data</span>
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('pos_store_target_access')
                                <li class="nav-item">
                                    <a href="{{ route("pos.storetarget.index") }}" class="nav-link">
                                        <i class="fas nav-icon fa-bullseye"></i>
                                        <p>
                                            <span>Store Target</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>