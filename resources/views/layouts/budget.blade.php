<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (isset($title))
    <title>{{ $title }}</title>
    @else
    <title>AMS-System Anggaran</title>
    @endif
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
    <!--end ache toggle checkbox -->
    <!--<link href="{{ asset('css/spinner.css') }}" rel="stylesheet" /> -->
    @yield('styles')
    <style>
      body {
      /* default is 1rem or 16px */
      font-size: 14px;
      }

      /* font datatable */
      th { font-size: 14px; }
      td { font-size: 13px; }
      th, td { white-space: nowrap; }
      tr.group,
      tr.group:hover {
          background-color: #ddd !important;
      }
      div.dataTables_wrapper {
          margin: 0 auto;
      }

      .dataTables tbody tr {
        min-height: 35px; /* or whatever height you need to make them all consistent */
      }
      
      #loader {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        background: rgba(0,0,0,0.75) url("{{ asset('img/spinner.gif') }}") no-repeat center center;
        z-index: 10000;
      }

      /* buat preview image */
      .thumb{
          margin: 10px 5px 0 0;
          width: 300px;
      } 
      [data-toggle="collapse"] .fa:before {  
        content: "\f139";
      }

      [data-toggle="collapse"].collapsed .fa:before {
        content: "\f13a";
      }

      .signature-pad{
        /*width: 100%; height: 200px;*/
        width: 300px; height: 200px;
        border: 1px solid grey;
      }

      /*untuk modal autocomplete */
      .ui-autocomplete { z-index:2147483647; }
      .disabled-select {
        background-color:#d5d5d5;
        opacity:0.5;
        border-radius:3px;
        cursor:not-allowed;
        position:absolute;
        top:0;
        bottom:0;
        right:0;
        left:0;
      }
      
      .main-sidebar {background-color: #3b5998 !important;}

      .scroll-card {
          max-height: 300px;
          overflow-y: auto;
      }

      .tabbable .nav-tabs {
      overflow-x: auto;
      overflow-y:hidden;
      flex-wrap: nowrap;
      }
      .tabbable .nav-tabs .nav-link {
      white-space: nowrap;
      }

    </style>
</head>
<!--hold-transition sidebar-mini sidebar-collapse layout-fixed-->
<!--sidebar-mini layout-fixed-->
<body class="sidebar-mini layout-fixed">
<div id="loader"></div>
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
                    <a class="dropdown-item" href="{{ route("admin.home") }}">Admin</a>
                    <a class="dropdown-item active" href="{{ route("budget.dashboard.index") }}">Anggaran</a>
                    <a class="dropdown-item" href="{{ route("pos.dashboard.index") }}">POS</a>
                    <a class="dropdown-item" href="{{ route("ticketing.dashboard.index") }}">Ticketing</a>
                    <a class="dropdown-item" href="{{ route("crm.dashboard.index") }}">CRM</a>
                    <a class="dropdown-item" href="{{ route("approval.dashboard.index") }}">Approval</a>
                    <a class="dropdown-item" href="{{ route("finance.dashboard.index") }}">Finance</a>
                  </div>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"> Hello, <b> {{auth()->user()->name}} - {{auth()->user()->kode_jabatan}}</b></a>
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
            @php( $user_notif = DB::select('select top 5 id, document_id, notes, pembuat, format(created_at,\'dd-MM-yyyy\') as tgl 
            from approval.dbo.document_note where penerima=\''.auth()->user()->nik.'\' and read_date is null
            order by created_at asc')) 
            
            @php( $jum_notif = DB::select('select count(notes) as jum 
            from approval.dbo.document_note where penerima=\''.auth()->user()->nik.'\' and read_date is null')) 

            @if (isset($user_notif))
            <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                @if ($jum_notif[0]->jum > 0))
                <span class="badge badge-danger navbar-badge">{{ $jum_notif[0]->jum }}</span>
                @endif
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
               @foreach($user_notif as $key => $data_notif)
                <a href="#" onclick="return bacaNotif('{{$data_notif->id}}');" class="dropdown-item">
                  <!-- Message Start -->
                  <div class="media">
                    <div class="media-body">
                      <h3 class="dropdown-item-title">
                        {{ $data_notif->pembuat }}
                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                      </h3>
                      <p class="text-sm">{{ $data_notif->document_id }} <br> {{ $data_notif->notes }}</p>
                      <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i>{{ $data_notif->tgl }}</p>
                    </div>
                  </div>
                  <!-- Message End -->
                </a>
                @endforeach
                <div class="dropdown-divider"></div>
                <a href="{{ route("approval.notes.index") }}" class="dropdown-item dropdown-footer">See All Notes</a>
              </div>
            </li>
            @endif

            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">{{ auth()->user()->nik }} - {{ auth()->user()->name }}</span>
                <div class="dropdown-divider"></div>
                <a href="{{ route("admin.profile.index") }}" class="dropdown-item">
                  <i class="fas fa-user-tie mr-2"></i> User Profile
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
            
            <section class="content text-sm" style="padding-top: 20px">
                @include('alert')
                @include('notification')
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
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
    
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('js/ajax/moment.min.js') }}"></script>
    <script src="{{ asset('js/ajax/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('js/ajax/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('plugins/overlayScrollbars/js/OverlayScrollbars.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <!-- Bootstrap Switch -->
    <script src="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.js') }}"></script>
    <script>
      var beforeload = (new Date()).getTime();

      function getPageLoadTime() {
        //calculate the current time in afterload
        var afterload = (new Date()).getTime();
        // now use the beforeload and afterload to calculate the seconds
        seconds = (afterload - beforeload) / 1000;
        // Place the seconds in the innerHTML to show the results
        $("#load_time").text('This Page Loaded in  ' + seconds + ' sec(s). :)');
      }

      window.onload = getPageLoadTime;

      function bacaNotif(id){
        if(!confirm("Anda yakin sudah membaca notes ini?")){
            event.preventDefault();
        }else{
            var spinner = $('#loader');
            spinner.show();
            var form_data = new FormData();
            var action_url = "{{ url('approval/notes/read') }}";
            var result_msg = "Succesfully";
            form_data.append('id', id);
            form_data.append('_token', '{{csrf_token()}}');
            $.ajax({
                    url: action_url,
                    method:"POST",
                    data:form_data,
                    contentType: false,
                    processData: false,
                    success:function(data)
                    {
                        
                        if(data.errors){
                            spinner.hide();
                            alert(data.errors);
                        }

                        if(data.success){
                            alert(data.success);
                            location.reload();
                        }
                    },
                    error: function(data){
                        console.log(data.responseJSON);
                        spinner.hide();
                        alert("gagal proses membaca notes, hubungi IT");
                    }
            });
        }
      }
    </script>
    <script>
      $(function() {
        //$.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' })
        $.fn.dataTable.ext.classes.sPageButton = '';
      });
    </script>
    @yield('scripts')
</body>

</html>