{{-- resources/views/my-page/profile.blade.php --}}
@extends('layouts.app')

@section('page-specific-header')
    @include('partials.sub_header')
@endsection

@section('content')
<div class="card mb-8 shadow">
    <div class="card-body">
        <div class="m-2">
            @if($user->icon_image)
                <img src="{{ asset('storage/' . $user->icon_image) }}" alt="No Image" style="max-width: 60px; max-height: 60px; border-radius: 50%; margin-right: 5px;">
            @else
                <img src="{{ asset('images/default-icons/avatar.png')}}" alt="No Image" style="max-width: 60px; max-height: 60px; border-radius: 50%; margin-right: 5px;">
            @endif
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Name:</strong> {{ $profile->name }}</li>
            <li class="list-group-item"><strong>Self-Introduction:</strong> {{ $profile->bio }}</li>
            <li class="list-group-item"><strong>E-mail:</strong> {{ $profile->public_email }}</li>
            <li class="list-group-item"><strong>GitHub:</strong> {{ $profile->github }}</li>
            <li class="list-group-item"><strong>website:</strong> {{ $profile->website }}</li>
            <li class="list-group-item"><strong>organization:</strong> {{ $profile->organization }}</li>
            <li class="list-group-item"><strong>location:</strong> {{ $profile->location }}</li>
            <li class="list-group-item"><strong>SNS1:</strong> {{ $profile->sns1 }}</li>
            <li class="list-group-item"><strong>SNS2:</strong> {{ $profile->sns2 }}</li>
            <li class="list-group-item"><strong>SNS3:</strong> {{ $profile->sns3 }}</li>
            <li class="list-group-item"><strong>SNS4:</strong> {{ $profile->sns4 }}</li>
            <li class="list-group-item"><strong>SNS5:</strong> {{ $profile->sns5 }}</li>
            <li class="list-group-item"><strong>SNS6:</strong> {{ $profile->sns6 }}</li>
        </ul>
    </div>
</div>
@endsection

