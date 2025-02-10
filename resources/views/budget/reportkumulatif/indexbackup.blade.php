@extends('layouts.budget')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-8">
        <h1 class="m-0 text-dark">Report Periode Kumulatif </h1>
      </div><!-- /.col -->
      <div class="col-sm-4">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Budgeting</a></li>
          <li class="breadcrumb-item"><a href="#">Report</a></li>
          <li class="breadcrumb-item active">Anggaran Report</li>
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

      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <label for="start_period" class="col-sm-4 col-form-label">Periode Awal</label>
                            <div class="col-sm-8">
                            <select class="form-control" name="start_period" id="start_period">
                                <option value="pilih">Pilih</option>
                                <option value="q1" {{ ( $start_period == "q1") ? 'selected' : '' }}>Quarter I</option>
                                <option value="q2" {{ ( $start_period == "q2") ? 'selected' : '' }}>Quarter II</option>
                                <option value="q3" {{ ( $start_period == "q3") ? 'selected' : '' }}>Quarter III</option>
                                <option value="q4" {{ ( $start_period == "q4") ? 'selected' : '' }}>Quarter IV</option>
                            </select>
                            </div>
                        </div>
                    </form>
                </div> <!-- div col-4-->
                <div class="col-md-6">
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <label for="end_period" class="col-sm-4 col-form-label">Periode Akhir</label>
                            <div class="col-sm-8">
                            <select class="form-control" name="end_period" id="end_period">
                                <option value="pilih">Pilih</option>
                                <option value="q1" {{ ( $end_period == "q1") ? 'selected' : '' }}>Quarter I</option>
                                <option value="q2" {{ ( $end_period == "q2") ? 'selected' : '' }}>Quarter II</option>
                                <option value="q3" {{ ( $end_period == "q3") ? 'selected' : '' }}>Quarter III</option>
                                <option value="q4" {{ ( $end_period == "q4") ? 'selected' : '' }}>Quarter IV</option>
                            </select>
                            </div>
                        </div>
                    </form>
                </div> <!-- div col-4-->
            </div>
            <div class="row">       
                <div class="col-md-6">
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <label for="tahun" class="col-sm-4 col-form-label">Tahun</label>
                            <div class="col-sm-8">
                            <select class="form-control" name="tahun" id="tahun">
                                <option value="now">Pilih</option>
                                <option value="2021" {{ ( $tahun == "2021") ? 'selected' : '' }}>2021</option>
                                <option value="2022" {{ ( $tahun == "2022") ? 'selected' : '' }}>2022</option>
                                <option value="2023" {{ ( $tahun == "2023") ? 'selected' : '' }}>2023</option>
                                <option value="2024" {{ ( $tahun == "2024") ? 'selected' : '' }}>2024</option>
                                <option value="2025" {{ ( $tahun == "2025") ? 'selected' : '' }}>2025</option>
                            </select>
                            </div>
                        </div>
                    </form>
                </div> <!-- div col-4-->    
                <div class="col-md-6">
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <label for="group" class="col-sm-4 col-form-label">Group Dept</label>
                            <div class="col-sm-8">
                            <select class="form-control" name="group" id="group">
                                @foreach($data_group as $datagr)
                                    <option value="{{ $datagr->kode_groupstr }}" {{ ($kodegroup == $datagr->kode_groupstr) ? 'selected' : ''}}>{{ $datagr->kode_groupstr }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                    </form>
                </div> <!-- div col-4-->
            </div>
            <div class="row">
                <div class="col-md-12">
                    <span class="float-md-right">
                        <button type="submit" id="btnFiterSubmitSearch" class="btn btn-info btn-sm">Submit</button>
                    </span>
                </div>
            </div> <!-- row -->
        </div>
    </div>
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
                                    <th>Kode Group</th>
                                    <th>Periode</th>
                                    <th>Tahun</th>
                                    <th>Nilai Budget</th>
                                    <th>Nilai Realisasi</th>
                                    <th>Nilai Sisa</th>
                                    <th>Serapan (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $total_budget=0;
                                $total_pakai=0;
                                $total_sisa=0;
                                $totalpersen=0;
                                @endphp
                                @foreach($datanya as $key => $data)
                                    <tr>
                                        <td> {{ $data->kode_group }}</td>
                                        <td> {{ $data->periode }}</td>
                                        <td> {{ $data->tahun }}</td>
                                        <td> {{ number_format($data->jum_budget,0) }}</td>
                                        <td> {{ number_format($data->jum_real,0) }}</td>
                                        <td> {{ ($data->jum_sisa < 0 ? "(".number_format(abs($data->jum_sisa),0).")" : number_format($data->jum_sisa,0)) }}</td>
                                        <td> {{ number_format($data->persentase,2) }}%</td>

                                        @php
                                        $total_budget += $data->jum_budget;
                                        $total_pakai += $data->jum_real;
                                        $total_sisa += $data->jum_sisa;
                                        @endphp

                                    </tr>
                                @endforeach
                                @php
                                   $totalpersen =  ($total_pakai / $total_budget) * 100;
                                @endphp
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3"><b>Total</b></td>
                                <td><b>{{ number_format($total_budget,0) }}</b></td>
                                <td><b>{{ number_format($total_pakai,0) }}</b></td>
                                <td><b>{{ ($total_sisa < 0 ? "(".number_format(abs($total_sisa),0).")" : number_format($total_sisa,0)) }} </b></td>
                                <td><b>{{ number_format($totalpersen,2) }}%</b></td>
                            </tr>
                            </tfoot>
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
        responsive: true,
        fixedHeader: true,
        //buttons: [
        //    'copy', 'csv', 'excel'
        //],
        dom: 'Bfrtip',
        // order: [[0, 'asc']],
        // rowGroup: {
        //     startRender: null,
        //     endRender: function ( rows, group ) {
        //         var sumbudget = rows
        //             .data()
        //             .pluck(3)
        //             .reduce( function (a, b) {
        //                 return a + b.replace(/[^\d]/g, '')*1;
        //             }, 0) / rows.count();
        //             sumbudget = $.fn.dataTable.render.number(',', '.', 0, '$').display( sumbudget );
 
        //         return $('<tr/>')
        //             .append( '<td colspan="3">Averages for '+group+'</td>' )
        //             .append( '<td>'+sumbudget+'</td>' )
        //             .append( '<td><td/><td><td/><td><td/>' );            },
        //     dataSrc: 2
        // },
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
                            return $.isNumeric(data.replace('.', '')) ? "\0"+data : data;

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
                            return $.isNumeric(data.replace('.', '')) ? "\0"+data : data;

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
                            return $.isNumeric(data.replace('.', '')) ? "\0"+data : data;

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

            scrollY:        true,
            scrollX:        true,
            //autoWidth:      true,
            visible: true,
            //responsive: true,
            ordering:       false,
            paging:         false
    });

    $('#btnFiterSubmitSearch').click(function(){
        var spinner = $('#loader');
        var start_period = $("#start_period option:selected").val();
        var end_period = $("#end_period option:selected").val();
        var group = $("#group option:selected").val();
        var tahun = $("#tahun option:selected").val();
        spinner.show();
        window.location.href = "{{ url('budget/reportkumulatif') }}"+ "/search/" +  start_period + "/" +  end_period + "/" + tahun + "/" + group + "";
    });


});
</script>
@endsection