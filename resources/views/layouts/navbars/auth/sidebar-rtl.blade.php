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
      @can('permissions', 'advertisements')
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('advertisements') ? 'active' : '') }}" href="{{ url('advertisements') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fa-solid fa-bullhorn ps-2 pe-2 text-center text-dark {{ (Request::is('advertisements') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text me-1">{{ __('message.advertisements') }}</span>
        </a>
      </li>
      @endcan
      @can('permissions', 'resumes')
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('resumes') ? 'active' : '') }}" href="{{ url('resumes') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fa-solid fa-memo ps-2 pe-2 text-center text-dark {{ (Request::is('resumes') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text me-1">{{ __('message.resumes') }}</span>
        </a>
      </li>
      @endcan
      @can('permissions', 'calendars')
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('calendars') ? 'active' : '') }}" href="{{ url('calendars') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fa-solid fa-calendar-check ps-2 pe-2 text-center text-dark {{ (Request::is('calendars') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text me-1">{{ __('message.calendars') }}</span>
        </a>
      </li>
      @endcan
      @can('permissions', 'categories')
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('categories') ? 'active' : '') }}" href="{{ url('categories') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fa-solid fa-sitemap ps-2 pe-2 text-center text-dark {{ (Request::is('categories') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text me-1">{{ __('message.categories') }}</span>
        </a>
      </li>
      @endcan
      @can('permissions', 'steps')
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('steps') ? 'active' : '') }}" href="{{ url('steps') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fa-solid fa-list-check ps-2 pe-2 text-center text-dark {{ (Request::is('steps') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text me-1">{{ __('message.steps') }}</span>
        </a>
      </li>
      @endcan
      @can('permissions', 'sms_patterns')
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('sms_patterns') ? 'active' : '') }}" href="{{ url('sms_patterns') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fa-solid fa-comment-dots ps-2 pe-2 text-center text-dark {{ (Request::is('sms_patterns') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text me-1">{{ __('message.sms_patterns') }}</span>
        </a>
      </li>
      @endcan
      @can('permissions', 'email_patterns')
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('email_patterns') ? 'active' : '') }}" href="{{ url('email_patterns') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fa-solid fa-envelope ps-2 pe-2 text-center text-dark {{ (Request::is('email_patterns') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text me-1">{{ __('message.email_patterns') }}</span>
        </a>
      </li>
      @endcan
      @can('permissions', 'permissions')
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('permissions') ? 'active' : '') }}" href="{{ url('permissions') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fa-solid fa-shield-halved ps-2 pe-2 text-center text-dark {{ (Request::is('permissions') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text me-1">{{ __('message.permissions') }}</span>
        </a>
      </li>
      @endcan
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('profile') ? 'active' : '') }}" href="{{ url('profile') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fas fa-lg fa-user-circle ps-2 pe-2 text-center text-dark {{ (Request::is('profile') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text me-1">{{ __('message.profile') }}</span>
        </a>
      </li>
      @can('permissions', 'users')
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
