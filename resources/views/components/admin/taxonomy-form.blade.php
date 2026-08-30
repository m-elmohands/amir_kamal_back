@php
    $isCategory = $type === 'category';
    $routeName = $isCategory ? 'admin.categories' : 'admin.brands';
    $model = $item;
@endphp

<form method="post" action="{{ $model->exists ? route("{$routeName}.update", $model) : route("{$routeName}.store") }}">
    @csrf
    @if ($model->exists)
        @method('PUT')
    @endif

    <div class="row g-3">
        @foreach (['en' => __('English'), 'ar' => __('Arabic')] as $locale => $label)
            @php($translation = $model->translate($locale))
            <fieldset class="col-12 col-xl-6" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
                <legend class="form-section-title">{{ $label }}</legend>
                <div class="mb-3">
                    <label class="form-label" for="{{ $type }}-{{ $model->id ?: 'new' }}-{{ $locale }}-name">{{ __('Name') }}</label>
                    <input class="form-control" id="{{ $type }}-{{ $model->id ?: 'new' }}-{{ $locale }}-name"
                           name="{{ $locale }}[name]" value="{{ old("{$locale}.name", $translation?->name) }}"
                           @required($locale === 'en')>
                </div>
                <div>
                    <label class="form-label" for="{{ $type }}-{{ $model->id ?: 'new' }}-{{ $locale }}-description">{{ __('Short description') }}</label>
                    <textarea class="form-control" id="{{ $type }}-{{ $model->id ?: 'new' }}-{{ $locale }}-description"
                              name="{{ $locale }}[description]" rows="3">{{ old("{$locale}.description", $translation?->description) }}</textarea>
                </div>
            </fieldset>
        @endforeach

        <div class="col-12 col-md-6">
            <label class="form-label" for="{{ $type }}-{{ $model->id ?: 'new' }}-slug">{{ __('Slug') }}</label>
            <input class="form-control" id="{{ $type }}-{{ $model->id ?: 'new' }}-slug"
                   name="slug" value="{{ old('slug', $model->slug) }}" required>
        </div>

        @if ($isCategory)
            <div class="col-12 col-md-6">
                <label class="form-label" for="category-{{ $model->id ?: 'new' }}-image">{{ __('Image URL') }}</label>
                <input class="form-control" id="category-{{ $model->id ?: 'new' }}-image"
                       type="url" name="image_url" value="{{ old('image_url', $model->image_url) }}">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="category-{{ $model->id ?: 'new' }}-order">{{ __('Sort order') }}</label>
                <input class="form-control" id="category-{{ $model->id ?: 'new' }}-order"
                       type="number" min="0" name="sort_order" value="{{ old('sort_order', $model->sort_order ?? 0) }}">
            </div>
        @else
            <div class="col-12 col-md-6">
                <label class="form-label" for="brand-{{ $model->id ?: 'new' }}-logo">{{ __('Logo URL') }}</label>
                <input class="form-control" id="brand-{{ $model->id ?: 'new' }}-logo"
                       type="url" name="logo_url" value="{{ old('logo_url', $model->logo_url) }}">
            </div>
        @endif

        <div class="col-12 d-flex align-items-end">
            <div class="form-check form-switch">
                <input class="form-check-input" id="{{ $type }}-{{ $model->id ?: 'new' }}-active"
                       type="checkbox" name="is_active" value="1" @checked(old('is_active', $model->exists ? $model->is_active : true))>
                <label class="form-check-label" for="{{ $type }}-{{ $model->id ?: 'new' }}-active">{{ __('Active') }}</label>
            </div>
        </div>
    </div>

    <div class="modal-footer px-0 pb-0 mt-4">
        <button class="btn btn-light" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{ __($model->exists ? 'Update' : 'Create') }}</button>
    </div>
</form>
