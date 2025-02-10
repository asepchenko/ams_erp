@extends('layouts.approval')
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
          <li class="breadcrumb-item"><a href="#">Approval</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Info boxes Danger-->
@if (isset($data_document))
<!--<div class="card">
  <div class="card-header">
    <h5>Outstanding Document by Dept</h5>
  </div>
  <div class="card-body">-->
    <div class="row">
      @foreach($data_document as $key => $data)
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-danger">{{ substr($data->current_dept,0,3)}}</span>
              <div class="info-box-content">
                @if (substr($data->current_dept,-2) == "MS")
                  <span class="info-box-text">{{ $data->nama_dept}} SM</span>
                @else
                  <span class="info-box-text">{{ $data->nama_dept}}</span>
                @endif
                <span class="info-box-number">{{ str_replace(',', '.',number_format($data->jum, 0)) ?? '' }} Pending</span>
                <span class="float-md-right">
                
                @if($data->current_dept == $dept)
                  <a href="{{ $data->link }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
                @else
                  @can('approval_dashboard_click_detail')
                    <a href="{{ $data->link }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
                  @endcan
                @endif

                </span>
              </div><!-- /.info-box-content -->
          </div><!-- /.info-box -->
        </div><!-- /.col -->
      @endforeach
    </div><!-- /.row -->
  <!--</div>
</div>-->
@endif
<!-- info boxes Danger -->

<!-- Info boxes Warning -->
@if (isset($data_document))
<!--<div class="card">
  <div class="card-header">
    <h5>Outstanding Document by Dept</h5>
  </div>
  <div class="card-body">-->
    <div class="row">
      @foreach($data_document_man as $key => $data_man)
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-warning">{{ substr($data_man->current_dept,0,3)}}</span>
              <div class="info-box-content">
                @if (substr($data_man->current_dept,-2) == "MS")
                  <span class="info-box-text">{{ $data_man->nama_dept}} SM</span>
                @else
                  <span class="info-box-text">{{ $data_man->nama_dept}}</span>
                @endif
                <span class="info-box-number">{{ str_replace(',', '.',number_format($data_man->jum, 0)) ?? '' }} Not Approved</span>
                <span class="float-md-right">

                @if($data_man->current_dept == $dept)
                  <a href="{{ $data_man->link }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
                @else
                  @can('approval_dashboard_click_detail')
                    <a href="{{ $data_man->link }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
                  @endcan
                @endif
                
                </span>
              </div><!-- /.info-box-content -->
          </div><!-- /.info-box -->
        </div><!-- /.col -->
      @endforeach
    </div><!-- /.row -->
  <!--</div>
</div>-->
@endif
<!-- info boxes Warning-->

<!-- Info boxes-->
@if (isset($data_document))
<!--<div class="card">
  <div class="card-header">
    <h5>Outstanding Document by Dept</h5>
  </div>
  <div class="card-body">-->
    <div class="row">
      @foreach($data_document_open as $key => $data_open)
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-info">{{ substr($data_open->current_dept,0,3)}}</span>
              <div class="info-box-content">
                @if (substr($data_open->current_dept,-2) == "MS")
                  <span class="info-box-text">{{ $data_open->nama_dept}} SM</span>
                @else
                  <span class="info-box-text">{{ $data_open->nama_dept}}</span>
                @endif
                <span class="info-box-number">{{ str_replace(',', '.',number_format($data_open->jum, 0)) ?? '' }} Not Submitted</span>
                <span class="float-md-right">
                
                @if($data_open->current_dept == $dept)
                  <a href="{{ $data_open->link }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
                @else
                  @can('approval_dashboard_click_detail')
                    <a href="{{ $data_open->link }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
                  @endcan
                @endif

                </span>
              </div><!-- /.info-box-content -->
          </div><!-- /.info-box -->
        </div><!-- /.col -->
      @endforeach
    </div><!-- /.row -->
  <!--</div>
</div>-->
@endif
<!-- info boxes -->

<div class="card">
  <div class="card-body">
    <div class="chart">
      <canvas id="lineChart" style="height:250px; min-height:250px"></canvas>
    </div>
  </div>
</div>
@endsection
@section('scripts')
@parent
<script src="{{ asset('plugins/chartjs/Chart.min.js') }}"></script>
<script>
$(document).ready(function(){

    //data chart
    var ChartData = {
      labels: <?php echo json_encode(array_keys($chartData)); ?>,
      datasets: [
        {
          label: 'Jumlah Document',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius         : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data: <?php echo json_encode(array_values($chartData)); ?>
        }
      ]
    }

    var aChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }

    var ChartOptions = {
      responsive: true,
      tooltips: {
        mode: 'index',
        intersect: false,
      },
      hover: {
        mode: 'nearest',
        intersect: true
      },
      scales: {
        xAxes: [{
          display: true,
          scaleLabel: {
          display: true,
          labelString: 'Tanggal'
          }
        }],
        yAxes: [{
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Total'
          }
        }]
		  }
    }

    var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
    var lineChartOptions = jQuery.extend(true, {}, ChartOptions)
    var lineChartData = jQuery.extend(true, {}, ChartData)
    lineChartData.datasets[0].fill = false;
    lineChartOptions.datasetFill = false

    var lineChart = new Chart(lineChartCanvas, { 
      type: 'line',
      data: lineChartData, 
      options: lineChartOptions
    })
});
</script>
@endsection