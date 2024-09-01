<?php

namespace App\Services\TelegramStrategies;

use App\Services\TelegramService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuardCommandStrategy implements TelegramStrategyInterface
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function handle($chatId, $text, $photo)
    {
        $this->sendDutyList($chatId);
    }

    protected function sendDutyList($chatId)
    {
        // Получаем список дежурных на текущее время
        $dutyList = $this->getCurrentDuties();

        if (empty($dutyList)) {
            $message = "Нет информации о дежурных в данный момент.";
        } else {
            $message = "Список дежурных по подразделениям на текущее время:\n\n";
            foreach ($dutyList as $department => $duties) {
                $message .= "*$department*\n";
                foreach ($duties as $duty) {
                    $message .= "• {$duty['name']}, Телефон: {$duty['contact']}, Telegram: {$duty['telegram_link']}\n";
                }
                $message .= "\n";
            }
        }

        $this->telegramService->sendMessage($chatId, $message);
    }

    protected function getCurrentDuties()
    {
        $now = now(); // Получаем текущую дату и время
        $currentDay = $now->format('j'); // Текущий день месяца
        $currentTime = $now->format('H:i'); // Текущее время

        Log::info("Current Day: $currentDay");
        Log::info("Current Time: $currentTime");

        // Получаем всех дежурных
        $guards = DB::table('guards')->get();

        Log::info('All Guards: '.json_encode($guards));

        $dutyList = [];

        foreach ($guards as $guard) {
            // Удаляем лишние кавычки из JSON строки
            $dutyScheduleJson = trim($guard->duty_schedule, '"');
            $dutySchedule = json_decode($dutyScheduleJson, true);

            Log::info("Guard ID: {$guard->id}");
            Log::info("Duty Schedule (Decoded): ".json_encode($dutySchedule));

            // Проверяем наличие информации о дежурстве за текущий день
            if (is_array($dutySchedule) && isset($dutySchedule[$currentDay])) {
                $duty = $dutySchedule[$currentDay];

                // Проверяем, что дежурный ID совпадает и есть информация о времени
                if ($duty['guard_id'] == $guard->id) {
                    $startTime = $duty['start'];
                    $endTime = $duty['end'];

                    Log::info("Start Time: $startTime");
                    Log::info("End Time: $endTime");

                    // Проверяем, есть ли время начала и окончания
                    if ($startTime && $endTime) {
                        // Дежурство переходит через полночь
                        if ($endTime < $startTime) {
                            if ($currentTime >= $startTime || $currentTime < $endTime) {
                                Log::info("Duty matches current time.");
                                $dutyList[$guard->department_id][] = [
                                    'name' => $guard->name,
                                    'contact' => $guard->contact,
                                    'telegram_link' => $guard->telegram_link,
                                ];
                            }
                        } else {
                            // Дежурство в пределах одного дня
                            if ($currentTime >= $startTime && $currentTime <= $endTime) {
                                Log::info("Duty matches current time.");
                                $dutyList[$guard->department_id][] = [
                                    'name' => $guard->name,
                                    'contact' => $guard->contact,
                                    'telegram_link' => $guard->telegram_link,
                                ];
                            }
                        }
                    }
                }
            }
        }

        // Группируем дежурных по подразделениям
        $dutyList = collect($dutyList)->map(function ($duties) {
            return collect($duties)->map(function ($duty) {
                return [
                    'name' => $duty['name'],
                    'contact' => $duty['contact'],
                    'telegram_link' => $duty['telegram_link'],
                ];
            })->toArray();
        })->toArray();

        // Отладочный вывод
        Log::info('Duty List: '.json_encode($dutyList));

        return $dutyList;
    }






}
