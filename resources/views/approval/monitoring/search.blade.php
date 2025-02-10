@extends('layouts.approval')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Monitoring Status</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Approval</a></li>
            <li class="breadcrumb-item active"><a href="#">Monitoring Document Document</a></li>
        </ol>
        </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="card">
    <div class="card-body">
    <form id="searhform" name="searchform" method="post" action="{{ route("approval.monitoring.search") }}">
        <div class="input-group">
        @csrf
        <input type="text" id="searchtext" name="searchtext" class="form-control" placeholder="ketikan no document...">
        <div class="input-group-append">
            <button class="btn btn-secondary" type="submit">
            <i class="fa fa-search"></i>
            </button>
        </div>
        </div>
    </form>
    </div>
</div>
@if (isset($datanya))
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
            <h5>Status Document</h5>
            </div>
            <div class="card-body">
                        <div class="table-responsive">
                        <table id="table_dept" class="display compact nowrap table-bordered table-striped" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No Document</th>
                                    <th>Ref</th>
                                    <th>Nama</th>
                                    <th>Deskripsi</th>
                                    <th>Last Status</th>
                                    <th>Notes</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($datanya as $key => $data)
                                    <tr>
                                        <td> {{ $data->no_document ?? '' }}</td>
                                        <td> {{ $data->no_ref ?? '' }}</td>
                                        <td> {{ $data->nama ?? '' }}</td>
                                        <td> {{ $data->keterangan ?? '' }}</td>
                                        <td> {{ $data->status ?? '' }}</td>
                                        <th>{{ $data->alasan ?? '' }}</td>
                                        <td> {{ $data->tglin ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                No Data Found :(
            </div>
        </div>
    </div>
</div>
@endif
@endsection
@section('scripts')
@parent
<script>
$(document).ready(function(){
    $('#table_status').DataTable({
        //responsive: true,
        //fixedHeader: true,
        //buttons: [
        //    'copy', 'csv', 'excel'
        //],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    format: {
                        body: function ( data, row, column, node ) {
                            return $.isNumeric(data.replace('.', '')) ? "\0"+data : data;
                        }
                    },
                    columns: function(idx, data, node) {
                        if ($(node).hasClass('noVis')) {
                            return false;
                        }
                        return $('#table_status').DataTable().column(idx).visible();
                    }
                }
            },
            {
                extend: 'excelHtml5',
                footer: true,
                exportOptions: {
                    orthogonal: 'sort',
                    format: {
                        body: function ( data, row, column, node ) {
                            return $.isNumeric(data.replace('.', '')) ? "\0"+data : data;

                        }
                    },
                    columns: function(idx, data, node) {
                        if ($(node).hasClass('noVis')) {
                            return false;
                        }
                        return $('#table_status').DataTable().column(idx).visible();
                    }
                }
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: function(idx, data, node) {
                        if ($(node).hasClass('noVis')) {
                            return false;
                        }
                        return $('#table_status').DataTable().column(idx).visible();
                    }
                }
            },
                'colvis'
            ],
            scrollY:        true,
            scrollX:        true,
            //autoWidth:      true,
            visible: true,
            //responsive: true,
            ordering:       true,
            paging:         true
    });

</script>
@endsection