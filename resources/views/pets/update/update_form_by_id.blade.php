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

        <h1>Update Pet By Id</h1>

        <form action="{{ route('pets.edit') }}" method="GET">
            @csrf
            <div class="form-group">
                <label for="id">Pet ID:</label>
                <input type="text" id="id" name="id" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Pet</button>
        </form>
    @endsection
</div>
