@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>
    <div class="panel">
        <a href="{{ route('admin.ormawa.create') }}" class="btn btn-primary">Create Ormawa</a>
        <a href="{{ route('admin.dosen.create') }}" class="btn btn-secondary">Create Dosen</a>
    </div>
</div>
@endsection
