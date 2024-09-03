{{-- resources/views/upload_schedule.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Загрузка графика дежурств</div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('dutySchedules.upload') }}" method="POST" enctype="multipart/form-data">
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
@endsection
