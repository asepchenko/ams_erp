@extends('layouts.budget')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-8">
        <h1 class="m-0 text-dark">Report Detil</h1>
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
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <label for="start_period" class="col-sm-4 col-form-label">Periode</label>
                            <div class="col-sm-8">
                            <select class="form-control" name="start_period" id="start_period">
                                <option value="pilih">Pilih</option>
                                <option value="q1" {{ ( $start_period == "q1") ? 'selected' : '' }}>Kuartal I</option>
                                <option value="q2" {{ ( $start_period == "q2") ? 'selected' : '' }}>Kuartal II</option>
                                <option value="q3" {{ ( $start_period == "q3") ? 'selected' : '' }}>Kuartal III</option>
                                <option value="q4" {{ ( $start_period == "q4") ? 'selected' : '' }}>Kuartal IV</option>
                            </select>
                            </div>
                        </div>
                    </form>
                </div> <!-- div col-4-->
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
                
            </div>
            <div class="row">
                <div class="col-md-6">
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <label for="kode_anggaran" class="col-sm-4 col-form-label">Kode Anggaran</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="kode_anggaran" id="kode_anggaran">
                                    @if (!empty($anggaran_pilih))
                                    <option value="{{$anggaran_pilih}}">{{$anggaran_pilih}}</option>
                                    @else
                                    <option value="all">Pilih Periode Dahulu</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </form>
                </div> <!-- div col-4-->
                <div class="col-md-6">
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <label for="status" class="col-sm-4 col-form-label">Status</label>
                            <div class="col-sm-8">
                            <select class="form-control" name="status" id="status">
                                <option value="all">All</option>
                                <option value="closed" {{ ( $status_pilih == "closed") ? 'selected' : '' }}>Closed</option>
                                <option value="proses" {{ ( $status_pilih == "proses") ? 'selected' : '' }}>Proses</option>
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
                                    <th>Budget</th>
                                    <th>Document Id</th>
                                    <th>Keterangan</th>
                                    <th>Tgl buat</th>
                                    <th>Realisasi</th>
                                    <th>pemakaian</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalkeluar = 0;
                                @endphp
                                @foreach ($datanya as $data)
                                {{-- @foreach ($databudget as $dbudget) --}}

                                    <tr>
                                        @php 
                                        $totalkeluar += $data->pemakaian;
                                        @endphp
                                        <td>{{ $data->descanggaran }}</td>
                                        <td>{{ $data->document_id }}</td>
                                        <td> {{ $data->keterangan }}</td>
                                        <td>{{ date_format(date_create($data->tanggal_buat) ,"d-M-y") }}</td>
                                        <td>{{ date_format(date_create($data->tanggal_realisasi) ,"d-M-y")}}</td>
                                        <td>{{ number_format($data->pemakaian,0) }}</td>
                                        <td>{{ $data->last_status }}</td>
                                        {{-- <td><b><a href="{{url('budget/reportprogress')}}/search/{{ strtolower($data->periode) }}/{{ strtolower($data->periode) }}/{{ $tahun }}/{{ $data->kode_group }}/realisasi" style="color:black" target="_blank"> 
                                            {{ number_format($data->progress,0) }}</a><b></td>
                                        <td><b><a href="{{url('budget/reportrealisasi')}}/search/{{ strtolower($data->periode) }}/{{ strtolower($data->periode) }}/{{ $tahun }}/{{ $data->kode_group }}/closed" style="color:black" target="_blank"> 
                                            {{ number_format($data->jum_real,0) }}</a></b></td>
                                        <td> {{ ($data->jum_sisa < 0 ? "(".number_format(abs($data->jum_sisa),0).")" : number_format($data->jum_sisa,0)) }}</td>
                                        <td> {{ number_format($data->persentase,2) }}%</td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr><td colspan="2">Total Pengeluaran : {{ number_format($totalkeluar,0) }}</td></tr>
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

    $('#start_period').on('change',function(){
        if($(this).val() != ''){
            $.getJSON('{{ url('budget/reportdetail/getanggaran') }}' + "/" + $("#start_period option:selected").val() + "/" + $("#group option:selected").val() , function(data) {
                var temp = [];
                $.each(data, function(key, value) {
                    temp.push({v:value, k: key});
                });
                $('#kode_anggaran').empty();
                $('#kode_anggaran').append('<option value="all">All</option>');   
                $.each(temp, function(key, obj) {
                $('#kode_anggaran').append('<option value="' + obj.k +'">' + obj.v + '</option>');           
                }); 
            });
        }
    });

    $('#start_period').on('change',function(){
        $('#kode_anggaran').val('');
    });

    $('#table_data').DataTable({
        // responsive: true,
        // fixedHeader: true,
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

            scrollY:        true,
            scrollX:        true,
            autoWidth:      true,
            visible: true,
            //responsive: true,
            // ordering:       true,
            paging:         true
    });

    $('#btnFiterSubmitSearch').click(function(){
        var spinner = $('#loader');
        var start_period = $("#start_period option:selected").val();
        // var end_period = $("#end_period option:selected").val();
        var group = $("#group option:selected").val();
        var tahun = $("#tahun option:selected").val();
        var anggaran = $("#kode_anggaran option:selected").val();
        var status = $("#status option:selected").val();
        spinner.show();
        window.location.href = "{{ url('budget/reportdetail') }}"+ "/search/" +  group + "/" + tahun + "/" + start_period + "/" + anggaran + "/" + status + "";
    });


});
</script>
@endsection