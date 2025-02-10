@extends('layouts.budget')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Serapan Budget (dashboard)</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Budgeting</a></li>
            <li class="breadcrumb-item"><a href="#">Budget</a></li>
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
            <div class="col-md-4">
            <form class="form-horizontal">
                <div class="form-group row">
                    <label for="periode" class="col-sm-4 col-form-label">Periode Awal</label>
                    <div class="col-sm-8">
                    <select class="form-control" name="periode" id="periode">
                        <option value="all">1 Tahun</option>
                        <option value="q1" {{ ( $periode == "q1") ? 'selected' : '' }}>Kuartal I</option>
                        <option value="q2" {{ ( $periode == "q2") ? 'selected' : '' }}>Kuartal II</option>
                        <option value="q3" {{ ( $periode == "q3") ? 'selected' : '' }}>Kuartal III</option>
                        <option value="q4" {{ ( $periode == "q4") ? 'selected' : '' }}>Kuartal IV</option>
                        <option value="semester1" {{ ( $periode == "semester1") ? 'selected' : '' }}>Semester 1</option>
                        <option value="semester2" {{ ( $periode == "semester2") ? 'selected' : '' }}>Semester 2</option>
                    </select>
                    </div>
                </div>
            </form>
            </div> <!-- div col-4-->
            <div class="col-md-4">
            <form class="form-horizontal">
                <div class="form-group row">
                    <label for="tahun" class="col-sm-4 col-form-label">Tahun</label>
                    <div class="col-sm-8">
                    <select class="form-control" name="tahun" id="tahun">
                   
                      <option value="2023" selected>2023</option>
                    </select>
                    </div>
                </div>
            </form>
            </div> <!-- div col-4-->      
            <div class="col-md-4">
                <span class="float-md-right">
                    <button type="submit" id="btnFiterSubmitSearch" class="btn btn-info btn-sm">Submit</button>
                </span>
            </div>
            </div> <!-- row -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
              <div class="card-header">
                    <div class="container-fluid">
                        <nav class="tabbable">
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <?php 
                            if(\Gate::allows('budget_access_special')){
                            ?>
                                <a class="nav-item nav-link active" aria-selected="true" href="#ams-pie" data-toggle="tab">AMS GROUP</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#acc-pie" data-toggle="tab">ACC</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#audit-pie" data-toggle="tab">AUDIT</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#bdv-pie" data-toggle="tab">BDV</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#creative-pie" data-toggle="tab">CREATIVE</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#dgm-pie" data-toggle="tab">DGM</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#direksi-pie" data-toggle="tab">DIREKSI</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#finance-pie" data-toggle="tab">FINANCE</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#ga-pie" data-toggle="tab">GA</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#hrd-pie" data-toggle="tab">HRD</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#hrdp-pie" data-toggle="tab">HRDP</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#it-pie" data-toggle="tab">IT</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#legal-pie" data-toggle="tab">LEGAL</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#marketing-pie" data-toggle="tab">MARKETING</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#opr-pie" data-toggle="tab">OPR</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#overseas-pie" data-toggle="tab">MD BUYER</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#scm-pie" data-toggle="tab">SCM</a>
                                {{-- <a class="nav-item nav-link" aria-selected="false" href="#scmwh-pie" data-toggle="tab">SCMWH</a> --}}
                                <a class="nav-item nav-link" aria-selected="false" href="#tax-pie" data-toggle="tab">TAX</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#marcomm-pie" data-toggle="tab">ART V</a>
                                <a class="nav-item nav-link" aria-selected="false" href="#vm-pie" data-toggle="tab">VM</a>
                            <?php
                            }else{
                            ?>
                                <a class="nav-item nav-link" aria-selected="false" href="#ams-pie" style="pointer-events:none;" data-toggle="tab">AMS GROUP</a>
                                <a class="nav-item nav-link {{ $group == 'ACC' ? 'active' : '' }}" aria-selected="{{$group == 'ACC' ? 'true' : 'false' }}" {{ $group == 'ACC' ? '' : 'style=pointer-events:none;' }} href="#acc-pie" data-toggle="tab">ACC</a>
                                <a class="nav-item nav-link {{ $group == 'AUDIT' ? 'active' : '' }}" aria-selected="{{$group == 'AUDIT' ? 'true' : 'false' }}" {{ $group == 'AUDIT' ? '' : 'style=pointer-events:none;' }} href="#audit-pie" data-toggle="tab">AUDIT</a>
                                <a class="nav-item nav-link {{ $group == 'BDV' ? 'active' : '' }}" aria-selected="{{$group == 'BDV' ? 'true' : 'false' }}" {{ $group == 'BDV' ? '' : 'style=pointer-events:none;' }} href="#bdv-pie" data-toggle="tab">BDV</a>
                                <a class="nav-item nav-link {{ $group == 'CREATIVE' ? 'active' : '' }}" aria-selected="{{$group == 'CREATIVE' ? 'true' : 'false' }}" {{ $group == 'CREATIVE' ? '' : 'style=pointer-events:none;' }} href="#creative-pie" data-toggle="tab">CREATIVE</a>
                                <a class="nav-item nav-link {{ $group == 'DGM' ? 'active' : '' }}" aria-selected="{{$group == 'DGM' ? 'true' : 'false' }}" {{ $group == 'DGM' ? '' : 'style=pointer-events:none;' }} href="#dgm-pie" data-toggle="tab">DGM</a>
                                <a class="nav-item nav-link {{ $group == 'DIREKSI' ? 'active' : '' }}" aria-selected="{{$group == 'DIREKSI' ? 'true' : 'false' }}" {{ $group == 'DIREKSI' ? '' : 'style=pointer-events:none;' }} href="#direksi-pie" data-toggle="tab">DIREKSI</a>
                                <a class="nav-item nav-link {{ $group == 'FINANCE' ? 'active' : '' }}" aria-selected="{{$group == 'FINANCE' ? 'true' : 'false' }}" {{ $group == 'FINANCE' ? '' : 'style=pointer-events:none;' }} href="#finance-pie" data-toggle="tab">FINANCE</a>
                                <a class="nav-item nav-link {{ $group == 'GA' ? 'active' : '' }}" aria-selected="{{$group == 'GA' ? 'true' : 'false' }}" {{ $group == 'GA' ? '' : 'style=pointer-events:none;' }} href="#ga-pie" data-toggle="tab">GA</a>
                                <a class="nav-item nav-link {{ $group == 'HRD' ? 'active' : '' }}" aria-selected="{{$group == 'HRD' ? 'true' : 'false' }}" {{ $group == 'HRD' ? '' : 'style=pointer-events:none;' }} href="#hrd-pie" data-toggle="tab">HRD</a>
                                <a class="nav-item nav-link {{ $group == 'HRDP' ? 'active' : '' }}" aria-selected="{{$group == 'HRDP' ? 'true' : 'false' }}" {{ $group == 'HRDP' ? '' : 'style=pointer-events:none;' }} href="#hrdp-pie" data-toggle="tab">HRDP</a>
                                <a class="nav-item nav-link {{ $group == 'IT' ? 'active' : '' }}" aria-selected="{{$group == 'IT' ? 'true' : 'false' }}" {{ $group == 'IT' ? '' : 'style=pointer-events:none;' }} href="#it-pie" data-toggle="tab">IT</a>
                                <a class="nav-item nav-link {{ $group == 'LEGAL' ? 'active' : '' }}" aria-selected="{{$group == 'LEGAL' ? 'true' : 'false' }}" {{ $group == 'LEGAL' ? '' : 'style=pointer-events:none;' }} href="#legal-pie" data-toggle="tab">LEGAL</a>
                                <a class="nav-item nav-link {{ $group == 'MARKETING' ? 'active' : '' }}" aria-selected="{{$group == 'MARKETING' ? 'true' : 'false' }}" {{ $group == 'MARKETING' ? '' : 'style=pointer-events:none;' }} href="#marketing-pie" data-toggle="tab">MARKETING</a>
                                <a class="nav-item nav-link {{ $group == 'OPR' ? 'active' : '' }}" aria-selected="{{$group == 'OPR' ? 'true' : 'false' }}" {{ $group == 'OPR' ? '' : 'style=pointer-events:none;' }} href="#opr-pie" data-toggle="tab">OPR</a>
                                <a class="nav-item nav-link {{ $group == 'MDB' ? 'active' : '' }}" aria-selected="{{$group == 'MDB' ? 'true' : 'false' }}" {{ $group == 'MDB' ? '' : 'style=pointer-events:none;' }} href="#overseas-pie" data-toggle="tab">MD BUYER</a>
                                <a class="nav-item nav-link {{ $group == 'SCM' ? 'active' : '' }}" aria-selected="{{$group == 'SCM' ? 'true' : 'false' }}" {{ $group == 'SCM' ? '' : 'style=pointer-events:none;' }} href="#scm-pie" data-toggle="tab">SCM</a>
                                {{-- <a class="nav-item nav-link {{ $group == 'SCMWH' ? 'active' : '' }}" aria-selected="{{$group == 'SCMWH' ? 'true' : 'false' }}" {{ $group == 'SCMWH' ? '' : 'style=pointer-events:none;' }} href="#scmwh-pie" data-toggle="tab">SCMWH</a> --}}
                                <a class="nav-item nav-link {{ $group == 'TAX' ? 'active' : '' }}" aria-selected="{{$group == 'TAX' ? 'true' : 'false' }}" {{ $group == 'TAX' ? '' : 'style=pointer-events:none;' }} href="#tax-pie" data-toggle="tab">TAX</a>
                                <a class="nav-item nav-link {{ $group == 'MARCOMM' ? 'active' : '' }}" aria-selected="{{$group == 'MARCOMM' ? 'true' : 'false' }}" {{ $group == 'MARCOMM' ? '' : 'style=pointer-events:none;' }} href="#marcomm-pie" data-toggle="tab">MARCOMM</a>
                                <a class="nav-item nav-link {{ $group == 'VM' ? 'active' : '' }}" aria-selected="{{$group == 'VM' ? 'true' : 'false' }}" {{ $group == 'VM' ? '' : 'style=pointer-events:none;' }} href="#vm-pie" data-toggle="tab">VM</a>
                            <?php
                            }
                            ?>
                            </div>
                        </div>
                    </div>                
              </div>
              <div class="card-body">
                <div class="tab-content p-0">
                <?php 
                    if(\Gate::allows('budget_access_special')){
                ?>
                    <div class="chart tab-pane active" id="ams-pie"
                        style="position: relative;">
                        <canvas id="pie_ams" style="height:150px; min-height:150px"></canvas>
                    </div>
                    <div class="chart-2 tab-pane" id="acc-pie" style="position: relative;">
                        <canvas id="pie_acc" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-3 tab-pane" id="audit-pie" style="position: relative;">
                        <canvas id="pie_audit" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-4 tab-pane" id="bdv-pie" style="position: relative;">
                        <canvas id="pie_bdv" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-5 tab-pane" id="creative-pie" style="position: relative;">
                        <canvas id="pie_creative" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-6 tab-pane" id="dgm-pie" style="position: relative;">
                        <canvas id="pie_dgm" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-7 tab-pane" id="direksi-pie" style="position: relative;">
                        <canvas id="pie_direksi" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-8 tab-pane" id="finance-pie" style="position: relative;">
                        <canvas id="pie_finance" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-9 tab-pane" id="ga-pie" style="position: relative;">
                        <canvas id="pie_ga" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-10 tab-pane" id="hrd-pie" style="position: relative;">
                        <canvas id="pie_hrd" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-11 tab-pane" id="hrdp-pie" style="position: relative;">
                        <canvas id="pie_hrdp" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-12 tab-pane" id="it-pie" style="position: relative;">
                        <canvas id="pie_it" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-13 tab-pane" id="legal-pie" style="position: relative;">
                        <canvas id="pie_legal" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-14 tab-pane" id="marcomm-pie" style="position: relative;">
                        <canvas id="pie_marcomm" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-15 tab-pane" id="marketing-pie" style="position: relative;">
                        <canvas id="pie_marketing" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-16 tab-pane" id="opr-pie" style="position: relative;">
                        <canvas id="pie_opr" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-17 tab-pane" id="overseas-pie" style="position: relative;">
                        <canvas id="pie_overseas" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-18 tab-pane" id="scm-pie" style="position: relative;">
                        <canvas id="pie_scm" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    {{-- <div class="chart-19 tab-pane" id="scmwh-pie" style="position: relative;">
                        <canvas id="pie_scmwh" style="height:150px; min-height:150px"></canvas>
                    </div>   --}}
                    <div class="chart-20 tab-pane" id="tax-pie" style="position: relative;">
                        <canvas id="pie_tax" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-21 tab-pane" id="vm-pie" style="position: relative;">
                        <canvas id="pie_vm" style="height:150px; min-height:150px"></canvas>
                    </div>  
                <?php
                    }
                    else{
                ?>
                    <div class="chart tab-pane" id="ams-pie"
                        style="position: relative;">
                        <canvas id="pie_ams" style="height:150px; min-height:150px"></canvas>
                    </div>
                    <div class="chart-2 tab-pane {{ $group == 'ACC' ? 'active' : '' }}" id="acc-pie" style="position: relative;">
                        <canvas id="pie_acc" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-3 tab-pane {{ $group == 'AUDIT' ? 'active' : '' }}" id="audit-pie" style="position: relative;">
                        <canvas id="pie_audit" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-4 tab-pane {{ $group == 'BDV' ? 'active' : '' }}" id="bdv-pie" style="position: relative;">
                        <canvas id="pie_bdv" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-5 tab-pane {{ $group == 'CREATIVE' ? 'active' : '' }}" id="creative-pie" style="position: relative;">
                        <canvas id="pie_creative" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-6 tab-pane {{ $group == 'DGM' ? 'active' : '' }}" id="dgm-pie" style="position: relative;">
                        <canvas id="pie_dgm" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-7 tab-pane {{ $group == 'DIREKSI' ? 'active' : '' }}" id="direksi-pie" style="position: relative;">
                        <canvas id="pie_direksi" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-8 tab-pane {{ $group == 'FINANCE' ? 'active' : '' }}" id="finance-pie" style="position: relative;">
                        <canvas id="pie_finance" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-9 tab-pane {{ $group == 'GA' ? 'active' : '' }}" id="ga-pie" style="position: relative;">
                        <canvas id="pie_ga" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-10 tab-pane {{ $group == 'HRD' ? 'active' : '' }}" id="hrd-pie" style="position: relative;">
                        <canvas id="pie_hrd" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-11 tab-pane {{ $group == 'HRDP' ? 'active' : '' }}" id="hrdp-pie" style="position: relative;">
                        <canvas id="pie_hrdp" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-12 tab-pane {{ $group == 'IT' ? 'active' : '' }}" id="it-pie" style="position: relative;">
                        <canvas id="pie_it" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-13 tab-pane {{ $group == 'LEGAL' ? 'active' : '' }}" id="legal-pie" style="position: relative;">
                        <canvas id="pie_legal" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-14 tab-pane {{ $group == 'MARCOMM' ? 'active' : '' }}" id="marcomm-pie" style="position: relative;">
                        <canvas id="pie_marcomm" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-15 tab-pane {{ $group == 'MARKETING' ? 'active' : '' }}" id="marketing-pie" style="position: relative;">
                        <canvas id="pie_marketing" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-16 tab-pane {{ $group == 'OPR' ? 'active' : '' }}" id="opr-pie" style="position: relative;">
                        <canvas id="pie_opr" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-17 tab-pane {{ $group == 'MDB' ? 'active' : '' }}" id="overseas-pie" style="position: relative;">
                        <canvas id="pie_overseas" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-18 tab-pane {{ $group == 'SCM' ? 'active' : '' }}" id="scm-pie" style="position: relative;">
                        <canvas id="pie_scm" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    {{-- <div class="chart-19 tab-pane {{ $group == 'SCMWH' ? 'active' : '' }}" id="scmwh-pie" style="position: relative;">
                        <canvas id="pie_scmwh" style="height:150px; min-height:150px"></canvas>
                    </div>   --}}
                    <div class="chart-20 tab-pane {{ $group == 'TAX' ? 'active' : '' }}" id="tax-pie" style="position: relative;">
                        <canvas id="pie_tax" style="height:150px; min-height:150px"></canvas>
                    </div>  
                    <div class="chart-21 tab-pane {{ $group == 'VM' ? 'active' : '' }}" id="vm-pie" style="position: relative;">
                        <canvas id="pie_vm" style="height:150px; min-height:150px"></canvas>
                    </div>  
                <?php

                    }
                ?>
                </div>
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
    </div>
</div>


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

function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
  window.chartColors = {
    red: '	rgb(255, 0, 0)',
    orange: 'rgb(255, 159, 64)',
    yellow: 'rgb(255, 205, 86)',
    green: 'rgb(27, 128, 1)',
    blue: 'rgb(54, 162, 235)',
    purple: 'rgb(153, 102, 255)',
    grey: 'rgb(201, 203, 207)'
  };
    // A plugin that hides slices, given their indices, across all datasets.
// var hideSlicesPlugin = {
//   afterInit: function(chartInstance) {
//     // If `hiddenSlices` has been set.
//     if (chartInstance.config.data.hiddenSlices !== undefined) {
//       // Iterate all datasets.
//       for (var i = 0; i < chartInstance.data.datasets.length; ++i) {
//         // Iterate all indices of slices to be hidden.
//         chartInstance.config.data.hiddenSlices.forEach(function(index) {
//           // Hide this slice for this dataset.
//           chartInstance.getDatasetMeta(i).data[index].hidden = true;
//         });
//       }
//       chartInstance.update();
//     }
//   }
// };

// Chart.pluginService.register(hideSlicesPlugin);

  var chartdata_pie = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $chart_data; ?>,
					backgroundColor: [
            window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
        hiddenSlices: [1,2],
				labels: <?php echo $chart_label; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
            $periodelabel = "Tahun";
        }elseif ($periode =="q1"){
            $periodelabel = "Kuartal 1";
        }elseif ($periode =="q2"){
            $periodelabel = "Kuartal 2";
        }elseif ($periode =="q3"){
            $periodelabel = "Kuartal 3";
        }elseif ($periode =="q4"){
            $periodelabel = "Kuartal 4";
        }elseif ($periode =="semester1"){
          $periodelabel = "Semester 1";
        }elseif ($periode =="semester2"){
          $periodelabel = "Semester 2";
        }else{
          $periodelabel = "hubungi IT";
        }
        ?>
      text: 'Serapan Budget AMS GROUP Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_pie = document.getElementById('pie_ams').getContext('2d');
  const chart = new Chart(ctx_pie, chartdata_pie);
  
    chart.getDatasetMeta(0).data[3].hidden = true;
    chart.update();
 

  //chart acc
  var chartdata_pieacc = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_acc; ?>,
					backgroundColor: [
            window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_acc; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group Accounting Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_pieacc = document.getElementById('pie_acc').getContext('2d');
  const chartacc = new Chart(ctx_pieacc, chartdata_pieacc);
  chartacc.getDatasetMeta(0).data[3].hidden = true;
  chartacc.update();

  //chart audit
  var chartdata_pieaudit = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_audit; ?>,
					backgroundColor: [
            window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_audit; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group Audit Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_pieaudit = document.getElementById('pie_audit').getContext('2d');
  const chartaudit = new Chart(ctx_pieaudit, chartdata_pieaudit);
  chartaudit.getDatasetMeta(0).data[3].hidden = true;
  chartaudit.update();

  //chart bdv
  var chartdata_piebdv = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_bdv; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_bdv; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group Bdv Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_piebdv = document.getElementById('pie_bdv').getContext('2d');
  const chartbdv = new Chart(ctx_piebdv, chartdata_piebdv);
  chartbdv.getDatasetMeta(0).data[3].hidden = true;
  chartbdv.update();

  //chart creative
  var chartdata_piecreative = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_creative; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_creative; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group Creative Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_piecreative = document.getElementById('pie_creative').getContext('2d');
  const chartcrt = new Chart(ctx_piecreative, chartdata_piecreative);
  chartcrt.getDatasetMeta(0).data[3].hidden = true;
  chartcrt.update();

  //chart dgm
  var chartdata_piedgm = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_dgm; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_dgm; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group DGM Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_piedgm = document.getElementById('pie_dgm').getContext('2d');
  const chartdgm = new Chart(ctx_piedgm, chartdata_piedgm);
  chartdgm.getDatasetMeta(0).data[3].hidden = true;
  chartdgm.update();

  //chart direksi
  var chartdata_piedireksi = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_direksi; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_direksi; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group Direksi Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_piedireksi = document.getElementById('pie_direksi').getContext('2d');
  const chartdireksi = new Chart(ctx_piedireksi, chartdata_piedireksi);
  chartdireksi.getDatasetMeta(0).data[3].hidden = true;
  chartdireksi.update();


  //chart finance
  var chartdata_piefinance = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_finance; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_finance; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group Finance Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_piefinance = document.getElementById('pie_finance').getContext('2d');  
  const chartfinance = new Chart(ctx_piefinance, chartdata_piefinance);
  // if (chartfinance.getDatasetMeta(0).data[3] < 0){
    chartfinance.getDatasetMeta(0).data[3].hidden = true;
    chartfinance.update();
  // }

  //chart ga
  var chartdata_piega = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_ga; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_ga; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group GA Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_piega = document.getElementById('pie_ga').getContext('2d');
  const chartga = new Chart(ctx_piega, chartdata_piega);
  chartga.getDatasetMeta(0).data[3].hidden = true;
  chartga.update();
  //chart hrd
  var chartdata_piehrd = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_hrd; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_hrd; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group HRD Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_piehrd = document.getElementById('pie_hrd').getContext('2d');
  const charthrd = new Chart(ctx_piehrd, chartdata_piehrd);
  charthrd.getDatasetMeta(0).data[3].hidden = true;
  charthrd.update();
  //chart hrdp
  var chartdata_piehrdp = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_hrdp; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_hrdp; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group HRDP Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_piehrdp = document.getElementById('pie_hrdp').getContext('2d');
  const charthrdp = new Chart(ctx_piehrdp, chartdata_piehrdp);
  charthrdp.getDatasetMeta(0).data[3].hidden = true;
  charthrdp.update();

  //chart IT
  var chartdata_pieit = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_it; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_it; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group IT Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_pieit = document.getElementById('pie_it').getContext('2d');
  const chartit = new Chart(ctx_pieit, chartdata_pieit);
  chartit.getDatasetMeta(0).data[3].hidden = true;
  chartit.update();

  //chart legal
  var chartdata_pielegal = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_legal; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_legal; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group LEGAL Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_pielegal = document.getElementById('pie_legal').getContext('2d');
  const chartlegal = new Chart(ctx_pielegal, chartdata_pielegal);
  chartlegal.getDatasetMeta(0).data[3].hidden = true;
  chartlegal.update();

  //chart Marcomm
  var chartdata_piemarcomm = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_marcomm; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_marcomm; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group Marcomm Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_piemarcomm = document.getElementById('pie_marcomm').getContext('2d');
  const chartmarcomm = new Chart(ctx_piemarcomm, chartdata_piemarcomm);
  chartmarcomm.getDatasetMeta(0).data[3].hidden = true;
  chartmarcomm.update();

  //chart Marketing
  var chartdata_piemarketing = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_marketing; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_marketing; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group Marketing Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_piemarketing = document.getElementById('pie_marketing').getContext('2d');
  const chartmarketing = new Chart(ctx_piemarketing, chartdata_piemarketing);
  chartmarketing.getDatasetMeta(0).data[3].hidden = true;
  chartmarketing.update();

  //chart OPR
  var chartdata_pieopr = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_opr; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_opr; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group OPR Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_pieopr = document.getElementById('pie_opr').getContext('2d');
  const chartopr = new Chart(ctx_pieopr, chartdata_pieopr);
  chartopr.getDatasetMeta(0).data[3].hidden = true;
  chartopr.update();

  //chart Overseas
  var chartdata_pieoverseas = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_overseas; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_overseas; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group MD Buyer Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_pieoverseas = document.getElementById('pie_overseas').getContext('2d');
  const chartovrss = new Chart(ctx_pieoverseas, chartdata_pieoverseas);
  chartovrss.getDatasetMeta(0).data[3].hidden = true;
  chartovrss.update();


  //chart SCM
  var chartdata_piescm = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_scm; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_scm; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group SCM Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_piescm = document.getElementById('pie_scm').getContext('2d');
  const chartscm = new Chart(ctx_piescm, chartdata_piescm);
  chartscm.getDatasetMeta(0).data[3].hidden = true;
  chartscm.update();

  //chart TAX
  var chartdata_pietax = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_tax; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_tax; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group TAX Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_pietax = document.getElementById('pie_tax').getContext('2d');
  const charttax = new Chart(ctx_pietax, chartdata_pietax);
  charttax.getDatasetMeta(0).data[3].hidden = true;
  charttax.update();

  //chart VM
  var chartdata_pievm = {
      type: 'pie',
      data: {
				datasets: [{
					data: <?php echo $data_vm; ?>,
					backgroundColor: [
                        window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.green,
					]
				}],
				labels: <?php echo $label_vm; ?>
			},
    options: {
    title: {
      display: true,
      <?php 
        if ($periode == "all"){
          $periodelabel = "Tahun";
      }elseif ($periode =="q1"){
          $periodelabel = "Kuartal 1";
      }elseif ($periode =="q2"){
          $periodelabel = "Kuartal 2";
      }elseif ($periode =="q3"){
          $periodelabel = "Kuartal 3";
      }elseif ($periode =="q4"){
          $periodelabel = "Kuartal 4";
      }elseif ($periode =="semester1"){
        $periodelabel = "Semester 1";
      }elseif ($periode =="semester2"){
        $periodelabel = "Semester 2";
      }else{
        $periodelabel = "hubungi IT";
      }
        ?>
      text: 'Serapan Budget Group VM Periode '.concat('<?php echo $periodelabel;?>').concat(' ').concat('<?php echo $tahun;?>'),
      fontStyle: 'bold',
      fontSize: 20
    },
    tooltips: {
      callbacks: {
        // this callback is used to create the tooltip label
        label: function(tooltipItem, data) {
          // get the data label and data value to display
          // convert the data value to local string so it uses a comma seperated number
          var dataLabel = data.labels[tooltipItem.index];
          var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

          // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
          if (Chart.helpers.isArray(dataLabel)) {
            // show value on first line of multiline label
            // need to clone because we are changing the value
            dataLabel = dataLabel.slice();
            dataLabel[0] += value;
          } else {
            dataLabel += value;
          }

          // return the text to display on the tooltip
          return dataLabel;
            }
        }
        }
    }
  };

  var ctx_pievm = document.getElementById('pie_vm').getContext('2d');
  const chartvm = new Chart(ctx_pievm, chartdata_pievm);
  chartvm.getDatasetMeta(0).data[3].hidden = true;
  chartvm.update();

  $('#btnFiterSubmitSearch').click(function(){
        var spinner = $('#loader');
        var periode = $("#periode option:selected").val();
        var tahun = $("#tahun option:selected").val();
        spinner.show();
        window.location.href = "{{ url('budget/dashboard') }}"+ "/search/" + periode +  "/" + tahun + "";
    });
</script>
@endsection