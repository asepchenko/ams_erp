@extends('layouts.library')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Dashboard</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">E-Library</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header" id="headingOne">
                <span class="float-md-left"><h5>Folder</h5></span>
                <span class="float-md-right">
                    <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="fa" aria-hidden="true"></i>
                    </button>
                    </h5>
                </span>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <ul data-widget="tree">
                        <li><a href="#">One Level</a></li>
                        <li class="treeview">
                            <a href="#">Multilevel</a>
                            <ul class="treeview-menu">
                            <li><a href="#">Level 2</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>File List</h5>
            </div>
            <div class="card-body">

            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
$(document).ready(function() {
    $('ul').on('expanded.tree', handleExpandedEvent);
});
</script>
@endsection