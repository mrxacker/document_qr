@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Document</h1>

    <form action="{{ route('store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="sender_name" class="form-label">Sender Name</label>
            <input type="text" name="sender_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="receiver_name" class="form-label">Receiver Name</label>
            <input type="text" name="receiver_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="sender_department_name" class="form-label">Sender Department Name</label>
            <input type="text" name="sender_department_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="files" class="form-label">Document Files (PDF)</label>
            <input type="file" name="files[]" class="form-control" accept="application/pdf" multiple>
        </div>

        <button type="submit" class="btn btn-primary">Save Document</button>
    </form>
</div>
@endsection
