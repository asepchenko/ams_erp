@extends('layouts.approval')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Document {{ $data->id }}</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Approval</a></li>
            <li class="breadcrumb-item"><a href="#">Document</a></li>
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
                        <label for="kode" class="col-sm-2 col-form-label">Kode</label>
                        <div class="col-sm-10">
                            <input type="text" name="kode" class="form-control" value="{{ $data->kode_departemen }}" readonly>
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
                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                        <input type="text" name="nama" class="form-control" value="{{ $data->nama }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                    <label for="keterangan" class="col-sm-2 col-form-label">Note</label>
                        <div class="col-sm-10">
                        <textarea name="keterangan" class="form-control">{{ old('keterangan', isset($data) ? $data->keterangan : '') }}</textarea>
                        </div>
                    </div>
            </div> <!-- col -->
            
            </div> <!-- row -->
            <a class="btn btn-primary btn-sm" href="{{ route("approval.document.index") }}">List</a>
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
                    <th>Aksi</th>
                    <th>Kode Category</th>
                    <th>Nomor</th>
                    <th>Tanggal Bayar</th>
                    <th>Nama Tujuan</th>
                    <th>Kode Bank</th>
                    <th>Nomor Rekening Tujuan</th>
                    <th>Nama Rekening Tujuan</th>
                    <th>Mata Uang</th>
                    <th>Jumlah</th>
                    <th>No Referensi</th>
                    <th>Keterangan</th>
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
                        <th>Aksi</th>
                        <th>Category</th>
                        <th>Nama File</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
              </table>
          </div>
        </div>

    </div>
</div>

<!-- START MODAL FORM DIGITAL -->
<div id="formmodaldigital" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
			  <h5 class="modal-title">Tambah/Edit Form Digital</h5>
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      	<span id="form_result_digital"></span>
      	<form method="post" id="formdigital" class="form-horizontal">
          @csrf
          <input type="hidden" name="document_id" id="document_id"  value="{{ $data->id }}"/>
          <input type="hidden" name="id_digital" id="id_digital"/>
          <input type="hidden" name="action_digital" id="action_digital"/>
          <div class="form-group row">
            <label for="category_document" class="col-sm-2 col-form-label">Kategori *</label>
            <div class="col-sm-10">
              <select name="category_document" id="category_document" class="form-control" required>
                <option value="">- Pilih -</option>
                    @foreach($data_category_document as $data_doc)
                        <option value="{{$data_doc->kode_category}}">{{$data_doc->nama_category}}</option>
                    @endforeach
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="tanggal_bayar" class="col-sm-2 col-form-label">Tanggal Bayar</label>
                <div class="col-sm-10">
                    <input type="text" name="tanggal_bayar" id="tanggal_bayar" class="form-control datepicker" autocomplete="off">
                </div>
          </div>
          <div class="form-group row">
              <label for="no_ref" class="col-sm-2 col-form-label">No Ref</label>
              <div class="col-sm-10">
                <input type="text" name="no_ref" id="no_ref" class="form-control">
              </div>
          </div>
          <div class="form-group row">
            <label for="nama_tujuan" class="col-sm-2 col-form-label">Tujuan *</label>
            <div class="col-sm-10">
                <select class="form-control select2" style="width:100%" name="nama_tujuan" id="nama_tujuan" data-dependent="nama_tujuan">
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="kode_bank" class="col-sm-2 col-form-label">Kode Bank *</label>
            <div class="col-sm-10">
              <select name="kode_bank" id="kode_bank" class="form-control" required>
                <option value="-">- Pilih -</option>
                    @foreach($data_bank as $bank)
                        <option value="{{$bank->kode_bank}}">{{$bank->kode_bank}}</option>
                    @endforeach
              </select>
            </div>
          </div>
          <div class="form-group row">
              <label for="no_rek" class="col-sm-2 col-form-label">No Rek *</label>
              <div class="col-sm-10">
                <input type="text" name="no_rek" id="no_rek" class="form-control" placeholder="contoh: 00-12345-678" autocomplete="off">
              </div>
          </div>
          <div class="form-group row">
              <label for="nama_rek" class="col-sm-2 col-form-label">Nama Rek (A/n) *</label>
              <div class="col-sm-10">
                <input type="text" name="nama_rek" id="nama_rek" class="form-control" placeholder="contoh: BUDI" autocomplete="off">
              </div>
          </div>
          <div class="form-group row">
            <label for="mata_uang" class="col-sm-2 col-form-label">Mata Uang *</label>
            <div class="col-sm-10">
              <select name="mata_uang" id="mata_uang" class="form-control" required>
                <option value="-">- Pilih -</option>
                    @foreach($data_matauang as $matauang)
                        <option value="{{$matauang->currency_type}}">{{$matauang->currency_type}}</option>
                    @endforeach
              </select>
            </div>
          </div>
          <div class="form-group row">
              <label for="jumlah" class="col-sm-2 col-form-label">Jumlah *</label>
              <div class="col-sm-10">
                <input type="text" name="jumlah" id="jumlah" class="form-control" autocomplete="off" required>
              </div>
          </div>
          <div class="form-group row">
            <label for="keterangan_document" class="col-sm-2 col-form-label">Keterangan *</label>
            <div class="col-sm-10">
              <textarea name="keterangan_document" id="keterangan_document" class="form-control" required></textarea>
            </div>
          </div>
          <br />
          <div class="modal-footer" align="center">
            <button type="submit" name="btnSaveDigital" id="btnSaveDigital" onclick="save_digital()" class="btn btn-info">Simpan</button>
          </div>
      	</form>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL FORM DIGITAL -->

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
          <input type="hidden" name="file_id" id="file_id" value="{{ $data->id}}"/>
          <div class="form-group row">
            <label for="category_file" class="col-sm-2 col-form-label">Kategori *</label>
            <div class="col-sm-10">
              <select name="category_file" id="category_file" class="form-control" required>
                <option value="">- Pilih -</option>
                    @foreach($data_category_file as $data_file)
                        <option value="{{$data_file->category_name}}">{{$data_file->category_name}}</option>
                    @endforeach
              </select>
            </div>
          </div>
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
          <input type="hidden" name="id" value="{{ $data->id }}"/>
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
          <input type="hidden" name="id_cancel" id="id_cancel" value="{{ $data->id }}"/>
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
    function new_digital(){
        //buka readonly
        readonly_select($(".select2"), false);
        $('#no_rek').prop('readonly',false);
        $('#nama_rek').prop('readonly',false);
        $('#jumlah').prop('readonly',false);

        $('#id_digital').val('');
        $('#category_document').val('');
        $('#nama_tujuan').empty();
        $('#no_rek').val('');
        $('#tanggal_bayar').val('');
        $('#jumlah').val('');
        $('#kode_bank').val('');
        $('#nama_rek').val('');
        $('#no_ref').val('');
        $('#keterangan_document').val('');
        $('#action_digital').val('add');
    }

    function new_file(){
        $('#category_file').val('');
        $('#keterangan_file').val('');
        //$('#action').val('add');
    }

    function save_digital() {
        event.preventDefault();

        if($("#category_document option:selected").val() == ""){
            alert("Category Dokumen belum dpilih");
        }else{
            $("#btnSaveDigital").attr("disabled", true);
            var form_data = new FormData();
            var action_url = '';
            var result_msg = '';
            var spinner = $('#loader');
            spinner.show();
            if($('#action_digital').val() == "add"){
                action_url = "{{ url('approval/document/document-save-digital') }}";
                result_msg = "Insert Data succesfully";
            }else if($('#action_digital').val() == "edit"){
                action_url = "{{ url('approval/document/document-update-digital') }}";
                result_msg = "Update Data succesfully";
            }

            if($("#nama_tujuan option:selected").val() == null ) {
                nama_tujuan = '';
            }else{
                nama_tujuan = $("#nama_tujuan option:selected").val();
            }

            var temp_jum = $("#jumlah").val().replace(/\,/g,'');
            $("#jumlah").val(temp_jum);

            if($("#tanggal_bayar").val() != ""){
                var temp_terima = moment($("#tanggal_bayar").val()).format("YYYY-MM-DD HH:mm");
                var cek_tgl = temp_terima.substring(0, 1);
                if(cek_tgl == "-"){
                    temp_terima = temp_terima.substr(1);
                }
                $("#tanggal_bayar").val(temp_terima);
            }else{
                $("#tanggal_bayar").val('');
            }

            if($('#action_digital').val() == "edit"){
                form_data.append('id',  $('#id_digital').val());
            }else{
                form_data.append('id',  $('#document_id').val());
            }
            form_data.append('category_document',  $("#category_document option:selected").val());
            form_data.append('tanggal_bayar',  $('#tanggal_bayar').val());
            form_data.append('nama_tujuan',  nama_tujuan);
            form_data.append('no_rek',  $('#no_rek').val());
            form_data.append('kode_bank',  $('#kode_bank').val());
            form_data.append('nama_rek',  $('#nama_rek').val());
            form_data.append('mata_uang',  $('#mata_uang').val());
            form_data.append('jumlah',  $('#jumlah').val());
            form_data.append('no_ref', $('#no_ref').val());
            form_data.append('keterangan_document', $('#keterangan_document').val());
            form_data.append('_token', '{{csrf_token()}}');
            $.ajax({
                url: action_url,
                method:"POST",
                data:form_data,
                contentType: false,
                processData: false,
                success:function(data)
                {
                    $("#btnSaveDigital").attr("disabled", false);
                    spinner.hide();
                    var html = '';
                    if(data.errors)
                    {
                        alert(data.errors);
                    }
                    if(data.success)
                    {
                        alert(result_msg);
                        $('#data_digital').DataTable().ajax.reload();
                        $('#formmodaldigital').modal('hide');
                        new_digital();
                    }
                    $('#form_result_digital').html(html);
                },
                error: function(data){
                    $("#btnSaveDigital").attr("disabled", false);
                    spinner.hide();
                    var errors = data.responseJSON;
                    alert("Ups terjadi kesalahan, silahkan coba lagi atau hubungi IT");
                }
            });
        }
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
            action_url = "{{ url('approval/document/document-save-file') }}";
            result_msg = "Upload File succesfully";
        /*}else if($('#action_digital').val() == "update"){
            action_url = "{{ url('admin/resi-update-detail') }}";
            result_msg = "Update Data succesfully";
        }*/

        var file_data = $('#lampiran').prop('files')[0];
        form_data.append('id',  $('#file_id').val());
        form_data.append('category_file',  $("#category_file option:selected").val());
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
                alert("Pastikan file adalah PDF, Excel, CSV atau JPG dan ukuran dibawah 1 MB");
            }
		});
    }

    function readonly_select(objs, action) {
        if (action===true){
            objs.prepend('<div class="disabled-select"></div>');
        }else{
            $(".disabled-select", objs).remove();
        }
    }
</script>
<script>
$(document).ready(function(){
    var signaturePad = new SignaturePad(document.getElementById('signature-pad'));

    $("#btnClear").click(function(){
        signaturePad.clear();
    });
    
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
        if(!confirm("Anda yakin ingin submit dokumen ini?")){
            event.preventDefault();
        }else{
            event.preventDefault();
            var spinner = $('#loader');
            spinner.show();
            var data = signaturePad.toDataURL('image/png');
            $('#output').val(data);
            $("#btnSubmit").attr("disabled", true);
            var form_data = new FormData();
            var action_url = "{{ url('approval/document/submit') }}";
            var result_msg = "Submit Data succesfully";
            form_data.append('id', '{{ $data->id  }}');
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
                            window.location.href = "{{ url('approval/document') }}";
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
    
    $('#no_ref').autocomplete({
    source: function( request, response ) {
          // Fetch data          
    //var supp = document.getElementById("no_ref");
    //var kode = supp.value;
          $.ajax({
            url:"{{url('approval/document/get-no-po')}}", // + "/" + kode + "",
            type: 'post',
            dataType: "json",
            data: {
             _token: "{{ csrf_token() }}",
             search: request.term
         },
         success: function( data ) {
             response( data );
         }
     });
      },
      select: function (event, ui) {
           // Set selection
           $('#no_ref').val(ui.item.label); // display the selected text
           var $newOption = $("<option selected='selected'></option>").val(ui.item.deskripsi).text(ui.item.deskripsi);
           $("#nama_tujuan").append($newOption).trigger('change');
           $('#no_rek').val(ui.item.no_rek); // save selected id to input
           $('#nama_rek').val(ui.item.nama_rek);
           $('#jumlah').val(ui.item.jumlah).mask("#.##0", {reverse: true});
           $('#keterangan_document').val(ui.item.keterangan);

           //set readonly
           //$('#nama_tujuan').prop('readonly',true);
           readonly_select($(".select2"), false);
           readonly_select($(".select2"), true);
           $('#no_rek').prop('readonly',true);
           $('#nama_rek').prop('readonly',true);
           $('#jumlah').prop('readonly',true);
           return false;
       }
   });
   //$( "#no_ref").autocomplete( "option", "appendTo", ".eventInsForm" );

    $('#jumlah').mask("#,##0.00", {reverse: true});

    $("#btnAdd").on("click", function() {
        new_digital();
        $('#formmodaldigital').modal('show');
    });

    $("#btnAddFile").on("click", function() {
        new_file();
        $('#formuploadfile').modal('show');
    });

    $('#data_digital').DataTable({
        paging: false,
        //responsive: true,
        processing: true,
        serverSide: true,
        ajax: "{{ url('approval/document/data-digital') }}"+'/'+'{{ $data->id }}',
        columns: [
            //{ data: 'id', name: 'id' },
            { data: 'action', name: 'action', orderable: false },
            { data: 'kode_category', name: 'kode_category' },
            { data: 'no_digital', name: 'no_digital' },
            { data: 'tanggal_bayar', name: 'tanggal_bayar' },
            { data: 'nama_tujuan', name: 'nama_tujuan' },
            { data: 'kode_bank', name: 'kode_bank' },
            { data: 'rek_tujuan', name: 'rek_tujuan' },
            { data: 'nama_rek', name: 'nama_rek' },
            { data: 'mata_uang', name: 'mata_uang' },
            { data: 'jumlah', name: 'jumlah' },
            { data: 'no_ref', name: 'no_ref' },
            { data: 'keterangan', name: 'keterangan' }
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
                    $('#mata_uang').val(data.success.mata_uang);
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
            var spinner = $('#loader');
            spinner.show();
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
                    spinner.hide();
                    if(data.success){
                        alert("Delete Data "+id+", successfully");
                        $('#data_digital').DataTable().ajax.reload();
                        new_digital();
                    }
                },
                error: function(data){
                    spinner.hide();
                    var errors = data.responseJSON;
                    alert("gagal menghapus data");
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
            { data: 'action', name: 'action', orderable: false },
            { data: 'category_name', name: 'category_name' },
            { data: 'nama_file', name: 'nama_file' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'created_at', name: 'created_at' }
        ]
    });

    $(document).on('click', '.delete_file', function(){
        var id = $(this).attr('id');
        var r = confirm("Are you sure want to delete this file : "+id+" ??");
        if (r == true) {
            delete_url = "{{ url('approval/document/delete-file') }}";
            event.preventDefault();
            var spinner = $('#loader');
            spinner.show();
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
                    spinner.hide();
                    if(data.success){
                        alert("Delete File "+id+", successfully");
                        $('#data_file').DataTable().ajax.reload();
                        new_file();
                    }
                },
                error: function(data){
                    spinner.hide();
                    var errors = data.responseJSON;
                    alert("gagal menghapus file");
                    console.log(errors);
                }
            });
        } else {
            alert("Delete Canceled");
        }
    });

    $('#nama_tujuan').select2({
        placeholder: 'Search...',
        tags: true,
        createTag: function (params) {
            return {
            id: params.term,
            text: params.term,
            newOption: true
            }
        },
        ajax: {
            url:"{{url('approval/document/get-supplier')}}",
            type: 'post',
            dataType: "json",
            delay:250,
            data: function(params) {
                return {
                    _token: "{{ csrf_token() }}",
                    search: params.term
                };
                
            },
            processResults: function(data){
                return {
                    results: $.map(data, function(item){
                        return {
                            text: item.nama_supplier,
                            id: item.nama_supplier
                        }
                    })
                };
            }
        },
    });

    $("#btnProsesCancel").click(function(){
        if($('#alasan').val() == ""){
            alert("Alasan harus di-isi");
            event.preventDefault();
        }else{
            if(!confirm("Anda yakin ingin cancel dokumen ini?jika dicancel maka dokumen akan dibatalkan dan tidak dapat dilanjutkan")){
                event.preventDefault();
            }else{
                event.preventDefault();
                var spinner = $('#loader');
                spinner.show();
                $("#btnProsesCancel").attr("disabled", true);
                var form_data = new FormData();
                var action_url = "{{ url('approval/document/cancel') }}";
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
                            window.location.href = "{{ url('approval/document') }}";
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
});
</script>
@endsection