@extends('layouts.approval')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Proses Document {{ $data->id }}</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Approval</a></li>
            <li class="breadcrumb-item"><a href="#">Document</a></li>
            <li class="breadcrumb-item active">Proses</li>
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
                        <label for="kode" class="col-sm-2 col-form-label">Kode</label>
                        <div class="col-sm-10">
                            <input type="text" name="kode" class="form-control" value="{{ $data->kode_departemen }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="labelkasbon" class="col-sm-2 col-form-label">Nilai Kasbon</label>
                        <div class="col-sm-10">
                            <input type="text" name="labelkasbon" id="labelkasbon" class="form-control" value="{{ $data->jum_kasbon }}" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label for="jumrealisasi" class="col-sm-2 col-form-label">Jumlah Realisasi</label>
                        <div class="col-sm-10">
                            <input type="text" name="jumrealisasi" id="jumrealisasi" class="form-control" value="{{ $data->jum_realisasi }}" readonly>
                        </div>
                    </div>
                    @if ($data->sisa > 0)
                    <div class="form-group row">
                        <label for="kembali" class="col-sm-2 col-form-label">Diff</label>
                        <div class="col-sm-10">
                            <input type="text" name="kembali" id="kembali" class="form-control" value="{{ $data->sisa }}" readonly>
                        </div>
                    </div>
                    @else
                    <div class="form-group row">
                        <label for="kembali" class="col-sm-2 col-form-label">Diff</label>
                        <div class="col-sm-10">
                            <input type="text" name="kembalian" id="kembalian" class="form-control" value="PASS" readonly>
                        </div>
                    </div>
                    @endif
                </form>
            </div> <!-- col -->

            <div class="col-md-6">
                <form class="form-horizontal" id="formdocument" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $data->id }}"/>
                    <div class="form-group row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                        <input type="text" name="nama" class="form-control" value="{{ $data->nama }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ket" class="col-sm-2 col-form-label">Note</label>
                        <div class="col-sm-10">
                        <textarea name="ket" class="form-control" readonly>{{ $data->keterangan }}</textarea>
                        </div>
                    </div>                    
                    @if ($data->last_status == "approval_tax" or $kode_dept == "TAX")
                    <div class="form-group row">
                        <label for="pu" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
                            <input type="checkbox" name="pu" id="pu" {{ $punya }} data-toggle="toggle">
                        </div>
                    </div>
                    @else
                    <div class="form-group row">
                        <label for="pu" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
                            <input type="checkbox" name="pu" id="pu" {{ $punya }} data-toggle="toggle" disabled="disabled">
                        </div>
                    </div>
                    @endif
            </div> <!-- col -->
            
            </div> <!-- row -->
            <a class="btn btn-primary btn-sm" href="{{ route("approval.document.index") }}">List</a>
            <span class="float-md-right">
                <!--<a class="btn btn-primary btn-sm" href="{{ url($link_cetak) }}" target="_blank">Cetak</a>-->
                @if ($data->last_status == "approval_tax" or $kode_dept == "TAX")
                <button type="submit" id="btnUpdate" name="btnUpdate" class="btn btn-danger btn-sm">Update</button>
                @endif
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
            <div class="table-responsive">
              <table id="data_digital" class="display" style="width:100%">
                <thead>
                  <tr>
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
                <h5>List File Realisasi</h5>
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
            </div> <!-- card body -->
        </div><!-- card -->

        
        <div class="card">
            <div class="card-header">
                <h5>List File Kasbon</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table id="data_file_realisasi" class="display compact" style="width:100%">
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
            </div> <!-- card body -->
        </div><!-- card -->

        <div class="card">
            <div class="card-header">
                <h5>List File KBT</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table id="data_file_kbt" class="display compact" style="width:100%">
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
            </div> <!-- card body -->
        </div><!-- card -->

        <div class="card">
            <div class="card-header">
                <h5>History Status</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table id="data_status" class="display compact" style="width:100%">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Alasan</th>
                        </tr>
                    </thead>
                </table>
                </div>
            </div> <!-- card body -->
        </div><!-- card -->
  </div> <!-- col -->
</div> <!-- row -->

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
            <button type="submit" id="btnCancel" name="btnCancel" class="btn btn-danger">Cancel</button>
            <button type="submit" id="btnReject" name="btnReject" class="btn btn-info">Reject</button>
            <button type="submit" id="btnApprove" name="btnApprove" class="btn btn-primary">Approve</button>
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
<!--<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>-->
<script src="{{ asset('js/signature_pad.min.js') }}"></script>
<script>
function disable_btn(){
    $("#btnApprove").attr("disabled", true);
    $("#btnReject").attr("disabled", true);
    $("#btnCancel").attr("disabled", true);
    $("#btnClear").attr("disabled", true);
}

function enable_btn(){
    $("#btnApprove").attr("disabled", false);
    $("#btnReject").attr("disabled", false);
    $("#btnCancel").attr("disabled", false);
    $("#btnClear").attr("disabled", false);
}

$(document).ready(function(){

    var signaturePad = new SignaturePad(document.getElementById('signature-pad'));
    
    $('#labelkasbon').mask("#.##0,00", {reverse: true});
    $('#jumrealisasi').mask("#.##0,00", {reverse: true});
    $('#kembali').mask("#.##0,00",{reverse:true});

    $("#btnClear").click(function(){
        signaturePad.clear();
    });

    $("#btnUpdate").click(function(){
            if(!confirm("Anda yakin ingin update dokumen ini?")){
                event.preventDefault();
            }else{
                event.preventDefault();
                $("#btnUpdate").attr("disabled", true);
                var spinner = $('#loader');
                spinner.show();
                var form_data = new FormData();
                var action_url = "{{ url('approval/document/update-pu') }}";
                var pu = 0; //$("#pu").attr("checked") ? 1 : 0;
                if ($('#pu').is(":checked"))
                {
                    pu=1;
                }
                //alert(pu);
                form_data.append('id', '{{ $data->id  }}');
                form_data.append('pu', pu);
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
                            $("#btnUpdate").attr("disabled", false);
                        }

                        if(data.success){
                            alert(data.success);
                            $("#btnUpdate").attr("disabled", false);
                            //window.location.href = "{{ url('approval/document') }}";
                        }
                    },
                    error: function(data){
                        console.log(data.responseJSON);
                        //alert(data);
                        spinner.hide();
                        alert("gagal reject, hubungi IT");
                        $("#btnUpdate").attr("disabled", false);
                    }
                });
            }
    });

    $("#btnApprove").click(function(){
        if(!confirm("Anda yakin ingin approve dokumen ini?")){
            event.preventDefault();
        }else{
            event.preventDefault();
            var spinner = $('#loader');
            spinner.show();
            var data = signaturePad.toDataURL('image/png');
            $('#output').val(data);
            disable_btn();
            var form_data = new FormData();
            var action_url = "{{ url('approval/document/approve') }}";
            var result_msg = "Approve Data succesfully";
            form_data.append('id', '{{ $data->id  }}');
            form_data.append('signature', data);
            form_data.append('notes', $('#alasan').val());
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
                            window.location.href = "{{ url('approval/outstanding-kasbon') }}";
                        }
                    },
                    error: function(data){
                        console.log(data.responseJSON);
                        //alert(data);
                        spinner.hide();
                        alert("gagal approve, hubungi IT");
                        enable_btn();
                    }
                });
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
                var action_url = "{{ url('approval/document/reject') }}";
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
                            window.location.href = "{{ url('approval/outstanding-kasbon') }}";
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

    $("#btnCancel").click(function(){
        if($('#alasan').val() == ""){
            alert("Alasan harus di-isi");
            event.preventDefault();
        }else{
            if(!confirm("Anda yakin ingin cancel dokumen ini?jika dicancel maka dokumen akan dibatalkan dan tidak dapat dilanjutkan")){
                event.preventDefault();
            }else{
                event.preventDefault();
                disable_btn();
                var spinner = $('#loader');
                spinner.show();
                var form_data = new FormData();
                var action_url = "{{ url('approval/document/cancel') }}";
                var result_msg = "Cancel Data succesfully";
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
                            window.location.href = "{{ url('approval/document') }}";
                        }
                    },
                    error: function(data){
                        console.log(data.responseJSON);
                        //alert(data);
                        spinner.hide();
                        alert("gagal cancel, hubungi IT");
                        enable_btn();
                    }
                });
            }
        }
    });

    $("#btnProses").on("click", function() {
        event.preventDefault();
        signaturePad.clear();
        $('#alasan').val('');
        $('#formmodalsubmit').modal('show');
    });

    $('#data_digital').DataTable({
        paging: false,
        processing: true,
        responsive: true,
        serverSide: true,
        ajax: "{{ url('approval/document/data-digital') }}"+'/'+'{{ $data->id }}',
        columns: [
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

    $('#data_file_realisasi').DataTable({
        paging: false,
        processing: true,
        responsive: true,
        serverSide: true,
        ajax: "{{ url('approval/outstanding-kasbon/data-file-realisasi') }}"+'/'+'{{ $data->id }}',
        columns: [
            { data: 'category_name', name: 'category_name' },
            { data: 'nama_file', name: 'nama_file' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false }
        ]
    });

    $('#data_file_kbt').DataTable({
        paging: false,
        processing: true,
        responsive: true,
        serverSide: true,
        ajax: "{{ url('approval/outstanding-kasbon/data-file-kbt') }}"+'/'+'{{ $data->id }}',
        columns: [
            { data: 'category_name', name: 'category_name' },
            { data: 'nama_file', name: 'nama_file' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false }
        ]
    });

    $('#data_status').DataTable({
        paging: false,
        processing: true,
        responsive: true,
        serverSide: true,
        order : [],
        ajax: "{{ url('approval/document/data-history-status') }}"+'/'+'{{ $data->id }}',
        columns: [
            { data: 'status', name: 'status' },
            { data: 'tanggal', name: 'tanggal' },
            { data: 'nama', name: 'nama' },
            { data: 'alasan', name: 'alasan' },
        ]
    });
});
</script>
@endsection