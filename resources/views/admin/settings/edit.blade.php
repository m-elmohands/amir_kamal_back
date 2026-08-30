@extends('admin.layout')
@section('title', __('Settings'))
@section('content')
    <form method="post" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="settings-layout">
            <div class="settings-main">
                <section class="card mb-4">
                    <div class="section-heading">
                        <div>
                            <span class="eyebrow">{{ __('Localized content') }}</span>
                            <h2>{{ __('Identity and SEO') }}</h2>
                        </div>
                    </div>
                    <div class="row g-4">
                        @foreach (['en' => __('English'), 'ar' => __('Arabic')] as $locale => $label)
                            @php($translation = $settings->translate($locale))
                            <fieldset class="col-12 col-xl-6" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
                                <legend class="form-section-title">{{ $label }}</legend>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Site name') }}</label>
                                    <input class="form-control" name="{{ $locale }}[site_name]"
                                           value="{{ old("{$locale}.site_name", $translation?->site_name) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Address') }}</label>
                                    <textarea class="form-control" name="{{ $locale }}[address]" rows="2">{{ old("{$locale}.address", $translation?->address) }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Footer description') }}</label>
                                    <textarea class="form-control" name="{{ $locale }}[footer_description]" rows="3">{{ old("{$locale}.footer_description", $translation?->footer_description) }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('SEO title') }}</label>
                                    <input class="form-control" name="{{ $locale }}[seo_title]"
                                           value="{{ old("{$locale}.seo_title", $translation?->seo_title) }}">
                                </div>
                                <div>
                                    <label class="form-label">{{ __('SEO description') }}</label>
                                    <textarea class="form-control" name="{{ $locale }}[seo_description]" rows="3">{{ old("{$locale}.seo_description", $translation?->seo_description) }}</textarea>
                                </div>
                            </fieldset>
                        @endforeach
                    </div>
                </section>

                <section class="card mb-4">
                    <div class="section-heading"><h2>{{ __('Contact information') }}</h2></div>
                    <div class="row g-3">
                        <div class="col-12 col-md-6"><label class="form-label">{{ __('Email address') }}</label><input class="form-control" type="email" name="email" value="{{ old('email', $settings->email) }}"></div>
                        <div class="col-12 col-md-6"><label class="form-label">{{ __('Phone') }}</label><input class="form-control" name="phone" value="{{ old('phone', $settings->phone) }}"></div>
                        <div class="col-12 col-md-6"><label class="form-label">{{ __('WhatsApp') }}</label><input class="form-control" name="whatsapp" value="{{ old('whatsapp', $settings->whatsapp) }}"></div>
                    </div>
                </section>

                <section class="card">
                    <div class="section-heading"><h2>{{ __('Social media') }}</h2></div>
                    <div class="row g-3">
                        @foreach (['facebook_url' => 'Facebook', 'instagram_url' => 'Instagram', 'linkedin_url' => 'LinkedIn', 'youtube_url' => 'YouTube'] as $field => $label)
                            <div class="col-12 col-md-6">
                                <label class="form-label">{{ $label }}</label>
                                <input class="form-control" type="url" name="{{ $field }}" value="{{ old($field, $settings->{$field}) }}" placeholder="https://">
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <aside class="settings-sidebar">
                <section class="card mb-4">
                    <div class="section-heading"><h2>{{ __('Brand assets') }}</h2></div>
                    <div class="mb-3">
                        <label class="form-label" for="logo">{{ __('Logo') }}</label>
                        @if ($logoUrl)
                            <img class="settings-logo-preview" src="{{ $logoUrl }}" alt="{{ __('Current logo') }}">
                        @endif
                        <input class="form-control" id="logo" type="file" name="logo" accept=".png,.jpg,.jpeg,.webp">
                        <div class="form-text">{{ __('Upload a PNG, JPG, or WebP logo up to 4 MB.') }}</div>
                    </div>
                    @if ($logoUrl)
                        <div class="form-check mb-3">
                            <input class="form-check-input" id="remove_logo" type="checkbox" name="remove_logo" value="1">
                            <label class="form-check-label" for="remove_logo">{{ __('Remove current logo') }}</label>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label" for="app_icon">{{ __('Favicon') }}</label>
                        @if ($iconUrl)
                            <img class="settings-icon-preview" src="{{ $iconUrl }}" alt="{{ __('Current app icon') }}">
                        @endif
                        <input class="form-control" id="app_icon" type="file" name="app_icon" accept=".png,.jpg,.jpeg,.webp,.ico">
                    </div>
                    @if ($iconUrl)
                        <div class="form-check">
                            <input class="form-check-input" id="remove_app_icon" type="checkbox" name="remove_app_icon" value="1">
                            <label class="form-check-label" for="remove_app_icon">{{ __('Remove current favicon') }}</label>
                        </div>
                    @endif
                </section>

                <section class="card mb-4 maintenance-card">
                    <div class="form-check form-switch">
                        <input class="form-check-input" id="maintenance_mode" type="checkbox" name="maintenance_mode" value="1" @checked(old('maintenance_mode', $settings->maintenance_mode))>
                        <label class="form-check-label" for="maintenance_mode">{{ __('Maintenance mode') }}</label>
                    </div>
                    <p class="muted mb-0 mt-2">{{ __('Show a maintenance notice instead of the public website.') }}</p>
                </section>

                <button class="btn btn-primary btn-lg w-100" type="submit">{{ __('Save settings') }}</button>
            </aside>
        </div>
    </form>
@endsection
