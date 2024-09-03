<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramResponseException;

class SetWebhook extends Command
{
    protected $signature = 'telegram:set-webhook';
    protected $description = 'Set the Telegram webhook';

    public function handle()
    {
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));

        $url = env('TELEGRAM_WEBHOOK_URL') . 'api/telegram/webhook'; // Убедитесь, что путь правильный

        try {
            $response = $telegram->setWebhook(['url' => $url]);

            // Проверка, если $response является true
            if ($response === true) {
                $this->info('Webhook установлен успешно: ' . $url);
            } else {
                $this->error('Ошибка при установке вебхука. Ответ: ' . print_r($response, true));
            }
        } catch (TelegramResponseException $e) {
            $this->error('Telegram API ошибка: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->error('Неизвестная ошибка: ' . $e->getMessage());
        }
    }
}
