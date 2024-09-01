<?php

namespace App\Services\TelegramStrategies;

use App\Services\TelegramService;

class StartCommandStrategy implements TelegramStrategyInterface
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function handle($chatId, $text, $photo)
    {
        $this->telegramService->sendMessage($chatId, 'Привет! Я бот для получения информации. Используйте команды /help и /guard [structure].');
    }
}

