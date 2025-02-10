@extends('layouts.approval')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Outstanding Kasbon Report</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Approval</a></li>
          <li class="breadcrumb-item"><a href="#">Report</a></li>
          <li class="breadcrumb-item active">Outstanding Kasbon Report</li>
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
                    <h5>Kategory <span class="text-danger"></span></h5>
                    <div class="controls">
                        <select class="form-control" name="key_tgl" id="key_tgl">
                            <option value="created_at" selected>Tgl Kasbon Outstanding</option>
                            <option value="tgl_realisasi">Tgl Realisasi</option>
                        </select>
                     </div>
                </div>
                <div class="form-group col-md-4">
                    <h5>Start Date <span class="text-danger"></span></h5>
                    <div class="controls">
                        <input type="text" name="start_date" autocomplete="off" id="start_date" class="form-control datepicker" required>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <h5>End Date <span class="text-danger"></span></h5>
                    <div class="controls">
                        <input type="text" name="end_date" autocomplete="off" id="end_date" class="form-control datepicker" required>
                    </div>
                </div>
            </div> <!-- div row-->
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="status" class="col-sm-4 col-form-label">Status</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="status" id="status">
                            <option value="all">All</option>
                            <option value="Outstanding" {{( $status_pilih == "Outstanding") ? 'selected' : ''}}>Outstanding</option>
                            <option value="Proses" {{ ( $status_pilih == "Proses") ? 'selected' : '' }}>Proses</option>
                            <option value="Realisasi" {{ ( $status_pilih == "Realisasi") ? 'selected' : '' }}>Realisasi</option>
                            </select>
                        </div>
                    </div>
                </div> <!--col 6-->
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="dept" class="col-sm-4 col-form-label">Dept</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="dept" id="dept" data-dependent="dept">
                                @foreach($dept as $dept_data)
                                <option value="{{$dept_data->kodedepartemenstr}}"
                                {{ ( $dept_pilih == $dept_data->kodedepartemenstr) ? 'selected' : '' }}
                                >{{$dept_data->kodedepartemenstr}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div> <!--col 6-->
            </div> <!-- row -->
            <span class="float-md-right">
                        <button type="submit" id="btnFiterSubmitSearch" class="btn btn-primary btn-sm">Submit</button>
            </span>
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
                                    <th>Aksi</th>
                                    <th>No Outstanding</th>
                                    <th>No Kasbon</th>
                                    <th>Kasbon Tambahan</th>
                                    <th>Tgl Kasbon</th>
                                    <th>Tgl Realisasi</th>
                                    <th>Kode Dept</th>
                                    <th>User</th>
                                    <th>Referensi</th>
                                    <th>Rekening</th>
                                    <th>Keterangan</th>
                                    <th>Amount</th>
                                    <th>Realisasi</th>
                                    <th>Diff</th>
                                    <th>Status</th>
                                    <th>Last Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $total_kasbon=0;
                                $total_realisasi=0;
                                $sisa=0;
                                @endphp
                                @foreach($datanya as $key => $data)
                                    @php
                                        $total_kasbon += $data->jum_kasbon;
                                        $total_realisasi += $data->jum_realisasi;
                                        $sisa += $data->diff;
                                    @endphp
                                    <tr>
                                        @if ($data->status == "Realisasi")
                                        <td> <a href="{{ url('approval/outstanding-report/') }}/{{ $data->document_kbr}}/print">Cetak</a></td>
                                        @else
                                        <td></td>
                                        @endif
                                        <td> 
                                            @can('approval_aksesprint')
                                            <a href="{{ url('approval/outstanding-report/') }}/{{ $data->document_kbr}}/print">Cetak</a> - 
                                            @endcan    
                                        {{ $data->document_kbr ?? '' }}</td>
                                        @if ($data->document_kb == "")
                                        <td></td>                                        
                                        @else
                                        <td> <a href="{{ url('approval/report/') }}/{{ $data->document_kb}}/print">{{ $data->document_kb }}</a></td>
                                        @endif
                                        @if ($data->document_kbt == "")
                                        <td></td>                                        
                                        @else
                                        <td> <a href="{{ url('approval/report/') }}/{{ $data->document_kbt}}/print">{{ $data->document_kbt }}</a></td>
                                        @endif
                                        <td> {{ $data->tgl_kasbon ?? '' }}</td>
                                        <td> {{ $data->tglrealisasi ?? '' }}</td>
                                        <td> {{ $data->kode_departemen ?? '' }}</td>
                                        <td> {{ $data->name ?? '' }}</td>
                                        <td> {{ $data->no_ref ?? '' }}</td>
                                        <td> {{ $data->rekening ?? '' }}</td>
                                        <td> {{ $data->keterangan ?? '' }}</td>
                                        <td> {{ number_format($data->jum_kasbon, 0) }}</td>
                                        <td> {{ number_format($data->jum_realisasi, 0) }}</td>
                                        <td> {{ number_format($data->diff, 0) }}</td>   
                                        @if($data->status != 'Realisasi' and $data->status_proses == 'Late')
                                        <td style="background-color:yellow"> {{ $data->status ?? '' }}</td>
                                        @else
                                        <td style="background-color:green"> {{ $data->status ?? '' }}</td>
                                        @endif
                                        <td> {{ $data->last_status ?? '' }}</td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2">Total Kasbon : {{ number_format($total_kasbon, 0) }}</td>
                                <td>||</td>
                                <td colspan="2">Total Realisasi : {{ number_format($total_realisasi, 0) }}</td>
                                <td>||</td>
                                <td colspan="2">Total Sisa : {{ number_format($sisa, 0) }}</td>
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
    $(".datepicker").datepicker({
    dateFormat: 'dd MM yy',
    changeMonth: true,
    changeYear: true,
    autoclose: true

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
        var start_date = document.getElementById("start_date").value;
		var end_date = document.getElementById("end_date").value;
		if (start_date == "" || end_date == "") {
			alert('Isi Tanggal dulu !');
            return false;
		}
        var spinner = $('#loader');
        var key_tgl = $("#key_tgl option:selected").val();
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var status = $("#status option:selected").val();
        var dept = $("#dept option:selected").val();
        spinner.show();
        window.location.href = "{{ url('approval/outstanding-report') }}"+ "/search/" + key_tgl + "/" + start_date + "/" + end_date + "/" + dept + "/" + status + "";
    });
});
</script>
@endsection