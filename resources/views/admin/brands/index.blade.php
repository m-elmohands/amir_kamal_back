@extends('admin.layout')
@section('title', __('Brands'))
@section('content')
    <x-admin.taxonomy-index type="brand" :brands="$brands" />
@endsection
