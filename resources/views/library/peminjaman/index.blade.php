@extends('layouts.library')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Peminjaman List</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">E-Library</a></li>
            <li class="breadcrumb-item"><a href="#">Peminjaman</a></li>
            <li class="breadcrumb-item active">List</li>
        </ol>
        </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Data</h5>
            </div>
            <div class="card-body">
                @can('approval_document_create')
                <button type="button" name="tambah" id="tambah" class="btn btn-primary btn-sm">Tambah</button>
                @endcan
                <hr>
                    <div class="table-responsive">
                        <table id="table_data" class="display compact" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No Pinjam</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Tgl Kembali</th>
                                    <th>Tipe Pinjam</th>
                                    <th>Status Pinjam</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>

<!-- START MODAL FORM -->
<div id="formModal" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
			<h5 class="modal-title">Peminjaman Baru</h5>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
      		</div>
      		<div class="modal-body">
      			<span id="form_result"></span>
      			<form method="post" id="addform" class="form-horizontal">
                @csrf
                <div class="form-group row">
                  <label for="nik" class="col-sm-4 col-form-label">NIK *</label>
                    <div class="col-sm-8">
                      <input type="text"  name="nik" class="form-control" value="{{ $nik }}" required readonly>
                    </div>
                </div>
                <div class="form-group row">
                  <label for="nama_customer" class="col-sm-4 col-form-label">Nama Peminjam *</label>
                    <div class="col-sm-8">
                      <input type="text" name="nama" class="form-control" value="{{ $name }}" required readonly>
                    </div>
                </div>
                <div class="form-group row">
                  <label for="tipe_pinjam" class="col-sm-4 col-form-label">Tipe Pinjam *</label>
                    <div class="col-sm-8">
                        <select name="tipe_pinjam" id="tipe_pinjam" class="form-control" required>
                            <option value="">- Pilih -</option>
                            <option value="DIGITAL">Digital</option>
                            <option value="FISIK">Fisik</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="keterangan" class="col-sm-4 col-form-label">Keterangan *</label>
                      <div class="col-sm-8">
                        <textarea id="keterangan" name="keterangan" class="form-control" required></textarea>
                      </div>
                </div>
              	<br />
              	<div class="modal-footer" align="center">
              		<input type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-info btn-sm" value="Simpan" />
              	</div>
      			</form>
      		</div>
    	</div>
    </div>
</div>
<!-- END MODAL FORM -->
@endsection
@section('scripts')
@parent
<script>
$(document).ready(function(){
    /* dibutuhkan jika ajax butuh autentikasi token */
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('#tambah').click(function(){
        $('#tipe_pinjam').val('');
        $('#keterangan').val('');
		$('#form_result').html('');
		$('#formModal').modal('show');
    });

    $('#table_data').DataTable({
        paging: true,
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ url('library/peminjaman') }}",
          type: 'GET'
         },
        columns: [
            { data: 'no_peminjaman', name: 'no_peminjaman' },
            { data: 'nik_peminjam', name: 'nik_peminjam' },
            { data: 'nama_peminjam', name: 'nama_peminjam' },
            { data: 'tgl_pinjam', name: 'tgl_pinjam' },
            { data: 'tgl_kembali', name: 'tgl_kembali' },
            { data: 'tipe_peminjaman', name: 'tipe_peminjaman' },
            { data: 'status_pinjam', name: 'status_pinjam' },
            { data: 'action', name: 'action', orderable: false }
        ]
    });

    $('#addform').on('submit', function(event){
        event.preventDefault();
        $("#btnSubmit").attr("disabled", true);
        var spinner = $('#loader');
        spinner.show();
		var action_url = "{{ route('library.peminjaman.store') }}";
		var result_msg = "Insert Data succesfully";

		$.ajax({
			url: action_url,
            method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
				if(data.errors)
				{
                    alert(data.errors);
                    spinner.hide();
                    $("#btnSubmit").attr("disabled", false);
				}
				if(data.success)
				{
                    var id = data.success;
                    window.location.href = "{{ url('library/peminjaman') }}" + "/" + id + "/edit";
				}
            },
            error: function(data){
                $("#btnSubmit").attr("disabled", false);
                spinner.hide();
                var errors = data.responseJSON;
                alert("gagal membuat peminjaman baru");
            }
		});
    });
});
</script>
@endsection