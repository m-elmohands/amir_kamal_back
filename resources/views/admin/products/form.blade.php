@extends('admin.layout')
@section('title', __($product->exists ? 'Edit product' : 'Add product'))
@section('content')
    <section class="card">
        @include('admin.products._form')
    </section>
@endsection
