<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

class TelegramService
{
    protected $telegram;

    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }

    public function sendMessage($chatId, $text)
    {
        try {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
        ]);
        } catch (\Exception $e) {
            Log::error('Telegram sendMessage error: ' . $e->getMessage());
        }
    }

    public function sendPhoto($chatId, $photoPath, $caption = '')
    {
        try {
        $this->telegram->sendPhoto([
            'chat_id' => $chatId,
            'photo' => InputFile::create($photoPath, 'photo.jpg'),
            'caption' => $caption,
        ]);
        } catch (\Exception $e) {
            Log::error('Telegram sendMessage error: ' . $e->getMessage());
        }
    }

    public function getWebhookUpdate()
    {
        return $this->telegram->getWebhookUpdate();
    }
}

