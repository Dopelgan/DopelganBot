@extends('layouts.app')

@section('content')
    <title>Дежурные</title>

    <div class="container mt-4">
        <h1>Список дежурных</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('guards.create') }}" class="btn btn-primary mb-3">Добавить дежурного</a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Подразделение</th>
                <th>Имя</th>
                <th>Контакт</th>
                <th>Telegram</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($guards as $guard)
                <tr>
                    <td>{{ $guard->id }}</td>
                    <td>{{ $guard->department->name}}</td>
                    <td>{{ $guard->name }}</td>
                    <td>{{ $guard->contact }}</td>
                    <td>{{ $guard->telegram_link }}</td>
                    <td>
                        <a href="{{ route('guards.edit', $guard->id) }}"
                           class="btn btn-warning btn-sm">Редактировать</a>
                        <form action="{{ route('guards.destroy', $guard->id) }}" method="POST" style="display:inline;">
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
