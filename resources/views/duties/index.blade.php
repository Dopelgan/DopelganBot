@extends('layouts.app')

@section('content')
    <title>Дежурные</title>

    <div class="container mt-4">
        <h1>Список дежурных</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('duties.create') }}" class="btn btn-primary mb-3">Добавить дежурного</a>

        <div class="mb-3">
            <div class="row">
                <div>
                    <div class="card">
                        <div class="card-header">Импорт данных о дежурных</div>
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('duties.upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="excelFile">Выберите Excel файл</label>
                                    <input type="file" class="form-control" id="excelFile" name="excelFile" required>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Загрузить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                    @if (isset($guard->department->name))<td>{{ $guard->department->name}}</td>@endif
                    <td>{{ $guard->name }}</td>
                    <td>{{ $guard->contact }}</td>
                    <td>{{ $guard->telegram_link }}</td>
                    <td>
                        <a href="{{ route('duties.edit', $guard->id) }}"
                           class="btn btn-warning btn-sm">Редактировать</a>
                        <form action="{{ route('duties.destroy', $guard->id) }}" method="POST" style="display:inline;">
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
