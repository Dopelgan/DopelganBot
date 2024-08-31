<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Api;

class TelegramController extends Controller
{
    protected $telegram;

    public function __construct()
    {
        // Инициализация объекта Telegram
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
    }

    // Обработка вебхука от Telegram
    public function webhook(Request $request)
    {
        $update = $this->telegram->getWebhookUpdate(); // Получение данных обновления
        $message = $update->getMessage(); // Извлечение сообщения из обновления

        $chatId = $message->getChat()->getId();
        $text = $message->getText();

        // Ответ на команду /start
        if ($text === '/start') {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Привет! Я простой телеграм-бот. Введите /help для списка команд.',
            ]);
        }

        // Ответ на команду /help
        if ($text === '/help') {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Доступные команды: /start, /help, /about',
            ]);
        }

        // Ответ на команду /about
        if ($text === '/about') {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Я простой бот, написанный на PHP с использованием Laravel!',
            ]);
        }
    }
}
