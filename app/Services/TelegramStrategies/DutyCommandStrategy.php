<?php

namespace App\Services\TelegramStrategies;

use App\Services\TelegramService;
use App\Models\DutySchedule;
use Illuminate\Support\Carbon;

class DutyCommandStrategy implements TelegramStrategyInterface
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function handle($chatId, $text, $photo)
    {
        // Получаем текущее время
        $now = Carbon::now()->addHours(3);

        // Получаем активные дежурства, которые включают текущее время
        $dutySchedules = DutySchedule::where('start_at', '<=', $now)
            ->where('end_at', '>=', $now)
            ->with(['department', 'duty'])  // Загрузка связанных моделей
            ->get();

        // Формируем текст сообщения
        $message = "<b>📅 Дежурные сейчас:</b>\n\n";

        if ($dutySchedules->isEmpty()) {
            $message = "📅 Дежурные сейчас:\n\nНа данный момент нет активных дежурств.";
        } else {
            foreach ($dutySchedules as $dutySchedule) {
                $department = $dutySchedule->department;
                $duty = $dutySchedule->duty;

                $message .= "<b>" . ($department ? $department->name : 'Неизвестное') .
                    "</b> - " .($duty ? $duty->name : 'Неизвестный') . " " . ($duty ? $duty->contact : 'Неизвестно') . "\n\n";
            }
        }

        // Отправляем сообщение через TelegramService с использованием Markdown
        $this->telegramService->sendMessage($chatId, $message);
    }
}
