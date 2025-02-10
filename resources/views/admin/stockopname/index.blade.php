@extends('layouts.adminajax')
@section('content')
<?php ini_set('max_execution_time', 180); //3 minutes ?>
<div class="card">
    
    <div class="card-header" id="headingOne">
    <h5>So Selisih List</h5>
        <h5 class="mb-0">
          <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">               <i class="fa" aria-hidden="true"></i>
            Filter
          </button>
        </h5>
      </div>

      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">
            <div class="col-md-12">
            <form class="form-horizontal" action="{{ route("admin.selisih.index") }}" method="GET">
                <div class="form-group row">
                    <input type="text" name="id_store" id="id_store" class="form-control col-sm-4" placeholder="input store id">
                    <div class="col-sm-8">
                    <button type="button" id="btnFiterSubmitSearch" class="btn btn-success btn-sm">Filter</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display responsive nowrap" cellspacing="0" width="100%" id="so_table">
                        <thead>
                            <tr>
                                <th>Store ID</th>
                                <th>PLU</th>
                                <th>STOUT</th>
                                <th>STIN</th>
                                <th>Sales</th>
                                <th>Prasales</th>
                                <th>Reject</th>
                                <th>Awal</th>
                                <th>QTY Awal SO</th>
                                <th>QTY Scan SO</th>
                                <th>QTY Selisih SO</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--<div class="col-md-6">
        <div class="card">
            <div class="card-body">

            </div>
        </div>
    </div>-->
</div>
@endsection


@section('scripts')
@parent
<script>
$(document).ready( function () {
    $('#so_table').DataTable({        
        processing: true,
        ordering: false, //new
        pagination: false, //new
        serverSide: true,
        scrollCollapse: true,
        ajax: {
          url: "{{ url('admin/stockopname') }}",
          type: 'GET',
          data: function (d) {
          d.id_store = $('#id_store').val();
          }
         },
        scrollX: true,
        scrollY: 300,
        scroller: {
            loadingIndicator: true
        },
        fixedColumns: {
            leftColumns: 2
        },
        columns: [
            { data: 'store_id', name: 'store_id' },
            { data: 'plu', name: 'plu' },
            { data: 'stout', name: 'stout' },
            { data: 'stin', name: 'stin' },
            { data: 'sales', name: 'sales' },
            { data: 'prasales', name: 'prasales' },
            { data: 'reject', name: 'reject' },
            { data: 'awal', name: 'awal' },
            { data: 'qty_awal_so', name: 'qty_awal_so' },
            { data: 'qty_scan_so', name: 'qty_scan_so' },
            { data: 'qty_selisih_so', name: 'qty_selisih_so' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' }
        ]
        
    });

    $('#btnFiterSubmitSearch').click(function(){
        $('#so_table').DataTable().clear();
        $('#so_table').DataTable().draw(true);
    });
});
</script>
@endsection