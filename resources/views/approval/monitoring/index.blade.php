@extends('layouts.approval')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Monitoring Status</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Approval</a></li>
            <li class="breadcrumb-item active"><a href="#">Monitoring Document Document</a></li>
        </ol>
        </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="card">
<div class="card-body">
  <form id="searhform" name="searchform" method="post" action="{{ route("approval.monitoring.search") }}">
    <div class="input-group">
    @csrf
      <input type="text" id="searchtext" name="searchtext" class="form-control" placeholder="ketikan no document...">
      <div class="input-group-append">
        <button class="btn btn-secondary" type="submit">
          <i class="fa fa-search"></i>
        </button>
      </div>
    </div>
  </form>
</div>
</div>

@endsection
@section('scripts')
@parent
@endsection