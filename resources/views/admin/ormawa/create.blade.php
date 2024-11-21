@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Ormawa</h1>
    <form action="{{ route('admin.ormawa.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nama">Nama Ormawa</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>
@endsection
