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
                    <h5 class="mb-0">{{ __('message.all_cases') }}</h5>
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

{!! $dataTable->scripts() !!}

@endsection
