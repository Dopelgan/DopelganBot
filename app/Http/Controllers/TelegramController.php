<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TelegramService;
use App\Services\TelegramStrategyManager;

class TelegramController extends Controller
{
    protected $telegramService;
    protected $strategyManager;

    public function __construct(TelegramService $telegramService, TelegramStrategyManager $strategyManager)
    {
        $this->telegramService = $telegramService;
        $this->strategyManager = $strategyManager;
    }

    // Обработка вебхука от Telegram
    public function webhook(Request $request)
    {
        $update = $this->telegramService->getWebhookUpdate(); // Получение данных обновления

        if ($update->isType('message')) {
            $message = $update->getMessage(); // Извлечение сообщения из обновления
            $chatId = $message->getChat()->getId();
            $text = $message->getText();
            $photo = $message->getPhoto();

            $this->strategyManager->handle($chatId, $text, $photo);
        } elseif ($update->isType('callback_query')) {
            // Обработка callback_query если нужно
        }
    }
}
