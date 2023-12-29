{{-- articles/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="m-4">
    <a class="m-2" href="{{ route('articles.index') }}">Articles List</a>
    <h1 class="m-2">Add a New Article</h1>
    <div class="m-2">
        <form action="{{ route('articles.store') }}" method="POST">
            @csrf
            <label for="link">Link:</label>
            <input type="url" name="link" id="link" class="form-control border border-secondary" required>
            <button type="submit" class="btn btn-primary my-2">Add Article</button>
        </form>
    </div>
</div>
@endsection
