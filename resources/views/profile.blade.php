@extends('layouts.user_type.auth')

@section('content')

<div>
    <form action="/edit_profile" method="POST" role="form" enctype="multipart/form-data">
    @csrf
    <div class="container-fluid">
        <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('../assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
            <span class="mask bg-gradient-primary opacity-6"></span>
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{asset('assets/img/avatar.png')}}" class="w-100 border-radius-lg shadow-sm">
                        <a href="javascript:;" class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2">
                            <i class="fa fa-pen top-0"></i>
                        </a>
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ auth()->user()->full_name }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{ auth()->user()->permission?->title }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        @if($errors->any())
            <div class="mt-1 alert alert-primary alert-dismissible fade show" role="alert">
                <span class="alert-text text-white">
                {{$errors->first()}}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="fa fa-close" aria-hidden="true"></i>
                </button>
            </div>
        @endif
        @if(session('success'))
            <div class="mt-1 alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                <span class="alert-text text-white">
                {{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="fa fa-close" aria-hidden="true"></i>
                </button>
            </div>
        @endif
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('message.profile_information') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="name" class="form-control-label">{{ __('message.name') }}</label>
                            <input readonly class="form-control" value="{{ $user->name }}" type="text" placeholder="{{__('message.name')}}" id="name" name="name">
                            @error('name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="last_name" class="form-control-label">{{ __('message.last_name') }}</label>
                            <input readonly class="form-control" value="{{ $user->last_name }}" type="text" placeholder="{{__('message.last_name')}}" id="last_name" name="last_name">
                            @error('last_name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="mobile" class="form-control-label">{{ __('message.mobile') }}</label>
                            <input style="direction: ltr;" onblur="if(this.value && !final_numeric_check(this.value)){$(this).val('')}" onkeypress="return numeric_check(event);" class="form-control" type="text" pattern="[0][9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]" placeholder="091..." id="mobile" name="mobile" value="{{ auth()->user()->mobile }}">
                            @error('mobile')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="email" class="form-control-label">{{ __('message.email') }}</label>
                            <input style="direction: ltr;" class="form-control" value="{{ auth()->user()->email }}" type="email" placeholder="@example.com" id="email" name="email">
                            @error('email')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="password" class="form-control-label">{{ __('message.new_password') }}</label>
                            <input style="direction: ltr;" class="form-control" value="" type="text" placeholder="{{__('message.password')}}" id="password" name="password">
                            @error('password')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn bg-gradient-dark btn-md mt-4">{{ __('message.save') }}</button>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

@endsection