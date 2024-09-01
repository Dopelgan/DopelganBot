<!-- resources/views/guards/index.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Дежурные</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Список дежурных</h1>

    <a href="{{ route('guards.create') }}" class="btn btn-primary mb-3">Добавить нового дежурного</a>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
    <tr>
        <th>ID</th>
        <th>Структура</th>
        <th>Имя</th>
        <th>Контакт</th>
        <th>Начало дежурства</th>
        <th>Окончание дежурства</th>
        <th>Telegram</th>
        <th>Действия</th>
    </tr>
    </thead>
    <tbody>
    @foreach($guards as $guard)
        <tr>
            <td>{{ $guard->id }}</td>
            <td>{{ $guard->structure }}</td>
            <td>{{ $guard->name }}</td>
            <td>{{ $guard->contact }}</td>
            <td>{{ $guard->duty_start }}</td>
            <td>{{ $guard->duty_end }}</td>
            <td>{{ $guard->telegram_link }}</td>
            <td>
                <a href="{{ route('guards.edit', $guard->id) }}" class="btn btn-warning btn-sm">Редактировать</a>
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
</body>
</html>
