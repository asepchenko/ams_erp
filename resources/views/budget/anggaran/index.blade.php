@extends('layouts.budget')
@section('content')
<div class = "content-header">
<div class = "container-fluid">
<div class = "row mb-2">
<div class = "col-sm-6">
<h1  class = "m-0 text-dark">Request List</h1>
            </div><!-- /.col -->
        <div class = "col-sm-6">
        <ol  class = "breadcrumb float-sm-right">
        <li  class = "breadcrumb-item"><a href = "#">Budgeting</a></li>
        <li  class = "breadcrumb-item"><a href = "#">Request Anggaran</a></li>
        <li  class = "breadcrumb-item active">List</li>
        </ol>
        </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div  class = "card">
<div  class = "card-header" id = "headingOne">
<span class = "float-md-left">
        <h5>Filter</h5>
        </span>
        <span   class = "float-md-right">
        <h5     class = "mb-0">
        <button class = "btn btn-link" data-toggle = "collapse" data-target = "#collapseOne" aria-expanded = "true" aria-controls = "collapseOne"><i class = "fa" aria-hidden = "true"></i>
          </button>
        </h5>
        </span>
      </div>

      <div    id    = "collapseOne" class = "collapse show" aria-labelledby = "headingOne" data-parent = "#accordion">
      <div    class = "card-body">
      <div    class = "row">
      <div    class = "col-md-6">
      <form   class = "form-horizontal">
      <div    class = "form-group row">
      <label  for   = "bulan" class       = "col-sm-4 col-form-label">Bulan</label>
      <div    class = "col-sm-8">
      <select class = "form-control" name = "bulan" id                      = "bulan">
      <option value = "">Pilih</option>
      <option value = "01" {{ ( $bulan == "01") ? 'selected' : '' }}>Januari</option>
      <option value = "02" {{ ( $bulan == "02") ? 'selected' : '' }}>Februari</option>
      <option value = "03" {{ ( $bulan == "03") ? 'selected' : '' }}>Maret</option>
      <option value = "04" {{ ( $bulan == "04") ? 'selected' : '' }}>April</option>
      <option value = "05" {{ ( $bulan == "05") ? 'selected' : '' }}>Mei</option>
      <option value = "06" {{ ( $bulan == "06") ? 'selected' : '' }}>Juni</option>
      <option value = "07" {{ ( $bulan == "07") ? 'selected' : '' }}>Juli</option>
      <option value = "08" {{ ( $bulan == "08") ? 'selected' : '' }}>Agustus</option>
      <option value = "09" {{ ( $bulan == "09") ? 'selected' : '' }}>September</option>
      <option value = "10" {{ ( $bulan == "10") ? 'selected' : '' }}>Oktober</option>
      <option value = "11" {{ ( $bulan == "11") ? 'selected' : '' }}>November</option>
      <option value = "12" {{ ( $bulan == "12") ? 'selected' : '' }}>Desember</option>
                    </select>
                    </div>
                </div>
            </form>
            </div> <!-- div col-4-->    

            <div    class = "col-md-6">
            <span   class = "float-md-right">
            <button type  = "submit" id = "btnFiterSubmitSearch" class = "btn btn-info btn-sm">Submit</button>
                </span>
            </div>
            </div> <!-- row -->
        </div>
    </div>
</div>

<div class = "row">
<div class = "col-md-12">

        <div class = "card">
        <div class = "card-header">
                <h5>Data</h5>
            </div>
            <div class = "card-body">
                @can('budget_anggaran_create')
                <button type = "button" name = "tambah" id = "tambah" class = "btn btn-primary btn-sm">Tambah</button>
                 @endcan
                    <div   class = "table-responsive">
                    <table id    = "table_data" class = "display compact" style = "width:100%">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>No Request</th>
                                    <th>Tanggal</th>
                                    <th>Category</th>
                                    <th>Group</th>
                                    <th>Periode</th>
                                    <th>Tahun</th>
                                    <th>Keterangan</th>
                                    <th>Nilai Budget</th>
                                    <th>Realisasi</th>
                                    <th>Sisa</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
            </div>
        </div> <!-- table data -->
    </div>
</div>

<!-- START MODAL FORM -->
<div    id    = "formModal" class   = "modal fade" role    = "dialog">
<div    class = "modal-dialog" role = "document">
<div    class = "modal-content">
<div    class = "modal-header">
<h5     class = "modal-title">Pengajuan Anggaran Baru</h5>
<button type  = "button" class      = "close" data-dismiss = "modal">&times;</button>
      		</div>
        <div  class  = "modal-body">
        <span id     = "form_result"></span>
        <form method = "post" id = "addform" class = "form-horizontal">
                @csrf
                <div   class = "form-group row">
                <label for   = "nik" class  = "col-sm-4 col-form-label">NIK *</label>
                <div   class = "col-sm-8">
                <input type  = "text"  name = "nik" class = "form-control" value = "{{ $nik }}" required readonly>
                    </div>
                </div>
                <div   class = "form-group row">
                <label for   = "nama_customer" class = "col-sm-4 col-form-label">Nama Pemohon *</label>
                <div   class = "col-sm-8">
                <input type  = "text" name           = "nama" class = "form-control" value = "{{ $name }}" required readonly>
                    </div>
                </div>
                <div    class = "form-group row">
                <label  for   = "kode_group" class = "col-sm-4 col-form-label">Kode Group *</label>
                <div    class = "col-sm-8">
                <select name  = "kode_group" id    = "kode_group" class = "form-control" required>
                            @foreach($code_group as $data_group)
                                <option value = "{{$data_group->kode_group}}">{{$data_group->deskripsigroup}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div    class = "form-group row">
                <label  for   = "category_request" class = "col-sm-4 col-form-label">category_request *</label>
                <div    class = "col-sm-8">
                <select name  = "category_request" id    = "category_request" class = "form-control" required>
                <option value = "">- Pilih -</option>
                            @foreach($category_request as $data_ct)
                                <option value = "{{$data_ct->code_category}}">{{$data_ct->category_request}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div      class = "form-group row">
                <label    for   = "keterangan" class = "col-sm-4 col-form-label">Keterangan *</label>
                <div      class = "col-sm-8">
                <textarea id    = "keterangan" name  = "keterangan" class = "form-control" required></textarea>
                      </div>
                </div>
              	<br />
               <div   class = "modal-footer" align = "center">
               <input type  = "submit" id          = "btnSubmit" name = "btnSubmit" class = "btn btn-info btn-sm" value = "Simpan" />
              	</div>
      			</form>
      		</div>
    	</div>
    </div>
</div>
<!-- END MODAL FORM -->

<!-- START MODAL FORM SUBMIT -->
<div    id    = "formmodalapprove" class = "modal fade" role    = "dialog">
<div    class = "modal-dialog" role      = "document">
<div    class = "modal-content">
<div    class = "modal-header">
<h5     class = "modal-title">Proses Mass Approve</h5>
<button type  = "button" class           = "close" data-dismiss = "modal">&times;</button>
      </div>
      <div  class  = "modal-body">
      <span id     = "form_approve"></span>
      <form method = "post" id = "formapprove" class = "form-horizontal" enctype = "multipart/form-data">
          @csrf
          <div      class = "form-group row">
          <label    for   = "signature" class     = "col-sm-2 col-form-label">Signature *</label>
          <div      class = "col-sm-10">
          <canvas   id    = "signature-pad" class = "signature-pad" width = "300px" height = "200px"></canvas>
          <textarea name  = "output" style        = "display:none;" id    = "output"></textarea>
              </div>
          </div>
          <br />
          <div    class = "modal-footer" align = "center">
          <button type  = "submit" id          = "btnApprove" name = "btnApprove" class = "btn btn-primary">Approve</button>
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
<script>
$(document).ready(function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var signaturePad = new SignaturePad(document.getElementById('signature-pad'));
    var idnya        = new Array();

    $('#tambah').click(function(){
        $('#category_request').val('');
        $('#keterangan').val('');
		$('#form_result').html('');
		$('#formModal').modal('show');
    });
    $('#addform').on('submit', function(event){
        event.preventDefault();
        $("#btnSubmit").attr("disabled", true);
        var spinner = $('#loader');
        spinner.show();
		var action_url = "{{ route('budget.anggaran.store') }}";
		var result_msg = "Insert Data succesfully";

		$.ajax({
			url     : action_url,
			method  : "POST",
			data    : $(this).serialize(),
			dataType: "json",
			success : function(data)
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

                    var id                   = data.success;
                        window.location.href = "{{ url('budget/anggaran') }}" + "/" + id + "/edit";
				}
                $('#form_result').html(html);
            },
            error: function(data){
                $("#btnSubmit").attr("disabled", false);
                spinner.hide();
                var errors = data.responseJSON;
                alert(data);
                console.log(errors);

                 errorsHtml = '<div class="alert alert-danger"><ul>';

                 $.each( errors.error, function( key, value ) {
                      errorsHtml += '<li>'+ value + '</li>';  //showing only the first error.
                 });
                 errorsHtml += '</ul></div>';
                 $('#form_result').html( errorsHtml );  //appending to a <div id="form-errors"></div> inside form
            }
		});
    });

    var events = $('#events');
    var table  = $('#table_data').DataTable({
              //scrollX: true,
        paging    : false,
        processing: true,
        serverSide: true,
        ordering  : false,
        ajax      : {
          url : "{{ url('budget/anggaran') }}",
          type: 'GET',
          data: function (d) {
          d.bulan = $("#bulan option:selected").val()
          }
         },
        columns: [
            { data: 'action', name: 'action', },
            { data: 'request_id', name: 'request_id' },
            { data: 'created_at', name: 'created_at' },
            { data: 'category_request', name: 'category_request' },
            { data: 'kode_group', name: 'kode_group' },
            { data: 'periode', name: 'periode' },
            { data: 'tahun', name: 'tahun' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'nilai_request', name: 'jum_budget' },
            { data : 'realisasi', name:'realisasi' },
            { data: 'sisa', name: 'sisa' },
            { data: 'last_status', name: 'last_status' }
        ]
    });
    $('#btnFiterSubmitSearch').click(function(){
        $('#table_data').DataTable().draw(true);
    });
    
});
</script>
@endsection