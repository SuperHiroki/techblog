{{-- resources/views/my-page/bookmarks.blade.php --}}
@extends('layouts.app')

@section('page-specific-header')
    @include('partials.sub_header')
@endsection

@section('content')
    @include('partials.articles_sort')
@endsection
