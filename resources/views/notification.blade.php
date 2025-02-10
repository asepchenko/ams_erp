@if(isset($data_notification))
    @foreach($data_notification as $data_notif)
    <div class="alert alert-{{ $data_notif->tipe }} alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-exclamation-triangle"></i> {{ $data_notif->judul }}</h5>
        {{ $data_notif->isi }}
    </div>
    @endforeach
@endif