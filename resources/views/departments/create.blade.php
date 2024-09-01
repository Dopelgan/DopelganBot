@extends('layouts.app')

@section('content')
    <title>Добавить подразделение</title>
<div class="container mt-4">
    <h1>Добавить подразделение</h1>

    <form action="{{ route('departments.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Добавить</button>
    </form>
</div>
@endsection
