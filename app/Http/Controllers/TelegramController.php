<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TelegramService;
use App\Services\TelegramStrategyManager;
use Exception;
use Illuminate\Support\Facades\Log;

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
        try {
            $update = $this->telegramService->getWebhookUpdate(); // Получение данных обновления

            if ($update->isType('message')) {
                $message = $update->getMessage();
                $chatId = $message->getChat()->getId();
                $text = $message->getText();
                $photo = $message->getPhoto();

                // Проверяем, что сообщение начинается с '/'
                if ($text && strpos($text, '/') === 0) {
                    $this->strategyManager->handle($chatId, $text, $photo);
                }
            } elseif ($update->isType('callback_query')) {
                // Обработка callback_query если нужно
            }

            // Возвращаем HTTP 200 для подтверждения успешной обработки
            return response()->json(['status' => 'ok'], 200);
        } catch (Exception $e) {
            // Логируем ошибку
            Log::error('Ошибка обработки вебхука Telegram: ' . $e->getMessage());

            // Отправляем сообщение пользователю о возникшей ошибке
            $chatId = $update->getMessage()->getChat()->getId(); // Получаем ID чата для отправки сообщения
            $this->telegramService->sendMessage($chatId, 'Возникла ошибка на стороне сервера. Пожалуйста, попробуйте позже.');

            // Возвращаем HTTP 200, чтобы Telegram не повторял запрос
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}
