@extends('layouts.user_type.auth')

@section('content')

<div>
    @if(session('success'))
        <div class="m-3 alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
            <span class="alert-text text-white">
            {{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fa fa-close" aria-hidden="true"></i>
            </button>
        </div>
        @php Session::forget('success'); @endphp
    @endif
    @if(session('error'))
        <div class="m-3 alert alert-primary alert-dismissible fade show" role="alert">
            <span class="alert-text text-white">
            {{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fa fa-close" aria-hidden="true"></i>
            </button>
        </div>
        @php Session::forget('error'); @endphp
    @endif
    @if($errors->any())
        <div class="mt-3 alert alert-primary alert-dismissible fade show" role="alert">
            <span class="alert-text text-white">
            {{$errors->first()}}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fa fa-close" aria-hidden="true"></i>
            </button>
        </div>
    @endif

    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
        <div class="container-fluid py-4">
          <div class="row">
            <div class="col-12">
              <div class="card mb-4">
                <div class="card-header pb-3">
                    <div class="d-flex flex-row justify-content-between">
                        <h5 class="mb-0">{{ __('message.all_users') }}</h5>
                        <a href="{{ url('add_user') }}" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; {{ __('message.add_user') }}</a>
                    </div>
                </div>
                <div class="card-body px-3 pt-0 pb-3">
                  <div class="table-responsive p-0">
                    {!! $dataTable->table(['class' => 'datatable-new table align-items-center mb-0 display responsive nowrap'], true) !!}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </main>
</div>

<!-- Change User Permission Modal -->
<div class="modal fade" id="change-user-permission-modal" tabindex="-1" role="dialog" aria-labelledby="change-user-permission-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="change-user-permission-modal-label">{{ __('message.change_user_permission') }} <strong id="user-name"></strong></h5>
      </div>
      <div class="modal-body">
        <form id="change-user-permission-form" action="{{url('change_user_permission')}}" method="POST" role="form">
        @csrf
        <input type="hidden" id="user-id" name="id">
        <div class="row">
            <div class="form-group">
                <label for="change-user-permission-select" class="form-control-label">{{ __('message.permission') }}</label>
                <select id="change-user-permission-select" name="permission_id" class="form-control">
                    <option value="">-- {{__('message.select')}} --</option>
                    {!! $permissions->map(fn ($val) => '<option value="'.$val->id.'">'.$val->title.'</option>')->implode('') !!}
                </select>
            </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="change-user-permission-form" class="btn bg-gradient-primary">{{ __('message.update') }}</button>
        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">{{ __('message.close') }}</button>
      </div>
    </div>
  </div>
</div>

{!! $dataTable->scripts() !!}

<script type="text/javascript">
    function change_user_status(id, status){
        window.location.replace("/change_user_status/"+id+"/"+status);
    }

    function change_user_permission(id, permission, full_name){
        $('#user-name').text(full_name);
        $('#user-id').val(id);
        $('#change-user-permission-select').val(permission);
        $('#change-user-permission-modal').modal('show');
    }
</script>
@endsection
