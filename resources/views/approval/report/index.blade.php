@extends('layouts.approval')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Document Report</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Approval</a></li>
          <li class="breadcrumb-item"><a href="#">Report</a></li>
          <li class="breadcrumb-item active">Document Report</li>
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
                <div class="form-group col-md-4">
                    <h5>Start Date <span class="text-danger"></span></h5>
                    <div class="controls">
                        <input type="text" name="start_date" autocomplete="off" id="start_date"
                        value="{{ ($start_date != '' ) ? $start_date : '' }}" class="form-control datepicker" required>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <h5>End Date <span class="text-danger"></span></h5>
                    <div class="controls">
                        <input type="text" name="end_date" autocomplete="off" id="end_date" 
                        value="{{ ($end_date != '' ) ? $end_date : '' }}" class="form-control datepicker" required>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <h5>Status <span class="text-danger"></span></h5>
                    <div class="controls">
                        <select class="form-control" name="status" id="status">
                            <option value="all">All</option>
                            @foreach($status as $status_data)
                            <option value="{{$status_data->nama_status}}"
                            {{ ( $status_pilih == $status_data->nama_status) ? 'selected' : '' }}
                            >{{$status_data->nama_status}}</option>
                            @endforeach
                        </select>
                     </div>
                </div>
            </div> <!-- div row-->
            <div class="row">
                <div class="form-group col-md-4">
                    <h5>Type Tanggal <span class="text-danger"></span></h5>
                    <div class="controls">
                        <select class="form-control" name="type_tgl" id="type_tgl">
                            <option value="tglbuat" {{ ($type_tgl == 'tglbuat') ? 'selected' : '' }} >Tgl Buat</option>
                            <option value="tanggal_realisasi" {{ ($type_tgl =='tanggal_realisasi') ? 'selected' : '' }}>Tgl Realisasi</option>
                        </select>
                    </div>
                </div>                
                <div class="form-group col-md-4">
                    <h5>Dept <span class="text-danger"></span></h5>
                    <div class="controls">
                        <select class="form-control" name="dept" id="dept">
                            @foreach($dept as $dept_data)
                            <option value="{{$dept_data->kodedepartemenstr}}"
                            {{ ( $dept_pilih == $dept_data->kodedepartemenstr) ? 'selected' : '' }}
                            >{{$dept_data->kodedepartemenstr}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <h5>Nama <span class="text-danger"></span></h5>
                    <div class="controls">
                        <select class="form-control" name="nama" id="nama">
                            @if (!empty($nama))
                            <option value="{{$nama}}">{{$nama}}</option>
                            @else
                            <option value="all">Pilih Dept Dahulu</option>
                            @endif
                        </select>
                    </div>
                </div>               
            </div> <!-- div row-->
            <span class="float-md-right">
                        <button type="submit" id="btnFiterSubmitSearch" class="btn btn-primary btn-sm">Submit</button>
                    </span>
        </div><!--end body-->
    </div>
</div>

@if (isset($datanya))
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <!--<div class="tab-content">
                    <div id="tabdata" class="tab-pane active">-->
                        
                        <div class="table-responsive">
                        <table id="table_data" class="cell-border" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Aksi</th>
                                    <th>ID</th>
                                    <th>No Document</th>
                                    <th>Tanggal Buat</th>
                                    <th>Nama</th>
                                    <th>Kode Dept</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Tanggal Realisasi</th>
                                    <th>Priority</th>
                                    <th>Telat Bayar</th>
                                    <th>Cek?</th>
                                    <th>Jumlah VM</th>
                                    <th>Keterangan</th>
                                    <th>Last Status</th>
                                    <th>Tanggal Status</th>
                                    <th>No Ref</th>
                                    <th>No Rekening</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $total_vm=0;
                                @endphp
                                @foreach($datanya as $key => $data)
                                    <tr>
                                        @if ($data->last_status == "proses_payment" or $data->last_status == "closed" or $data->last_status == "approval_fin")
                                        <td> <a href="{{ url('approval/report/') }}/{{ $data->no_document}}/print">Cetak</a></td>
                                        @elseif (auth()->user()->nik == '00002886' or auth()->user()->nik == '00003790' or auth()->user()->nik == '00003840')
                                        <td> <a href="{{ url('approval/report/') }}/{{ $data->no_document}}/print">Cetak</a></td>
                                        @else
                                        <td></td>
                                        @endif
                                        <td>{{ $data->id }}</td>
                                        <td> {{ $data->no_document ?? '' }}</td>
                                        <td> {{ $data->created_at }}</td>
                                        <td> {{ $data->nama ?? '' }}</td>
                                        <td> {{ $data->kode_departemen ?? '' }}</td>
                                        <td> {{ date_format(date_create($data->tanggal_jt) ,"d-M-y") }}</td>
                                        @if ($data->tanggal_realisasi == "")
                                        <td> </td>
                                        @else
                                        <td> {{ date_format(date_create($data->tanggal_realisasi) ,"d-M-y") }}</td>
                                        @endif

                                        <td> {{ $data->prioritas ?? '' }}</td>
                                        @php
                                        $total_vm += $data->jum_vm;
                                        $date1 = new DateTime($data->tanggal_jt);
                                        $date2 = new DateTime(date("Y-m-d"));
                                        $interval  = $date1->diff($date2);
                                        @endphp

                                        @if ($data->tanggal_realisasi == "" and $date1 < $date2)
                                        <td>{{ (int)$interval->format('%a') }} Hari</td>
                                        @else
                                        <td>0</td>
                                        @endif

                                        @if($data->is_pu == 1)
                                        <td><input type="checkbox" checked="checked" disabled="disabled"/></td>
                                        @else
                                        <td><input type="checkbox" disabled="disabled"/></td>
                                        @endif
                                        <td> {{ number_format($data->jum_vm, 2) }}</td>
                                        <td> {{ $data->keterangan ?? '' }}</td>
                                        <td> {{ $data->last_status ?? '' }}</td>
                                        <td> {{ $data->tanggal_status }}</td>
                                        <td> {{ $data->no_ref ?? '' }}</td>
                                        <td> {{ $data->rekening ?? '' }}</td>
                                        <td> {{ $data->jumlah ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2">Total VM : {{ number_format($total_vm, 0) }}</td>
                            </tr>
                            </tfoot>
                        </table>
                        <!--</div>
                    </div>-->
                    <!--<div id="tabbars" class="tab-pane">
                        <div class="chart">
                            <canvas id="barChart" style="height:250px; min-height:250px"></canvas>
                        </div>
                    </div>
                    <div id="tablines" class="tab-pane">
                        <div class="chart">
                            <canvas id="lineChart" style="height:250px; min-height:250px"></canvas>
                        </div>
                    </div>-->
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
        columnDefs: [{
            targets:[10],
            render: function(data, type, row, meta){
                if(type === 'sort'){
                    var $input = $(data).find('input[type="checkbox"]').addBack();
                    data = ($input.prop('checked')) ? "1" : "0";
                }

                return data;    
            }
        }],
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
                    // columns: [ ':visible' ]
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
                    // columns: [ ':visible' ]
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
                    
                    // columns: [ ':visible' ]
                }
            }
            ,
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
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        // var bulan = $("#bulan option:selected").val();
        var status = $("#status option:selected").val();
        var dept = $("#dept option:selected").val();
        var nama = $("#nama option:selected").val();
        var typetgl = $("#type_tgl option:selected").val();
        spinner.show();
        window.location.href = "{{ url('approval/report') }}"+ "/search/" + start_date +  "/" + end_date + "/" + status + "/" + dept + "/" + nama + "/" + typetgl + "";
    });
});
</script>
@endsection