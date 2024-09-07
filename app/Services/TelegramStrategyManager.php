<?php

namespace App\Services;

use App\Services\TelegramStrategies\TelegramStrategyInterface;
use Illuminate\Support\Str;

class TelegramStrategyManager
{
    protected $strategies;

    // Здесь можно добавить список разрешённых chat_id
    protected $allowedChatIds = [
        1538089400, // Пример chat_id пользователя, которому разрешён доступ
        // Добавьте другие chat_id, если нужно
    ];

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
        // Проверяем, что chat_id отправителя разрешённый
        if (!in_array($chatId, $this->allowedChatIds)) {
            $this->unauthorizedResponse($chatId);
            return;
        }

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

    protected function unauthorizedResponse($chatId)
    {
        app(TelegramService::class)->sendMessage($chatId, 'У вас нет доступа к этому сервису.');
    }

    protected function defaultResponse($chatId)
    {
        // Общий ответ для неизвестных команд
        app(TelegramService::class)->sendMessage($chatId, 'Команда не распознана.');
    }
}
