@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{$department->name}}</h1>
        <h1>Редактирование дежурных по дням месяца</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('dutySchedule.update_monthly_duties', $department->id) }}" method="POST">
            @csrf
            @method('PUT')
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>День</th>
                    <th>Дежурный</th>
                    <th>Начало</th>
                    <th>Окончание</th>
                </tr>
                </thead>
                <tbody>
                @for ($day = 1; $day <= 31; $day++)
                    <tr>
                        <td>{{ $day }}</td>
                        <td>
                            <select name="duties[{{ $day }}][guard_id]" class="form-control">
                                <option value="">Выберите дежурного</option>
                                @foreach ($guards as $guard)
                                    <option value="{{ $guard->id }}">
                                        {{ $guard->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="time" class="form-control" name="duties[{{ $day }}][start]"
                                   value="{{ $schedule[$day][0]['start'] ?? '' }}">
                        </td>
                        <td>
                            <input type="time" class="form-control" name="duties[{{ $day }}][end]"
                                   value="{{ $schedule[$day][0]['end'] ?? '' }}">
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
@endsection
