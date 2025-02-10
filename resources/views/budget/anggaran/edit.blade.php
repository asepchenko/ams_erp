@extends('layouts.budget')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Permohonan {{ $data->request_id }}</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Budget</a></li>
            <li class="breadcrumb-item"><a href="#">Request Budget</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
        </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="row">
    <div class="col-md-12">

        @if(isset($alasan))
        <div class="alert alert-warning alert-dismissible">
            <h5><i class="icon fas fa-exclamation-triangle"></i> Warning! dari {{ $alasan->nama}}</h5>
            {{ $alasan->alasan }}
        </div>
        @endif
        @if($data->code_category=='OPQ')
        <div class="alert alert-warning alert-dismissible">
            <h5><i class="icon fas fa-exclamation-triangle"></i> Info</h5>
            Open Kuartal akan otomatis mengambil budget periode berikutnya.
        </div>
        @endif
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
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                            <input type="text" name="nama" class="form-control" value="{{ $data->nama }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="group" class="col-sm-2 col-form-label">Group</label>
                            <div class="col-sm-10">
                                <input type="text" name="group" class="form-control" value="{{ $data->kode_group }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="category" class="col-sm-2 col-form-label">Category</label>
                            <div class="col-sm-10">
                                <input type="text" name="category" class="form-control" value="{{ $data->code_category }}" readonly>
                            </div>
                        </div>
                        
                    </form>
                </div> <!-- col -->

                <div class="col-md-6">
                    <form class="form-horizontal" id="formdocument" action="{{ route("budget.anggaran.update", [$data->request_id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $data->request_id }}"/>
                        <div class="form-group row">
                            <label for="budget_id" class="col-sm-2 col-form-label">Code Budget</label>
                            <div class="col-sm-10">
                                <select name="budget_id" id="budget_id" class="form-control" required>
                                <option value="">- Pilih -</option>
                                @foreach($data_coa as $data_coaa)
                                    <option value="{{$data_coaa->budget_id}}" 
                                    {{ ($data->budget_id == $data_coaa->budget_id) ? 'selected' : '' }}>
                                    {{$data_coaa->deskripsi}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="periode" class="col-sm-2 col-form-label">Periode</label>
                            <div class="col-sm-10">
                                <select name="periode" id="periode" class="form-control" required>
                                <option value="">- Pilih -</option>
                                @foreach($data_bulan as $databulan)
                                    <option value="{{$databulan->id}}" 
                                    {{ ($data->bulan == $databulan->id) ? 'selected' : '' }}>
                                    {{$databulan->nama_bulan}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nilai_request" class="col-sm-2 col-form-label">Nilai Request</label>
                            <div class="col-sm-10">
                                <input type="text" name="nilai_request" id="nilai_request" class="form-control" value="{{ $data->nilai_request }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nilai_realisasi" class="col-sm-2 col-form-label">Nilai Realisasi</label>
                            <div class="col-sm-10">
                                <input type="text" name="nilai_realisasi" class="form-control" value="{{ $data->nilai_realisasi }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                        <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                            <div class="col-sm-10">
                            <textarea name="keterangan" class="form-control">{{ old('keterangan', isset($data) ? $data->keterangan : '') }}</textarea>
                            </div>
                        </div>
                </div> <!-- col -->
                
                </div> <!-- row -->
                <a class="btn btn-primary btn-sm" href="{{ route("budget.anggaran.index") }}">List</a>
                <span class="float-md-right">
                    <button type="submit" id="btnUpdate" name="btnUpdate" class="btn btn-danger btn-sm">Update</button>
                    <button type="button" id="btnCancel" name="btnCancel" class="btn btn-info btn-sm">Cancel</button>
                    <button type="button" id="btnProses" name="btnProses" class="btn btn-info btn-sm">Proses</button>
                </span>
                </form>
            </div> <!-- card body -->
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
                                <th>Aksi</th>
                                <th>Nama File</th>
                                <th>Keterangan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                    </table>
                </div>
    </div>

    </div><!-- end div -->
</div>


<!-- START MODAL FORM UPLOAD FILE -->
<div id="formuploadfile" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
			  <h5 class="modal-title">Upload File</h5>
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      	<span id="form_result_file"></span>
      	<form method="post" id="formfile" class="form-horizontal" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="file_id" id="file_id" value="{{ $data->request_id}}"/>
          
          <div class="form-group row">
            <label for="keterangan_file" class="col-sm-2 col-form-label">Keterangan *</label>
            <div class="col-sm-10">
              <textarea name="keterangan_file" id="keterangan_file" class="form-control" required></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label for="lampiran" class="col-sm-2 col-form-label">Lampiran *</label>
              <div class="col-sm-10">
                <input type="file" class="form-control" id="lampiran" name="lampiran" required>
              </div>
          </div>
          <br />
          <div class="modal-footer" align="center">
            <button type="submit" name="btnSaveFile" id="btnSaveFile" onclick="save_file()" class="btn btn-info">Upload</button>
          </div>
      	</form>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL FORM UPLOAD FILE -->

<!-- START MODAL FORM SUBMIT -->
<div id="formmodalsubmit" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
			  <h5 class="modal-title">Proses Submit</h5>
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      	<span id="form_submit"></span>
      	<form method="post" id="formsubmit" class="form-horizontal" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="id" value="{{ $data->request_id }}"/>
          <div class="form-group row">
            <label for="signature" class="col-sm-2 col-form-label">Signature *</label>
              <div class="col-sm-10">
                <canvas id="signature-pad" class="signature-pad" width="300px" height="200px"></canvas>
                <textarea name="output" style="display:none;" id="output"></textarea>
              </div>
          </div>
          <br />
          <div class="modal-footer" align="center">
            <button type="button" id="btnClear" name="btnClear" class="btn btn-info">Clear Signature</button>
            <button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary">Submit</button>
          </div>
      	</form>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL FORM SUBMIT -->

<!-- START MODAL FORM CANCEL -->
<div id="formmodalcancel" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
			  <h5 class="modal-title">Proses Cancel</h5>
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
      <div class="modal-body">
      	<span id="form_submit"></span>
      	<form method="post" id="formsubmitcancel" class="form-horizontal">
          @csrf
          <input type="hidden" name="id_cancel" id="id_cancel" value="{{ $data->request_id }}"/>
          <div class="form-group row">
            <label for="alasan" class="col-sm-2 col-form-label">Alasan *</label>
            <div class="col-sm-10">
              <textarea id="alasan" name="alasan" class="form-control"></textarea>
            </div>
          </div>
          <br />
          <div class="modal-footer" align="center">
            <button type="submit" id="btnProsesCancel" name="btnProsesCancel" class="btn btn-danger">Proses Cancel</button>
          </div>
      	</form>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL FORM CANCEL -->
@endsection
@section('scripts')
@parent
<script src="{{ asset('js/signature_pad.min.js') }}"></script>
<script>
    function readonly_select(objs, action) {
        if (action===true){
            objs.prepend('<div class="disabled-select"></div>');
        }else{
            $(".disabled-select", objs).remove();
        }
    }
    function new_file(){
        $('#category_file').val('');
        $('#keterangan_file').val('');
        //$('#action').val('add');
    }
    function save_file() {
        event.preventDefault();
        $("#btnSaveFile").attr("disabled", true);
        var form_data = new FormData();
		var action_url = '';
        var result_msg = '';
        var spinner = $('#loader');
        spinner.show();
        //if($('#action_digital').val() == "add"){
            action_url = "{{ url('budget/anggaran/save-file') }}";
            result_msg = "Upload File succesfully";
        /*}else if($('#action_digital').val() == "update"){
            action_url = "{{ url('admin/resi-update-detail') }}";
            result_msg = "Update Data succesfully";
        }*/

        var file_data = $('#lampiran').prop('files')[0];
        form_data.append('id',  $('#file_id').val());
        form_data.append('file',  file_data);
        form_data.append('keterangan_file', $('#keterangan_file').val());
        form_data.append('_token', '{{csrf_token()}}');
		$.ajax({
			url: action_url,
            method:"POST",
            data:form_data,
            contentType: false,
            processData: false,
			success:function(data)
			{
                $("#btnSaveFile").attr("disabled", false);
                spinner.hide();
				var html = '';
				if(data.errors)
				{
                    alert(data.errors);
				}
				if(data.success)
				{
                    alert(result_msg);
                    $('#data_file').DataTable().ajax.reload();
                    $('#formuploadfile').modal('hide');
                    new_file();
				}
                $('#form_result_file').html(html);
            },
            error: function(data){
                $("#btnSaveFile").attr("disabled", false);
                spinner.hide();
                var errors = data.responseJSON;
                alert(data.errors);
            }
		});
    }
</script>
<script>
$(document).ready(function(){
    $("#btnAddFile").on("click", function() {
        new_file();
        $('#formuploadfile').modal('show');
    });
    var signaturePad = new SignaturePad(document.getElementById('signature-pad'));

    $("#btnClear").click(function(){
        signaturePad.clear();
    });

    $('#nilai_request').mask("#.##0,00", {reverse: true});

    $("#btnProses").on("click", function() {
        event.preventDefault();
        signaturePad.clear();
        $('#formmodalsubmit').modal('show');
    });

    $("#btnCancel").on("click", function() {
        event.preventDefault();
        $('#alasan').val('');
        $('#formmodalcancel').modal('show');
    });

    $("#btnSubmit").click(function(){
        if(!confirm("Anda yakin ingin submit permohonan ini?")){
            event.preventDefault();
        }else{
            event.preventDefault();
            var spinner = $('#loader');
            spinner.show();
            var data = signaturePad.toDataURL('image/png');
            $('#output').val(data);
            $("#btnSubmit").attr("disabled", true);
            var form_data = new FormData();
            var action_url = "{{ url('budget/anggaran/submit') }}";
            var result_msg = "Submit Data succesfully";
            form_data.append('id', '{{ $data->request_id  }}');
            form_data.append('signature', data);
            form_data.append('_token', '{{csrf_token()}}');
                $.ajax({
                    url: action_url,
                    method:"POST",
                    data:form_data,
                    contentType: false,
                    processData: false,
                    success:function(data)
                    {
                        spinner.hide();
                        var html = '';
                        if(data.errors){
                            alert(data.errors);
                            $("#btnSubmit").attr("disabled", false);
                        }

                        if(data.success){
                            alert(data.success);
                            //alert(result_msg);
                            window.location.href = "{{ url('budget/anggaran') }}";
                        }
                    },
                    error: function(data){
                        console.log(data.responseJSON);
                        //alert(data);
                        spinner.hide();
                        $("#btnSubmit").attr("disabled", false);
                        alert("gagal submit, hubungi IT");
                    }
                });
        }
    });

    $('.datepicker').datetimepicker({
        format: 'DD-MMM-YYYY HH:mm', 
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
    
    $('#jumlah').mask("#,##0.00", {reverse: true});

    $("#btnProsesCancel").click(function(){
        if($('#alasan').val() == ""){
            alert("Alasan harus di-isi");
            event.preventDefault();
        }else{
            if(!confirm("Anda yakin ingin cancel pengajuan ini?")){
                event.preventDefault();
            }else{
                event.preventDefault();
                var spinner = $('#loader');
                spinner.show();
                $("#btnProsesCancel").attr("disabled", true);
                var form_data = new FormData();
                var action_url = "{{ url('budget/anggaran/cancel') }}";
                var result_msg = "Cancel Data succesfully";
                form_data.append('id', $('#id_cancel').val());
                form_data.append('alasan', $('#alasan').val());
                form_data.append('_token', '{{csrf_token()}}');
                $.ajax({
                    url: action_url,
                    method:"POST",
                    data:form_data,
                    contentType: false,
                    processData: false,
                    success:function(data)
                    {
                        spinner.hide();
                        var html = '';
                        if(data.errors){
                            alert(data.errors);
                            $("#btnProsesCancel").attr("disabled", false);
                        }

                        if(data.success){
                            alert(data.success);
                            //alert(result_msg);
                            window.location.href = "{{ url('budget/anggaran') }}";
                        }
                    },
                    error: function(data){
                        console.log(data.responseJSON);
                        //alert(data);
                        spinner.hide();
                        alert("gagal cancel, hubungi IT");
                        $("#btnProsesCancel").attr("disabled", false);
                    }
                });
            }
        }
    });

        $('#data_file').DataTable({
        paging: false,
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: "{{ url('budget/anggaran/data-file') }}"+'/'+'{{ $data->request_id }}',
        columns: [
            { data: 'action', name: 'action', orderable: false },
            { data: 'nama_file', name: 'nama_file' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'created_at', name: 'created_at' }
        ]
    });

});
</script>
@endsection