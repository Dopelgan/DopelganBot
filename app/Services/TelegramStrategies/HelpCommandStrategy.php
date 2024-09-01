<?php

namespace App\Services\TelegramStrategies;

use App\Services\TelegramService;

class HelpCommandStrategy implements TelegramStrategyInterface
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function handle($chatId, $text, $photo)
    {
        $helpText = "Доступные команды:\n";
        $helpText .= "/start - Запускает бот и предоставляет информацию о его функционале.\n";
        $helpText .= "/guard [structure] - Выводит дежурного для указанной структуры.\n";

        $this->telegramService->sendMessage($chatId, $helpText);
    }
}

