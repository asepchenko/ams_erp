@extends('layouts.pos')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Sales Detail</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">POS</a></li>
            <li class="breadcrumb-item"><a href="#">Sales</a></li>
            <li class="breadcrumb-item active">Sales Detail</li>
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
                    <label for="tgl_start" class="col-sm-4 col-form-label">Tgl Awal</label>
                    <div class="col-sm-8">
                        <input type="text" name="tgl_start" id="tgl_start" class="form-control datepicker" autocomplete="off" value="{{ $start }}">
                    </div>
                </div>
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
            </form>
            </div> <!-- div col-6-->

            <div class="col-md-6">
                <div class="form-group row">
                    <label for="tgl_end" class="col-sm-4 col-form-label">Tgl Akhir</label>
                    <div class="col-sm-8">
                        <input type="text" name="tgl_end" id="tgl_end" class="form-control datepicker" autocomplete="off" value="{{ $end }}">
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
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table_data" class="display compact" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>No Struk</th>
                                    <th>Kasir</th>
                                    <th>Jam</th>
                                    <th>QTY</th>
                                    <th>Total HPP</th>
                                    <th>Total Harga</th>
                                    <th>Total Discount</th>
                                    <th>Grand Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 0;
                                    $sum_qty = 0;
                                    $sum_hpp = 0;
                                    $sum_harga = 0;
                                    $sum_discount = 0;
                                    $sum_grandtotal = 0;
                                @endphp
                                @foreach($datanya as $key => $data)
                                @php($no += 1)
                                    <tr>
                                        <td> {{ $no }}</td>
                                        <td> {{ $data->tgl }}</td>
                                        <td> {{ $data->no_struk ?? '' }}</td>
                                        <td> {{ $data->kasir ?? '' }}</td>
                                        <td> {{ $data->jam ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($data->total_qty, 0)) ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($data->total_hpp, 0)) ?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($data->total_harga, 0))?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($data->total_discount, 0))?? '' }}</td>
                                        <td> {{ str_replace(',', '.',number_format($data->grand_total, 0)) ?? '' }}</td>
                                    </tr>
                                    @php($sum_qty += $data->total_qty)
                                    @php($sum_hpp += $data->total_hpp)
                                    @php($sum_harga += $data->total_harga)
                                    @php($sum_discount += $data->total_discount)
                                    @php($sum_grandtotal += $data->grand_total)
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5"><strong>Total</strong></td>
                                    <td> {{ str_replace(',', '.',number_format($sum_qty, 0)) ?? '' }}</td>
                                    <td> {{ str_replace(',', '.',number_format($sum_hpp, 0)) ?? '' }}</td>
                                    <td> {{ str_replace(',', '.',number_format($sum_harga, 0)) ?? '' }}</td>
                                    <td> {{ str_replace(',', '.',number_format($sum_discount, 0)) ?? '' }}</td>
                                    <td> {{ str_replace(',', '.',number_format($sum_grandtotal, 0))?? '' }}</td>
                                </tr>
                            </tfoot>
                    </table>
                </div> <!-- table -->
            </div> <!-- card body -->
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
<script>
$(document).ready(function(){
    $('.datepicker').datetimepicker({
        format: 'DD-MMM-YYYY', 
        useCurrent: false,
        showTodayButton: true,
        showClear: true,
        toolbarPlacement: 'bottom',
        sideBySide: true,
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down",
            previous: "fa fa-chevron-left",
            next: "fa fa-chevron-right",
            today: "fa fa-clock-o",
            clear: "fa fa-trash-o"
        }
    });

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

    $('#table_data').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: function(idx, data, node) {
                        if ($(node).hasClass('noVis')) {
                            return false;
                        }
                        return $('#table_data').DataTable().column(idx).visible();
                    }
                }
            },
            {
                extend: 'excelHtml5',
                footer: true,
                exportOptions: {
                    format: {
                        body: function ( data, row, column, node ) {
                            return $.isNumeric(data.replace('.', '')) ? "\0"+data : data;

                        }
                    },
                    columns: function(idx, data, node) {
                        if ($(node).hasClass('noVis')) {
                            return false;
                        }
                        return $('#table_data').DataTable().column(idx).visible();
                    }
                }
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: function(idx, data, node) {
                        if ($(node).hasClass('noVis')) {
                            return false;
                        }
                        return $('#table_data').DataTable().column(idx).visible();
                    }
                }
            },
                'colvis'
            ],
        scrollY:        false,
        scrollX:        true,
        autoWidth:      true,
        ordering:       false,
        paging:         false,
        fixedHeader: true
    });
    
    $('#btnFiterSubmitSearch').click(function(){
        var spinner = $('#loader');
        var brand = $("#BRAND option:selected").val();
        var store = $("#STORE option:selected").val();

        if($("#tgl_start").val() != ""){
            var temp_terima = moment($("#tgl_start").val()).format("YYYY-MM-DD");
            var cek_tgl = temp_terima.substring(0, 1);
            if(cek_tgl == "-"){
                temp_terima = temp_terima.substr(1);
            }
            $("#tgl_start").val(temp_terima);
        }else{
            $("#tgl_start").val('');
        }

        if($("#tgl_end").val() != ""){
            var temp_terima = moment($("#tgl_end").val()).format("YYYY-MM-DD");
            var cek_tgl = temp_terima.substring(0, 1);
            if(cek_tgl == "-"){
                temp_terima = temp_terima.substr(1);
            }
            $("#tgl_end").val(temp_terima);
        }else{
            $("#tgl_end").val('');
        }

        var start = $("#tgl_start").val();
        var end = $("#tgl_end").val();
        spinner.show();
        window.location.href = "{{ url('pos/sales-detail') }}"+ "/search/" + brand + "/" + store + "/" + start + "/" + end;
    });
});
</script>
@endsection