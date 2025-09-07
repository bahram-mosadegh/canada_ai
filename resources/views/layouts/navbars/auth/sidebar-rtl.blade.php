<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-end me-3 rotate-caret noprint" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute start-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="/">
        <img src="{{asset('assets/img/logo-ct.png')}}" class="navbar-brand-img h-100" alt="...">
        <span class="me-3 font-weight-bold"><h5>{{ __('message.site_name') }}</h5></span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <div class="collapse navbar-collapse px-0 w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link position-relative {{ (Request::is('check_resumes') ? 'active' : '') }}" href="{{ url('check_resumes') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fa-solid fa-memo-circle-check ps-2 pe-2 text-center text-dark {{ (Request::is('check_resumes') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text me-1">{{ __('message.check_resumes') }}</span>
            <div class="position-absolute end-0" id="check_resumes_notif"></div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('profile') ? 'active' : '') }}" href="{{ url('profile') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fas fa-lg fa-user-circle ps-2 pe-2 text-center text-dark {{ (Request::is('profile') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text me-1">{{ __('message.profile') }}</span>
        </a>
      </li>
      @can('users')
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('users') ? 'active' : '') }}" href="{{ url('users') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fas fa-lg fa-user ps-2 pe-2 text-center text-dark {{ (Request::is('users') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text me-1">{{ __('message.users') }}</span>
        </a>
      </li>
      @endcan
    </ul>
  </div>
  
</aside>
