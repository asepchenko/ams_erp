@extends('layouts.approval')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">My Report</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Approval</a></li>
          <li class="breadcrumb-item"><a href="#">Report</a></li>
          <li class="breadcrumb-item active">My Report</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="row">
    <div class="col-md-12">
    <div class="callout callout-info">
        <h5><i class="fas fa-magic"></i> INFO !</h5>
        Buat report untuk anda sendiri menggunakan My Report Generator. Cukup pilih table, kolom apa saja yang akan
        ditampilkan dan sesuaikan kondisinya apakah menampikan semua data, data per-hari, per-departemen dsb.
    </div>

        <div class="card">
            <div class="card-body">
            <button type="button" name="tambah" id="tambah" class="btn btn-primary btn-sm">Tambah</button>
            <hr>
                <div class="table-responsive">
                    <table id="table_data" class="cell-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>Aksi</th>
                                <th>Nama Report</th>
                                <th>Database</th>
                                <th>Table</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datanya as $key => $data)
                            <tr>
                                <td> <a href="{{ url('approval/my-report/') }}/{{ $data->id}}/view">View</a></td>
                                <td> {{ $data->report_name ?? '' }}</td>
                                <td> {{ $data->database_name ?? '' }}</td>
                                <td> {{ $data->table_name ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
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
			<h5 class="modal-title">Create Report</h5>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
      		</div>
      		<div class="modal-body">
      			<span id="form_result"></span>
      			<form method="post" id="addform" class="form-horizontal">
                @csrf
                <div class="form-group row">
                  <label for="nik" class="col-sm-4 col-form-label">NIK *</label>
                    <div class="col-sm-8">
                      <input type="text"  name="nik" id="nik" class="form-control" value="{{ $nik }}" required readonly>
                    </div>
                </div>
                <div class="form-group row">
                  <label for="databasenya" class="col-sm-4 col-form-label">Database *</label>
                    <div class="col-sm-8">
                      <input type="text" name="databasenya" id="databasenya" class="form-control" value="{{ $database }}" required readonly>
                    </div>
                </div>
                <div class="form-group row">
                  <label for="tablenya" class="col-sm-4 col-form-label">Table *</label>
                    <div class="col-sm-8">
                        <select name="tablenya" id="tablenya" class="form-control" required>
                            <option value="">- Pilih -</option>
                            @foreach($tabel as $data_tbl)
                                <option value="{{$data_tbl->table_name}}" title="{{$data_tbl->keterangan}}">{{$data_tbl->table_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nama" class="col-sm-4 col-form-label">Nama Report *</label>
                      <div class="col-sm-8">
                        <textarea id="nama" name="nama" class="form-control" required></textarea>
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

  $('#addform').on('submit', function(event){
    event.preventDefault();
    $("#btnSubmit").attr("disabled", true);
    var spinner = $('#loader');
    spinner.show();
		var action_url = "{{ route('approval.my-report.store') }}";
		var result_msg = "Insert Data succesfully";

		$.ajax({
			url: action_url,
      method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
                
				var html = '';
				if(data.errors)
				{
          alert(data.errors);
          spinner.hide();
          $("#btnSubmit").attr("disabled", false);
				}
				if(data.success)
				{
          //alert(data.success);
          var id = data.success;
          window.location.href = "{{ url('approval/my-report') }}" + "/" + id + "/edit";
				}
          $('#form_result').html(html);
      },
      error: function(data){
        $("#btnSubmit").attr("disabled", false);
        spinner.hide();
        var errors = data.responseJSON;
        alert("gagal generate report");
        console.log(errors);
        }
		  });
    });

    $('#tambah').click(function(){
      $('#tablenya').val('');
      $('#nama').val('');
		  $('#formModal').modal('show');
    });

    $('#table_data').DataTable({
        responsive: true,
        ordering: true,
        paging: true
    });
});
</script>
@endsection