{{-- articles/create.blade.php --}}
@extends('layouts.app')

@section('content')
    <a href="{{ route('articles.index') }}">Articles List</a>
    <h1>Add a New Article</h1>
    <form action="{{ route('articles.store') }}" method="POST">
        @csrf
        <label for="link">Link:</label>
        <input type="url" name="link" id="link" required>
        <button type="submit">Add Article</button>
    </form>
@endsection
