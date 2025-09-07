@if(isset($model) && isset($model->logs))
@foreach($model->logs as $log)
<div class="card mb-2">
    <div class="card-body p-2">
        <div class="row">
            <div class="col-md-1 d-flex align-items-center">
                <div style="width: 40px;height: 40px;" class="icon icon-shape bg-gradient-{{$log->action == 'add' || $log->action == 'success' ? 'success' : ($log->action == 'edit' ? 'warning' : ($log->action == 'remove' || $log->action == 'fail' ? 'danger' : 'secondary'))}} shadow text-center border-radius-md">
                    <i class="fa {{$log->action == 'add' ? 'fa-plus-circle' : ($log->action == 'edit' ? 'fa-pencil' : ($log->action == 'remove' ? 'fa-trash' : ($log->action == 'success' ? 'fa-thumbs-up' : ($log->action == 'fail' ? 'fa-thumbs-down' : 'fa-sticky-note'))))}} text-lg opacity-10" aria-hidden="true"></i>
                </div>
            </div>
            <div class="col-md-7 d-flex align-items-center">
                <p class="m-0">{!! \Helper::log_beautifier($log->data) !!}</p>
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <i style="font-size: 1rem;" class="fa fa-lg fa-{{$log->user_id > 0 ? 'user' : ($log->user_id == 0 ? 'cog' : 'user-o')}} ps-2 pe-2 text-center text-dark text-dark " aria-hidden="true"></i>
                <p class="m-0">{{$log->user ? $log->user->name.' '.$log->user->last_name : ($log->user_id == 0 ? __('message.systemic') : ($log->user_id == -1 ? __('message.customer') : ''))}}</p>
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <i style="font-size: 1rem;" class="fa fa-lg fa-calendar ps-2 pe-2 text-center text-dark text-dark " aria-hidden="true"></i>
                @php $d = $log->created_at; @endphp
                <p style="direction: ltr;" class="m-0">{{\Helper::gregorian_to_jalali($d->format('Y'), $d->format('m'), $d->format('d'), '/').' '.$d->format('H:i:s')}}</p>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
