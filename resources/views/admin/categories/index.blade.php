@extends('admin.layout')
@section('title', __('Categories'))
@section('content')
    <x-admin.taxonomy-index type="category" :categories="$categories" />
@endsection
