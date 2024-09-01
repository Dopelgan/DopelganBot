<?php
namespace App\Services\TelegramStrategies;

interface TelegramStrategyInterface
{
    public function handle($chatId, $text, $photo);
}
