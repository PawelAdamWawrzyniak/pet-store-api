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

        @session('error')
        <div class="alert alert-danger">
            {{ $value }}
        </div>
        @endsession

        @session('message')
        <div class="alert alert-info">
            {{ $value }}
        </div>
        @endsession

        <h1>Delete Pet By Id</h1>

        <form action="{{ route('pets.delete') }}" method="GET">
            @csrf
            <div class="form-group">
                <label for="id">Pet ID:</label>
                <input type="text" id="id" name="id" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Delete Pet</button>
        </form>
    @endsection
</div>
