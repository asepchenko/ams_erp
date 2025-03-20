@extends('layouts.budget')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0 text-dark">Report Periode Kumulatif </h1>
            </div><!-- /.col -->
            <div class="col-sm-4">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Budgeting</a></li>
                    <li class="breadcrumb-item"><a href="#">Report</a></li>
                    <li class="breadcrumb-item active">Realisasi Periode</li>
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
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <i class="fa" aria-hidden="true"></i>
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
                            <label for="start_period" class="col-sm-4 col-form-label">Periode Awal</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="start_period" id="start_period">
                                    <option value="sm1" {{ ( $start_period == "sm1") ? 'selected' : '' }}>Semester I</option>
                                    <option value="sm2" {{ ( $start_period == "sm2") ? 'selected' : '' }}>Semester II</option>
                                    <option value="all" {{ ( $start_period == "all") ? 'selected' : '' }}>1 Tahun</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div> <!-- div col-4-->
                <div class="col-md-6">
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <label for="tahun" class="col-sm-4 col-form-label">Tahun</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="tahun" id="tahun">
                                    <option value="now">Pilih</option>
                                    <?php
                                    $t = 2024;
                                    for ($i = $t; $i <= $t + 5; $i++) {
                                    ?>
                                        <option value={{ $i }} {{ ($tahun == $i) ? 'selected' :'' }}>{{ $i }}</option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div> <!-- div col-4-->
            </div>
            <div class="row">
                <div class="col-md-4">
                    <form class="form-horizontal">                        
                        <div class="form-group row">
                            <label for="type_budget" class="col-sm-4 col-form-label">Type Budget</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="type_budget" id="type_budget">
                                    <option value="ori" {{ ( $type_budget == "ori") ? 'selected' : '' }}>Original</option>
                                    <option value="75" {{ ( $type_budget == "75") ? 'selected' : '' }}>75%</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div> <!-- div col-4-->
                <div class="col-md-4">
                    <form class="form-horizontal">                        
                        <div class="form-group row">
                            <label for="group" class="col-sm-4 col-form-label">Group</label>
                            <div class="col-sm-8">
                            <select class="form-control" name="group" id="group">
                                @foreach($data_group as $datagr)
                                    <option value="{{ $datagr->kode_groupstr }}" {{ ($kodegroup == $datagr->kode_groupstr) ? 'selected' : ''}}>{{ $datagr->kode_groupstr }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                    </form>
                </div> <!-- div col-4-->
                <div class="col-md-4">
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <label for="brand" class="col-sm-4 col-form-label">Brand</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="brand" id="brand">
                                    <option value="all" {{ ( $brand == "all") ? 'selected' : '' }}>ALL</option>
                                    <option value="HO" {{ ( $brand == "HO") ? 'selected' : '' }}>HO</option>
                                    <option value="RC" {{ ( $brand == "RC") ? 'selected' : '' }}>RC</option>
                                    <option value="RCW" {{ ( $brand == "RCW") ? 'selected' : '' }}>RCW</option>
                                    <option value="RM" {{ ( $brand == "RM") ? 'selected' : '' }}>RM</option>
                                    <option value="RQ" {{ ( $brand == "RQ") ? 'selected' : '' }}>RQ</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div> <!-- div col-4-->
            </div>
            <div class="row">
                <div class="col-md-12">
                    <span class="float-md-right">
                        <button type="submit" id="btnFiterSubmitSearch" class="btn btn-info btn-sm">Submit</button>
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
                    <table id="table_data" class="cell-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>Kode Group</th>
                                <th>Deskripsi</th>
                                <th>Budget</th>
                                <th>Progress</th>
                                <th>Realisasi</th>
                                <th>Sisa</th>
                                <th>Serapan (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            ($current_kode_group = null)
                            @endphp
                            @foreach ($datanya as $data)
                            @php
                                $sisa = $data->jum_budget - ($data->progress + $data->jum_real);
                            @endphp
                            @if ($loop->index > 0 && $current_kode_group != $data->kode_group)
                            {{-- @include ('budget.reportsemester.subtotal', compact('datanya', 'current_kode_group')) --}}
                            @endif
                            <tr>

                                @php ($current_kode_group = $data->kode_group)
                                <td>{{ $data->kode_group }}</td>
                                <td>{{ $data->keterangan }}</td>
                                <td> {{ number_format($data->jum_budget,0) }}</td>
                                {{-- <td><b><a href="{{url('budget/reportperbulan')}}/search/{{ strtolower($data->periode) }}/{{ $tahun }}/{{ $current_kode_group }}"
                                style="color:black" target="_blank"> {{ number_format($data->progress,0) }}</a><b></td>
                                    <td><b><a href="{{url('budget/reportperbulan')}}/search/{{ strtolower($data->periode) }}/{{ $tahun }}/{{ $current_kode_group }}"
                                                style="color:black" target="_blank"> {{ number_format($data->jum_real,0) }}</a><b></td> --}}
                                    <td><b>{{ number_format($data->progress,0) }}<b></td>
                                    <td><b> {{ number_format($data->jum_real,0) }}<b></td>
                                    <td> {{ ($sisa < 0 ? "(".number_format(abs($sisa),0).")" : number_format($sisa,0)) }}</td>
                                    @if($data->jum_budget > 0)
                                    <td> {{ number_format(($data->jum_real/$data->jum_budget)*100,2) }}%</td>
                                    @else
                                    <td>0</td>
                                    @endif
                            </tr>
                            @if ($loop->last)
                            {{-- @include ('budget.reportsemester.subtotal', compact('datanya', 'current_kode_group')) --}}
                            {{-- @include ('budget.reportsemester.total', compact('datanya')) --}}
                            @endif
                            @endforeach
                        </tbody>
                    </table>

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
<script>
    $(document).ready(function() {
        $('#table_data').DataTable({
            responsive: false,
            fixedHeader: true,
            //buttons: [
            //    'copy', 'csv', 'excel'
            //],
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'copyHtml5',
                    exportOptions: {
                        format: {
                            body: function(data, row, column, node) {
                                data = $.isNumeric(data.replace('.', '')) ? "\0" + data : data;
                                //return $.isNumeric(data.replace('.', '')) ? "\0"+data : data;
                                data = data.replace(/<.*?>/g, "");
                                return data;
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
                    extend: 'excelHtml5',
                    footer: true,
                    exportOptions: {
                        orthogonal: 'sort',
                        format: {
                            body: function(data, row, column, node) {
                                data = $.isNumeric(data.replace('.', '')) ? "\0" + data : data;
                                //return $.isNumeric(data.replace('.', '')) ? "\0"+data : data;
                                data = data.replace(/<.*?>/g, "");
                                return data;
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
                    extend: 'pdfHtml5',
                    footer: true,
                    exportOptions: {
                        orthogonal: 'sort',
                        format: {
                            body: function(data, row, column, node) {
                                data = $.isNumeric(data.replace('.', '')) ? "\0" + data : data;
                                //return $.isNumeric(data.replace('.', '')) ? "\0"+data : data;
                                data = data.replace(/<.*?>/g, "");
                                return data;
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
                        format: {
                            body: function(data, row, column, node) {
                                data = $.isNumeric(data.replace('.', '')) ? "\0" + data : data;
                                //return $.isNumeric(data.replace('.', '')) ? "\0"+data : data;
                                data = data.replace(/<.*?>/g, "");
                                return data;
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
                'colvis'
            ],

            scrollY: true,
            scrollX: true,
            //autoWidth:      true,
            visible: true,
            //responsive: true,
            ordering: false,
            paging: false
        });

        $('#btnFiterSubmitSearch').click(function() {
            var spinner = $('#loader');
            var start_period = $("#start_period option:selected").val();
            var end_period = $("#end_period option:selected").val();
            var group = $("#group option:selected").val();
            var tahun = $("#tahun option:selected").val();
            var type_budget = $("#type_budget option:selected").val();
            var brand = $("#brand option:selected").val();

            spinner.show();
            window.location.href = "{{ url('budget/reportsemester') }}" + "/search/" + start_period + "/" + tahun + "/" + type_budget + "/" + group + "/" + brand + "";
        });


    });
</script>
@endsection