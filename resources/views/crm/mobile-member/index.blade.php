@extends('layouts.crm')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Active Member List</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">CRM</a></li>
            <li class="breadcrumb-item"><a href="#">Member Mobile App</a></li>
            <li class="breadcrumb-item active">Member List</li>
        </ol>
        </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Data</h5>
            </div>
            <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_data" class="display compact" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>E-mail</th>
                                    <th>Tanggal Aktivasi</th>
                                    <th>Last Aktif</th>
                                    <th>Koordinat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('scripts')
@parent
<script>
$(document).ready(function(){
    /* dibutuhkan jika ajax butuh autentikasi token */
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('#table_data').DataTable({
        paging: true,
        //responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ url('crm/mobile-member') }}",
          type: 'GET'
         },
        columns: [
            { data: 'nama', name: 'nama' },
            { data: 'email', name: 'email' },
            { data: 'first_login', name: 'first_login' },
            { data: 'last_active', name: 'last_active' },
            { data: 'latlong', name: 'latlong' },
            { data: 'action', name: 'action', orderable: false }
        ]
    });
});
</script>
@endsection