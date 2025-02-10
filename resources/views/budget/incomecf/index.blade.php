@extends('layouts.budget')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Request List</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Budgeting</a></li>
            <li class="breadcrumb-item"><a href="#">Request Anggaran</a></li>
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
            <div class="col-md-4">
            <form class="form-horizontal">
                <div class="form-group row">
                    <label for="tahun" class="col-sm-4 col-form-label">Tahun</label>
                    <div class="col-sm-8">
                    <select class="form-control" name="tahun" id="tahun">
                        <option value="now">Pilih</option>
                        <?php
                        $t = date(2023);
                        for ($i=$t; $i<=$t +5; $i++){
                        ?>
                        <option value={{ $i }} {{ ($tahun == $i) ? 'selected' :'' }}>{{ $i }}</option>
                        <?php
                        }
                        ?>
                    </select>
                    </div>
                </div>
            </form>
            </div> <!-- div col-4-->
            <div class="col-md-4">
            <form class="form-horizontal">
                <div class="form-group row">
                    <label for="bulan" class="col-sm-4 col-form-label">Bulan</label>
                    <div class="col-sm-8">
                    <select class="form-control" name="bulan" id="bulan">
                        <option value="">Pilih</option>
                        @foreach($data_bulan as $data_bln)
                                <option value="{{$data_bln->id}}">{{$data_bln->nama_bulan}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
            </div> <!-- div col-4-->    

            <div class="col-md-4">
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
                <button type="button" name="tambah" id="tambah" class="btn btn-primary btn-sm">Tambah</button>
                    <div class="table-responsive">
                        <table id="table_data" class="display compact" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Komponen Income</th>
                                    <th>Tahun</th>
                                    <th>Bulan</th>
                                    <th>Bulan Str</th>
                                    <th>Budget</th>
                                    <th>Realisasi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
            </div>
        </div> <!-- table data -->
    </div>
</div>

<!-- START MODAL FORM -->
<div id="formModal" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
			<h5 class="modal-title">Input Data Income</h5>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
      		</div>
      		<div class="modal-body">
      			<span id="form_result"></span>
      			<form method="post" id="addform" class="form-horizontal">
                @csrf
                <div class="form-group row">
                  <label for="komponen_income" class="col-sm-4 col-form-label">Komponen Income</label>
                    <div class="col-sm-8">
                        <select name="komponen_income" id="komponen_income" class="form-control" required>
                            <option value="">- Pilih -</option>
                            @foreach($komponen_income as $data_ct)
                                <option value="{{$data_ct->id}}">{{$data_ct->label_income}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                  <label for="komponen_income" class="col-sm-4 col-form-label">Periode Bulan</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="bulan_budget" id="bulan_budget">
                        <option value="">Pilih</option>
                        @foreach($data_bulan as $data_bln)
                                <option value="{{$data_bln->id}}">{{$data_bln->nama_bulan}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                <label for="budget" class="col-sm-4 col-form-label">Budget</label>
                    <div class="col-sm-8">
                        <input type="text" name="budget" id="budget" class="form-control" autocomplete="off" required>
                    </div>
                </div>
                <div class="form-group row">
                <label for="realisasi" class="col-sm-4 col-form-label">Realisasi</label>
                    <div class="col-sm-8">
                        <input type="text" name="realisasi" id="realisasi" class="form-control" autocomplete="off" required>
                    </div>
                </div>
                <div class="form-group row">
                <label for="realisasi" class="col-sm-4 col-form-label">aktif</label>
                    <div class="col-sm-8">
                        <input type="checkbox" name="aktif" id="aktif" data-width="100">
                    </div>
                </div>
              	<br />
              	<div class="modal-footer" align="center">                         
                    <input type="hidden" name="id_detail" id="id_detail"/>
                    <input type="hidden" name="action_income" id="action_income"/>        
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
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var idnya = new Array();
    $('#aktif').bootstrapToggle({
		on: 'Yes',
      off: 'No'
	});
    $('#budget').mask("#,##0.00", {reverse: true});
    $('#realisasi').mask("#,##0.00", {reverse: true});

    var events = $('#events');
    var table = $('#table_data').DataTable({
        //scrollX: true,
        paging: true,    
        processing: true,
        serverSide: true,
        ordering: false,
        ajax: {
          url: "{{ url('budget/incomecf') }}",
          type: 'GET',
          data: function (d) {            
          d.tahun = $("#tahun option:selected").val()
          d.bulan = $("#bulan option:selected").val()
          }
         },
        columns: [
            { data: 'action', name: 'action', },
            { data: 'label_income', name: 'label_income' },
            { data: 'tahun', name: 'tahun' },
            { data: 'bulan', name: 'bulan' },
            { data: 'label_bulan', name: 'label_bulan' },
            { data: 'budget_income', name: 'budget_income', render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' ) },
            { data: 'real_income', name: 'real_income', render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' ) }
        ]
    });
	$(document).on('hidden.bs.modal','.modal',function(e){
		$('#addform')[0].reset();
        $('#budget').unmask("#,##0.00", {reverse: true});
        $('#realisasi').unmask("#,##0.00", {reverse: true});
	});
    $('#tambah').click(function(){
        $('#komponen_income').val('');
        $('#keterangan').val('');
        $('#action_income').val('Add');
		$('#form_result').html('');
		$('#formModal').modal('show');
    });
    $('#addform').on('submit', function(event){
        event.preventDefault();
        $("#btnSubmit").attr("disabled", true);
        var spinner = $('#loader');
        spinner.show();
		var result_msg = "Insert Data succesfully";
        if($('#action_income').val() == 'Add')
		{
			action_url = "{{ route('budget.incomecf.store') }}";
			result_msg = "Insert Data succesfully";
		}

		if($('#action_income').val() == 'Edit')
		{
			action_url = "{{ route('budget.incomecf.update') }}";
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
				var html = '';
				if(data.errors)
				{
                    spinner.hide();
                    $("#btnSubmit").attr("disabled", false);
				}
				if(data.success)
				{

                    var id = data.success;
                    
                    alert(data.success);
                    $('#formModal').modal('hide');  
                    $('#table_data').DataTable().ajax.reload();
                    
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
                      errorsHtml += '<li>'+ value + '</li>'; //showing only the first error.
                 });
                 errorsHtml += '</ul></div>';
                 $('#form_result').html( errorsHtml ); //appending to a <div id="form-errors"></div> inside form
            }
		});
    });

    $(document).on('click', '.edit', function(){
        var id_edit = $(this).attr('id');
        var spinner = $('#loader');
        var action_url = "{{ url('budget/incomecf/edit-income') }}"+ "/" +id_edit;
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

                    var $optlabel = $("<option selected></option>").val(data.success.id_income).text(data.success.label_income);
                    var $optbulan = $("<option selected></option>").val(data.success.bulan).text(data.success.nama_bulan);
                    $('#id_detail').val(data.success.id);
                    $('#komponen_income').append($optlabel).trigger("select");
                    $('#komponen_income').attr('disabled',true);     
                    $('#bulan_budget').append($optbulan).trigger("select");                    
                    $('#budget').unmask("#,##0.00", {reverse: true});
                    $('#realisasi').unmask("#,##0.00", {reverse: true});
                    $('#budget').val(data.success.budget_income).mask("#,##0.00", {reverse: true});
                    $('#realisasi').val(data.success.real_income).mask("#,##0.00", {reverse: true});
                    if (data.success.aktif == 1){
					$('#aktif').prop('checked', true).change()
                    }else{
                        $('#aktif').prop('checked', false).change()
                    }
                    $('#btnSubmit').val('Update');                    
                    $('#action_income').val('Edit');
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

    $('#btnFiterSubmitSearch').click(function(){
        $('#table_data').DataTable().draw(true);
    });
    
});
</script>
@endsection