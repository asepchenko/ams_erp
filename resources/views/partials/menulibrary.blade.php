<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route("library.dashboard.index") }}" class="brand-link">
        <img src="{{ asset('img/amswhitenew.png') }}" alt="Logo" class="brand-image">
        <span class="brand-text font-weight-light">E-Library</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar text-sm">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        @can('library_app_access')
        <nav class="mt-2">
            <ul class="nav nav-pills nav-flat nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route("library.dashboard.index") }}" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route("library.file.index") }}" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>File</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a class="nav-link nav-dropdown-toggle">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p><span>Admin</span>
                        <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route("approval.my-report.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-th-large"></i>
                                <p>File Category</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("approval.my-report.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>File Type</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("approval.my-report.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-th-list"></i>
                                <p>File Variety</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("approval.my-report.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-map-marker-alt"></i>
                                <p>File Location</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("approval.my-report.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>File Status</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("approval.my-report.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>File Status Pinjam</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("approval.my-report.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-cube"></i>
                                <p>File Space</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a class="nav-link nav-dropdown-toggle">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p><span>Peminjaman</span>
                        <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route("library.peminjaman.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>List Peminjaman</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a class="nav-link nav-dropdown-toggle">
                        <i class="nav-icon fas fa-file"></i>
                        <p><span>Report</span>
                        <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route("approval.my-report.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>Report Peminjaman</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        @endcan
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>