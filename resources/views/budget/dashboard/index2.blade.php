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
            <div class="col-md-3">
            <form class="form-horizontal">
                <div class="form-group row">
                    <label for="periode" class="col-sm-4 col-form-label">Periode</label>
                    <div class="col-sm-8">
                    <select class="form-control" name="periode" id="periode">
                        <option value="all">All</option>
                        <option value="q1" {{ ( $periode == "q1") ? 'selected' : '' }}>Kuartal I</option>
                        <option value="q2" {{ ( $periode == "q2") ? 'selected' : '' }}>Kuartal II</option>
                        <option value="q3" {{ ( $periode == "q3") ? 'selected' : '' }}>Kuartal III</option>
                        <option value="q4" {{ ( $periode == "q4") ? 'selected' : '' }}>Kuartal IV</option>
                    </select>
                    </div>
                </div>
            </form>
            </div> <!-- div col-4-->
            <div class="col-md-3">
            <form class="form-horizontal">
                <div class="form-group row">
                    <label for="tahun" class="col-sm-4 col-form-label">Tahun</label>
                    <div class="col-sm-8">
                    <select class="form-control" name="tahun" id="tahun">
                        <option value="">Pilih</option>
                        <option value="2021" {{ ( $tahun == "2021") ? 'selected' : '' }}>2021</option>
                        <option value="2022" {{ ( $tahun == "2022") ? 'selected' : '' }}>2022</option>
                        <option value="2023" {{ ( $tahun == "2023") ? 'selected' : '' }}>2023</option>
                        <option value="2024" {{ ( $tahun == "2024") ? 'selected' : '' }}>2024</option>
                        <option value="2025" {{ ( $tahun == "2025") ? 'selected' : '' }}>2025</option>
                    </select>
                    </div>
                </div>
            </form>
            </div> <!-- div col-4-->            
            <div class="col-md-3">
            <form class="form-horizontal">
                <div class="form-group row">
                    <label for="group" class="col-sm-4 col-form-label">Group Dept</label>
                    <div class="col-sm-8">
                    <select class="form-control" name="group" id="group">
                        <option value="">Pilih</option>
                        @foreach($data_group as $datagr)
                            <option value="{{ $datagr->kode_groupstr }}">{{ $datagr->kode_groupstr }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
            </form>
            </div> <!-- div col-4-->
    
            <div class="col-md-3">
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

        <div class="card">
            <div class="card-header">
                <h5>Data</h5>
            </div>
            <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_data" class="display compact" style="width:100%">
                            <thead>
                                <tr>
                                
                                    <th>Budget Id</th>
                                    <th>Group</th>
                                    <th>Periode</th>
                                    <th>Tahun</th>
                                    <th>COA</th>
                                    <th>Deskripsi</th>
                                    <th>Nilai Budget</th>
                                    <th>Proses</th>
                                    <th>Realisasi</th>
                                    <th>Sisa</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="6" style="text-align:right">Total:</th>
                                    <th style="text-align:right"></th>
                                    <th style="text-align:right"></th>
                                    <th style="text-align:right"></th>
                                    <th style="text-align:right"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
            </div>
        </div> <!-- table data -->
    </div>
</div>


@endsection
@section('scripts')
@parent
<script>
$(document).ready(function(){
  
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var events = $('#events');
    var table = $('#table_data').DataTable({
        scrollX: true,    
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ url('budget/dashboard') }}",
          type: 'GET',
          data: function (d) {
          d.periode = $("#periode option:selected").val()
          d.tahun = $("#tahun option:selected").val()
          d.kodegroup = $("#group option:selected").val()
          }
         },
         columns: [
            { data: 'budget_id', name: 'budget_id' },
            { data: 'kode_group', name: 'kode_group' },
            { data: 'periode', name: 'periode' },
            { data: 'tahun', name: 'tahun' },
            { data: 'coa', name: 'coa' },
            { data: 'description', name: 'description' },
            { data: 'jum_budget', name: 'jum_budget' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
            { data : 'jum_proses', name:'jum_proses' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
            { data: 'jum_realisasi', name: 'jum_realisasi' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
            { data: 'jum_sisa', name: 'jum_sisa' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) }
        // columns: [
        //     { data: 'budget_id', name: 'budget_id' },
        //     { data: 'kode_group', name: 'kode_group' },
        //     { data: 'periode', name: 'periode' },
        //     { data: 'tahun', name: 'tahun' },
        //     { data: 'coa', name: 'coa' },
        //     { data: 'description', name: 'description' },
        //     { data: 'nilai', name: 'nilai' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
        //     { data : 'progress', name:'progress' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
        //     { data: 'realisasi', name: 'realisasi' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
        //     { data: 'sisa', name: 'sisa' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) }
        ],
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            tbudget = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            tproses = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            treal = api
                .column( 8, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            tsisa = api
                .column( 9, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            var numidr = $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' ).display;
            $( api.column( 6 ).footer() ).html(
                numidr(tbudget)
            );
            $( api.column( 7 ).footer() ).html(
                numidr(tproses)
            );
            $( api.column( 8 ).footer() ).html(
                numidr(treal)
            );
            $( api.column( 9 ).footer() ).html(
                numidr(tsisa)
            );
        }
    });
    $('#btnFiterSubmitSearch').click(function(){
        $('#table_data').DataTable().draw(true);
    });
    
});
</script>
@endsection