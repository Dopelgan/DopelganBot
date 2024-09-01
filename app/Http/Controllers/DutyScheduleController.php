<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DutyScheduleController extends Controller
{
    public function editMonthlyDuties($id)
    {
        $department = Department::with('guardRelation')->findOrFail($id);
        $guards = $department->guards;
        $schedule = $this->getDutySchedule($id); // Метод для получения расписания

        return view('dutySchedule.edit_monthly_duties', compact('department', 'guards', 'schedule'));
    }

    public function updateMonthlyDuties(Request $request, $id)
    {
        $data = $request->except('_token');

        // Обновляем расписание дежурств
        foreach ($data['duties'] as $day => $details) {
            $this->updateDutySchedule($id, $day, $details);
        }

        return redirect()->route('dutySchedule.edit_monthly_duties', $id)->with('success', 'Дежурные обновлены.');
    }

    protected function getDutySchedule($departmentId)
    {
        // Получаем все записи дежурства для указанного подразделения
        $dutySchedules = \App\Models\DutySchedule::where('department_id', $departmentId)
            ->orderBy('date') // Можно добавить сортировку по дате
            ->get();

        $schedule = [];

        foreach ($dutySchedules as $dutySchedule) {
            // Преобразуем строку даты в объект Carbon
            $date = Carbon::parse($dutySchedule->date);

            // Получаем день месяца из даты
            $day = (int) $date->format('j'); // 'j' для получения дня месяца без ведущих нулей

            if (!isset($schedule[$day])) {
                $schedule[$day] = [];
            }

            $schedule[$day][] = [
                'guard_id' => $dutySchedule->guard_id,
                'start' => $dutySchedule->start,
                'end' => $dutySchedule->end,
            ];
        }

        return $schedule;
    }


    protected function updateDutySchedule($departmentId, $day, $details)
    {
        // Убедитесь, что $details['start'] и $details['end'] корректно установлены
        $details['start'] = $details['start'] ?? '00:00:00';
        $details['end'] = $details['end'] ?? '00:00:00';

        // Получаем всех охранников для указанного подразделения
        $guards = \App\Models\Guard::where('department_id', $departmentId)->get();

        foreach ($guards as $guard) {
            // Формируем дату для дежурства
            $date = date('Y-m-d', strtotime("$day-01")); // Например, 2024-09-01

            // Обновляем или создаем новое расписание
            \App\Models\DutySchedule::updateOrCreate(
                [
                    'department_id' => $guard->department_id,
                    'guard_id' => $guard->id,
                    'date' => $date,
                ],
                [
                    'start' => $details['start'],
                    'end' => $details['end'],
                ]
            );
        }
    }
}
