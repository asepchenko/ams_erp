@extends('layouts.crm')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Promo List</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">CRM</a></li>
            <li class="breadcrumb-item"><a href="#">Member Mobile App</a></li>
            <li class="breadcrumb-item active">Promo List</li>
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
                    <label for="status" class="col-sm-4 col-form-label">Status</label>
                    <div class="col-sm-8">
                    <select class="form-control" name="status" id="status">
                        <option value="">Pilih</option>
                        <option value="1" {{ ( $status == "1") ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ ( $status == "0") ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    </div>
                </div>
            </form>
            </div> <!-- div col-6-->

            <div class="col-md-6">
                <span class="float-md-right">
                    <button type="submit" id="btnFiterSubmitSearch" class="btn btn-primary btn-sm">Submit</button>
                </span>
            </div>
            </div> <!-- row -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Data</h5>
            </div>
            <div class="card-body">
                @can('crm_mobile_promo_create')
                <button type="button" name="tambah" id="tambah" class="btn btn-primary btn-sm">Tambah</button>
                @endcan
                <hr>
                    <div class="table-responsive">
                        <table id="table_data" class="display compact" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nama Promo</th>
                                    <th>Gambar</th>
                                    <th>Keterangan</th>
                                    <th>Syarat & Ketentuan</th>
                                    <th>Status</th>
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
			<h5 class="modal-title">Promo Baru</h5>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
      		</div>
      		<div class="modal-body">
      			<span id="form_result"></span>
      			<form method="post" id="addform" class="form-horizontal">
                @csrf
                <input type="hidden" name="id_promo" id="id_promo"/>
                <input type="hidden" name="action" id="action"/>
                <div class="form-group row">
                  <label for="nama" class="col-sm-4 col-form-label">Nama Promo *</label>
                    <div class="col-sm-8">
                      <input type="text"  id="nama" name="nama" class="form-control"  required>
                    </div>
                </div>
                <div class="form-group row">
                  <label for="gambar" class="col-sm-4 col-form-label">URL Gambar *</label>
                    <div class="col-sm-8">
                      <input type="text" id="gambar" name="gambar" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="keterangan" class="col-sm-4 col-form-label">Keterangan *</label>
                      <div class="col-sm-8">
                        <textarea id="keterangan" name="keterangan" class="form-control" required></textarea>
                      </div>
                </div>
                <div class="form-group row">
                    <label for="sk" class="col-sm-4 col-form-label">Syarat & Ketentuan *</label>
                      <div class="col-sm-8">
                        <textarea id="sk" name="sk" class="form-control" required></textarea>
                      </div>
                </div>
                <div class="form-group row">
                  <label for="status" class="col-sm-4 col-form-label">Status *</label>
                    <div class="col-sm-8">
                        <select name="status" id="status" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
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

    function loading(){
        $('#loading').modal({
            backdrop: "static", //remove ability to close modal with click
            keyboard: false, //remove option to close with keyboard
            show: true //Display loader!
        });
    }

    /* dibutuhkan jika ajax butuh autentikasi token */
    /*$.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });*/

    /* dibutuhkan agar modal bisa scroll */
    $('.modal').on("hidden.bs.modal", function (e) { //fire on closing modal box
        if ($('.modal:visible').length) { // check whether parent modal is opend after child modal close
            $('body').addClass('modal-open'); // if open mean length is 1 then add a bootstrap css class to body of the page
        }
    });

    $('#tambah').click(function(){
        $('#id_promo').val('');
        $('#nama').val('');
        $('#gambar').val('');
        $('#keterangan').val('');
        $('#sk').val('');
        $('#status').val('');
        $('#action').val('add');
		$('#form_result').html('');
		$('#formModal').modal('show');
    });

    $('#addform').on('submit', function(event){
        event.preventDefault();
        loading();
        $("#btnSubmit").attr("disabled", true);
        var action_url = '';
        var result_msg = '';

        if($('#action').val() == "add"){
            action_url = "{{ route('crm.mobile-promo.store') }}";
            result_msg = "Insert Data succesfully";
        }else if($('#action').val() == "edit"){
            var idnya = $("#id_promo").val();
            action_url = "{{ url('crm/mobile-promo/update') }}";
            result_msg = "Update Data succesfully";
        }

		$.ajax({
			url: action_url,
            method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
                $('#loading').modal('hide');
				if(data.errors)
				{
					alert(data.errors);
                    $("#btnSubmit").attr("disabled", false);
				}
				if(data.success)
				{
                    alert(result_msg);
                    $('#formModal').modal('hide');
                    $('#table_data').DataTable().ajax.reload();
                    $("#btnSubmit").attr("disabled", false);
				}
            },
            error: function(data){
                $('#loading').modal('hide');
                $("#btnSubmit").attr("disabled", false);
                var errors = data.responseJSON;
                alert("gagal membuat promo baru");
                console.log(errors);
            }
		});
    });

    $('#table_data').DataTable({
        paging: true,
        //responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ url('crm/mobile-promo') }}",
          type: 'GET',
          data: function (d) {
          d.status = $("#status option:selected").val()
          }
         },
        columns: [
            { data: 'nama_promo', name: 'nama_promo' },
            { data: 'image', name: 'image' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'syarat_ketentuan', name: 'syarat_ketentuan' },
            { data: 'is_aktif', name: 'is_aktif' },
            { data: 'action', name: 'action', orderable: false }
        ]
    });
    
    $(document).on('click', '.edit', function(){
        var id_edit = $(this).attr('id');
        var action_url = "{{ url('crm/mobile-promo/edit') }}"+ "/" +id_edit;
        loading();
		$.ajax({
			url: action_url,
            method:"GET",
			success:function(data)
			{
                $('#loading').modal('hide');
				var html = '';
				if(data.errors)
				{
					alert(data.errors);
				}
				if(data.success)
				{
                    $('#id_promo').val(data.success.id);
                    $('#nama').val(data.success.nama_promo);
                    $('#gambar').val(data.success.image);
                    $('#keterangan').val(data.success.keterangan);
                    $('#sk').val(data.success.syarat_ketentuan);
                    $('#status').val(data.success.is_aktif);
                    $('#action').val('edit');
                    $('#formModal').modal('show');
				}
            },
            error: function(data){
                $('#loading').modal('hide');
                var errors = data.responseJSON;
                alert(data);
                console.log(errors);
            }
		});
    });

    $(document).on('click', '.push', function(){
        var id_edit = $(this).attr('id');
        var action_url = "{{ url('crm/mobile-promo/edit') }}"+ "/" +id_edit;
        loading();
		$.ajax({
			url: action_url,
            method:"GET",
			success:function(data)
			{
                $('#loading').modal('hide');
				if(data.errors)
				{
					alert(data.errors);
				}
				if(data.success)
				{
                    var settings = {
                    "url": "https://fcm.googleapis.com/fcm/send",
                    "method": "POST",
                    "timeout": 0,
                    "headers": {
                        "Content-Type": "application/json",
                        "Authorization": "key=AAAAJtN1Lt0:APA91bG-QWuBsvEYKKumum3upBs--t9v7qQxYxqcN8HVw_nMRfD9sX-u672lt8r4Jv0rL5DYYxApJOUWy5pJEcweD0XVpe7EXxPSuTy2e7-m12KoWvluAFq6wMLgDOkvB9hMsh2DZNTD"
                    },
                    "data": JSON.stringify({"to":"/topics/all","restricted_package_name":"com.yudhatp.amsmember","notification":{"title":data.success.nama_promo,"body":data.success.keterangan,"image":data.success.image,"click_action":"FCM_PLUGIN_ACTIVITY"}}),
                    };

                    $.ajax(settings).done(function (response) {
                        console.log(response);
                        $('#loading').modal('hide');
                        alert("Berhasil mengirimkan push notification");
                    });
				}
            },
            error: function(data){
                $('#loading').modal('hide');
                var errors = data.responseJSON;
                alert(data);
                console.log(errors);
            }
        });
        
        /*var settings = {
        "url": "https://fcm.googleapis.com/fcm/send",
        "method": "POST",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/json",
            "Authorization": "key=AAAAJtN1Lt0:APA91bG-QWuBsvEYKKumum3upBs--t9v7qQxYxqcN8HVw_nMRfD9sX-u672lt8r4Jv0rL5DYYxApJOUWy5pJEcweD0XVpe7EXxPSuTy2e7-m12KoWvluAFq6wMLgDOkvB9hMsh2DZNTD"
        },
        "data": JSON.stringify({"to":"/topics/all","restricted_package_name":"com.yudhatp.amsmember","notification":{"title":"Promo Disc 10","body":"Jangan lupa diskon 10% hanya hari ini, dapatkan sekarang !!","image":"https://image.freepik.com/free-psd/smiley-woman-spring-sale-banner_23-2148437360.jpg","click_action":"FCM_PLUGIN_ACTIVITY"}}),
        };

        $.ajax(settings).done(function (response) {
            console.log(response);
            $('#loading').modal('hide');
			alert("Berhasil mengirimkan push notification");
        });*/
    });

    $(document).on('click', '.delete', function(){
        var id = $(this).attr('id');
        var r = confirm("Are you sure want to delete this data : "+id+" ??");
        if (r == true) {
            delete_url = "{{ url('crm/mobile-promo/delete') }}";
            event.preventDefault();
            loading();
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
                    $('#loading').modal('hide');
                    if(data.success){
                        alert("Delete Data successfully");
                        $('#table_data').DataTable().ajax.reload();
                    }
                },
                error: function(data){
                    $('#loading').modal('hide');
                    var errors = data.responseJSON;
                    alert("Error while deleting data");
                    console.log(errors);
                }
            });
        } else {
            alert("Delete Canceled");
        }
    });

    $('#btnFiterSubmitSearch').click(function(){
        $('#table_data').DataTable().draw(true);
    });
});
</script>
@endsection