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
                    <th>Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Tags</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pets as $pet)
                    <tr>
                        <td>{{ $pet['id'] ?? $noSetValue}}</td>
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
                    </tr>
                @endforeach
                </tbody>
            </table>
    @endsection
</div>
