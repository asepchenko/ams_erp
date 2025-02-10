@extends('layouts.finance')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Realisasi Document {{ $data->no_document }} - {{ $data->id }}</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Finance</a></li>
            <li class="breadcrumb-item"><a href="#">Realisasi</a></li>
            <li class="breadcrumb-item"><a href="#">Document</a></li>
            <li class="breadcrumb-item active">{{ $data->id }}</li>
        </ol>
        </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="row">
    <div class="col-md-12">

    <div class="callout callout-info">
        <h5><i class="fas fa-user"></i> {{ $data->nik }} - {{ $data->nama }} ({{ $data->kode_departemen }})</h5>
        {{ $data->keterangan }}
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">

            <div class="col-md-6">
                <form class="form-horizontal" id="formdocument" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $data->id }}"/>
                    <div class="form-group row">
                        <label for="tanggal_realisasi" class="col-sm-4 col-form-label">Tanggal Realisasi</label>
                        <div class="col-sm-8">
                            <input type="text" name="tanggal_realisasi" id="tanggal_realisasi" class="form-control datepicker" autocomplete="off" required>
                        </div>
                    </div>
            </div> <!-- col -->
            
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="bukti_file" class="col-sm-4 col-form-label">Bukti File</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control" id="bukti_file" name="bukti_file" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pu" class="col-sm-4 col-form-label"></label>
                    <div class="col-sm-8">
                        <input type="checkbox" name="pu" id="pu" {{ $punya }} data-toggle="toggle" disabled="disabled">
                    </div>
                </div>
            </div> <!-- col -->
            </div> <!-- row -->
            <a class="btn btn-primary btn-sm" href="{{ route("finance.realisasi-document.index") }}">List</a>
            <span class="float-md-right">
                <button type="submit" id="btnRealisasi" name="btnRealisasi" class="btn btn-info btn-sm">Realisasi</button>
                <!-- <button type="submit" id="btnRejectdulu" name="btnRejectdulu" class="btn btn-danger btn-sm">Reject</button> -->
            </span>
            </form>
        </div> <!-- card body -->
        </div> <!-- card -->

        <div class="card">
          <div class="card-header">
            <h5>List Digital Form</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="data_digital" class="display" style="width:100%">
                <thead>
                  <tr>
                    <th>Kode Category</th>
                    <th>Tanggal JT</th>
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
            <label for="alasan" class="col-sm-2 col-form-label">Alasan/Notes *</label>
            <div class="col-sm-10">
              <textarea id="alasan" name="alasan" class="form-control"></textarea>
            </div>
          </div>
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
            <button type="submit" id="btnReject" name="btnReject" class="btn btn-info">Reject</button>
          </div>
      	</form>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL FORM SUBMIT -->
@endsection
@section('scripts')
@parent
<script src="{{ asset('js/signature_pad.min.js') }}"></script>
<script>
$(document).ready(function(){
    var signaturePad = new SignaturePad(document.getElementById('signature-pad'));

    $("#btnClear").click(function(){
        signaturePad.clear();
    });

    $("#btnRejectdulu").on("click", function() {
        event.preventDefault();
        signaturePad.clear();
        $('#alasan').val('');
        $('#formmodalsubmit').modal('show');
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

    $("#btnRealisasi").click(function(){
        if(!confirm("Anda yakin ingin realisasi dokumen ini?")){
            event.preventDefault();
        }else{
            event.preventDefault();
            $("#btnRealisasi").attr("disabled", true);
            var file_data = $('#bukti_file').prop('files')[0];
            if($("#tanggal_realisasi").val() != ""){
                var temp_real = moment($("#tanggal_realisasi").val()).format("YYYY-MM-DD HH:mm");
                var cek_tgl = temp_real.substring(0, 1);
                if(cek_tgl == "-"){
                    temp_real = temp_real.substr(1);
                }
                $("#tanggal_realisasi").val(temp_real);
                var form_data = new FormData();
                var action_url = "{{ url('finance/realisasi-document/realisasi') }}";
                form_data.append('id', '{{ $data->id  }}');
                form_data.append('tanggal_realisasi',  $('#tanggal_realisasi').val());
                if ($('#bukti_file').get(0).files.length === 0) {
                    //do nothing :D
                }else{
                    form_data.append('file',  file_data);
                }
            
                form_data.append('_token', '{{csrf_token()}}');
                $.ajax({
                    url: action_url,
                    method:"POST",
                    data:form_data,
                    contentType: false,
                    processData: false,
                    success:function(data)
                    {
                        
                        var html = '';
                        if(data.errors){
                            alert(data.errors);
                            $("#btnRealisasi").attr("disabled", false);
                        }

                        if(data.success){
                            alert(data.success);
                            //alert(result_msg);
                            window.location.href = "{{ url('finance/realisasi-document') }}";
                        }
                    },
                    error: function(data){
                        console.log(data.responseJSON);
                        //alert(data);
                        alert("gagal realisasi, hubungi IT");
                        $("#btnRealisasi").attr("disabled", false);
                    }
                });
            }else{
                alert("tanggal realisasi masih kosong");
            }
        }
    });

    $("#btnReject").click(function(){
        if($('#alasan').val() == ""){
            alert("Alasan harus di-isi");
            event.preventDefault();
        }else{
            if(!confirm("Anda yakin ingin reject dokumen ini?")){
                event.preventDefault();
            }else{
                event.preventDefault();
                disable_btn();
                var spinner = $('#loader');
                spinner.show();
                var form_data = new FormData();
                var action_url = "{{ url('finance/realisasi-document/reject') }}";
                var result_msg = "Reject Data succesfully";
                form_data.append('id', '{{ $data->id  }}');
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
                        
                        var html = '';
                        if(data.errors){
                            spinner.hide();
                            alert(data.errors);
                            enable_btn();
                        }

                        if(data.success){
                            alert(data.success);
                            //alert(result_msg);
                            window.location.href = "{{ url('finance/realisasi-document') }}";
                        }
                    },
                    error: function(data){
                        console.log(data.responseJSON);
                        spinner.hide();
                        //alert(data);
                        alert("gagal reject, hubungi IT");
                        enable_btn();
                    }
                });
            }
        }
    });

    $('#data_digital').DataTable({
        paging: false,
        searching: false,
        processing: true,
        responsive: true,
        serverSide: true,
        ajax: "{{ url('approval/document/data-digital') }}"+'/'+'{{ $data->id }}',
        columns: [
            { data: 'kode_category', name: 'kode_category' },
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

    $('#data_file').DataTable({
        paging: false,
        processing: true,
        responsive: true,
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
});
</script>
@endsection