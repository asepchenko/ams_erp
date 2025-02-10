@extends('layouts.crmmap')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Detail Member</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">CRM</a></li>
            <li class="breadcrumb-item"><a href="#">Member Mobile App</a></li>
            <li class="breadcrumb-item active">Detail Member</li>
        </ol>
        </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="row">
    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
            <div class="text-center">
                <img class="profile-user-img img-fluid img-circle"
                  src="{{ asset('img/avatar.jpg') }}"
                       alt="User profile picture">
            </div>
            <h3 class="profile-username text-center">{{ $profil->nama }}</h3>
            <p class="text-muted text-center">{{ $profil->email }}</p>

            <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                    <b>Total Transaction This Month</b> <a class="float-right">{{ $data->total_trans_month }}</a>
                </li>
                <li class="list-group-item">
                    <b>Total Transaction</b> <a class="float-right">{{ $data->total_trans }}</a>
                </li>
                <li class="list-group-item">
                    <b>Total Point</b> <a class="float-right">{{ $data->point }}</a>
                </li>
                <li class="list-group-item">
                    <b>Total Redeem</b> <a class="float-right">{{ $data->total_redeem }} x</a>
                </li>
            </ul>

            <a class="btn btn-primary btn-sm" href="{{ route("crm.mobile-member.index") }}">Back to List</a>
            </div> <!-- /.card-body -->
        </div><!-- /.card -->

        <!-- About Me Box -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Profile</h3>
            </div><!-- /.card-header -->
            <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Address</strong>
                <p class="text-muted">{{ $profil->alamat }}</p>
                <hr>
                <strong><i class="fas fa-map-marker-alt mr-1"></i> Coordinate</strong>
                <p class="text-muted">{{ $profil->latlong }}</p>
                <hr>
                <strong><i class="fas fa-pencil-alt mr-1"></i> Mobile Phone</strong>
                <p class="text-muted">{{ $profil->notelpon }}
                </p>
                <hr>
                <strong><i class="far fa-file-alt mr-1"></i> Member Date</strong>
                <p class="text-muted">{{ $profil->tglmember }} @ {{ $profil->store_daftar }}</p>
            </div><!-- /.card-body -->
        </div><!-- /.card -->
    </div><!-- /.col -->
    
    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#product" data-toggle="tab">Member Product</a></li>
                  <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">History Transaction</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">

                  <div class="active tab-pane" id="product">
                    <div class="table-responsive">
                        <table id="table_plu" class="display compact" style="width:100%">
                            <thead>
                                <tr>
                                    <th>PLU</th>
                                    <th>Article</th>
                                    <th>Deskripsi</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data_plu as $key => $plu)
                                <tr>
                                    <td>{{ $plu->plu ?? '' }}</td>
                                    <td>{{ $plu->article ?? '' }}</td>
                                    <td>{{ $plu->deskripsi ?? '' }}</td>
                                    <td>{{ $plu->total ?? '' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- table responsive -->
                  </div><!-- /.tab-pane -->


                  <div class="tab-pane" id="timeline">
                    <div class="timeline timeline-inverse">
                        @foreach($data_transaksi as $key => $trans)
                        <div class="time-label">
                            <span class="bg-danger">
                            {{ $trans->tanggal ?? '' }}
                            </span>
                        </div>
                        <div>
                            <i class="fas fa-envelope bg-primary"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">{{ $trans->store_id ?? '' }}</h3>
                                <div class="timeline-body">
                                        Point Reward {{ $trans->point_reward ?? '' }}<br>
                                        Bonus Point Reward {{ $trans->bonus_point_reward ?? '' }}<br>
                                        Bonus Tanggal Bagus {{ $trans->bonus_tanggal_bagus ?? '' }}
                                        {{ $trans->tgl_special ?? '' }}
                                        {{ $trans->nama_event ?? '' }} 
                                        <hr>
                                        {{ $trans->plu ?? '' }} 
                                </div>
                                <div class="timeline-footer">
                                    <a href="#" class="btn btn-primary btn-sm">Detail</a>
                                </div>
                            </div><!-- timeline item -->
                        </div>
                      @endforeach
                      <div>
                        <i class="far fa-clock bg-gray"></i>
                      </div>
                    </div> <!-- timeline inverse -->
                  </div><!-- /.tab-pane -->

                </div><!-- /.tab-content -->
            </div><!-- /.card-body -->
        </div><!-- /.card -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-map-marker-alt mr-1"></i>Map Visualization</h3>
                        </div> <!-- card header -->
                        <div class="card-body">
                            <div id="mapid" class="map" style="height: 500px;"></div>
                        </div> <!-- card body -->
                    </div> <!-- card -->
                </div> <!-- col -->
            </div><!-- /.row -->

    </div><!-- /.col-md-9-->
</div><!-- /.row -->
@endsection
@section('scripts')
@parent
<script>
  var mymap = L.map('mapid').setView([<?php echo $profil->latlong; ?>], 15);
  L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(mymap);
  
  var locations = [<?php echo $profil->lokasi; ?>];
	for (var i = 0; i < locations.length; i++) {
		marker = new L.marker([locations[i][1],locations[i][2]])
			.addTo(mymap);
	}
	var popup = L.popup();
</script>
<script>
$(document).ready(function(){

    function loading(){
        $('#loading').modal({
            backdrop: "static", //remove ability to close modal with click
            keyboard: false, //remove option to close with keyboard
            show: true //Display loader!
        });
    }

    /* dibutuhkan jika ajax butuh autentikasi token */
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('#table_plu').DataTable({
        responsive: true
    });
});
</script>
@endsection