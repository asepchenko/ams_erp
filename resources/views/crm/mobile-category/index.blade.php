@extends('layouts.crm')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Category List</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">CRM</a></li>
            <li class="breadcrumb-item"><a href="#">Member Mobile App</a></li>
            <li class="breadcrumb-item active">Category List</li>
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
                        <option value="all">All</option>
                        <option value="1" {{ ( $status == "1") ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ ( $status == "0") ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    </div>
                </div>
            </form>
            </div> <!-- div col-6-->

            <div class="col-md-6">
                <div class="form-group row">
                    <label for="brand_filter" class="col-sm-4 col-form-label">Brand</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="brand_filter" id="brand_filter">
                            <option value="all">All</option>
                            @foreach($brands as $brand_filter)
                            <option value="{{$brand_filter->nama_brand}}"
                            {{ ( $brand_pilih == $brand_filter->nama_brand) ? 'selected' : '' }}
                            >{{$brand_filter->nama_brand}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
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
        <div class="card">
            <div class="card-header">
                <h5>Data</h5>
            </div>
            <div class="card-body">
                @can('crm_mobile_promo_create')
                <button type="button" name="tambah" id="tambah" class="btn btn-info btn-sm">Tambah</button>
                @endcan
                <hr>
                    <div class="table-responsive">
                        <table id="table_data" class="display compact" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Gambar</th>
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
			<h5 class="modal-title">Create/Edit Category</h5>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
      		</div>
      		<div class="modal-body">
      			<span id="form_result"></span>
      			<form method="post" id="addform" class="form-horizontal">
                @csrf
                <input type="hidden" name="id_category" id="id_category"/>
                <input type="hidden" name="action" id="action"/>
                <div class="form-group row">
                  <label for="brand" class="col-sm-4 col-form-label">Brand *</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="brand" id="brand">
                            <option value="">Pilih</option>
                            @foreach($brands as $brand_data)
                            <option value="{{$brand_data->nama_brand}}">
                            {{$brand_data->nama_brand}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                  <label for="image" class="col-sm-4 col-form-label">URL Gambar *</label>
                    <div class="col-sm-8">
                      <input type="text" id="image" name="image" class="form-control" autocomplete="off" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="category" class="col-sm-4 col-form-label">Category *</label>
                      <div class="col-sm-8">
                        <input type="text" id="category" name="category" class="form-control" autocomplete="off" required>
                      </div>
                </div>
                <div class="form-group row">
                  <label for="status_form" class="col-sm-4 col-form-label">Status *</label>
                    <div class="col-sm-8">
                        <select name="status_form" id="status_form" class="form-control" required>
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

    /* dibutuhkan jika ajax butuh autentikasi token */
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('#tambah').click(function(){
        $('#id_category').val('');
        $('#brand').val('');
        $('#image').val('');
        $('#category').val('');
        $('#status_form').val('');
        $('#action').val('add');
		$('#form_result').html('');
		$('#formModal').modal('show');
    });

    $('#addform').on('submit', function(event){
        event.preventDefault();
        $("#btnSubmit").attr("disabled", true);
        var spinner = $('#loader');
        spinner.show();
        var action_url = '';
        var result_msg = '';

        if($('#action').val() == "add"){
            action_url = "{{ route('crm.mobile-category.store') }}";
            result_msg = "Insert Data succesfully";
        }else if($('#action').val() == "edit"){
            var idnya = $("#id_category").val();
            action_url = "{{ url('crm/mobile-category/update') }}";
            result_msg = "Update Data succesfully";
        }

		$.ajax({
			url: action_url,
            method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
                spinner.hide();
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
                spinner.hide();
                $("#btnSubmit").attr("disabled", false);
                var errors = data.responseJSON;
                alert("gagal membuat category baru");
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
          url: "{{ url('crm/mobile-category') }}",
          type: 'GET',
          data: function (d) {
          d.status = $("#status option:selected").val(),
          d.brand_filter = $("#brand_filter option:selected").val()
          }
         },
        columns: [
            { data: 'brand', name: 'brand' },
            { data: 'category', name: 'category' },
            { data: 'image', name: 'image' },
            { data: 'status_aktif', name: 'status_aktif' },
            { data: 'action', name: 'action', orderable: false }
        ]
    });
    
    $(document).on('click', '.edit', function(){
        var id_edit = $(this).attr('id');
        var action_url = "{{ url('crm/mobile-category/edit') }}"+ "/" +id_edit;
        var spinner = $('#loader');
        spinner.show();
		$.ajax({
			url: action_url,
            method:"GET",
			success:function(data)
			{
                spinner.hide();
				if(data.errors)
				{
					alert(data.errors);
				}
				if(data.success)
				{
                    $('#id_category').val(data.success.id);
                    $('#brand').val(data.success.brand);
                    $('#image').val(data.success.image);
                    $('#category').val(data.success.category);
                    $('#status_form').val(data.success.is_aktif);
                    $('#action').val('edit');
                    $('#formModal').modal('show');
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

    $(document).on('click', '.delete', function(){
        var id = $(this).attr('id');
        var r = confirm("Are you sure want to delete this data : "+id+" ??");
        if (r == true) {
            delete_url = "{{ url('crm/mobile-category/delete') }}";
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
                        alert("Delete Data successfully");
                        $('#table_data').DataTable().ajax.reload();
                    }
                },
                error: function(data){
                    spinner.hide();
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