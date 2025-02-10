@extends('layouts.pos')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Daily Sales Report by Group Head</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">POS</a></li>
            <li class="breadcrumb-item active">Daily Sales Report by Group Head</li>
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
                    <label for="GH" class="col-sm-4 col-form-label">Group Head</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="GH" id="GH" data-dependent="STORE">
                            <option value="0">All</option>
                            @foreach($ghs as $gh_data)
                            <option value="{{$gh_data->id}}"
                            {{ ( $gh_pilih == $gh_data->id) ? 'selected' : '' }}
                            >{{$gh_data->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
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

@if (isset($datanya))
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <ul class="nav nav-tabs">
                <li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#tabdata">Data</a></li>
                <!--<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tabbars">Bars Chart</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tablines">Lines Chart</a></li>-->
            </ul>
            <div class="card-body">
                <div class="tab-content">
                    <div id="tabdata" class="tab-pane active">
                        
                        <div class="table-responsive">
                        <table id="table_data" class="display compact" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th colspan="5" align="center">Sales In Value</th>
                                    <th colspan="5" align="center">Sales In QTY</th>
                                    <th colspan="5" align="center">Sales In Transaksi</th>
                                    <th colspan="2" align="center">Target</th>
                                    <th rowspan="2">Discount</th>
                                    <th rowspan="2">Voucher</th>
                                </tr>
                                <tr>
                                    <th class="no-sort">Store ID</th>
                                    <th>Tgl</th>
                                    <!-- sales value -->
                                    <th class="no-sort">2018</th>
                                    <th class="no-sort">2019</th>
                                    <th class="no-sort">Growth</th>
                                    <th class="no-sort">2020</th>
                                    <th class="no-sort">Growth</th>
                                    <!-- sales qty -->
                                    <th class="no-sort">2018</th>
                                    <th class="no-sort">2019</th>
                                    <th class="no-sort">Growth</th>
                                    <th class="no-sort">2020</th>
                                    <th class="no-sort">Growth</th>
                                    <!-- sales trs -->
                                    <th class="no-sort">2018</th>
                                    <th class="no-sort">2019</th>
                                    <th class="no-sort">Growth</th>
                                    <th class="no-sort">2020</th>
                                    <th class="no-sort">Growth</th>
                                    <th class="no-sort">Value</th>
                                    <th class="no-sort">Ach</th>
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
                                        <td>{{ $data->id_store}}</td>
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
                            <canvas id="test_charts" style="height:250px; min-height:250px"></canvas>
                        </div>
                    </div>
                    <div id="tablines" class="tab-pane">
                        <div class="chart">
                            <canvas id="line_charts" style="height:250px; min-height:250px"></canvas>
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
    /*$('#GH').on('change',function(){
        if($(this).val() != ''){
            $.getJSON('{{ url('pos/dailyreportgh-store') }}' + "/" + $("#GH option:selected").val(), function(data) {
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

    $('#GH').on('change',function(){
        $('#STORE').val('');
    });*/

    /*paging:         false,
            fixedColumns:   {
                leftColumns: 1
            } */

        var groupColumn = 0;
		var table = $('#table_data').DataTable({
            //"responsive": true,
            //"fixedHeader": true,
            /*{
            orderable: false,
            targets: "no-sort"
            */
            "dom": 'Bfrtip',
            "buttons": [
                'copy', 'csv', 'excel'
            ],
            "scrollY":        false,
            "scrollX":        true,
            "autoWidth":      true,
            "paging":         false,
			"columnDefs": [
                { "visible": false, "targets": groupColumn  },
                { "orderable": false, "targets": "no-sort"}
			],
            "order": [[ groupColumn, 'asc' ]],
            //"ordering":       false,
			"drawCallback": function ( settings ) {
				/*var api = this.api();
				var rows = api.rows( {page:'current'} ).nodes();
				var last=null;
	 
				api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
					if ( last !== group ) {
						$(rows).eq( i ).before(
							'<tr class="group"><td colspan="20">'+group+'</td></tr>'
						);
	 
						last = group;
					}
                });*/
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last = null;
                var subTotal = new Array();
                var groupID = -1;
                var aData = new Array();
                var index = 0;
            
                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    
                // console.log(group+">>>"+i);
                
                var vals = api.row(api.row($(rows).eq(i)).index()).data();
                //console.log(vals[2]);
                var tempsales2018 = vals[2].replace(/\./g,'');
                var tempsales2019 = vals[3].replace(/\./g,'');
                var tempsales2020 = vals[5].replace(/\./g,'');
                
                var tempqty2018 = vals[7].replace(/\./g,'');
                var tempqty2019 = vals[8].replace(/\./g,'');
                var tempqty2020 = vals[10].replace(/\./g,'');

                var tempjum2018 = vals[12].replace(/\./g,'');
                var tempjum2019 = vals[13].replace(/\./g,'');
                var tempjum2020 = vals[15].replace(/\./g,'');

                var temptarget2020 = vals[17].replace(/\./g,'');
                var tempdisc2020 = vals[19].replace(/\./g,'');
                var tempvoucher2020 = vals[20].replace(/\./g,'');

                //console.log(tempsales2018);
                var sales2018 = tempsales2018 ? parseFloat(tempsales2018) : 0;
                var sales2019 = tempsales2019 ? parseFloat(tempsales2019) : 0;
                var sales2020 = tempsales2020 ? parseFloat(tempsales2020) : 0;

                var qty2018 = tempqty2018 ? parseFloat(tempqty2018) : 0;
                var qty2019 = tempqty2019 ? parseFloat(tempqty2019) : 0;
                var qty2020 = tempqty2020 ? parseFloat(tempqty2020) : 0;

                var jum2018 = tempjum2018 ? parseFloat(tempjum2018) : 0;
                var jum2019 = tempjum2019 ? parseFloat(tempjum2019) : 0;
                var jum2020 = tempjum2020 ? parseFloat(tempjum2020) : 0;

                var target2020 = temptarget2020 ? parseFloat(temptarget2020) : 0;
                var disc2020 = tempdisc2020 ? parseFloat(tempdisc2020) : 0;
                var voucher2020 = tempvoucher2020 ? parseFloat(tempvoucher2020) : 0;

                if (typeof aData[group] == 'undefined') {
                    aData[group] = new Array();
                    aData[group].rows = [];
                    aData[group].sales2018 = [];
                    aData[group].sales2019 = [];
                    aData[group].sales2020 = [];
                    aData[group].qty2018 = [];
                    aData[group].qty2019 = [];
                    aData[group].qty2020 = [];
                    aData[group].jum2018 = [];
                    aData[group].jum2019 = [];
                    aData[group].jum2020 = [];
                    aData[group].target2020 = [];
                    aData[group].disc2020 = [];
                    aData[group].voucher2020 = [];
                }
            
                    aData[group].rows.push(i); 
                    aData[group].sales2018.push(sales2018); 
                    aData[group].sales2019.push(sales2019);
                    aData[group].sales2020.push(sales2020);
                    aData[group].qty2018.push(qty2018);
                    aData[group].qty2019.push(qty2019);
                    aData[group].qty2020.push(qty2020);
                    aData[group].jum2018.push(jum2018);
                    aData[group].jum2019.push(jum2019);
                    aData[group].jum2020.push(jum2020);
                    aData[group].target2020.push(target2020);
                    aData[group].disc2020.push(disc2020);
                    aData[group].voucher2020.push(voucher2020);
                });
                var idx= 0;
                for(var office in aData){
        
                    idx =  Math.max.apply(Math,aData[office].rows);
        
                    var sum2018 = 0; 
                    var sum2019 = 0;
                    var sum2020 = 0;

                    var sumqty2018 = 0;
                    var sumqty2019 = 0;
                    var sumqty2020 = 0;

                    var sumjum2018 = 0;
                    var sumjum2019 = 0;
                    var sumjum2020 = 0;

                    var sumtarget2020 = 0;
                    var sumdisc2020 = 0;
                    var sumvoucher2020 = 0;

                    $.each(aData[office].sales2018,function(k,v){
                        sum2018 = sum2018 + v;
                    });

                    $.each(aData[office].sales2019,function(k,v){
                        sum2019 = sum2019 + v;
                    });

                    $.each(aData[office].sales2020,function(k,v){
                        sum2020 = sum2020 + v;
                    });

                    //-----------------------------------------
                    $.each(aData[office].qty2018,function(k,v){
                        sumqty2018 = sumqty2018 + v;
                    });

                    $.each(aData[office].qty2019,function(k,v){
                        sumqty2019 = sumqty2019 + v;
                    });

                    $.each(aData[office].qty2020,function(k,v){
                        sumqty2020 = sumqty2020 + v;
                    });

                    //-----------------------------------------
                    $.each(aData[office].jum2018,function(k,v){
                        sumjum2018 = sumjum2018 + v;
                    });

                    $.each(aData[office].jum2019,function(k,v){
                        sumjum2019 = sumjum2019 + v;
                    });

                    $.each(aData[office].jum2020,function(k,v){
                        sumjum2020 = sumjum2020 + v;
                    });

                    //-----------------------------------------
                    $.each(aData[office].target2020,function(k,v){
                        sumtarget2020 = sumtarget2020 + v;
                    });

                    $.each(aData[office].disc2020,function(k,v){
                        sumdisc2020 = sumdisc2020 + v;
                    });

                    $.each(aData[office].voucher2020,function(k,v){
                        sumvoucher2020 = sumvoucher2020 + v;
                    });

                    //console.log(aData[office].sales2018);
                    var sum_sales_2018 = numberWithCommas(sum2018);
                    var sum_sales_2019 = numberWithCommas(sum2019);
                    var sum_sales_2020 = numberWithCommas(sum2020);
                    var growth_sales_2019 = Math.round(((sum2019-sum2018) * 100 / sum2019));
                    var growth_sales_2020 = Math.round(((sum2020-sum2019) * 100 / sum2020));
                    growth_sales_2019 = growth_sales_2019 ? growth_sales_2019 : 0;
                    growth_sales_2020 = growth_sales_2020 ? growth_sales_2020 : 0;

                    var sum_qty_2018 = numberWithCommas(sumqty2018);
                    var sum_qty_2019 = numberWithCommas(sumqty2019);
                    var sum_qty_2020 = numberWithCommas(sumqty2020);
                    var growth_qty_2019 = Math.round(((sumqty2019-sumqty2018) * 100 / sumqty2019));
                    var growth_qty_2020 = Math.round(((sumqty2020-sumqty2019) * 100 / sumqty2020));
                    growth_qty_2019 = growth_qty_2019 ? growth_qty_2019 : 0;
                    growth_qty_2020 = growth_qty_2020 ? growth_qty_2020 : 0;

                    var sum_jum_2018 = numberWithCommas(sumjum2018);
                    var sum_jum_2019 = numberWithCommas(sumjum2019);
                    var sum_jum_2020 = numberWithCommas(sumjum2020);
                    var growth_jum_2019 = Math.round(((sumjum2019-sumjum2018) * 100 / sumjum2019));
                    var growth_jum_2020 = Math.round(((sumjum2020-sumjum2019) * 100 / sumjum2020));
                    growth_jum_2019 = growth_jum_2019 ? growth_jum_2019 : 0;
                    growth_jum_2020 = growth_jum_2020 ? growth_jum_2020 : 0;

                    var sum_target_2020 = numberWithCommas(sumtarget2020);
                    var sum_disc_2020 = numberWithCommas(sumdisc2020);
                    var sum_voucher_2020 = numberWithCommas(sumvoucher2020);

                    if(sumtarget2020 == 0){
                        var sum_target_2020 = 0;
                        var ach_target_2020 = 0;
                    }else{
                        var sum_target_2020 = numberWithCommas(sumtarget2020);
                        var ach_target_2020 = Math.round((sum2020 * 100 / sumtarget2020));
                    }
                    
                    //ach_target_2020 = ach_target_2020 ? ach_target_2020 : 0;

                    $(rows).eq( idx ).after(
                            '<tr class="group"><td>'+office+'</td>'+
                            '<td>'+sum_sales_2018+'</td>'+
                            '<td>'+sum_sales_2019+'</td>'+
                            '<td>'+growth_sales_2019+' %</td>'+
                            '<td>'+sum_sales_2020+'</td>'+
                            '<td>'+growth_sales_2020+' %</td>'+
                            '<td>'+sum_qty_2018+'</td>'+
                            '<td>'+sum_qty_2019+'</td>'+
                            '<td>'+growth_qty_2019+' %</td>'+
                            '<td>'+sum_qty_2020+'</td>'+
                            '<td>'+growth_qty_2020+' %</td>'+
                            '<td>'+sum_jum_2018+'</td>'+
                            '<td>'+sum_jum_2019+'</td>'+
                            '<td>'+growth_jum_2019+' %</td>'+
                            '<td>'+sum_jum_2020+'</td>'+
                            '<td>'+growth_jum_2020+' %</td>'+
                            '<td>'+sum_target_2020+'</td>'+
                            '<td>'+ach_target_2020+' %</td>'+
                            '<td>'+sum_disc_2020+'</td>'+
                            '<td>'+sum_voucher_2020+'</td>'+
                            '</tr>'
                        );
                        
                };
			}
		} );
 
        function numberWithCommas(x) {
            var parts = x.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            return parts.join(".");
        }

		// Order by the grouping
		/*$('#table_data tbody').on( 'click', 'tr.group', function () {
			var currentOrder = table.order()[0];
			if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
				table.order( [ groupColumn, 'desc' ] ).draw();
			}
			else {
				table.order( [ groupColumn, 'asc' ] ).draw();
			}
        } );*/
        
    /*$('#table_data').DataTable({
        
        responsive: true,
        // fixedHeader: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
            scrollY:        false,
            scrollX:        true,
            autoWidth:      true,
            paging:         false
    });*/
    
    $('#btnFiterSubmitSearch').click(function(){
        //$('#table_data').DataTable().draw(true);
        //alert("test");
        var spinner = $('#loader');
        var bulan = $("#bulan option:selected").val();
        var gh = $("#GH option:selected").val();

        if(gh == "0"){
            alert("Group Head Belum dipilih");
        }else{
            //var store = $("#STORE option:selected").val();
            spinner.show();
            window.location.href = "{{ url('pos/dailyreportgh') }}"+ "/search/" + bulan + "/" + gh + ""; //"/" + store + "";
        }
        
    });

    /*var data1 = {
        label: '2018',
        data: <?php //echo $data1; ?>,
        backgroundColor: 'rgba(0, 99, 132, 0.6)',
        borderWidth: 0,
        yAxisID: "2018"
    };

    var data2 = {
        label: '2019',
        data: <?php //echo $data2; ?>,
        backgroundColor: 'rgba(99, 132, 0, 0.6)',
        borderWidth: 0,
        yAxisID: "2019"
    };

    var data3 = {
        label: '2020',
        data: <?php //echo $data3; ?>,
        backgroundColor: 'rgba(63, 63, 0, 0.6)',
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
                {id: "2018"}, 
                {id: "2019"},
                {id: "2020"}
            ]
        }
    };

    var chartdata = {
      type: 'bar',
      data: {
        labels: <?php //echo $Months; ?>,
        datasets: [data1,data2,data3]
      },
      options: chartOptions
    }

    var chartdata_line = {
      type: 'line',
      data: {
        labels: <?php //echo $Months; ?>,
        datasets: [data1,data2,data3]
      },
      options: chartOptions
    }

    var ctx = document.getElementById('test_charts').getContext('2d');
    new Chart(ctx, chartdata);

    var ctx_line = document.getElementById('line_charts').getContext('2d');
    new Chart(ctx_line, chartdata_line);*/
});
</script>
@endsection