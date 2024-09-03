@extends('layouts.app')

@section('content')
    <title>Управление подразделениями</title>
    <div class="container mt-4">
        <h1>Список подразделений</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('departments.create') }}" class="btn btn-primary mb-3">Добавить подразделение</a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Название</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($departments as $department)
                <tr>
                    <td>
                        {{ $department->name }}
                    </td>
                    <td>
                        <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-warning btn-sm">Изменить</a>
                        <form action="{{ route('departments.destroy', $department->id) }}" method="POST"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
