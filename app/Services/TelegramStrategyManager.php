<?php

namespace App\Services;

use App\Services\TelegramStrategies\DutyCommandStrategy;
use App\Services\TelegramStrategies\HelpCommandStrategy;
use App\Services\TelegramStrategies\StartCommandStrategy;
use Illuminate\Support\Str;

class TelegramStrategyManager
{
    protected $strategies;

    // Здесь можно добавить список разрешённых chat_id
    protected $allowedChatIds;

    public function __construct()
    {
        // Преобразуем строку из env в массив
        $this->allowedChatIds = explode(',', env('ALLOWED_CHAT_IDS'));
        $this->strategies = [
            '/start' => StartCommandStrategy::class,
            '/help' => HelpCommandStrategy::class,
            '/duty' => DutyCommandStrategy::class,
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
