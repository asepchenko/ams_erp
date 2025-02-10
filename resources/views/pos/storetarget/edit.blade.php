@extends('layouts.pos')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Store Target</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">POS</a></li>
            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
            <li class="breadcrumb-item"><a href="{{ url('pos/storetarget') }}">Store Target</a></li>
            <li class="breadcrumb-item active">{{ $store }}</li>
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
                <a class="btn btn-primary btn-sm" href="{{ url('pos/storetarget') }}">List Target</a>
                <hr>
                    <div class="table-responsive">
                        <table id="table_data" class="display compact" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Store ID</th>
                                    <th>Store Target</th>
                                    <th>Hari</th>
                                    <th>Bulan</th>
                                    <th>Tahun</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($datanya as $key => $data)
                                <tr>
                                    <td> {{ $data->STORE_ID ?? '' }}</td>
                                    <td> {{ str_replace(',', '.',number_format($data->STORE_TARGET, 0)) ?? '' }}</td>
                                    <td> {{ $data->HARI ?? '' }}</td>
                                    <td> {{ $data->BULAN ?? '' }}</td>
                                    <td> {{ $data->TAHUN ?? '' }}</td>
                                    <td> </td>
                                </tr>
                                @endforeach
                            </tbody>
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
    $('#table_data').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel'
        ],
            scrollY:        false,
            scrollX:        true,
            autoWidth:      true,
            ordering:       false,
            paging:         false
    });
});
</script>
@endsection