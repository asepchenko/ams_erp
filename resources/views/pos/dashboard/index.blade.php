@extends('layouts.posdashboard')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Dashboard</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">POS</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- shadow p-3 mb-5 bg-white rounded-->
    <!--<div class="card-header" id="headDash">
      <span class="float-md-left"><h5>Dashboard Report</h5></span>-->
        <!--<span class="float-md-right">
        <h5 class="mb-0">
          <button class="btn btn-link" data-toggle="collapse" data-target="#divdash" aria-expanded="true" aria-controls="collapseOne">               <i class="fa" aria-hidden="true"></i>
            
          </button>
        </h5>
        </span>
    </div>
    <div id="divdash" class="collapse show" aria-labelledby="headDash" data-parent="#accordion">-->
 
        <!-- Info boxes -->
        @if (isset($data_brands))
        <div class="row">
          @foreach($data_brands as $key => $data)
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1">{{ $data->kode_brand}}</span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ $data->nama_brand}}</span>
                    <span class="info-box-number">{{ str_replace(',', '.',number_format($data->today, 0)) ?? '' }}
                    @php
                    $persentase = ((($data->today - $data->yesterday)/$data->yesterday)*100)
                    @endphp

                    @if ($persentase > 0)
                    <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> {{ number_format((float)$persentase, 2, '.', '') }}%</span>
                    @else
                    <span class="description-percentage text-success"><i class="fas fa-caret-down"></i> {{ number_format((float)$persentase, 2, '.', '') }}%</span>
                    @endif
                    </span>
                    <span class="info-box-number" style="color:red;">{{ str_replace(',', '.',number_format($data->yesterday, 0)) ?? '' }}</span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
          </div><!-- /.col -->
          @endforeach
        </div><!-- /.row -->
        <!-- info boxes -->
        @endif

<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-map-marker-alt mr-1"></i>Map Store Visualization</h3>
      </div>
      <div class="card-body">
          <div id="mapid" class="map" style="height: 750px;"></div>
      </div>
    </div>
  </div> <!-- col -->

  <div class="col-md-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i>Graph Sales</h3>
            <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="btn btn-info" href="#revenue-chart" data-toggle="tab">Area</a>
                  </li>
                  &nbsp;
                  <li class="nav-item">
                      <a class="btn btn-info" href="#sales-chart" data-toggle="tab">Donut</a>
                  </li>
                </ul>
              </div>
          </div><!-- /.card-header -->
          <div class="card-body">
              <div class="tab-content p-0">
                <!-- Morris chart - Sales -->
                <div class="chart tab-pane active" id="revenue-chart"
                    style="position: relative; height: 200px;">
                    <canvas id="line_charts" style="height:200px; min-height:200px"></canvas>                    
                </div>
                <div class="chart-2 tab-pane" id="sales-chart" style="position: relative; height: 200px;">
                  <canvas id="pie_charts" style="width:100%; height:200px; min-height:200px"></canvas>                       
                </div>  
              </div>
          </div><!-- /.card-body -->
      </div> <!-- /.card -->
  

  <div class="row">
    <div class="col-md-12">
      <div class="card">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-dollar-sign mr-1"></i>Store Sales</h3>
          </div>
          <div class="card-body">
            <div class="table-responsive">
                <table id="table_data" class="display compact" style="width:100%">
                    <thead>
                        <tr>
                            <th>Store ID</th>
                            <th>Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($top_sales as $key => $top)
                          <tr>
                              <td>
                                <a href="{{ url('pos/sales-detail') }}/search/{{ substr($top->id_store,0,2)}}/{{ $top->id_store}}/{{ $today }}/{{ $today }}">
                                {{ $top->id_store}}
                                </a>
                              </td>
                              <td>{{ $top->total}}</td>
                          </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> <!-- table -->
          </div>
      </div> <!-- /.card -->
    </div> <!-- col -->
  </div> <!-- row -->

  </div> <!-- col -->
</div> <!-- row -->
@endsection
@section('scripts')
@parent
<script src="{{ asset('plugins/chartjs/Chart.min.js') }}"></script>
<script>
$(document).ready(function(){
  $('#table_data').DataTable({
        paging: true,
        lengthChange: false,
        info: false,
        pagingType: "simple"
  });
});
</script>
<script>
  var mymap = L.map('mapid').setView([-2.2459632, 116.2409634], 5);
  L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(mymap);

  /*var locations = [
		["RC 999 Pangkalan Bun",-2.6889405,111.6136432],
		["RM 999 Kalibata",-6.2569102,106.8498382]
  ];*/
  
  var locations = [<?php echo $store_location; ?>];

	for (var i = 0; i < locations.length; i++) {
		marker = new L.marker([locations[i][1],locations[i][2]])
			.bindPopup(locations[i][0]+"<br><br><a href='/salesdetail/"+locations[i][0]+"'>Sales Detail</a>")
			.addTo(mymap);
	}

  /*marker.on('click', function(e){
    mymap.setView(e.latlng, 13);
  });*/
  //-2.6889405,111.6136432
  /*L.marker([-6.2569102,106.8498382]).addTo(mymap)
		.bindPopup("<b>RC Kalibata</b><br /> Open").openPopup();
  */
	var popup = L.popup();

	function onMapClick(e) {
		popup
			.setLatLng(e.latlng)
			.setContent("You clicked the map at " + e.latlng.toString())
			.openOn(mymap);
	}

  mymap.on('click', onMapClick);
  
  var data = {
        label: '2020',
        data: <?php echo $chart_data; ?>,
        backgroundColor: 'rgba(0, 99, 132, 0.6)',
        borderWidth: 0,
        yAxisID: "2020"
  };

  var chartOptions = {
        responsive: true,
        scaleShowLabels: false,
        scales: {
            xAxes: [
            {
            barPercentage: 1,
            categoryPercentage: 0.6,
            //stacked: true,
            }],
            yAxes: [
                {id: "2020"}
            ]
        }
  };

  var chartdata = {
      type: 'line',
      data: {
        labels: <?php echo $chart_brands; ?>,
        datasets: [data]
      },
      options: chartOptions
  };

  window.chartColors = {
    red: 'rgb(255, 99, 132)',
    orange: 'rgb(255, 159, 64)',
    yellow: 'rgb(255, 205, 86)',
    green: 'rgb(75, 192, 192)',
    blue: 'rgb(54, 162, 235)',
    purple: 'rgb(153, 102, 255)',
    grey: 'rgb(201, 203, 207)'
  };

  var chartdata_pie = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $chart_data; ?>,
					backgroundColor: [
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
						window.chartColors.blue,
					],
					label: '2020'
				}],
				labels: <?php echo $chart_brands; ?>
			},
      options: {
				responsive: true
			}
  };

  var ctx = document.getElementById('line_charts').getContext('2d');
  new Chart(ctx, chartdata);

  var ctx_pie = document.getElementById('pie_charts').getContext('2d');
  new Chart(ctx_pie, chartdata_pie);
</script>
@endsection