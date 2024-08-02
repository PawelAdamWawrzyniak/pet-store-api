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

            @php
                $noSetValue = 'Empty Value in API';
            @endphp

            <h1>Pet List</h1>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Action</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Tags</th>
                    <th>Status</th>
                    <th class="photo-column">Photos</th>

                </tr>
                </thead>
                <tbody>
                @foreach($pets as $pet)
                    <tr>
                        <td>{{ $pet['id'] ?? $noSetValue}}</td>
                        <td>
                            <a href="{{ route('pets.edit', [ 'id' => $pet['id']]) }}" class="btn btn-primary">Update</a>
                        </td>
                        <td>{{ $pet['name'] ?? $noSetValue}}</td>
                        <td>{{ $pet['category']['name'] ?? $noSetValue}}</td>
                        <td>{{ $pet['status'] ?? $noSetValue}}</td>
                        <td>
                            <ul>
                                @foreach($pet['tags'] as $tag)
                                    <li>{{ $tag['name'] ?? $noSetValue}}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="photo-column">
                            @if(!(empty($pet['photoUrls'])))
                            <ul>
                                @foreach($pet['photoUrls'] as $photo)
                                    <li>{{ $photo ?? $noSetValue}}</li>
                                @endforeach
                            </ul>
                            @else
                            {{ $noSetValue }}
                            @endif
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
    @endsection
</div>
