<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route("approval.dashboard.index") }}" class="brand-link">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
        <span class="brand-text font-weight-light">Approval System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar text-sm">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        @can('approval_app_access')
        <nav class="mt-2">
            <ul class="nav nav-pills nav-flat nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route("approval.dashboard.index") }}" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                    </a>
                </li>

                @can('approval_document_access')
                    <li class="nav-item">
                        <a href="{{ route("approval.tempkasbon.index") }}" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>
                            Transfer Kasbon (Temp)
                        </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route("approval.outstanding-kasbon.index") }}" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>
                            Kasbon Outstanding
                        </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route("approval.monitoring.index") }}" class="nav-link">
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>
                                Monitoring Status Document
                            </p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview">
                        <a class="nav-link nav-dropdown-toggle">
                            <i class="nav-icon fas fa-copy">

                            </i>
                            <p>
                                <span>Document</span>
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route("approval.document.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Document List
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("approval.notes.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-comment-dots"></i>
                                <p>
                                    Document Notes
                                    <span class="right badge badge-danger">New</span>
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("approval.file.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-file"></i>
                                <p>
                                    File List
                                </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('approval_report_document')
                    <li class="nav-item has-treeview">
                        <a class="nav-link nav-dropdown-toggle">
                            <i class="nav-icon fas fa-file">

                            </i>
                            <p>
                                <span>Report</span>
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <!--<li class="nav-item">
                                <a href="{{ route("approval.my-report.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    My Report
                                    <span class="right badge badge-danger">New</span>
                                </p>
                                </a>
                            </li>-->
                            <li class="nav-item">
                                <a href="{{ route("approval.report.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Report Document
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("approval.spd-report.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Report SPD
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("approval.kasbon-report.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Report Kasbon
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("approval.outstanding-report.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Report Kasbon Outstanding
                                </p>
                                </a>
                            </li>                            
                        </ul>
                    </li>
                @endcan

            </ul>
        </nav>
        @endcan
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>