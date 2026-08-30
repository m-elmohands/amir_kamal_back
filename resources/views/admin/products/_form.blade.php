<form method="post" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
    @csrf
    @if ($product->exists)
        @method('PUT')
    @endif

    <div class="row g-4">
        @foreach (['en' => __('English'), 'ar' => __('Arabic')] as $locale => $label)
            <fieldset class="col-12 col-xl-6" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
                <legend class="form-section-title">{{ $label }}</legend>
                @php($translation = $product->translate($locale))
                <div class="mb-3">
                    <label class="form-label">{{ __('Name') }}</label>
                    <input class="form-control" name="{{ $locale }}[name]" value="{{ old("$locale.name", $translation?->name) }}" @required($locale === 'en')>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Summary') }}</label>
                    <textarea class="form-control" name="{{ $locale }}[summary]" rows="2" @required($locale === 'en')>{{ old("$locale.summary", $translation?->summary) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Description') }}</label>
                    <textarea class="form-control" name="{{ $locale }}[description]" rows="4" @required($locale === 'en')>{{ old("$locale.description", $translation?->description) }}</textarea>
                </div>
                <div>
                    <label class="form-label">{{ __('Specifications') }} <small class="text-body-secondary">{{ __('One “Label: Value” per line') }}</small></label>
                    <textarea class="form-control" name="{{ $locale }}[specifications_text]" rows="3">{{ old("$locale.specifications_text", collect($translation?->specifications)->map(fn ($value, $key) => "{$key}: {$value}")->implode("\n")) }}</textarea>
                </div>
            </fieldset>
        @endforeach
    </div>

    <hr class="my-4">

    <div class="row g-3">
        <div class="col-12 col-md-6">
            <label class="form-label">{{ __('Slug') }}</label>
            <input class="form-control" name="slug" value="{{ old('slug', $product->slug) }}" required>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label">{{ __('SKU') }}</label>
            <input class="form-control" name="sku" value="{{ old('sku', $product->sku) }}" required>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label">{{ __('Category') }}</label>
            <select class="form-select" name="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((int) old('category_id', $product->category_id) === $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label">{{ __('Brand') }}</label>
            <select class="form-select" name="brand_id">
                <option value="">{{ __('No brand') }}</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" @selected((int) old('brand_id', $product->brand_id) === $brand->id)>{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">{{ __('Image URL') }}</label>
            <input class="form-control" type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}">
        </div>
        <div class="col-12 d-flex flex-wrap gap-4">
            <div class="form-check form-switch">
                <input class="form-check-input" id="product-featured" type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))>
                <label class="form-check-label" for="product-featured">{{ __('Featured') }}</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" id="product-active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true))>
                <label class="form-check-label" for="product-active">{{ __('Active') }}</label>
            </div>
        </div>
    </div>

    <div class="modal-footer px-0 pb-0 mt-4">
        <a class="btn btn-light" href="{{ route('admin.products.index') }}" data-bs-dismiss="modal">{{ __('Cancel') }}</a>
        <button class="btn btn-primary" type="submit">{{ __('Save product') }}</button>
    </div>
</form>
