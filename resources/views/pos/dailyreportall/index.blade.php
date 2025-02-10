@extends('layouts.pos')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Daily Sales Report All</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">POS</a></li>
            <li class="breadcrumb-item"><a href="#">Sales</a></li>
            <li class="breadcrumb-item active">Daily Sales Report All</li>
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
          <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">               <i class="fa" aria-hidden="true"></i>
            
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
                    <label for="BRAND" class="col-sm-4 col-form-label">Brand</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="BRAND" id="BRAND" data-dependent="STORE">
                            <option value="all">All</option>
                            @foreach($brands as $brand_data)
                            <option value="{{$brand_data->kode_brand}}"
                            {{ ( $brand_pilih == $brand_data->kode_brand) ? 'selected' : '' }}
                            >{{$brand_data->nama_brand}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="STORE" class="col-sm-4 col-form-label">ID Store</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="STORE" id="STORE">
                            @if (!empty($store))
                                <option value="{{$store}}">{{$store}}</option>
                            @else
                                <option value="all">Pilih Brand Dahulu</option>
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
            <ul class="nav nav-tabs">
                <li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#tabdata">Data</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tabbars">Bars Chart</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tablines">Lines Chart</a></li>
            </ul>
            <div class="card-body">
                <div class="tab-content">
                    <div id="tabdata" class="tab-pane active">
                        <!--<h5>Report {{ $bulan}}</h5><br>
                        <h5>Brand : {{ $brand_pilih}}</h5><br>
                        <h5>Store : {{ $store}}</h5><br>-->
                        
                        <div class="table-responsive">
                        <table id="table_data" class="display compact" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th colspan="5" align="center">Sales In Value</th>
                                    <th colspan="5" align="center">Sales In QTY</th>
                                    <th colspan="5" align="center">Sales In Transaksi</th>
                                    <th colspan="2" align="center">Target</th>
                                    <th rowspan="2">Discount</th>
                                    <th rowspan="2">Voucher</th>
                                </tr>
                                <tr>
                                    <th>Tgl</th>
                                    <!-- sales value -->
                                    <th>2018</th>
                                    <th>2019</th>
                                    <th>Growth</th>
                                    <th>2020</th>
                                    <th>Growth</th>
                                    <!-- sales qty -->
                                    <th>2018</th>
                                    <th>2019</th>
                                    <th>Growth</th>
                                    <th>2020</th>
                                    <th>Growth</th>
                                    <!-- sales trs -->
                                    <th>2018</th>
                                    <th>2019</th>
                                    <th>Growth</th>
                                    <th>2020</th>
                                    <th>Growth</th>
                                    <th>Value</th>
                                    <th>Ach</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_sales_2018 = 0;
                                    $total_sales_2019 = 0;
                                    $total_sales_2020 = 0;
                                    $total_qty_2018 = 0;
                                    $total_qty_2019 = 0;
                                    $total_qty_2020 = 0;
                                    $total_jum_2018 = 0;
                                    $total_jum_2019 = 0;
                                    $total_jum_2020 = 0;
                                    $total_target = 0;
                                    $total_discount = 0;
                                    $total_voucher = 0;
                                @endphp
                                @foreach($datanya as $key => $data)
                                    @php
                                        if(strlen($data->tanggal) < 2){
                                            $tglnya = "0".$data->tanggal;
                                        }else{
                                            $tglnya = $data->tanggal;
                                        }

                                        if(isset($batas_hari)){
                                            if($tglnya == $batas_hari) break;
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                        @php
                                            if(strlen($data->tanggal) < 2){
                                                echo "0".$data->tanggal;
                                            }else{
                                                echo $data->tanggal;
                                            }
                                        @endphp
                                        </td>
                                        <td> {{ str_replace(',', '.',number_format($data->sales_2018, 0)) ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($data->sales_2019, 0)) ?? '' }}</td>
                                        <td> {{ $data->growth_sales_1 ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($data->sales_2020, 0)) ?? '' }}</td>
                                        <td> {{ $data->growth_sales_2 ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($data->sales_2018_qty, 0)) ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($data->sales_2019_qty, 0)) ?? '' }}</td>
                                        <td> {{ $data->growth_qty_1 ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($data->sales_2020_qty, 0)) ?? '' }}</td>
                                        <td> {{ $data->growth_qty_2 ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($data->sales_2018_jum, 0)) ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($data->sales_2019_jum, 0))?? '' }}</td>
                                        <td> {{ $data->growth_jum_1 ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($data->sales_2020_jum, 0))?? '' }}</td>
                                        <td> {{ $data->growth_jum_2 ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($data->store_target, 0)) ?? '' }}</td>
                                        <td> {{ $data->ach ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($data->discount_2020, 0)) ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($data->voucher_2020, 0)) ?? '' }}</td>
                                    </tr>
                                    @php($total_sales_2018 += $data->sales_2018)
                                    @php($total_sales_2019 += $data->sales_2019)
                                    @php($total_sales_2020 += $data->sales_2020)
                                    @php($total_qty_2018 += $data->sales_2018_qty)
                                    @php($total_qty_2019 += $data->sales_2019_qty)
                                    @php($total_qty_2020 += $data->sales_2020_qty)
                                    @php($total_jum_2018 += $data->sales_2018_jum)
                                    @php($total_jum_2019 += $data->sales_2019_jum)
                                    @php($total_jum_2020 += $data->sales_2020_jum)
                                    @php($total_target += $data->store_target)
                                    @php($total_discount += $data->discount_2020)
                                    @php($total_voucher += $data->voucher_2020)
                                @endforeach
                                   
                            </tbody>
                            <tfoot>
                            <tr>
                                        <td><strong>Total</strong></td>
                                        <!-- sales-->
                                        <td> {{ str_replace(',', '.',number_format($total_sales_2018, 0)) ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($total_sales_2019, 0)) ?? '' }}</td>
                                        <td> {{ round(round($total_sales_2019-$total_sales_2018) * 100.0 / $total_sales_2019,0) }} %</td>
                                        <td> {{ str_replace(',', '.',number_format($total_sales_2020, 0)) ?? '' }}</td>
                                        <td> {{ round(round($total_sales_2020-$total_sales_2019) * 100.0 / $total_sales_2020,0) }} %</td>
                                        <!-- qty-->
                                        <td> {{ str_replace(',', '.',number_format($total_qty_2018, 0)) ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($total_qty_2019, 0)) ?? '' }}</td>
                                        <td> {{ round(round($total_qty_2019-$total_qty_2018) * 100.0 / $total_qty_2019,0) }} %</td>
                                        <td> {{ str_replace(',', '.',number_format($total_qty_2020, 0)) ?? '' }}</td>
                                        <td> {{ round(round($total_qty_2020-$total_qty_2019) * 100.0 / $total_qty_2020,0) }} %</td>
                                        <!-- jum-->
                                        <td> {{ str_replace(',', '.',number_format($total_jum_2018, 0)) ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($total_jum_2019, 0))?? '' }}</td>
                                        <td> {{ round(round($total_jum_2019-$total_jum_2018) * 100.0 / $total_jum_2019,0) }} %</td>
                                        <td> {{ str_replace(',', '.',number_format($total_jum_2020, 0))?? '' }}</td>
                                        <td> {{ round(round($total_jum_2020-$total_jum_2019) * 100.0 / $total_jum_2020,0) }} %</td>
                                        <td> {{ $total_target == 0 ? 0 : str_replace(',', '.',number_format($total_target, 0)) ?? '' }}</td>
                                        <td> {{ $total_target == 0 ? 0 : round($total_sales_2020 * 100.0 / $total_target,0) }} %</td>
                                        <td> {{ str_replace(',', '.',number_format($total_discount, 0)) ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($total_voucher, 0)) ?? '' }}</td>
                                    </tr>
                            </tfoot>
                        </table>
                        </div>
                    </div>
                    <div id="tabbars" class="tab-pane">
                        <div class="chart">
                            <canvas id="barChart" style="height:250px; min-height:250px"></canvas>
                        </div>
                    </div>
                    <div id="tablines" class="tab-pane">
                        <div class="chart">
                            <canvas id="lineChart" style="height:250px; min-height:250px"></canvas>
                        </div>
                    </div>
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
<script src="{{ asset('plugins/chartjs/Chart.min.js') }}"></script>
<script>
$(document).ready(function(){
    $('#BRAND').on('change',function(){
        if($(this).val() != ''){
            $.getJSON('{{ url('pos/dailyreportall-store') }}' + "/" + $("#BRAND option:selected").val(), function(data) {
                var temp = [];
                $.each(data, function(key, value) {
                    temp.push({v:value, k: key});
                });
                $('#STORE').empty();
                $('#STORE').append('<option value="all">All</option>');   
                $.each(temp, function(key, obj) {
                $('#STORE').append('<option value="' + obj.k +'">' + obj.v + '</option>');           
                }); 
            });
        }
    });

    $('#BRAND').on('change',function(){
        $('#STORE').val('');
    });

    /*paging:         false,
            fixedColumns:   {
                leftColumns: 1
            }, */

    $('#table_data').DataTable({
        //responsive: true,
        //fixedHeader: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel'
        ],
            scrollY:        false,
            scrollX:        true,
            autoWidth:      true,
            ordering:       false,
            paging:         false
    });
    
    $('#btnFiterSubmitSearch').click(function(){
        //$('#table_data').DataTable().draw(true);
        //alert("test");
        var spinner = $('#loader');
        var bulan = $("#bulan option:selected").val();
        var brand = $("#BRAND option:selected").val();
        var store = $("#STORE option:selected").val();
        spinner.show();
        window.location.href = "{{ url('pos/dailyreportall') }}"+ "/search/" + bulan + "/" + brand + "/" + store + "";
    });

    //data chart
    var areaChartData = {
      labels  : <?php echo $Months; ?>,
      datasets: [
        {
          label               : '2018',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : <?php echo $data1; ?>
        },
        {
          label               : '2019',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : <?php echo $data2; ?>
        },
        {
          label               : '2020',
          backgroundColor     : 'rgba(59, 89, 152, 1)',
          borderColor         : 'rgba(59, 89, 152, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(59, 89, 152, 1)',
          pointStrokeColor    : '#3b5998',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(59,89,152,1)',
          data                : <?php echo $data3; ?>
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

    var areaChartLineOptions = {
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
					labelString: 'Day'
				}
			}],
			yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Sales'
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
    var temp2 = areaChartData.datasets[2]
    barChartData.datasets[0] = temp0
    barChartData.datasets[1] = temp1
    barChartData.datasets[2] = temp2

    //bar chart
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

    //stacked bar chart
    /*var stackedBarChartCanvas = $('#barChart').get(0).getContext('2d')
    var stackedBarChartData = jQuery.extend(true, {}, barChartData)

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true
        }]
      }
    }

    var stackedBarChart = new Chart(stackedBarChartCanvas, {
      type: 'bar', 
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })*/

    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
    var lineChartOptions = jQuery.extend(true, {}, areaChartOptions)
    var lineChartData = jQuery.extend(true, {}, areaChartData)
    lineChartData.datasets[0].fill = false;
    lineChartData.datasets[1].fill = false;
    lineChartData.datasets[2].fill = false;
    lineChartOptions.datasetFill = false

    var lineChart = new Chart(lineChartCanvas, { 
      type: 'line',
      data: lineChartData, 
      options: areaChartLineOptions
    })
});
</script>
@endsection