@extends('layouts.budget')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-8">
        <h1 class="m-0 text-dark">Report Detil </h1>
      </div><!-- /.col -->
      <div class="col-sm-4">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Budgeting</a></li>
          <li class="breadcrumb-item active"><a href="#">Report Detil</a></li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="card">
    <div class="card-header" id="headingOne">
    <span class="float-md-left">
    <h5>Filter</h5>
    </span>
        <span class="float-md-right">
        <h5 class="mb-0">
          <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          <i class="fa" aria-hidden="true"></i>
          </button>
        </h5>
        </span>
      </div>
      <!-- Pencarian ada disini -->

      <!-- end pencarian didisni -->
</div>

@if (isset($datanya))
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_data" class="cell-border" style="width:100%">
                        <thead>
                                <tr>
                                    <th>Anggaran</th>
                                    <th>Doc ID</th>
                                    <th>Nama</th>
                                    <th>Keterangan</th>
                                    <th>Tgl Buat</th>
                                    <th>Tgl Realisasi</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $current_anggaran = null;
                                $anggaran = null;
                                @endphp
                                @foreach ($datanya as $data)
                                {{-- @foreach ($databudget as $dbudget) --}}

                                    {{-- @if ($loop->index > 0 && $current_kode_group != $data->descanggaran)
                                    @include ('budget.reportkodanggaran.subtotal', compact('datanya', 'databudget', 'current_kode_group'))
                                    @endif --}}
                                    <tr>
                                    
                                        @php $current_anggaran = $data->descanggaran;
                                        $anggaran = $data->kode_anggaran
                                        @endphp
                                        <td>{{ $data->descanggaran }}</td>
                                        <td> {{ $data->document_id }}</td>
                                        <td> {{ $data->nama }}</td>
                                        <td>{{ $data->keterangan }}</td>
                                        <td> {{ $data->tanggal_buat }}</td>
                                        <td> {{ $data->tanggal_realisasi }}</td>
                                        <td>{{ number_format($data->pemakaian,0) }}</td>
                                    </tr>
                                    {{-- @if ($loop->last)
                                        @include ('budget.reportkodanggaran.subtotal', compact('datanya','databudget','current_kode_group','anggaran'))
                                        @include ('budget.reportkodanggaran.total', compact('datanya','anggaran','databudget'))
                                    @endif --}}
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
    $('#table_data').DataTable({
        // responsive: false,
        // fixedHeader: false,
        // paging: true,
            // scrollY:        true,
            // scrollX:        true,
            // autoWidth:      false,
            // visible: true,
            // responsive: true,
            // ordering:       false,
            // paging:         false,
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
                            data = $.isNumeric(data.replace('.', '')) ? "\0"+data : data;
                            //return $.isNumeric(data.replace('.', '')) ? "\0"+data : data;
                            data = data.replace(/<.*?>/g, "");
                            return data;
                        }
                    },
                    columns: function(idx, data, node) {
                        if ($(node).hasClass('noVis')) {
                            return false;
                        }
                        return $('#table_data').DataTable().column(idx).visible();
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
                            data = $.isNumeric(data.replace('.', '')) ? "\0"+data : data;
                            //return $.isNumeric(data.replace('.', '')) ? "\0"+data : data;
                            data = data.replace(/<.*?>/g, "");
                            return data;
                        }
                    },
                    columns: function(idx, data, node) {
                        if ($(node).hasClass('noVis')) {
                            return false;
                        }
                        return $('#table_data').DataTable().column(idx).visible();
                    }
                }
            },
            {
                extend: 'pdfHtml5',
                footer: true,
                exportOptions: {
                    orthogonal: 'sort',
                    format: {
                        body: function ( data, row, column, node ) {
                            data = $.isNumeric(data.replace('.', '')) ? "\0"+data : data;
                            //return $.isNumeric(data.replace('.', '')) ? "\0"+data : data;
                            data = data.replace(/<.*?>/g, "");
                            return data;
                        }
                    },
                    columns: function(idx, data, node) {
                        if ($(node).hasClass('noVis')) {
                            return false;
                        }
                        return $('#table_data').DataTable().column(idx).visible();
                    }
                }
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                    format: {
                        body: function ( data, row, column, node ) {
                            data = $.isNumeric(data.replace('.', '')) ? "\0"+data : data;
                            //return $.isNumeric(data.replace('.', '')) ? "\0"+data : data;
                            data = data.replace(/<.*?>/g, "");
                            return data;
                        }
                    },
                    columns: function(idx, data, node) {
                        if ($(node).hasClass('noVis')) {
                            return false;
                        }
                        return $('#table_data').DataTable().column(idx).visible();
                    }
                }
            },
                'colvis'
            ],

    });

    $('#btnFiterSubmitSearch').click(function(){
        var spinner = $('#loader');
        var start_period = $("#start_period option:selected").val();
        var end_period = $("#end_period option:selected").val();
        var group = $("#group option:selected").val();
        var tahun = $("#tahun option:selected").val();
        spinner.show();
        window.location.href = "{{ url('budget/reportkodanggaran') }}"+ "/search/" +  start_period + "/" + tahun + "/" + group + "";
    });


});
</script>
@endsection