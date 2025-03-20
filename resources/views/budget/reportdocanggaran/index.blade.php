@extends('layouts.budget')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-8">
        <h1 class="m-0 text-dark">Report Document Budget </h1>
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
                <div class="col-md-4">
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <label for="bulan" class="col-sm-4 col-form-label">Bulan</label>
                            <div class="col-sm-8">
                            <select class="form-control" name="bulan" id="bulan">
                                <option value="">Pilih</option>
                                <option value="01" {{ ( $bulan == "01") ? 'selected' : '' }}>Januari</option>
                                <option value="02" {{ ( $bulan == "02") ? 'selected' : '' }}>Februari</option>
                                <option value="03" {{ ( $bulan == "03") ? 'selected' : '' }}>Maret</option>
                                <option value="04" {{ ( $bulan == "04") ? 'selected' : '' }}>April</option>
                                <option value="05" {{ ( $bulan == "05") ? 'selected' : '' }}>Mei</option>
                                <option value="06" {{ ( $bulan == "06") ? 'selected' : '' }}>Juni</option>
                                <option value="07" {{ ( $bulan == "07") ? 'selected' : '' }}>Juli</option>
                                <option value="08" {{ ( $bulan == "08") ? 'selected' : '' }}>Agustus</option>
                                <option value="09" {{ ( $bulan == "09") ? 'selected' : '' }}>September</option>
                                <option value="10" {{ ( $bulan == "10") ? 'selected' : '' }}>Oktober</option>
                                <option value="11" {{ ( $bulan == "11") ? 'selected' : '' }}>November</option>
                                <option value="12" {{ ( $bulan == "12") ? 'selected' : '' }}>Desember</option>
                            </select>
                            </div>
                        </div>
                    </form>
                </div> <!-- div col-4-->
                <div class="col-md-4">
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <label for="brand" class="col-sm-4 col-form-label">Brand</label>
                            <div class="col-sm-8">
                             <select class="form-control" name="brand" id="brand">
                                    <option value="all" {{ ( $brand == "all") ? 'selected' : '' }}>ALL</option>
                                    <option value="HO" {{ ( $brand == "HO") ? 'selected' : '' }}>HO</option>
                                    <option value="RC" {{ ( $brand == "RC") ? 'selected' : '' }}>RC</option>
                                    <option value="RCW" {{ ( $brand == "RCW") ? 'selected' : '' }}>RCW</option>
                                    <option value="RM" {{ ( $brand == "RM") ? 'selected' : '' }}>RM</option>
                                    <option value="RQ" {{ ( $brand == "RQ") ? 'selected' : '' }}>RQ</option>
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
                                    <th>Doc ID</th>
                                    <th>NO Document</th>
                                    <th>Tanggal Buat</th>
                                    <th>Dept</th>
                                    <th>Nama</th>
                                    <th>Keterangan</th>
                                    <th>Pemakaian</th>
                                    <th>Brand</th>
                                    <th>Anggaran</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                                @foreach ($datanya as $data)
                                  
                                    <tr>
                                    
                                        <td>{{ $data->document_id }}</td>
                                        <td>{{ $data->no_document }}</td>
                                        <td>{{ $data->tgl_buat }}</td>
                                        <td>{{ $data->kode_departemen }}</td>
                                        <td>{{ $data->nama }}</td>
                                        <td>{{ $data->keterangan }}</td>
                                        <td>{{ number_format($data->pemakaian,0) }}</td>
                                        <td>{{ $data->brand }}</td>
                                        <td>{{ $data->descanggaran }}</td>
                                        <td>{{ $data->last_status }}</td>
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
    $('#table_data').DataTable({
        responsive: false,
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

            scrollY:        true,
            scrollX:        true,
            //autoWidth:      true,
            visible: true,
            // responsive: true,
            ordering:       false,
            paging:         true
    });

    $('#btnFiterSubmitSearch').click(function(){
        var spinner = $('#loader');
        var bulan = $("#bulan option:selected").val();
        var brand = $("#brand option:selected").val();
        var group = $("#group option:selected").val();
        spinner.show();
        window.location.href = "{{ url('budget/reportanggaran') }}"+ "/search/" +  bulan + "/" +  brand + "/" + group + "";
    });


});
</script>
@endsection