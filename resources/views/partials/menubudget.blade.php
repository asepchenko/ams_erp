<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route("budget.dashboard.index") }}" class="brand-link">
        <img src="{{ asset('img/amswhitenew.png') }}" alt="Logo" class="brand-image">
        <span class="brand-text font-weight-light">Anggaran System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar text-sm">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        @can('budget_app_access')
        <nav class="mt-2">
            <ul class="nav nav-pills nav-flat nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="{{ route("budget.dashboard2024.index") }}" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                    </a>
                </li>
                

                @can('budget_new_access')
                    <li class="nav-item has-treeview">
                        <a class="nav-link nav-dropdown-toggle">
                            <i class="nav-icon fas fa-copy">

                            </i>
                            <p>
                                <span>Anggaran</span>
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route("budget.budgeting.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Anggaran List
                                </p>
                                </a>
                            </li>
                            @can('budget_new_crud')
                            <li class="nav-item">
                                <a href="{{ route("budget.budgetingnew.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Master Data Budget
                                </p>
                                </a>
                            </li>
                             <li class="nav-item">
                                <a href="{{ route("budget.anggaran.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Refill Anggaran
                                </p>
                                </a>
                            </li>
                            @endcan
                           {{-- @can('budget_anggaran_addonaccess')
                            <li class="nav-item">
                                <a href="{{ route("budget.addonanggaran.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Add ON Anggaran                                
                                </p>
                                </a>
                            </li>
                            @endcan --}}
                        </ul>
                    </li>
                @endcan
                    {{-- <li class="nav-item">
                        <a href="{{ route("budget.dashboard.index") }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard 2023
                        </p>
                        </a>
                    </li> --}}

                @can('budget_report_anggaran')
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
                            <li class="nav-item">
                                <a href="{{ route("budget.reportanggaran.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Anggaran List
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("budget.reportrealisasi.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Tahunan Realisasi
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("budget.reportkumulatif.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Kumulatif Periode
                                </p>
                                </a>
                            </li>  
                            <li class="nav-item">
                                <a href="{{ route("budget.reportperbulan.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Periode Bulanan
                                </p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a href="{{ route("budget.reportkodanggaran.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Report Kode Anggaran
                                </p>
                                </a>
                            </li>   
                            <li class="nav-item">
                                <a href="{{ route("budget.reportdetail.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Report Detail
                                </p>
                                </a>
                            </li>    --}}
                        </ul>
                    </li>
                @endcan
                {{-- @can('budget_report_anggaran')
                    <li class="nav-item has-treeview">
                        <a class="nav-link nav-dropdown-toggle">
                            <i class="nav-icon fas fa-file">

                            </i>
                            <p>
                                <span>Report Khusus</span>
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route("budget.reportcf.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    CF
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("budget.incomecf.index") }}" class="nav-link">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Data Income
                                </p>
                                </a>
                            </li>
                            
                        </ul>
                    </li>
                @endcan --}}

            </ul>
        </nav>
        @endcan
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>