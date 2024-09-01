@extends('layouts.app')

@section('content')
    <title>Изменить подразделение</title>
<div class="container mt-4">
    <h1>Изменить подразделение</h1>

    <form action="{{ route('departments.update', $department->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $department->name }}" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Обновить</button>
    </form>
</div>
@endsection
