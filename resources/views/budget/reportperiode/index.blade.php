@extends('layouts.budget')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-8">
        <h1 class="m-0 text-dark">Report Periode :  {{ $periode }} - {{ $tahun }}</h1>
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
            <div class="col-md-3">
            <form class="form-horizontal">
                <div class="form-group row">
                    <label for="periode" class="col-sm-4 col-form-label">Periode</label>
                    <div class="col-sm-8">
                    <select class="form-control" name="periode" id="periode">
                        <option value="all">All</option>
                        <option value="q1" {{ ( $periode == "q1") ? 'selected' : '' }}>Quarter I</option>
                        <option value="q2" {{ ( $periode == "q2") ? 'selected' : '' }}>Quarter II</option>
                        <option value="q3" {{ ( $periode == "q3") ? 'selected' : '' }}>Quarter III</option>
                        <option value="q4" {{ ( $periode == "q4") ? 'selected' : '' }}>Quarter IV</option>
                    </select>
                    </div>
                </div>
            </form>
            </div> <!-- div col-4-->
            <div class="col-md-3">
            <form class="form-horizontal">
                <div class="form-group row">
                    <label for="tahun" class="col-sm-4 col-form-label">Tahun</label>
                    <div class="col-sm-8">
                    <select class="form-control" name="tahun" id="tahun">
                        <option value="now">Pilih</option>
                        <?php
                        $t = date(2023);
                        for ($i=$t; $i<=$t +5; $i++){
                        ?>
                        <option value={{ $i }} {{ ($tahun == $i) ? 'selected' :'' }}>{{ $i }}</option>
                        <?php
                        }
                        ?>
                    </select>
                    </div>
                </div>
            </form>
            </div> <!-- div col-4-->            
            <div class="col-md-3">
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
    
            <div class="col-md-3">
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
            ordering:       true,
            paging:         false
    });

    $('#btnFiterSubmitSearch').click(function(){
        var spinner = $('#loader');
        var periode = $("#periode option:selected").val();
        var group = $("#group option:selected").val();
        var tahun = $("#tahun option:selected").val();
        spinner.show();
        window.location.href = "{{ url('budget/reportperiode') }}"+ "/search/" +  periode + "/" + tahun + "/" + group + "";
    });


});
</script>
@endsection