<?php

namespace App\Services\TelegramStrategies;

use App\Services\TelegramService;
use Illuminate\Support\Facades\DB;

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
        $now = now();

        // Получаем дежурных, которые активны на текущее время
        $dutyList = DB::table('guards')
            ->where('duty_start', '<=', $now)
            ->where('duty_end', '>=', $now)
            ->get()
            ->groupBy('structure')
            ->map(function ($guards) {
                return $guards->map(function ($guard) {
                    return [
                        'name' => $guard->name,
                        'contact' => $guard->contact,
                        'telegram_link' => $guard->telegram_link,
                    ];
                });
            });

        return $dutyList->toArray();
    }
}
