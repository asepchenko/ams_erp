@extends('layouts.pos')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Store Target</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">POS</a></li>
            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
            <li class="breadcrumb-item active">Store Target</li>
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
                <div class="form-group row">
                    <label for="GH" class="col-sm-4 col-form-label">Group Head</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="GH" id="GH" data-dependent="STORE">
                            <option value="0">All</option>
                            @foreach($ghs as $gh_data)
                            <option value="{{$gh_data->id}}"
                            {{ ( $gh_pilih == $gh_data->id) ? 'selected' : '' }}
                            >{{$gh_data->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!--<div class="form-group row">
                    <label for="STORE" class="col-sm-4 col-form-label">ID Store</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="STORE" id="STORE">
                            @if (!empty($store))
                                <option value="{{$store}}">{{$store}}</option>
                            @else
                                <option value="all">Pilih Group Head Dahulu</option>
                            @endif
                        </select>
                    </div>
                </div>-->
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
                <a class="btn btn-primary btn-sm" href="{{ url('pos/storetarget-download-excel') }}">Download Template</a>
                <button type="button" id="btnImport" class="btn btn-info btn-sm" onclick="prosesImport()">
                    Import Excel
                </button>
                        <div class="table-responsive">
                        <table id="table_data" class="display compact" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Store ID</th>
                                    <th>Bulan</th>
                                    <th>Tahun</th>
                                    <th>Total Target</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                        </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>

function prosesImport() {
    $('#file').click();
}

$('#file').click(function () {
    if ($(this).val() != '') {
        import_excel(this);
    }
});

function import_excel(excelnya){
    event.preventDefault();
    $("#btnImport").attr("disabled", true);
    var form_data = new FormData();
	var action_url = '';
	var result_msg = '';
	action_url = "{{ url('pos/storetarget-import-excel') }}";
    result_msg = "Import Data succesfully";
    form_data.append('file',  excelnya.files[0]);
    form_data.append('_token', '{{csrf_token()}}');
	$.ajax({
		url: action_url,
        method:"POST",
        data:form_data,
        contentType: false,
        processData: false,
		success:function(data)
		{
            //alert(data);
            $("#btnImport").attr("disabled", false);
			var html = '';
			if(data.errors){
                alert(data.errors);
            }

			if(data.success){
                alert(result_msg);
                $('#table_data').DataTable().ajax.reload();
			}
        },
        error: function(data){
            $("#btnImport").attr("disabled", false);
            alert("gagal import excel, hubungi IT");
            //console.log(data);
        }
	});
}

$(document).ready(function(){
    $('#table_data').DataTable({
        paging: false,
        //fixedHeader: true,
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ url('pos/storetarget') }}",
          type: 'GET',
          data: function (d) {
          d.bulan = $("#bulan option:selected").val()
          d.gh = $("#GH option:selected").val()
          }
         },
        columns: [
            { data: 'store_id', name: 'store_id' },
            { data: 'bulan', name: 'bulan' },
            { data: 'tahun', name: 'tahun' },
            { data: 'total_store_target', name: 'total_store_target',render: $.fn.dataTable.render.number( '.', '.', 0, '' ) },
            { data: 'action', name: 'action', orderable: false }
        ]
    });

    $('#btnFiterSubmitSearch').click(function(){
        $('#table_data').DataTable().draw(true);
    });
    
    /*$('#GH').on('change',function(){
        if($(this).val() != ''){
            $.getJSON('{{ url('pos/dailyreportgh-store') }}' + "/" + $("#GH option:selected").val(), function(data) {
                var temp = [];
                $.each(data, function(key, value) {
                    temp.push({v:value, k: key});
                });
                $('#STORE').empty();
                $('#STORE').append('<option value="all">All</option>');   
                $.each(temp, function(key, obj) {
                $('#STORE').append('<option value="' + obj.k +'">' + obj.v + '</option>');           
                }); 
            });
        }
    });

    $('#GH').on('change',function(){
        $('#STORE').val('');
    });*/

    
});
</script>
@endsection