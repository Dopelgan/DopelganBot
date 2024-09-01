@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактирование дежурных по дням месяца</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('guards.update_monthly_duties') }}" method="POST">
            @csrf
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Подразделение</th>
                    @for ($day = 1; $day <= 31; $day++)
                        <th>{{ $day }}</th>
                    @endfor
                </tr>
                </thead>
                <tbody>
                @foreach ($guards as $guard)
                    <tr>
                        <td>{{ $guard->name }}</td>
                        <input type="hidden" name="{{ $guard->id }}[id]" value="{{ $guard->id }}">
                        @for ($day = 1; $day <= 31; $day++)
                            <td>
                                <div class="form-group">
                                    <label for="{{ $guard->id }}_duty_{{ $day }}">Дежурный:</label>
                                    <input type="text" class="form-control" id="{{ $guard->id }}_duty_{{ $day }}"
                                           name="{{ $guard->id }}[{{ $day }}][duty]"
                                           value="{{ $guard->duty_schedule[$day]['duty'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="{{ $guard->id }}_start_{{ $day }}">Начало:</label>
                                    <input type="time" class="form-control" id="{{ $guard->id }}_start_{{ $day }}"
                                           name="{{ $guard->id }}[{{ $day }}][start]"
                                           value="{{ $guard->duty_schedule[$day]['start'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="{{ $guard->id }}_end_{{ $day }}">Окончание:</label>
                                    <input type="time" class="form-control" id="{{ $guard->id }}_end_{{ $day }}"
                                           name="{{ $guard->id }}[{{ $day }}][end]"
                                           value="{{ $guard->duty_schedule[$day]['end'] ?? '' }}">
                                </div>
                            </td>
                        @endfor
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
@endsection
