@extends('layouts.admin')
@section('content')
<div class="card">

  <!--<ul class="nav nav-tabs nav-justified">-->
  <ul class="nav nav-tabs">
      <li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#tab1">Tab 1 (5)</a></li>
      <li class="nav-item @if($active_tab=="tab2") active @endif"><a class="nav-link" data-toggle="tab" href="#tab2">Tab 2</a></li>
      <li class="nav-item @if($active_tab=="tab3") active @endif"><a class="nav-link" data-toggle="tab" href="#tab3">Tab 3</a></li>
      <li class="nav-item @if($active_tab=="tab4") active @endif"><a class="nav-link" data-toggle="tab" href="#tab4">Tab 4</a></li>
  </ul>

<div class="card-body">
    <div class="tab-content">
        <div id="tab1" class="tab-pane active">
            <h3>Tab1</h3>
            <!-- BAR CHART -->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Bar Chart</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="barChart" style="height:230px; min-height:230px"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <div id="tab2" class="tab-pane">
            <h3>Tab2</h3>
            <div class="form-group">
                <label for="test2">Test 2 *</label>
                <input type="text" id="test2" name="test2" class="form-control" value="">
            </div>
        </div>
        <div id="tab3" class="tab-pane">
            <h3>Tab3</h3>
            <div class="form-group">
                <label for="test3">Test 3 *</label>
                <input type="text" id="test3" name="test3" class="form-control" value="">
            </div>
        </div>
        @include('tabs.tab4')
    </div>
  </div>
</div>
@endsection
@section('scripts')
@parent
<script src="{{ asset('plugins/chartjs/Chart.min.js') }}"></script>
<script>
    var areaChartData = {
      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label               : 'Digital Goods',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [28, 48, 40, 19, 86, 27, 90]
        },
        {
          label               : 'Electronics',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [65, 59, 80, 81, 56, 55, 40]
        },
      ]
    }

    var areaChartOptions = {
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
    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = jQuery.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    var barChart = new Chart(barChartCanvas, {
      type: 'bar', 
      data: barChartData,
      options: barChartOptions
    })
</script>
@endsection