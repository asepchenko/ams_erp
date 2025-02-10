@extends('layouts.admin')
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
          <li class="breadcrumb-item"><a href="#">Admin</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Default box -->
<div class="card card-solid">
        <div class="card-body pb-0">
          <div class="row d-flex align-items-stretch">

            

            @can('approval_app_access')
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
              <div class="card bg-light">
                <div class="card-header text-muted border-bottom-0">
                  Approval
                </div>
                <div class="card-body pt-0">
                  <div class="row">
                    <div class="col-7">
                      <h2 class="lead"><b>Approval System</b></h2>
                      <p class="text-muted text-sm">App for approve document. Used by GA, TAX, Audit, Accounting and Finance Departemen</p>
                    </div>
                    <div class="col-5 text-center">
                      <img src="{{ asset('img/approval.png') }}" alt="" class="img-circle img-fluid">
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    <a href="{{ route("approval.dashboard.index") }}" class="btn btn-sm btn-info">
                      <i class="fas fa-arrow-alt-circle-right"></i> Open App
                    </a>
                  </div>
                </div>
              </div>
            </div>
            @endcan

            @can('finance_app_access')
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
              <div class="card bg-light">
                <div class="card-header text-muted border-bottom-0">
                  Finance
                </div>
                <div class="card-body pt-0">
                  <div class="row">
                    <div class="col-7">
                      <h2 class="lead"><b>Finance</b></h2>
                      <p class="text-muted text-sm">App for Finance Departement only</p>
                    </div>
                    <div class="col-5 text-center">
                      <img src="{{ asset('img/finance.png') }}" alt="" class="img-circle img-fluid">
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    <a href="{{ route("finance.dashboard.index") }}" class="btn btn-sm btn-info">
                      <i class="fas fa-arrow-alt-circle-right"></i> Open App
                    </a>
                  </div>
                </div>
              </div>
            </div>
            @endcan

            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
              <div class="card bg-light">
                <div class="card-header text-muted border-bottom-0">
                GA System
                </div>
                <div class="card-body pt-0">
                  <div class="row">
                    <div class="col-7">
                      <h2 class="lead"><b>GA System</b></h2>
                      <p class="text-muted text-sm">App For Request, Purchase and Working Order</p>
                    </div>
                    <div class="col-5 text-center">
                      <img src="{{ asset('img/file_manager.png') }}" alt="" class="img-circle img-fluid">
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    <a href="{{ route("gasystem.dashboard.index") }}" class="btn btn-sm btn-info">
                      <i class="fas fa-arrow-alt-circle-right"></i> Open App
                    </a>
                  </div>
                </div>
              </div>
            </div>
             <!-- Budgeting System -->
            
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
              <div class="card bg-light">
                <div class="card-header text-muted border-bottom-0">
                  SAP
                </div>
                <div class="card-body pt-0">
                  <div class="row">
                    <div class="col-7">
                      <h2 class="lead"><b>System Anggaran Perusahaan</b></h2>
                      <p class="text-muted text-sm">System perencanaan dan controlling pengeluaran anggaran tiap dept perusahaan.</p>
                    </div>
                    <div class="col-5 text-center">
                      <img src="{{ asset('img/budgeting.png') }}" alt="" class="img-circle img-fluid">
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    <a href="{{ route("budget.dashboard.index") }}" class="btn btn-sm btn-info">
                      <i class="fas fa-arrow-alt-circle-right"></i> Open App
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <!-- end budgeting system-->

            {{-- @can('pos_app_access')
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
              <div class="card bg-light">
                <div class="card-header text-muted border-bottom-0">
                  Point of Sales
                </div>
                <div class="card-body pt-0">
                  <div class="row">
                    <div class="col-7">
                      <h2 class="lead"><b>POS System</b></h2>
                      <p class="text-muted text-sm">App for view reporting of POS System </p>
                    </div>
                    <div class="col-5 text-center">
                      <img src="{{ asset('img/pos.png') }}" alt="" class="img-circle img-fluid">
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    <a href="{{ route("pos.dashboard.index") }}" class="btn btn-sm btn-info">
                      <i class="fas fa-arrow-alt-circle-right"></i> Open App
                    </a>
                  </div>
                </div>
              </div>
            </div>
            @endcan

            @can('ticketing_app_access')
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
              <div class="card bg-light">
                <div class="card-header text-muted border-bottom-0">
                  Ticketing
                </div>
                <div class="card-body pt-0">
                  <div class="row">
                    <div class="col-7">
                      <h2 class="lead"><b>Ticketing/Help Desk System</b></h2>
                      <p class="text-muted text-sm">App for submit a ticket</p>
                    </div>
                    <div class="col-5 text-center">
                      <img src="{{ asset('img/ticketing.png') }}" alt="" class="img-circle img-fluid">
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    <a href="{{ route("ticketing.dashboard.index") }}" class="btn btn-sm btn-info">
                      <i class="fas fa-arrow-alt-circle-right"></i> Open App
                    </a>
                  </div>
                </div>
              </div>
            </div>
            @endcan
            
            @can('crm_app_access')
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
              <div class="card bg-light">
                <div class="card-header text-muted border-bottom-0">
                  CRM
                </div>
                <div class="card-body pt-0">
                  <div class="row">
                    <div class="col-7">
                      <h2 class="lead"><b>Customer Relation Management</b></h2>
                      <p class="text-muted text-sm">App for manage our customer (member) and promotion</p>
                    </div>
                    <div class="col-5 text-center">
                      <img src="{{ asset('img/member.png') }}" alt="" class="img-circle img-fluid">
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    <a href="{{ route("crm.dashboard.index") }}" class="btn btn-sm btn-info">
                      <i class="fas fa-arrow-alt-circle-right"></i> Open App
                    </a>
                  </div>
                </div>
              </div>
            </div>
            @endcan --}}
           
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
@endsection