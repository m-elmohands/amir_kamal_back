<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') · {{ $adminSite['name'] }}</title>
    @if ($adminSite['favicon_url'])
        <link rel="icon" href="{{ $adminSite['favicon_url'] }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="sidebar">
            <a class="admin-brand" href="{{ route('admin.dashboard') }}">
                @if ($adminSite['logo_url'])
                    <img class="admin-brand-logo" src="{{ $adminSite['logo_url'] }}" alt="">
                @else
                    <span class="brand-mark" aria-hidden="true">{{ $adminSite['initial'] }}</span>
                @endif
                <span>
                    {{ $adminSite['name'] }}
                    <small>{{ __('Control center') }}</small>
                </span>
            </a>

            <nav class="admin-nav" aria-label="{{ __('Admin navigation') }}">
                @foreach ([
                    ['admin.dashboard', __('Overview')],
                    ['admin.products.index', __('Products')],
                    ['admin.categories.index', __('Categories')],
                    ['admin.brands.index', __('Brands')],
                    ['admin.inquiries.index', __('Inquiries')],
                    ['admin.settings.edit', __('Settings')],
                ] as [$route, $label])
                    @php
                        $routePattern = str_replace('.index', '.*', $route);
                        $isActive = request()->routeIs($route) || request()->routeIs($routePattern);
                    @endphp
                    <a class="{{ $isActive ? 'active' : '' }}" href="{{ route($route) }}"
                       @if ($isActive) aria-current="page" @endif>
                        <span class="nav-icon" aria-hidden="true">{{ mb_substr($label, 0, 1) }}</span>
                        {{ $label }}
                    </a>
                @endforeach
            </nav>

            <div class="sidebar-foot">
                <div class="user-chip">
                    <span aria-hidden="true">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                    <div>
                        <strong>{{ auth()->user()->name }}</strong>
                        <small>{{ auth()->user()->email }}</small>
                    </div>
                </div>
            </div>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <button class="mobile-nav-toggle icon-button" type="button" data-sidebar-toggle
                        aria-label="{{ __('Open navigation') }}" aria-expanded="false">☰</button>
                <div>
                    <p class="breadcrumb">{{ __('Workspace') }} / @yield('title')</p>
                    <h1>@yield('title')</h1>
                </div>
                <div class="top-actions">
                    <form method="post" action="{{ route('admin.locale') }}">
                        @csrf
                        <input type="hidden" name="locale" value="{{ app()->isLocale('ar') ? 'en' : 'ar' }}">
                        <button class="icon-button" type="submit" aria-label="{{ __('Switch language') }}">
                            {{ app()->isLocale('ar') ? 'EN' : 'ع' }}
                        </button>
                    </form>
                    <form method="post" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="secondary-button" type="submit">{{ __('Sign out') }}</button>
                    </form>
                </div>
            </header>

            <main class="content">
                @if (session('success'))
                    <div class="toast success" role="status">✓ {{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="toast danger" role="alert">{{ $errors->first() }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <div class="sidebar-backdrop" data-sidebar-dismiss></div>

    <div class="modal fade" id="remote-crud-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" data-remote-modal-title></h2>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body" data-remote-modal-body>
                    <div class="modal-loading">{{ __('Loading…') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-confirmation-modal" tabindex="-1" aria-labelledby="delete-confirmation-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="delete-confirmation-title">{{ __('Confirm deletion') }}</h2>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('This record will be moved to trash and can be recovered later.') }}</p>
                    <strong data-delete-record-name></strong>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <form method="post" data-delete-form>
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">{{ __('Move to trash') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
