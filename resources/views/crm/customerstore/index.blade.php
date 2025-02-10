@extends('layouts.crm')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Customer Store List</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">CRM</a></li>
            <li class="breadcrumb-item"><a href="#">Customer Store</a></li>
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
                <!--<div class="form-group row">
                    <label for="STORE" class="col-sm-4 col-form-label">ID Store</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="STORE" id="STORE">
                            @if (!empty($store))
                                <option value="{{$store}}">{{$store}}</option>
                            @else
                                <option value="all">Pilih Group Head Dahulu</option>
                            @endif
                        </select>
                    </div>
                </div>-->
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
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>No Telpon</th>
                                <th>E-mail</th>
                                <!--<th>Total Point</th>
                                <th>Total Redeem</th>-->
                                <th>Tanggal Daftar</th>
                                <th>Store Daftar</th>
                                <!--<th>First Login</th>
                                <th>Last Aktif</th>
                                <th>Latlong</th>-->
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
        //paging: true,
        //fixedHeader: true,
        processing: true,
        serverSide: true,
        scrollY:        200,
        deferRender:    true,
        scroller:       true,
        ajax: {
          url: "{{ url('crm/customerstore') }}",
          type: 'GET',
          data: function (d) {
          d.bulan = $("#bulan option:selected").val()
          }
         },
        columns: [
            { data: 'action', name: 'action', orderable: false },
            { data: 'nama', name: 'nama' },
            { data: 'alamat', name: 'alamat' },
            { data: 'notelpon', name: 'notelpon' },
            /*{ data: 'email', name: 'email' },
            { data: 'point', name: 'point' },
            { data: 'total_redeem', name: 'total_redeem' },*/
            { data: 'tgl_daftar', name: 'tgl_daftar' },
            { data: 'store_daftar', name: 'store_daftar' }
            /*{ data: 'first_login', name: 'first_login' },
            { data: 'last_active', name: 'last_active' },
            { data: 'latlong', name: 'latlong' }*/
        ]
    });

    $('#btnFiterSubmitSearch').click(function(){
        $('#table_data').DataTable().draw(true);
    });
    
});
</script>
@endsection