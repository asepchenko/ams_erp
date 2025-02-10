@extends('layouts.library')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">File</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">E-Library</a></li>
          <li class="breadcrumb-item active">File</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="row">
<div class="col-md-3">
    <div class="card">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="file-manager">
                    <div class="hr-line-dashed"></div>
                    <button class="btn btn-info btn-block">Upload File</button>
                    <div class="hr-line-dashed"></div>
                    <h5>Category</h5>
                    <ul class="folder-list" style="padding: 0">
                        @foreach($category as $key => $ctg)
                            <li><a href="#" onclick="return reloadData('{{$ctg->kategori}}');"><i class="fa fa-folder"></i> {{ $ctg->kategori}}</a></li>
                        @endforeach
                    </ul>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div> <!-- card -->
</div>

    <div class="col-md-9 animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                    <input type="hidden" name="kategorinya" id="kategorinya" value="{{ $kategori}}"/>
                    <input type="hidden" name="lokasinya" id="lokasinya" value="{{ $lokasi}}"/>
                    @if (isset($datanya))
                        <div class="table-responsive">
                            <table id="data_file" class="display compact" style="width:100%">
                                <thead>
                                    <tr>
                                    <th>Tipe</th>
                                    <th>Lokasi</th>
                                    <th>Nama</th>
                                    <th>Keterangan</th>
                                    <th>Dipinjam ?</th>
                                    <th>Ada Fisik ?</th>
                                    <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datanya as $key => $data)
                                        <tr>
                                            <td> {{ $data->tipe ?? '' }}</td>
                                            <td> {{ $data->lokasi ?? '' }}</td>
                                            <td> {{ $data->nama_file ?? '' }}</td>
                                            <td> {{ $data->keterangan ?? '' }}</td>
                                            <td> {{ $data->is_pinjam ?? '' }}</td>
                                            <td> {{ $data->ada_fisik ?? '' }}</td>
                                            <td> </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <h5>Please select category</h5>
                    @endif
                    </div> <!-- card body-->
                </div> <!-- card -->
            </div>
        </div> <!-- row -->
    </div>
    
</div>
@endsection
@section('scripts')
@parent
<script>
function reloadData(kategori){
    var spinner = $('#loader');
    var lokasi = $("#lokasinya").val();
    spinner.show();
    window.location.href = "{{ url('library/file/master') }}"+ "/" + kategori + "/" + lokasi + "";
}

$(document).ready(function(){
    $('#data_file').DataTable({
        paging: true,
        responsive: true
    });
});
</script>
@endsection