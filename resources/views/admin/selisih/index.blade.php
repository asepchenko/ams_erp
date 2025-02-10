@extends('layouts.admin')
@section('content')

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
            <form class="form-horizontal">
                <div class="form-group row">
                    <input type="text" name="id_store" id="id_store" class="form-control col-sm-4" placeholder="input store id">
                    <div class="col-sm-8">
                    <button type="submit" id="btnFiterSubmitSearch" class="btn btn-success btn-sm">Filter</button>
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
            <table class=" table table-bordered table-striped table-hover datatable" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="10"></th>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataso as $key => $data)
                        <tr data-entry-id="{{ $data->id }}">
                            <td></td>
                            <td> {{ $data->store_id ?? '' }}</td>
                            <td> {{ $data->plu ?? '' }}</td>
                            <td> {{ $data->stin ?? '' }}</td>
                            <td> {{ $data->stout ?? '' }}</td>
                            <td> {{ $data->sales ?? '' }}</td>
                            <td> {{ $data->prasales ?? '' }}</td>
                            <td> {{ $data->reject ?? '' }}</td>
                            <td> {{ $data->awal ?? '' }}</td>
                            <td> {{ $data->qty_awal_so ?? '' }}</td>
                            <td> {{ $data->qty_scan_so ?? '' }}</td>
                            <td> {{ $data->qty_selisih_so ?? '' }}</td>
                            <td> {{ $data->status ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.brands.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('brand_delete')
  dtButtons.push(deleteButton)
@endcan

  $('.datatable:not(.ajaxTable)').DataTable({ buttons: dtButtons })
})

</script>
@endsection