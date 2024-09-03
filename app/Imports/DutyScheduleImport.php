<?php

namespace App\Imports;

use App\Models\DutySchedule;
use App\Models\Duty;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DutyScheduleImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * Обработка каждой строки Excel-файла.
     *
     * @param Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Получаем или создаем подразделение
            $department = Department::firstOrCreate(['name' => $row['department']]);

            // Получаем или создаем дежурного, связанного с подразделением
            $duty = Duty::where('name', $row['name'])
                ->where('department_id', $department->id)
                ->first();

            if (!$duty) {
                // Дежурный не найден, создаем нового
                $duty = new Duty();
                $duty->name = $row['name'];
                $duty->department_id = $department->id;
                $duty->contact = $row['contact'];
                $duty->telegram_link = $row['telegram_link'];
                $duty->save();
            }

            // Определите параметры для поиска существующей записи
            $dutyId = $duty->id;
            $departmentId = $department->id;
            $startAt = Carbon::parse($row['start_at']);
            $endAt = Carbon::parse($row['end_at']);

            // Найдите существующую запись
            $existingSchedule = DutySchedule::where('duty_id', $dutyId)
                ->where('department_id', $departmentId)
                ->where('start_at', $startAt)
                ->where('end_at', $endAt)
                ->first();

            if (!$existingSchedule) {
                // Запись не найдена, создаем новую
                $dutySchedule = new DutySchedule();
                $dutySchedule->duty_id = $dutyId;
                $dutySchedule->department_id = $departmentId;
                $dutySchedule->start_at = $startAt;
                $dutySchedule->end_at = $endAt;
                $dutySchedule->save();
            }
        }
    }
    /**
     * Определите правила валидации для загружаемых данных.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.name' => 'required|string',
            '*.department' => 'required|string',
            '*.start_at' => 'required|date_format:Y-m-d H:i:s',
            '*.end_at' => 'required|date_format:Y-m-d H:i:s',
        ];
    }
}
