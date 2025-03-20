@extends('layouts.budget')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Report Anggaran {{ (isset($kodegroup) ? $kodegroup : $group) }} Tahun {{ $tahun }}</h1>
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
                        $t = date(2024);
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
                                    <th>Group</th>
                                    <th>Deskripsi</th>
                                    <th>Tahun</th>
                                    <th>Januari</th>
                                    <th>Februari</th>
                                    <th>Maret</th>
                                    <th>April</th>
                                    <th>Mei</th></th>
                                    <th>Juni</th>
                                    <th>Juli</th>
                                    <th>Agustus</th>
                                    <th>September</th>
                                    <th>Oktober</th>
                                    <th>November</th>
                                    <th>Desember</th>
                                    <th>Budget Awal</th>
                                    <th>Total Budget</th>
                                    <th>Total Realisasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($datanya as $key => $data)
                                    <tr>
                                        <td> {{ $data->kode_group }}</td>
                                        <td> {{ $data->year }}</td>
                                        <td> {{ $data->description }}</td>
                                        <td> {{ number_format($data->January,0) }}</td>
                                        <td> {{ number_format($data->February,0) }}</td>
                                        <td> {{ number_format($data->March,0) }}</td>
                                        <td> {{ number_format($data->April,0) }}</td>
                                        <td> {{ number_format($data->May,0) }}</td>
                                        <td> {{ number_format($data->June,0) }}</td>
                                        <td> {{ number_format($data->July,0) }}</td>
                                        <td> {{ number_format($data->August,0) }}</td>
                                        <td> {{ number_format($data->September,0) }}</td>
                                        <td> {{ number_format($data->October,0) }}</td>
                                        <td> {{ number_format($data->November,0) }}</td>
                                        <td> {{ number_format($data->December,0) }}</td>
                                        <td> {{ number_format($data->Ori_budget,0) }}</td>
                                        <td> {{ number_format($data->Total_budget,0) }}</td>
                                        <td> {{ number_format($data->Total_realisasi,0) }}</td>

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
                                    <th>Group</th>
                                    <th>Deskripsi</th>
                                    <th>Tahun</th>
                                    <th>Januari</th>
                                    <th>Februari</th>
                                    <th>Maret</th>
                                    <th>April</th>
                                    <th>Mei</th></th>
                                    <th>Juni</th>
                                    <th>Juli</th>
                                    <th>Agustus</th>
                                    <th>September</th>
                                    <th>Oktober</th>
                                    <th>November</th>
                                    <th>Desember</th>
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
        window.location.href = "{{ url('budget/reportrealisasi') }}"+ "/search/" + tahun + "/" + group + "";
    });


});
</script>
@endsection