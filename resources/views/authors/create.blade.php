{{-- authors/create.blade.php --}}
@extends('layouts.app')

@section('content')
    <a href="{{ route('authors.index') }}">Authors List</a>
    <h1>Add a New Author</h1>
    <form action="{{ route('authors.store') }}" method="POST">
        @csrf
        <label for="link">Link:</label>
        <input type="url" name="link" id="link" required>
        <button type="submit">Add Author</button>
    </form>
@endsection
