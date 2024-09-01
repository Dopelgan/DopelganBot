<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Api;
use App\Services\MemeService;

class TelegramController extends Controller
{
    protected $telegram;
    protected $memeService;

    public function __construct()
    {
        // Инициализация объекта Telegram и MemeService
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $this->memeService = new MemeService();
    }

    // Обработка вебхука от Telegram
    public function webhook(Request $request)
    {
        $update = $this->telegram->getWebhookUpdate(); // Получение данных обновления
        $message = $update->getMessage(); // Извлечение сообщения из обновления

        $chatId = $message->getChat()->getId();
        $text = $message->getText();
        $photo = $message->getPhoto();

        // Обработка команды /start
        if ($text === '/start') {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Привет! Я простой телеграм-бот. Введите /help для списка команд.',
            ]);
        }

        // Обработка команды /help
        if ($text === '/help') {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Доступные команды: /start, /help, /about, /meme',
            ]);
        }

        // Обработка команды /about
        if ($text === '/about') {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Я простой бот, написанный на PHP с использованием Laravel!',
            ]);
        }

        // Обработка команды /meme
        if ($text === '/meme') {
            // Устанавливаем состояние ожидания фото
            Cache::put("user_{$chatId}_state", 'waiting_for_photo', now()->addMinutes(10));

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Пожалуйста, отправьте изображение для создания мема.',
            ]);
        } elseif ($photo && Cache::get("user_{$chatId}_state") === 'waiting_for_photo') {
            $fileId = $photo[count($photo) - 1]->getFileId(); // Получаем ID самого большого изображения
            $file = $this->telegram->getFile(['file_id' => $fileId]);

            $filePath = $file->getFilePath();
            $fileUrl = "https://api.telegram.org/file/bot" . env('TELEGRAM_BOT_TOKEN') . "/" . $filePath;

            // Создаем папку, если её нет
            if (!file_exists(public_path('tmp'))) {
                mkdir(public_path('tmp'), 0775, true);
            }

            // Скачиваем изображение
            $imagePath = public_path('tmp/' . basename($filePath));
            file_put_contents($imagePath, file_get_contents($fileUrl));

            // Сохраняем путь к изображению в кэше
            Cache::put("user_{$chatId}_photo_path", $imagePath, now()->addMinutes(10));

            // Устанавливаем состояние ожидания текста
            Cache::put("user_{$chatId}_state", 'waiting_for_caption', now()->addMinutes(10));

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Теперь отправьте текст для мема.',
            ]);
        } elseif (Cache::get("user_{$chatId}_state") === 'waiting_for_caption') {
            $caption = $text;

            // Получаем сохраненный путь к изображению
            $imagePath = Cache::get("user_{$chatId}_photo_path");

            // Создаем мем
            $memePath = $this->memeService->createMeme($imagePath, $caption);

            // Используем InputFile для отправки мема
            $this->telegram->sendPhoto([
                'chat_id' => $chatId,
                'photo' => InputFile::create($memePath, 'meme.jpg'),
                'caption' => 'Ваш мем',
            ]);

            // Удаляем временные файлы
            unlink($imagePath);
            unlink($memePath);

            // Сбрасываем состояние пользователя
            Cache::forget("user_{$chatId}_state");
            Cache::forget("user_{$chatId}_photo_path");
        }
    }

}
