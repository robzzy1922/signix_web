@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Dosen</h1>
    <form action="{{ route('admin.dosen.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nama">Nama Dosen</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="telepon">Telepon</label>
            <input type="text" name="telepon" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>
@endsection
