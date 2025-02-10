@extends('layouts.approval')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Document Notes</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Approval</a></li>
          <li class="breadcrumb-item"><a href="#">Document</a></li>
          <li class="breadcrumb-item active">Notes</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
@if (isset($datanya))
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table_data" class="cell-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>Aksi</th>
                                <th>Tanggal</th>
                                <th>Document ID</th>
                                <th>Dari</th>
                                <th>Notes</th>
                                <th>Tanggal dibaca</th>
                                <th>Dibaca oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datanya as $key => $data)
                            <tr>
                                @if($data->read_by == NULL)
                                <td><a href="#" class="btn btn-info btn-sm" onclick="return bacaNotif('{{$data->id}}');">Read</a></td>
                                @else
                                <td><a href="#" class="btn btn-info btn-sm disabled">Read</a></td>
                                @endif
                                <td> {{ $data->tgl ?? '' }}</td>
                                <td> {{ $data->document_id ?? '' }}</td>
                                <td> {{ $data->pembuat ?? '' }}</td>
                                <td> {{ $data->notes ?? '' }}</td>
                                <td> {{ $data->read_date ?? '' }}</td>
                                <td> {{ $data->read_by ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                There Are No Any Notes Here :)
            </div>
        </div>
    </div>
</div>
@endif

@endsection
@section('scripts')
@parent
<script>
$(document).ready(function(){

    function bacaNotif(id){
        if(!confirm("Anda yakin sudah membaca notes ini?")){
            event.preventDefault();
        }else{
            var spinner = $('#loader');
            spinner.show();
            var form_data = new FormData();
            var action_url = "{{ url('approval/notes/read') }}";
            var result_msg = "Succesfully";
            form_data.append('id', id);
            form_data.append('_token', '{{csrf_token()}}');
            $.ajax({
                    url: action_url,
                    method:"POST",
                    data:form_data,
                    contentType: false,
                    processData: false,
                    success:function(data)
                    {
                        if(data.errors){
                            spinner.hide();
                            alert(data.errors);
                        }

                        if(data.success){
                            alert(data.success);
                            location.reload();
                        }
                    },
                    error: function(data){
                        console.log(data.responseJSON);
                        spinner.hide();
                        alert("gagal proses membaca notes, hubungi IT");
                    }
            });
        }
    }

    $('#table_data').DataTable({
        ordering: true,
        responsive: true,
        paging: true
    });
});
</script>
@endsection