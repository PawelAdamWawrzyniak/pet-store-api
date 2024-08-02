<div>
    @extends('layouts.app')

    @section('content')
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @session('message')
        <div class="alert alert-info">
            {{ $value }}
        </div>
        @endsession
        <h1>Pet Details</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Name: {{ $pet['name'] }}</h5>
                <p class="card-text">Category: {{ $pet['category']['name'] }}</p>
                <p class="card-text">Status: {{ $pet['status'] }}</p>
                @if(!empty($pet['photoUrls']))
                <p class="card-text">Photos:</p>
                <ul>
                    @foreach($pet['photoUrls'] as $photo)
                        <li><a href=" {{ $photo }}" target="_blank" > {{ $photo }}</a></li>
                    @endforeach
                </ul>
                @else
                    <p>No photos available</p>
                @endif
                <p class="card-text">Tags:</p>
                <ul>
                    @foreach($pet['tags'] as $tag)
                        <li>{{ $tag['name'] }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endsection
</div>
