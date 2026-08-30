<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Admin sign in') }} · {{ $adminSite['name'] }}</title>
    @if ($adminSite['favicon_url'])
        <link rel="icon" href="{{ $adminSite['favicon_url'] }}">
    @endif
    @vite(['resources/css/app.css'])
</head>
<body class="login-body">
    <main class="login-shell">
        <section class="login-visual" aria-label="{{ $adminSite['name'] }}">
            <a class="login-brand" href="{{ url('/') }}">
                @if ($adminSite['logo_url'])
                    <img class="admin-brand-logo" src="{{ $adminSite['logo_url'] }}" alt="">
                @else
                    <span class="brand-mark" aria-hidden="true">{{ $adminSite['initial'] }}</span>
                @endif
                <span>{{ $adminSite['name'] }}</span>
            </a>

            <div class="login-message">
                <span class="eyebrow">{{ __('Control center') }}</span>
                <h1>{{ __('Built for precise operations.') }}</h1>
                <p>{{ __('Manage your catalog, brand content, and customer inquiries from one focused workspace.') }}</p>
            </div>

            <div class="industrial-orbit" aria-hidden="true">
                <span></span>
            </div>
        </section>

        <section class="login-panel">
            <form class="login-form" method="post" action="{{ route('admin.login.store') }}">
                @csrf
                <div class="login-heading">
                    <span class="eyebrow">{{ __('Secure access') }}</span>
                    <h2>{{ __('Welcome back') }}</h2>
                    <p>{{ __('Sign in with your administrator credentials.') }}</p>
                </div>

                <div class="field">
                    <label for="email">{{ __('Email address') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           autocomplete="email" required autofocus>
                </div>

                <div class="field">
                    <label for="password">{{ __('Password') }}</label>
                    <input id="password" type="password" name="password"
                           autocomplete="current-password" required>
                </div>

                @error('email')
                    <div class="toast danger" role="alert">{{ $message }}</div>
                @enderror

                <button class="login-submit" type="submit">{{ __('Sign in') }}</button>
            </form>
        </section>
    </main>
</body>
</html>
