@extends('layouts.admin')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Profile</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Admin</a></li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="row">
    <div class="col-md-4">
            <!-- Widget: user widget style 1 -->
            <div class="card card-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-info">
                <h3 class="widget-user-username">{{ $name }}</h3>
                <h5 class="widget-user-desc">{{ $dept}} - {{ $jab }}</h5>
              </div>
              <div class="widget-user-image">
                <img class="img-circle elevation-2" src="{{ asset('img/avatar.jpg') }}" alt="User Avatar">
              </div>
              <!--<div class="card-footer">
                <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">Join Date</h5>
                      <span class="description-text">16 April 2018</span>
                    </div>
                  </div>
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">Status</h5>
                      <span class="description-text">Contract <br>(16 April 2021)</span>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="description-block">
                      <h5 class="description-header">Location</h5>
                      <span class="description-text">Head Office</span>
                    </div>
                  </div>
                </div>
                <hr>
                <label class="col-sm-4 col-form-label">BPJS</label>&nbsp;<input type="checkbox" data-toggle="toggle" checked="checked" disabled="disabled"><br>
                <label class="col-sm-4 col-form-label">BPJS-TK</label>&nbsp;<input type="checkbox" data-toggle="toggle" checked="checked" disabled="disabled"><br>
                <label class="col-sm-4 col-form-label">TAX</label>&nbsp;<input type="checkbox" data-toggle="toggle" checked="checked" disabled="disabled">
              </div>-->
            </div> <!-- /.widget-user -->
        </div> <!-- /.col -->
        <div class="col-md-8">
          <div class="card card-info">
            <div class="card-body">
              <!--<div class="card-header">
                <h5 class="card-title">Ganti Password</h5>
              </div>
              <br>-->
              <!--<canvas id="stat_charts" style="height:200px; min-height:200px"></canvas> 
              <hr>
              <label class="col-sm-4 col-form-label">Projects/Task</label>&nbsp; 120
              <label class="col-sm-4 col-form-label">Absent</label>&nbsp; 1 <br>
              <label class="col-sm-4 col-form-label">Working Hour (Total)</label>&nbsp; 360
              <label class="col-sm-4 col-form-label">Work Speed</label>&nbsp; 14 Days/Project<br>
              <label class="col-sm-4 col-form-label">Overtime (Total)</label>&nbsp; 2-->
              <form class="form-horizontal" method="post" action="{{ url('admin/profile/change-password') }}">
              @csrf
              <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                      <label for="pass_lama" class="col-sm-4 col-form-label">Password Lama</label>
                        <div class="col-sm-8">
                          <input type="password" class="form-control" name="pass_lama" id="pass_lama" required>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="pass_baru" class="col-sm-4 col-form-label">Password Baru</label>
                        <div class="col-sm-8">
                          <input type="password" class="form-control" name="pass_baru" id="pass_baru" required>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="pass_confirm" class="col-sm-4 col-form-label">Password Confirm</label>
                        <div class="col-sm-8">
                          <input type="password" class="form-control" name="pass_confirm" id="pass_confirm" required>
                        </div>
                    </div>
                </div> <!-- col -->
              <!--</div>--> <!-- row -->
              <span class="float-md-right">
                  <input type="submit" name="btnChangePass" id="btnChangePass" onclick="change_password()" class="btn btn-info btn-sm" value="Submit"/>
              </span>
              </form>
            </div> <!-- card body -->
          </div> <!-- card -->
        </div> <!-- /.col -->

        <!--<div class="col-md-4">
            <div class="card card-info">
              <div class="card-body">

              </div>
            </div>
        </div>-->
      </div>
@endsection
@section('scripts')
<script src="{{ asset('plugins/chartjs/Chart.min.js') }}"></script>
<script>
    function change_password() {
        if(!confirm("Anda yakin ingin mengubah password?")){
            event.preventDefault();
        }
    }

$(document).ready(function(){
  var color = Chart.helpers.color;
  var config = {
			type: 'radar',
			data: {
				labels: ['Discipline', 'Presence', 'Problem Solving', 'TeamWork', 'Maturity', 'Spirit at Work', 'Integrity'],
				datasets: [{
					label: 'Data',
					backgroundColor: 'rgba(0, 99, 132, 0.6)',
					borderColor: 'rgba(0, 99, 132, 0.6)',
					pointBackgroundColor: 'rgba(0, 99, 132, 0.6)',
					data: [
						50,
						45,
						45,
						45,
						40,
						50,
						40
					]
				}]
			},
			options: {
				legend: {
					position: 'top',
				},
				title: {
					display: true,
					text: 'Employee Statistic (2020)'
				},
				scale: {
					ticks: {
						beginAtZero: true
					}
				}
			}
		};

    window.onload = function() {
			window.myRadar = new Chart(document.getElementById('stat_charts'), config);
		};
});
</script>
@parent
@endsection