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


        <h1>Update Pet</h1>
        <form action="{{ route('pets.update', $pet['id']) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Pet Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $pet['name'] }}" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" class="form-control" id="category" name="category"
                       value="{{ $pet['category']['name'] ?? '' }}" required>
            </div>
            <div class="form-group">
                <label for="tags_names">Tags</label>
                <div id="tagsIdsContainer">
                    @foreach($pet['tags'] as $tag)
                        <input type="text" class="form-control mb-2" name="tags_names[]" value="{{ $tag['name'] }}"
                               placeholder="Enter tag name">
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary" onclick="addTagUrlField()">Add another Tag Name
                </button>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="available" {{ $pet['status'] == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="pending" {{ $pet['status'] == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="sold" {{ $pet['status'] == 'sold' ? 'selected' : '' }}>Sold</option>
                </select>
            </div>
            <div class="form-group">
                <label for="photoUrls">Photo URLs</label>
                <div id="photoUrlsContainer">
                    @foreach($pet['photoUrls'] as $photoUrl)
                        <input type="text" class="form-control mb-2" name="photoUrls[]" value="{{ $photoUrl }}"
                               placeholder="Enter photo URL">
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary" onclick="addPhotoUrlField()">Add another photo URL
                </button>
            </div>
            <button type="submit" class="btn btn-primary">Update Pet</button>
        </form>

        <script>
            function addPhotoUrlField() {
                const container = document.getElementById('photoUrlsContainer');
                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'form-control mb-2';
                input.name = 'photoUrls[]';
                input.placeholder = 'Enter photo URL';
                container.appendChild(input);
            }
        </script>
        <script>
            function addTagUrlField() {
                const container = document.getElementById('tagsIdsContainer');
                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'form-control mb-2';
                input.name = 'tags_names[]';
                input.placeholder = 'Enter Tag Name';
                container.appendChild(input);
            }
        </script>
    @endsection
</div>
