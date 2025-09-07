@extends('layouts.user_type.auth')

@section('content')

<div class="container-fluid py-4">
    @if($errors->any())
        <div class="mt-3 alert alert-primary alert-dismissible fade show" role="alert">
            <span class="alert-text text-white">
            {{$errors->first()}}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fa fa-close" aria-hidden="true"></i>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="mt-3 alert alert-primary alert-dismissible fade show" role="alert">
            <span class="alert-text text-white">
            {{session('error')}}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fa fa-close" aria-hidden="true"></i>
            </button>
        </div>
    @endif
    @if(session('success'))
        <div class="mt-3 alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
            <span class="alert-text text-white">
            {{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fa fa-close" aria-hidden="true"></i>
            </button>
        </div>
    @endif
    <div class="card">
        <div class="card-header pb-0 px-3">
            <h6 class="mb-0">{{ __('message.add_user') }}</h6>
        </div>
        <div class="card-body pt-4 p-3">
            <form action="/add_user" method="POST" role="form text-left">
                @csrf
                <div class="row">
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="nilgam_id" class="form-control-label">{{ __('message.nilgam_id') }}</label>
                            <input autocomplete="off" class="form-control" id="nilgam_id" value="{{old('nilgam_id')}}" type="text" placeholder="{{ __('message.nilgam_id') }}" name="nilgam_id">
                            @error('nilgam_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="name" class="form-control-label">{{ __('message.name') }}</label>
                            <input required autocomplete="off" class="form-control" id="name" value="{{old('name')}}" type="text" placeholder="{{ __('message.name') }}" name="name">
                            @error('name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="last_name" class="form-control-label">{{ __('message.last_name') }}</label>
                            <input required autocomplete="off" class="form-control" id="last_name" value="{{old('last_name')}}" type="text" placeholder="{{ __('message.last_name') }}" name="last_name">
                            @error('last_name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="mobile" class="form-control-label">{{ __('message.mobile') }}</label>
                            <input required style="direction: ltr;" onblur="if(this.value && !final_numeric_check(this.value)){$(this).val('')}" onkeypress="return numeric_check(event);" class="form-control" type="text" pattern="[0][9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]" placeholder="091..." id="mobile" name="mobile" value="{{old('mobile')}}">
                            @error('mobile')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="email" class="form-control-label">{{ __('message.email') }}</label>
                            <input style="direction: ltr;" class="form-control" value="{{old('email')}}" type="email" placeholder="@example.com" id="email" name="email">
                            @error('email')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="password" class="form-control-label">{{ __('message.password') }}</label>
                            <input required style="direction: ltr;" type="text" class="form-control" value="{{old('password')}}" type="text" placeholder="{{__('message.password')}}" id="password" name="password">
                            @error('password')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="permission_id" class="form-control-label">{{ __('message.permission') }}</label>
                            <select class="form-control" name="permission_id" id="permission_id">
                                <option value="">-- {{__('message.select')}} --</option>
                                {!! $permissions->map(fn ($val) => '<option value="'.$val->id.'" '.(old('permission_id') == $val->id ? 'selected' : '').'>'.$val->title.'</option>')->implode('') !!}
                            </select>
                            @error('permission_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="active" class="form-control-label">{{ __('message.status') }}</label>
                            <select required class="form-control" name="active" id="active">
                                <option value="1" {{old('active') == '1' ? 'selected' : ''}}>{{ __('message.active') }}</option>
                                <option value="0" {{old('active') == '0' ? 'selected' : ''}}>{{ __('message.inactive') }}</option>
                            </select>
                            @error('active')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    <button type="submit" class="btn bg-gradient-dark btn-md mb-2">{{ __('message.add_user') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
