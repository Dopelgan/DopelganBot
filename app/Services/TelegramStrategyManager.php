<?php

namespace App\Services;

use App\Services\TelegramStrategies\TelegramStrategyInterface;
use Illuminate\Support\Str;

class TelegramStrategyManager
{
    protected $strategies;

    public function __construct()
    {
        $this->strategies = [
            '/start' => \App\Services\TelegramStrategies\StartCommandStrategy::class,
            '/help' => \App\Services\TelegramStrategies\HelpCommandStrategy::class,
            '/duty' => \App\Services\TelegramStrategies\DutyCommandStrategy::class,
        ];
    }

    public function handle($chatId, $text, $photo)
    {
        foreach ($this->strategies as $command => $strategyClass) {
            if (Str::startsWith($text, $command)) {
                $strategy = app($strategyClass);
                $strategy->handle($chatId, $text, $photo);
                return;
            }
        }

        // Обработка неизвестных команд
        $this->defaultResponse($chatId);
    }

    protected function defaultResponse($chatId)
    {
        // Общий ответ для неизвестных команд
        app(TelegramService::class)->sendMessage($chatId, 'Команда не распознана.');
    }
}
