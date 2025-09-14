@extends('layouts.user_type.auth')

@section('content')
<div class="row justify-content-center mb-4">
    <div class="col-md-8">
        @livewire('case-intake')
        @livewireScripts
    </div>
</div>
@endsection


