@extends('layouts.budget')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Budget List</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Budgeting</a></li>
            <li class="breadcrumb-item active">List</li>
        </ol>
        </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
		@if ($sukses = Session::get('sukses'))
		<div class="alert alert-success alert-block">
			<button type="button" class="close" data-dismiss="alert">×</button> 
			<strong>{{ $sukses }}</strong>
		</div>
		@endif
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
                    <div class="form-group row">
                        <label for="tahun" class="col-sm-4 col-form-label">Tahun</label>
                        <div class="col-sm-8">
                        <select class="form-control" name="tahun" id="tahun">
                                    <option value="">Pilih</option>
                                    <?php
                                    $t = date("Y");
                                    for ($i=$t; $i<=$t +5; $i++){
                                    ?>
                                    <option value={{ $i }} {{ ($tahun == $i) ? 'selected' :'' }}>{{ $i }}</option>
                                    <?php
                                    }
                                    ?>
                        </select>
                        </div>
                    </div>
                </div> <!-- div col-4-->            
                <div class="col-md-6">
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
                    <span class="float-md-right">
                        <button type="submit" id="btnFiterSubmitSearch" class="btn btn-info btn-sm">Submit</button>
                    </span>
                </div> <!-- div col-4-->

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
                <div align="left">
                    {{-- <a class="btn btn-primary btn-sm" href="{{ url('budget/budgetingnew-download-excel') }}">Template</a>   
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#importExcel">Import Excel</button> --}}
                </div>
                    <div class="table-responsive">
                        <table id="table_data" class="display compact" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Budget Id</th>
                                    <th>Group</th>
                                    <th>Deskripsi</th>
                                    <th>Tahun</th>
                                    <th>Januari</th>
                                    <th>Februari</th>
                                    <th>Maret</th>
                                    <th>April</th>
                                    <th>Mei</th>
                                    <th>Juni</th>
                                    <th>Juli</th>
                                    <th>Agustus</th>
                                    <th>September</th>
                                    <th>Oktober</th>
                                    <th>November</th>
                                    <th>Desember</th>
                                    <th>Total Budget</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="4" style="text-align:right">Total:</th>
                                    <th style="text-align:right"></th>
                                    <th style="text-align:right"></th>
                                    <th style="text-align:right"></th>
                                    <th style="text-align:right"></th>
                                    <th style="text-align:right"></th>
                                    <th style="text-align:right"></th>
                                    <th style="text-align:right"></th>
                                    <th style="text-align:right"></th>
                                    <th style="text-align:right"></th>
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

<!-- Import Excel -->
<div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form method="post" action="/budget/budgetingnew-import-excel" enctype="multipart/form-data">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
						</div>
						<div class="modal-body">

							{{ csrf_field() }}

							<label>Pilih file excel</label>
							<div class="form-group">
								<input type="file" name="file" id="file" required="required">
							</div>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Import</button>
						</div>
					</div>
				</form>
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
        scrollX: false,    
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ url('budget/budgetingnew') }}",
          type: 'GET',
          data: function (d) {
          d.tahun = $("#tahun option:selected").val()
          d.kodegroup = $("#group option:selected").val()
          }
         },
        columns: [
            { data: 'budget_id', name: 'budget_id' },
            { data: 'kode_group', name: 'kode_group' },
            { data: 'description', name: 'description' },
            { data: 'year', name: 'year' },
            { data: 'value_01', name: 'value_01' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
            { data: 'value_02', name: 'value_02' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
            { data: 'value_03', name: 'value_03' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
            { data: 'value_04', name: 'value_04' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
            { data: 'value_05', name: 'value_05' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
            { data: 'value_06', name: 'value_06' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
            { data: 'value_07', name: 'value_07' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
            { data: 'value_08', name: 'value_08' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
            { data: 'value_09', name: 'value_09' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
            { data: 'value_10', name: 'value_10' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
            { data: 'value_11', name: 'value_11' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
            { data: 'value_12', name: 'value_12' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) },
            { data: 'total_budget', name: 'total_budget' , render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ) }
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
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            t1 = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            t2 = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            t3 = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            t4 = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            t5 = api
                .column( 8, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            t6 = api
                .column( 9, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            t7 = api
                .column( 10, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            t8 = api
                .column( 11, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            t9 = api
                .column( 12, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            t10 = api
                .column( 13, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            t11 = api
                .column( 14, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            t12 = api
                .column( 15, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            tbudget = api
                .column( 16, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            var numidr = $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' ).display;
            $( api.column( 4 ).footer() ).html(
                numidr(t1)
            );
            $( api.column( 5 ).footer() ).html(
                numidr(t2)
            );
            $( api.column( 6 ).footer() ).html(
                numidr(t3)
            );
            $( api.column( 7 ).footer() ).html(
                numidr(t4)
            );
            $( api.column( 8 ).footer() ).html(
                numidr(t5)
            );
            $( api.column( 9 ).footer() ).html(
                numidr(t6)
            );
            $( api.column( 10 ).footer() ).html(
                numidr(t7)
            );
            $( api.column( 11 ).footer() ).html(
                numidr(t8)
            );$( api.column( 12 ).footer() ).html(
                numidr(t9)
            );
            $( api.column( 13 ).footer() ).html(
                numidr(t10)
            );
            $( api.column( 14 ).footer() ).html(
                numidr(t11)
            );
            $( api.column( 15 ).footer() ).html(
                numidr(t12)
            );
            $( api.column( 16 ).footer() ).html(
                numidr(tbudget)
            );
        }
    });
    $('#btnFiterSubmitSearch').click(function(){
        $('#table_data').DataTable().draw(true);
    });

 
});
</script>
@endsection