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

        <h1>Add New Pet</h1>

        <form action="{{ route('pets.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Pet Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="category_id">Category:</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="categories">Tags:</label>
                <div id="categories">
                    @foreach($tags as $tag)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="tag{{ $tag->id }}" name="tags_ids[]" value="{{ $tag->id }}">
                            <label class="form-check-label" for="tag{{ $tag->id }}">{{ $tag->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="available">Available</option>
                    <option value="pending">Pending</option>
                    <option value="sold">Sold</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Pet</button>
        </form>
    @endsection
</div>
