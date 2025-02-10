@extends('layouts.approval')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Document List</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Approval</a></li>
            <li class="breadcrumb-item"><a href="#">SKBDN</a></li>
            <li class="breadcrumb-item active">List</li>
        </ol>
        </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="card">
    <div class="card-header" id="headingOne">
        <span class="float-md-left">
        <h5>Filter</h5>
        </span>
        <span class="float-md-right">
        <h5 class="mb-0">
          <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="fa" aria-hidden="true"></i>
          </button>
        </h5>
        </span>
      </div>

      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">
            <div class="row">
            <div class="col-md-6">
            <form class="form-horizontal">
                <div class="form-group row">
                    <label for="bulan" class="col-sm-4 col-form-label">Bulan</label>
                    <div class="col-sm-8">
                    <select class="form-control" name="bulan" id="bulan">
                        <option value="">Pilih</option>
                        <option value="01" {{ ( $bulan == "01") ? 'selected' : '' }}>Januari</option>
                        <option value="02" {{ ( $bulan == "02") ? 'selected' : '' }}>Februari</option>
                        <option value="03" {{ ( $bulan == "03") ? 'selected' : '' }}>Maret</option>
                        <option value="04" {{ ( $bulan == "04") ? 'selected' : '' }}>April</option>
                        <option value="05" {{ ( $bulan == "05") ? 'selected' : '' }}>Mei</option>
                        <option value="06" {{ ( $bulan == "06") ? 'selected' : '' }}>Juni</option>
                        <option value="07" {{ ( $bulan == "07") ? 'selected' : '' }}>Juli</option>
                        <option value="08" {{ ( $bulan == "08") ? 'selected' : '' }}>Agustus</option>
                        <option value="09" {{ ( $bulan == "09") ? 'selected' : '' }}>September</option>
                        <option value="10" {{ ( $bulan == "10") ? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{ ( $bulan == "11") ? 'selected' : '' }}>November</option>
                        <option value="12" {{ ( $bulan == "12") ? 'selected' : '' }}>Desember</option>
                    </select>
                    </div>
                </div>
            </form>
            </div> <!-- div col-6-->

            <div class="col-md-6">
                <span class="float-md-right">
                    <button type="submit" id="btnFiterSubmitSearch" class="btn btn-info btn-sm">Submit</button>
                </span>
            </div>
            </div> <!-- row -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

    @can('approval_document_submit')
    <div class="callout callout-info">
        <h5><i class="fas fa-magic"></i> INFO !</h5>
        Fitur Mass Approve (Approve banyak dokumen bersamaan) digunakan untuk mempercepat proses approval.
        Hanya gunakan fitur ini jika dokumen yang akan di approve sangat banyak. Jika ada error segera laporkan ke IT.
    </div>
    @endcan

        <div class="card">
            <div class="card-header">
                <h5>Data</h5>
            </div>
            <div class="card-body">
                @can('approval_document_submit')
                <button type="button" id="btnMassApprove" name="btnMassApprove" class="btn btn-info btn-sm">Mass Approve (BETA)</button>
                @endcan
                    <div class="table-responsive">
                        <table id="table_data" class="display compact" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="10"></th>
                                    <th>Action</th>
                                    <th>Document ID</th>
                                    <th>Ref</th>
                                    <th>Prioritas</th>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>PU ?</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Last Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
            </div>
        </div> <!-- table data -->
        <!-- table data proses -->
        <div class="card">
            <div class="card-header">
                <h5>Status Data Proses</h5>
            </div>
            <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_proses" class="display compact" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Document ID</th>
                                    <th>Ref</th>
                                    <th>Prioritas</th>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>PU ?</th>
                                    <th>Description</th>
                                    <th>Last Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
            </div>
        </div> <!-- table data -->
    </div>
</div>

<!-- START MODAL FORM SUBMIT -->
<div id="formmodalapprove" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
			  <h5 class="modal-title">Proses Mass Approve</h5>
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      	<span id="form_approve"></span>
      	<form method="post" id="formapprove" class="form-horizontal" enctype="multipart/form-data">
          @csrf
          <div class="form-group row">
            <label for="signature" class="col-sm-2 col-form-label">Signature *</label>
              <div class="col-sm-10">
                <canvas id="signature-pad" class="signature-pad" width="300px" height="200px"></canvas>
                <textarea name="output" style="display:none;" id="output"></textarea>
              </div>
          </div>
          <br />
          <div class="modal-footer" align="center">
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
<script src="{{ asset('js/signature_pad.min.js') }}"></script>
<script>
$(document).ready(function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var signaturePad = new SignaturePad(document.getElementById('signature-pad'));
    var idnya = new Array();

    $("#btnMassApprove").on("click", function() {
        event.preventDefault();

        if (!idnya || !idnya.length) {
            alert("pilih dulu dokumennya");
        }else{
            signaturePad.clear();
            $('#formmodalapprove').modal('show');
        }
    });

    $('#tambah').click(function(){
        $('#prioritas').val('');
        $('#keterangan').val('');
		$('#form_result').html('');
		$('#formModal').modal('show');
    });

    $("#btnApprove").click(function(){
        if(!confirm("Anda yakin ingin melakukan mass approve pada dokumen-dokumen terpilih?")){
            event.preventDefault();
        }else{
            event.preventDefault();
            var spinner = $('#loader');
            spinner.show();
            var data = signaturePad.toDataURL('image/png');
            $('#output').val(data);
            $("#btnApprove").attr("disabled", true);
            var form_data = new FormData();
            var action_url = "{{ url('approval/document/mass-approve') }}";
            var result_msg = "Mass Approve succesfully";
            form_data.append('id', idnya);
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
                        var html = '';
                        if(data.errors){
                            alert(data.errors);
                            spinner.hide();
                            $("#btnApprove").attr("disabled", false);
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
                        $("#btnApprove").attr("disabled", false);
                        alert("gagal mass approve, hubungi IT");
                    }
                });
        }
    });
    //table proses
    var table = $('#table_proses').DataTable({
        scrollX: true,    
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ url('approval/documentproses') }}",
          type: 'GET',
          data: function (d) {
          d.bulan = $("#bulan option:selected").val()
          }
         },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'no_ref', name: 'no_ref' },
            { data: 'priority_name', name: 'priority_name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'nama', name: 'nama' },
            {
                data: 'is_pu',
                render: function(data, type, row) {
                if (data === '1') {
                    return '<input type="checkbox" class="editor-active" onclick="return false;" checked>';
                } else {
                    return '<input type="checkbox" onclick="return false;" class="editor-active">';
                }
                return data;
                },
                className: "dt-body-center text-center",
                name: 'is_pu'
            },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'last_status', name: 'last_status' }
        ]
    });
    //end table proses
    var events = $('#events');
    var table = $('#table_data').DataTable({
        paging: false,
        select: true,
        //responsive: true,
        //fixedHeader: true,
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ url('approval/document') }}",
          type: 'GET',
          data: function (d) {
          d.bulan = $("#bulan option:selected").val()
          }
         },
         columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'multi',
            selector: 'td:first-child'
        },
        columns: [
            { data: 'pilih', orderable: false },
            { data: 'action', name: 'action', orderable: false },
            { data: 'id', name: 'id' },
            { data: 'no_ref', name: 'no_ref' },
            { data: 'priority_name', name: 'priority_name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'nama', name: 'nama' },
            //{ data: 'is_pu', name: 'is_pu' },
            {
                data: 'is_pu',
                render: function(data, type, row) {
                if (data === '1') {
                    return '<input type="checkbox" class="editor-active" onclick="return false;" checked>';
                } else {
                    return '<input type="checkbox" onclick="return false;" class="editor-active">';
                }
                return data;
                },
                className: "dt-body-center text-center",
                name: 'is_pu'
            },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'jum_vm', name: 'jum_vm' },
            { data: 'last_status', name: 'last_status' }
        ]
    });
    
    $('#table_data').DataTable().on( 'select', function ( e, dt, type, indexes ) {
        if ( type === 'row' ) {
            var rowData = table.rows( indexes ).data().toArray();
            var jsonStringify = JSON.stringify(rowData);
            var jsonObj = JSON.parse(jsonStringify);
            //alert(jsonObj[0]['id']);
            idnya.push(jsonObj[0]['id']);
            //alert(idnya);
        }
    });

    $('#table_data').DataTable().on( 'deselect', function ( e, dt, type, indexes ) {
        if ( type === 'row' ) {
            var rowData = table.rows( indexes ).data().toArray();
            var jsonStringify = JSON.stringify(rowData);
            var jsonObj = JSON.parse(jsonStringify);
            //alert(jsonObj[0]['id']);
            //idnya.splice(jsonObj[0]['id'], 1);

            /*for (var key in idnya) {
                if (key == jsonObj[0]['id']) {
                    idnya.splice(key, 1);
                }
            }*/
            idnya.splice( idnya.indexOf(jsonObj[0]['id']), 1 );
            //alert(idnya);
        }
    });

    $('#btnFiterSubmitSearch').click(function(){
        $('#table_data').DataTable().draw(true);
    });
    
});
</script>
@endsection