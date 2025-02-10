@extends('layouts.approval')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit {{ $data->report_name }}</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Approval</a></li>
            <li class="breadcrumb-item"><a href="#">My Report</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
        </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="row">
    <div class="col-md-12">

        <div class="card">
        <div class="card-body">
            <div class="row">
            <div class="col-md-6">
                <form class="form-horizontal">
                    <div class="form-group row">
                        <label for="nik" class="col-sm-2 col-form-label">NIK</label>
                        <div class="col-sm-10">
                            <input type="text" name="nik" class="form-control" value="{{ $data->nik }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tabelnya" class="col-sm-2 col-form-label">Tabel</label>
                        <div class="col-sm-10">
                            <input type="text" name="tabelnya" class="form-control" value="{{ $data->table_name }}" readonly>
                        </div>
                    </div>
                </form>
            </div> <!-- col -->

            <div class="col-md-6">
                <form class="form-horizontal" id="formdocument" action="{{ route("approval.document.update", [$data->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $data->id }}"/>
                    <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Report Name</label>
                        <div class="col-sm-10">
                        <textarea name="nama" class="form-control">{{ old('nama', isset($data) ? $data->report_name : '') }}</textarea>
                        </div>
                    </div>
            </div> <!-- col -->
            
            </div> <!-- row -->
            <a class="btn btn-primary btn-sm" href="{{ route("approval.my-report.index") }}">List</a>
            <span class="float-md-right">
                <button type="submit" id="btnUpdate" name="btnUpdate" class="btn btn-danger btn-sm">Update</button>
            </span>
            </form>
        </div> <!-- card body -->
        </div> <!-- card -->

        <div class="card">
          <div class="card-header">
            <h5>List Digital Form</h5>
          </div>
          <div class="card-body">
            <button type="button" id="btnAdd" class="btn btn-info btn-sm">Tambah</button>
            <hr>
            <div class="table-responsive">
              <table id="data_digital" class="display" style="width:100%">
                <thead>
                  <tr>
                    <!--<th>ID</th>-->
                    <th>Kode Category</th>
                    <th>Nomor</th>
                    <th>Tanggal Bayar</th>
                    <th>Nama Tujuan</th>
                    <th>Kode Bank</th>
                    <th>Nomor Rekening Tujuan</th>
                    <th>Nama Rekening Tujuan</th>
                    <th>Jumlah</th>
                    <th>No Referensi</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
              </table>
            </div> <!-- table responsive -->
          </div>
        </div> <!-- card -->

        <div class="card">
          <div class="card-header">
            <h5>List File</h5>
        </div>
        <div class="card-body">
          <button type="button" id="btnAddFile" class="btn btn-info btn-sm">Tambah</button>
          <hr>
          <div class="table-responsive">
              <table id="data_file" class="display compact" style="width:100%">
                <thead>
                    <tr>
                      <th>Category</th>
                      <th>Nama File</th>
                      <th>Keterangan</th>
                      <th>Tanggal</th>
                      <th>Action</th>
                    </tr>
                </thead>
              </table>
          </div>
        </div>

    </div>
</div>

@endsection
@section('scripts')
@parent
<script>
$(document).ready(function(){
   
    $('#data_digital').DataTable({
        paging: false,
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: "{{ url('approval/document/data-digital') }}"+'/'+'{{ $data->id }}',
        columns: [
            //{ data: 'id', name: 'id' },
            { data: 'kode_category', name: 'kode_category' },
            { data: 'no_digital', name: 'no_digital' },
            { data: 'tanggal_bayar', name: 'tanggal_bayar' },
            { data: 'nama_tujuan', name: 'nama_tujuan' },
            { data: 'kode_bank', name: 'kode_bank' },
            { data: 'rek_tujuan', name: 'rek_tujuan' },
            { data: 'nama_rek', name: 'nama_rek' },
            { data: 'jumlah', name: 'jumlah' },
            { data: 'no_ref', name: 'no_ref' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'action', name: 'action', orderable: false }
        ]
    });

    $(document).on('click', '.edit', function(){
        var id_edit = $(this).attr('id');
        var spinner = $('#loader');
        var action_url = "{{ url('approval/document/edit-document-digital') }}"+ "/" +id_edit;
        spinner.show();
		$.ajax({
			url: action_url,
            method:"GET",
			success:function(data)
			{
                spinner.hide();
				var html = '';
				if(data.errors)
				{
					alert(data.errors);
				}
				if(data.success)
				{
                    console.log(data);
                    readonly_select($(".select2"), false);
                    $('#no_rek').prop('readonly',false);
                    $('#nama_rek').prop('readonly',false);
                    $('#jumlah').prop('readonly',false);

                    $('#id_digital').val(data.success.id);
                    $('#category_document').val(data.success.kode_category);
                    $('#nama_tujuan').val(data.success.nama_tujuan);
                    $('#no_rek').val(data.success.rek_tujuan);
                    $('#tanggal_bayar').val(data.success.tanggal_bayar);
                    $('#jumlah').val(data.success.jumlah);
                    $('#kode_bank').val(data.success.kode_bank);
                    $('#nama_rek').val(data.success.nama_rek);
                    $('#no_ref').val(data.success.no_ref);
                    $('#keterangan_document').val(data.success.keterangan);
                    $('#action_digital').val('edit');
                    $('#formmodaldigital').modal('show');
				}
            },
            error: function(data){
                spinner.hide();
                var errors = data.responseJSON;
                alert(data);
                console.log(errors);
            }
		});
    });

    $(document).on('click', '.delete_digital', function(){
        var id = $(this).attr('id');
        var r = confirm("Are you sure want to delete this data : "+id+" ??");
        if (r == true) {
            delete_url = "{{ url('approval/document/delete-digital') }}";
            event.preventDefault();
            var form_data = new FormData();
            form_data.append('id', id);
            form_data.append('_token', '{{csrf_token()}}');
		    $.ajax({
                url: delete_url,
                method:"POST",
                data:form_data,
                contentType: false,
                processData: false,
                success:function(data)
                {
                    if(data.success){
                        alert("Delete Data "+id+", successfully");
                        $('#data_digital').DataTable().ajax.reload();
                        new_digital();
                    }
                },
                error: function(data){
                    var errors = data.responseJSON;
                    alert(data);
                    console.log(errors);
                }
            });
        } else {
            alert("Delete Canceled");
        }
    });

    $('#data_file').DataTable({
        paging: false,
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: "{{ url('approval/document/data-file') }}"+'/'+'{{ $data->id }}',
        columns: [
            { data: 'category_name', name: 'category_name' },
            { data: 'nama_file', name: 'nama_file' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false }
        ]
    });

    $(document).on('click', '.delete_file', function(){
        var id = $(this).attr('id');
        var r = confirm("Are you sure want to delete this file : "+id+" ??");
        if (r == true) {
            delete_url = "{{ url('approval/document/delete-file') }}";
            event.preventDefault();
            var form_data = new FormData();
            form_data.append('nama_file', id);
            form_data.append('_token', '{{csrf_token()}}');
		    $.ajax({
                url: delete_url,
                method:"POST",
                data:form_data,
                contentType: false,
                processData: false,
                success:function(data)
                {
                    if(data.success){
                        alert("Delete File "+id+", successfully");
                        $('#data_file').DataTable().ajax.reload();
                        new_file();
                    }
                },
                error: function(data){
                    var errors = data.responseJSON;
                    alert(data);
                    console.log(errors);
                }
            });
        } else {
            alert("Delete Canceled");
        }
    });
});
</script>
@endsection