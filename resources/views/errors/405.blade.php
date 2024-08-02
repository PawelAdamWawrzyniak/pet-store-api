@extends('layouts.app')

@section('content')
    <h1>Validation Error</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @session('error')
    <div class="alert alert-danger">
        {{ $value }}
    </div>
    @endsession

    <div class="alert alert-warning" role="alert">
        {{ $exception->getMessage() }}
    </div>

@endsection
