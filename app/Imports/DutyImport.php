<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Duty;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DutyImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    /**
     * Обработка коллекции данных из Excel.
     *
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        // Получаем или создаем подразделение


        foreach ($collection as $row) {
            $department = Department::firstOrCreate(['name' => $row['department']]);
            // Ищем существующую запись
            $existingDuty = Duty::where('name', $row['name'])
                ->where('department_id', $department->id)
                ->first();

            if (!$existingDuty) {
                // Запись не найдена, создаем новую
                $duty = new Duty();
                $duty->name = $row['name'];
                $duty->department_id = $department->id;
                $duty->contact = $row['contact'];
                $duty->telegram_link = $row['telegram_link'];
                $duty->save();
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
            '*.contact' => 'required|string',
            '*.telegram_link' => 'nullable|url',
        ];
    }
}
