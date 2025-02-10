@extends('layouts.adminajax')
@section('content')

<div class="card">
    <div class="card-header">
        PLU List
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable" cellspacing="0" width="100%" id="plu-table">
                <thead>
                    <tr>
                        <th>PLU</th>
                        <th>Article</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(document).ready( function () {
    $('#plu-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('admin/plus-data') }}",
        columns: [
            { data: 'PLU', name: 'PLU' },
            { data: 'ARTICLE', name: 'ARTICLE' },
            { data: 'HARGA', name: 'HARGA' },
            { data: 'DESKRIPSI', name: 'DESKRIPSI' }
        ]
    });
});
</script>
@endsection