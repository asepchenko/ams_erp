@extends('layouts.admin')
@section('content')
<div class="card">


    <div class="card-body">
        <!-- Timelime example  -->
        <div class="row">
          <div class="col-md-12">
            <!-- The time line -->
            <div class="timeline">
              <!-- timeline time label -->
              <div class="time-label">
                <span class="bg-red">19 Nov. 2019</span>
              </div>
              <!-- /.timeline-label -->
              <!-- timeline item -->
              <div>
                <i class="fas fa-envelope bg-blue"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                  <h3 class="timeline-header">Inspect Bahan</h3>

                  <div class="timeline-body">
                    ada kendala di proses ABC
                  </div>
                  <div class="timeline-footer">
                    <a class="btn btn-primary btn-sm">Info</a>
                  </div>
                </div>
              </div>
              <!-- END timeline item -->
              <!-- timeline item -->
              <div>
                <i class="fas fa-user bg-green"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fas fa-clock"></i> 5 mins ago</span>
                  <h3 class="timeline-header no-border">Pak ABC (Manager XYZ) - Approve</h3>
                </div>
              </div>
              <!-- END timeline item -->
              <!-- timeline item
              <div>
                <i class="fas fa-comments bg-yellow"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fas fa-clock"></i> 27 mins ago</span>
                  <h3 class="timeline-header">Approve</h3>
                  <div class="timeline-body">
                    
                  </div>
                </div>
              </div>
              END timeline item -->
              <!-- timeline time label -->
              <div class="time-label">
                <span class="bg-green">10 Nov. 2019</span>
              </div>
              <!-- /.timeline-label -->
              <!-- timeline item -->
              <div>
                <i class="fa fa-camera bg-purple"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fas fa-clock"></i> 2 days ago</span>
                  <h3 class="timeline-header">Sample Room</h3>
                  <div class="timeline-body">
                    <img src="http://placehold.it/150x100" alt="...">
                    <img src="http://placehold.it/150x100" alt="...">
                    <img src="http://placehold.it/150x100" alt="...">
                    <img src="http://placehold.it/150x100" alt="...">
                    <img src="http://placehold.it/150x100" alt="...">
                  </div>
                </div>
              </div>
              <!-- END timeline item -->
              <div class="time-label">
                <span class="bg-green">1 Nov. 2019</span>
              </div>
              <!-- timeline item -->
              <div>
                <i class="fa fa-camera bg-purple"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fas fa-clock"></i> Test</span>
                  <h3 class="timeline-header">Open PBO</h3>
                  <div class="timeline-body">

                  </div>
                </div>
              </div>
              <!-- END timeline item -->
              <div>
                <i class="fas fa-clock bg-gray"></i>
              </div>
            </div>
          </div>
    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection