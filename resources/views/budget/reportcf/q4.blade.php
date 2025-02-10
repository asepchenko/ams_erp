@extends('layouts.budget')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Report CF</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Budgeting</a></li>
          <li class="breadcrumb-item"><a href="#">Report</a></li>
          <li class="breadcrumb-item active">Cash Flow</li>
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
            <div class="col-md-4">
            <form class="form-horizontal">
                <div class="form-group row">
                    <label for="group" class="col-sm-4 col-form-label">Periode</label>
                    <div class="col-sm-8">
                    <select class="form-control" name="group" id="group">                        
                        <option value="sm1" {{ ($kodegroup == 'sm1') ? 'selected':'' }}>Semester 1</option>
                        <option value="sm2" {{ ($kodegroup == 'sm2') ? 'selected':'' }}>Semester 2</option>
                        <option value="q1" {{ ($kodegroup == 'q1') ? 'selected':'' }}>Quartal 1</option>
                        <option value="q2" {{ ($kodegroup == 'q2') ? 'selected':'' }}>Quartal 2</option>
                        <option value="q3" {{ ($kodegroup == 'q3') ? 'selected':'' }}>Quartal 3</option>
                        <option value="q2" {{ ($kodegroup == 'q4') ? 'selected':'' }}>Quartal 4</option>
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
                                    <th rowspan="2" style="text-align: center;">Item</th>
                                    <th colspan="2" style="text-align: center;">Oktober</th>
                                    <th colspan="2" style="text-align: center;">November</th></th>
                                    <th colspan="2" style="text-align: center;">Desember</th>
                                </tr>
                                <tr>
                                    <th>Budget</th><th>Realisasi</th>
                                    <th>Budget</th><th>Realisasi</th>
                                    <th>Budget</th><th>Realisasi</th>
                                </tr>
                                <tr>
                                    <th colspan="13" style="text-align: center;">PENGELUARAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($datanya as $key => $data)
                                    <tr>
                                        <td> {{ $data->label_report }}</td>
                                        <td> {{ number_format($data->budget_10,0) }}</td>
                                        <td> {{ number_format($data->realisasi_10,0) }}</td>
                                        <td> {{ number_format($data->budget_11,0) }}</td>
                                        <td> {{ number_format($data->realisasi_11,0) }}</td>
                                        <td> {{ number_format($data->budget_12,0) }}</td>
                                        <td> {{ number_format($data->realisasi_12,0) }}</td>
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
            <div class="table-responsive">
                        <table id="table_data" class="cell-border" style="width:100%">
                            <thead>
                                
                                <tr>
                                    <th rowspan="2" style="text-align: center;">Item</th>
                                    <th colspan="2" style="text-align: center;">Oktober</th>
                                    <th colspan="2" style="text-align: center;">November</th></th>
                                    <th colspan="2" style="text-align: center;">Desember</th>
                                </tr>
                                <tr>
                                    <th>Budget</th><th>Realisasi</th>
                                    <th>Budget</th><th>Realisasi</th>
                                    <th>Budget</th><th>Realisasi</th>
                                </tr>
                                <tr>
                                    <th colspan="13" style="text-align: center;font-size:20px;">PENGELUARAN</th>
                                </tr>
                            </thead>
                        </table>
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
    $(".datepicker").datepicker({
    dateFormat: 'dd MM yy',
    changeMonth: true,
    changeYear: true,
    autoclose: true

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
        window.location.href = "{{ url('budget/reportcf') }}"+ "/search/" + tahun + "/" + group + "";
    });


});
</script>
@endsection