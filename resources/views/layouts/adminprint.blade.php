<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AMS - Admin</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/datatable/css/scroller.dataTables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/datatable/css/select.dataTables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/datatable/css/buttons.dataTables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/datatable/css/fixedColumns.dataTables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/datatable/css/fixedHeader.dataTables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <!--ache toggle checkbox -->
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">    
    <link rel="icon" type="image/x-icon" href="{{ asset('/img/pavicon.ico') }}"/>
    @yield('styles')
    <style>
      body {
      /* default is 1rem or 16px */
      font-size: 14px;
      }
    </style>
</head>

<body class="sidebar-mini layout-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand bg-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Select Apps
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item active" href="{{ route("admin.home") }}">Admin</a>
                    <a class="dropdown-item" href="{{ route("budget.dashboard.index") }}">Anggaran</a>
                    <a class="dropdown-item" href="{{ route("pos.dashboard.index") }}">POS</a>
                    <a class="dropdown-item" href="{{ route("ticketing.dashboard.index") }}">Ticketing</a>
                    <a class="dropdown-item" href="{{ route("crm.dashboard.index") }}">CRM</a>
                    <a class="dropdown-item" href="{{ route("approval.dashboard.index") }}">Approval</a>
                    <a class="dropdown-item" href="{{ route("finance.dashboard.index") }}">Finance</a>
                  </div>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"> Hello, <b> {{auth()->user()->name}}</b></a>
                </li>
            </ul>
            <!-- SEARCH FORM -->
            <!--<form class="form-inline ml-3">
              <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </form>-->
            <!-- Right navbar links -->
            @if(count(config('panel.available_languages', [])) > 1)
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            {{ strtoupper(app()->getLocale()) }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @foreach(config('panel.available_languages') as $langLocale => $langName)
                                <a class="dropdown-item" href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langLocale) }} ({{ $langName }})</a>
                            @endforeach
                        </div>
                    </li>
                </ul>
            @endif
            <ul class="navbar-nav ml-auto">
              
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <span class="dropdown-item dropdown-header">{{ auth()->user()->nik }} - {{ auth()->user()->name }}</span>
                <div class="dropdown-divider"></div>
                <a href="{{ route("admin.profile.index") }}" class="dropdown-item">
                  <i class="fas fa-user-tie mr-2"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                  <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </li>
          </ul>
        </nav>

        @include('partials.menubudget')
        <div class="content-wrapper" style="min-height: 917px;">
            <!-- Main content -->
            <section class="content" style="padding-top: 20px">
                @include('alert')
                @yield('content')
            </section>
            <!-- /.content -->
        </div>

        <footer class="main-footer">
            <p align="center" id="load_time"></p>
            <div class="float-right d-none d-sm-block">
                AMS ERP v1.0.0
            </div>
            PT. Aditya Mandiri Sejahtera <strong> &copy;</strong> 2020. {{ trans('global.allRightsReserved') }}
        </footer>
        <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatable/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('plugins/datatable/js/dataTables.scroller.min.js') }}"></script>
    <script src="{{ asset('plugins/datatable/js/dataTables.fixedColumns.min.js') }}"></script>
    <script src="{{ asset('plugins/datatable/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>

    <script src="{{ asset('plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/js/vfs_fonts.js') }}"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>
    
    
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('js/ajax/moment.min.js') }}"></script>
    <script src="{{ asset('js/ajax/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('js/ajax/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/overlayScrollbars/js/OverlayScrollbars.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script>
        $(function() {
  let copyButtonTrans = '{{ trans('global.datatables.copy') }}'
  let csvButtonTrans = '{{ trans('global.datatables.csv') }}'
  let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
  let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'
  let printButtonTrans = '{{ trans('global.datatables.print') }}'
  let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'

  let languages = {
    'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
  };

  $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' })
  $.extend(true, $.fn.dataTable.defaults, {
    language: {
      url: languages.{{ app()->getLocale() }}
    },
    columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        targets: 0
    }, {
        orderable: false,
        searchable: false,
        targets: -1
    }],
    select: {
      style:    'multi+shift',
      selector: 'td:first-child'
    },
    order: [],
    scrollX: true,
    pageLength: 10,
    dom: 'lBfrtip<"actions">',
    buttons: [
      {
        extend: 'copy',
        className: 'btn-default',
        text: copyButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'csv',
        className: 'btn-default',
        text: csvButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'excel',
        className: 'btn-default',
        text: excelButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'pdf',
        className: 'btn-default',
        text: pdfButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'print',
        className: 'btn-default',
        text: printButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'colvis',
        className: 'btn-default',
        text: colvisButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      }
    ]
  });

  $.fn.dataTable.ext.classes.sPageButton = '';
});

    </script>
    @yield('scripts')
    <script>
    </script>
</body>

</html>