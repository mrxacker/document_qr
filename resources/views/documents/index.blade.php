@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Documents</h1>
    
    <!-- Add Document Button -->
    <a href="{{ route('create') }}" class="btn btn-primary mb-3">Add Document</a>
    
    <!-- Display all Documents -->
    @if ($documents->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sender Name</th>
                    <th>Receiver Name</th>
                    <th>Sender Department</th>
                    <th>Phone</th>
                    <th>Files</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($documents as $document)
                    <tr>
                        <td>{{ $document->id }}</td>
                        <td>{{ $document->sender_name }}</td>
                        <td>{{ $document->receiver_name }}</td>
                        <td>{{ $document->sender_department_name }}</td>
                        <td>{{ $document->phone }}</td>
                        <td>
                            <ul>
                                @foreach ($document->documentFiles as $file)
                                    <li>
                                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">View PDF</a>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No documents available.</p>
    @endif
</div>
@endsection
