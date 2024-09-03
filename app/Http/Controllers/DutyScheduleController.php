<?php

namespace App\Http\Controllers;

use App\Imports\DutyScheduleImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class DutyScheduleController extends Controller
{
    // Отображение формы загрузки
    public function showUploadForm()
    {
        return view('dutySchedules.upload_schedule');
    }

    // Обработка загрузки файла
    public function upload(Request $request)
    {
        $request->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new DutyScheduleImport, $request->file('excelFile'));

        return redirect()->route('dutySchedules.uploadForm')->with('success', 'График дежурства успешно загружен.');
    }
}
