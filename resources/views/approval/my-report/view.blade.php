@extends('layouts.approval')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{$judul}}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Approval</a></li>
          <li class="breadcrumb-item active">View Report</li>
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
                <div class="table-responsive">
                    <table id="table_data" class="cell-border" style="width:100%">
                        <thead>
                            <tr>
                            @foreach($data_column as $key => $data)
                                <th> {{ $data->field_name ?? '' }}</th>
                            @endforeach
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
$(document).ready(function(){

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    var columns = [];
    var data = {"kolom":<?php echo $arrData;?>};
    //var data = {"kolom":['id','nama','keterangan']};  //hardcode
     
    for (i in data.kolom) {
        columns.push({data: data.kolom[i], 
                    title: capitalizeFirstLetter(data.kolom[i])});
    }
    console.log(columns);

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('#table_data').DataTable({
        responsive: true,
        //ordering: false,
        paging: true,
        processing: true,
        serverSide: true,
        ajax: "{{ url('approval/my-report/get-report') }}"+'/'+'{{ $id }}',
        columns : columns
    });

    /*var columns = [];
    getDT();

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function getDT() {
    $.ajax({
        
      url: "{{ url('approval/my-report/get-column-report') }}"+'/'+'{{ $id }}',
      success: function (data) {
        
        // Parse the JSON response to a JS object
        data = JSON.parse(data);
        
        console.log(data.data[0]);  
        // Get the object keys from the first row of data
        // This might be different in a real world example
        // where specific column config data is returned
        columnNames = Object.keys(data.data[0]);
        
        // Iterate each of the columnNames to build the columns.data and columns.title optins
        for (var i in columnNames) {
          
          // Push the {data: ..., title: ...} onto array
          columns.push({data: columnNames[i], 
                    title: capitalizeFirstLetter(columnNames[i])});
        }
        
        // Once columns array is built init server side Datatables
	    $('#table_data').DataTable( {
		    processing: true,
		    serverSide: true,
            //ajax: "{{ url('approval/my-report/get-report') }}"+'/'+'{{ $id }}',
            ajax: {
            'url':'{{ url('approval/my-report/get-report') }}'+'/'+'{{ $id }}',
            'type': 'GET',
            'headers': {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
            },
		    columns: columns
	    } );
      }
    });
    }*/
});
</script>
@endsection