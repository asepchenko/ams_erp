@extends('layouts.budget')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Report Anggaran Tahun {{ $tahun }}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
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
            <div class="col-md-4">
            <form class="form-horizontal">
                <div class="form-group row">
                    <label for="tahun" class="col-sm-4 col-form-label">Tahun</label>
                    <div class="col-sm-8">
                    <select class="form-control" name="tahun" id="tahun">
                        <option value="now">Pilih</option>
                        <?php
                        $t = 2023;
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
            <div class="col-md-4">
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
    
            <div class="col-md-4">
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
                                    <th rowspan="2" style="text-align:center">Group</th>
                                    <th Colspan="4" style="text-align:center">Periode Q1</th>
                                    <th Colspan="4" style="text-align:center">Periode Q2</th>
                                    <th Colspan="4" style="text-align:center">Periode Q3</th>
                                    <th Colspan="4" style="text-align:center">Periode Q4</th>
                                </tr>
                                <tr>
                                    <th>Budget</th>
                                    <th>Realisasi</th>
                                    <th>Sisa</th>
                                    <th>%</th>
                                    <th>Budget</th>
                                    <th>Realisasi</th>
                                    <th>Sisa</th>
                                    <th>%</th>
                                    <th>Budget</th>
                                    <th>Realisasi</th>
                                    <th>Sisa</th>
                                    <th>%</th>
                                    <th>Budget</th>
                                    <th>Realisasi</th>
                                    <th>Sisa</th>
                                    <th>%</th>
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
                                        <td> {{ number_format($data->budget_q1,0) }}</td>
                                        <td> {{ number_format($data->real_q1,0) }}</td>
                                        <td> {{ ($data->sisa_q1 < 0 ? "(".number_format(abs($data->sisa_q1),0).")" : number_format($data->sisa_q1,0)) }}</td>
                                        <td> {{ number_format($data->persen_q1,2) }}%</td>
                                        <td> {{ number_format($data->budget_q2,0) }}</td>
                                        <td> {{ number_format($data->real_q2,0) }}</td>
                                        <td> {{ ($data->sisa_q2 < 0 ? "(".number_format(abs($data->sisa_q2),0).")" : number_format($data->sisa_q2,0)) }}</td>
                                        <td> {{ number_format($data->persen_q2,2) }}%</td>
                                        <td> {{ number_format($data->budget_q3,0) }}</td>
                                        <td> {{ number_format($data->real_q3,0) }}</td>
                                        <td> {{ ($data->sisa_q3 < 0 ? "(".number_format(abs($data->sisa_q3),0).")" : number_format($data->sisa_q3,0)) }}</td>
                                        <td> {{ number_format($data->persen_q3,2) }}%</td>
                                        <td> {{ number_format($data->budget_q4,0) }}</td>
                                        <td> {{ number_format($data->real_q4,0) }}</td>
                                        <td> {{ ($data->sisa_q4 < 0 ? "(".number_format(abs($data->sisa_q4),0).")" : number_format($data->sisa_q4,0)) }}</td>
                                        <td> {{ number_format($data->persen_q4,2) }}%</td>

                                        @php
                                        $total_budget += $data->summary;
                                        $total_pakai += $data->sumreal;
                                        $total_sisa += $data->sumsisa;
                                        @endphp

                                    </tr>
                                @endforeach
                                @php
                                   $totalpersen =  ($total_pakai / $total_budget) * 100;
                                @endphp
                            </tbody>
                            <tfoot>
                            <tr>
                                <td><strong>Total</strong></td>
                                <td colspan="2"><b>Budget : {{ number_format($total_budget,0) }}</b></td>
                                <td colspan="2"><b>Realisasi : {{ number_format($total_pakai,0) }}</b></td>
                                <td colspan="2"><b>Sisa : {{ ($total_sisa < 0 ? "(".number_format(abs($total_sisa),0).")" : number_format($total_sisa,0)) }} </b></td>
                                <td colspan="2"><b>Persentase Tahunan : {{ number_format($totalpersen,2) }}%</b></td>
                                <!-- <td colspan="2"><b>Sisa : {{ number_format($total_sisa,0) }}</b></td> -->
                                <td></td>
                                <td></td><td></td><td></td><td></td><td></td>
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
            <div class="table-responsive">
                        <table id="table_data" class="cell-border" style="width:100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="text-align:center">Dept</th>
                                    <th Colspan="3" style="text-align:center">Periode Q1</th>
                                    <th Colspan="3" style="text-align:center">Periode Q2</th>
                                    <th Colspan="3" style="text-align:center">Periode Q3</th>
                                    <th Colspan="3" style="text-align:center">Periode Q4</th>
                                </tr>
                                <tr>
                                    <th>Budget</th>
                                    <th>Realisasi</th>
                                    <th>Sisa</th>
                                    <th>Budget</th>
                                    <th>Realisasi</th>
                                    <th>Sisa</th>
                                    <th>Budget</th>
                                    <th>Realisasi</th>
                                    <th>Sisa</th>
                                    <th>Budget</th>
                                    <th>Realisasi</th>
                                    <th>Sisa</th>
                                </tr>
                            </thead>
                        </table>
                <!-- No Data Found :( -->
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
    $(".datepicker").datepicker({
    dateFormat: 'dd MM yy',
    changeMonth: true,
    changeYear: true,
    autoclose: true

});

    $('#dept').on('change',function(){
        if($(this).val() != ''){
            $.getJSON('{{ url('approval/get-name-by-dept') }}' + "/" + $("#dept option:selected").val(), function(data) {
                var temp = [];
                $.each(data, function(key, value) {
                    temp.push({v:value, k: key});
                });
                $('#nama').empty();
                $('#nama').append('<option value="all">All</option>');   
                $.each(temp, function(key, obj) {
                $('#nama').append('<option value="' + obj.k +'">' + obj.v + '</option>');           
                }); 
            });
        }
    });

    $('#dept').on('change',function(){
        $('#nama').val('');
    });

    $('#table_data').DataTable({
        //responsive: true,
        //fixedHeader: true,
        //buttons: [
        //    'copy', 'csv', 'excel'
        //],
        dom: 'Bfrtip',
        // columnDefs: [{
        //     targets:[8],
        //     render: function(data, type, row, meta){
        //         if(type === 'sort'){
        //             var $input = $(data).find('input[type="checkbox"]').addBack();
        //             data = ($input.prop('checked')) ? "1" : "0";
        //         }

        //         return data;    
        //     }
        // }],
        buttons: [
            {
                extend: 'copyHtml5', footer: true, header: true,
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
                footer: true, header: true,
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
                extend: 'csvHtml5', footer: true, header: true,
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
            paging:         true
    });

    $('#btnFiterSubmitSearch').click(function(){
        var spinner = $('#loader');
        //var periode = $("#periode option:selected").val();
        var group = $("#group option:selected").val();
        var tahun = $("#tahun option:selected").val();
        spinner.show();
        window.location.href = "{{ url('budget/reportanggaran') }}"+ "/search/" + tahun + "/" + group + "";
    });

    $('#btnPrintPdf').click(function(){
        var spinner = $('#loader');
        //var periode = $("#periode option:selected").val();
        var group = $("#group option:selected").val();
        var tahun = $("#tahun option:selected").val();
        spinner.show();
        window.location.href = "{{ url('budget/reportanggaran') }}"+ "/printpdf/" + tahun + "/" + group + "";
    });
});
</script>
@endsection