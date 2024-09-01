@extends('layouts.app')

@section('content')
    <title>Редактировать дежурного</title>
    <div class="container mt-4">
        <h1>Редактировать дежурного</h1>

        <form action="{{ route('guards.update', $guard->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="department_id">Подразделение</label>
                <select name="department_id" id="department_id" class="form-control" required>
                    <option value="">Выберите подразделение</option>
                    @foreach ($departments as $department)
                        <option
                            value="{{ $department->id }}" {{ isset($guard) && $guard->department_id == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $guard->name }}" required>
            </div>

            <div class="form-group">
                <label for="contact">Контакт</label>
                <input type="text" name="contact" id="contact" class="form-control" value="{{ $guard->contact }}"
                       required>
            </div>

            <div class="form-group">
                <label for="telegram_link">Telegram</label>
                <input type="url" name="telegram_link" id="telegram_link" class="form-control"
                       value="{{ $guard->telegram_link }}">
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        </form>
    </div>
@endsection
