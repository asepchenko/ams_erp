@extends('layouts.approval')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">SPD Report</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Approval</a></li>
          <li class="breadcrumb-item"><a href="#">Report</a></li>
          <li class="breadcrumb-item active">SPD Report</li>
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
                <div class="form-group row">
                    <label for="dept" class="col-sm-4 col-form-label">Dept</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="dept" id="dept">
                            @foreach($dept as $dept_data)
                            <option value="{{$dept_data->kodedepartemenstr}}"
                            {{ ( $dept_pilih == $dept_data->kodedepartemenstr) ? 'selected' : '' }}
                            >{{$dept_data->kodedepartemenstr}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
            </div> <!-- div col-6-->

            <div class="col-md-6">
                <div class="form-group row">
                    <label for="status" class="col-sm-4 col-form-label">Status</label>
                    <div class="col-sm-8">
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
                <div class="form-group row">
                    <label for="nama" class="col-sm-4 col-form-label">Nama</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="nama" id="nama">
                            @if (!empty($nama))
                                <option value="{{$nama}}">{{$nama}}</option>
                            @else
                                <option value="all">Pilih Dept Dahulu</option>
                            @endif
                        </select>
                    </div>
                </div>
                <span class="float-md-right">
                    <button type="submit" id="btnFiterSubmitSearch" class="btn btn-primary btn-sm">Submit</button>
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
                                    <!--<th>Aksi</th>-->
                                    <th>Document ID</th>
                                    <th>No Digital</th>
                                    <th>Nama Pembuat</th>
                                    <th>Departemen</th>
                                    <th>Nama Tujuan</th>
                                    <th>Rek Tujuan</th>
                                    <th>Kode Bank</th>
                                    <th>Nama Rek</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                    <th>Last Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $total_spd=0;
                                @endphp
                                @foreach($datanya as $key => $data)
                                    @php
                                        $total_spd += $data->jumlah;
                                    @endphp
                                    <tr>
                                        <!--<td> <a href="{{ url('approval/report/') }}/{{ $data->id}}/print">Cetak</a></td>-->
                                        <td> {{ $data->document_id ?? '' }}</td>
                                        <td> {{ $data->no_digital ?? '' }}</td>
                                        <td> {{ $data->nama ?? '' }}</td>
                                        <td> {{ $data->kode_departemen ?? '' }}</td>
                                        <td> {{ $data->nama_tujuan ?? '' }}</td>
                                        <td> {{ $data->rek_tujuan ?? '' }}</td>
                                        <td> {{ $data->kode_bank ?? '' }}</td>
                                        <td> {{ $data->nama_rek ?? '' }}</td>
                                        <td> {{ number_format($data->jumlah, 2) }}</td>
                                        <td> {{ $data->keterangan ?? '' }}</td>
                                        <td> {{ $data->last_status ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2">Total SPD : {{ number_format($total_spd, 0) }}</td>
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
            visible: true,
            //responsive: true,
            ordering:       true,
            paging:         true
    });

    $('#btnFiterSubmitSearch').click(function(){
        var spinner = $('#loader');
        var bulan = $("#bulan option:selected").val();
        var status = $("#status option:selected").val();
        var dept = $("#dept option:selected").val();
        var nama = $("#nama option:selected").val();
        spinner.show();
        window.location.href = "{{ url('approval/spd-report') }}"+ "/search/" + bulan + "/" + status + "/" + dept + "/" + nama + "";
    });
});
</script>
@endsection