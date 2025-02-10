@extends('layouts.finance')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Realisasi Document List</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Finance</a></li>
            <li class="breadcrumb-item"><a href="#">Realisasi</a></li>
            <li class="breadcrumb-item"><a href="#">Document</a></li>
            <li class="breadcrumb-item active">List</li>
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
          <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="fa" aria-hidden="true"></i>
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
                
            </form>
            </div> <!-- div col-6-->

            <div class="col-md-6">
                <div class="form-group row">
                    <label for="status" class="col-sm-4 col-form-label">Status</label>
                    <div class="col-sm-8">
                    <select class="form-control" name="status" id="status">
                        <option value="0" {{ ( $status == "0") ? 'selected' : '' }}>Belum Realisasi</option>
                        <option value="1" {{ ( $status == "1") ? 'selected' : '' }}>Sudah Realisasi</option>
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

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Data</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table_data" class="display compact" style="width:100%">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Document ID</th>
                                <th>No Document</th>
                                <th>Category</th>
                                <th>Tanggal JT</th>
                                <th>Tujuan</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                                <th>PU ?</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
@parent
<script>
$(document).ready(function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('#table_data').DataTable({
        paging: true,
        responsive: false,
        //fixedHeader: true,
        buttons: [
            'copy', 'csv', 'excel'
        ],
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ url('finance/realisasi-document') }}",
          type: 'GET',
          data: function (d) {
          d.bulan = $("#bulan option:selected").val(),
          d.status = $("#status option:selected").val()
          }
         },
        columns: [
            { data: 'action', name: 'action', orderable: false },
            { data: 'id', name: 'id' },
            { data: 'no_document', name: 'no_document' },
            { data: 'no_ref', name: 'no_ref' },
            { data: 'tglbayar', name: 'tglbayar' },
            { data: 'nama_tujuan', name: 'nama_tujuan' },
            { data: 'jum_vm', name: 'jum_vm' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'is_pu', name: 'is_pu' }
        ],
        "rowCallback": function( row, data, index ) {
            if ( data["last_status"] == "proses_payment" )
            {
                $('td', row).css('background-color', 'yellow');
            }
        }
    });

    $('#btnFiterSubmitSearch').click(function(){
        $('#table_data').DataTable().draw(true);
    });
    
});
</script>
@endsection