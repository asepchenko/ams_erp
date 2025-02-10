<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route("finance.dashboard.index") }}" class="brand-link">
        <img src="{{ asset('img/amswhitenew.png') }}" alt="Logo" class="brand-image">
        <span class="brand-text font-weight-light">Finance</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar text-sm">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        @can('finance_app_access')
        <nav class="mt-2">
            <ul class="nav nav-pills nav-flat nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route("finance.dashboard.index") }}" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                    </a>
                </li>

                @can('finance_realisasi')
                    <li class="nav-item has-treeview">
                        <a class="nav-link nav-dropdown-toggle">
                            <i class="nav-icon fas fa-hand-holding-usd">

                            </i>
                            <p>
                                <span>Realisasi</span>
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route("finance.realisasi-document.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Document
                                </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('finance_report')
                    <!--<li class="nav-item has-treeview">
                        <a class="nav-link nav-dropdown-toggle">
                            <i class="nav-icon fas fa-file">

                            </i>
                            <p>
                                <span>Report</span>
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route("finance.dashboard.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Report
                                </p>
                                </a>
                            </li>
                        </ul>
                    </li>-->
                @endcan

            </ul>
        </nav>
        @endcan
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>