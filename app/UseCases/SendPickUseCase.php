<?php

namespace App\UseCases;

use App\Models\Type;
use App\Utils\HtmlParser;
use App\Utils\Telegram;
use Illuminate\Support\Str;
use TelegramBot\Api\BotApi;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendPickUseCase
{
    private $chatId;
    private $bot;

    public function __construct()
    {
        $this->chatId = env('API_KEY');
        $this->bot = new \TelegramBot\Api\BotApi(config('services.telegram.token'));

    }

    public function execute($subject, $htmlBody)
    {
        try {
            if (!Str::contains($subject, ['pick', 'PICK', 'Pick'])) {
                return ['ok' => 'Not received pick message'];
            }

            $type = $this->getPickType($subject);
            $tipster = Str::between($subject, 'pick from ', ' for ');

            switch ($type->getType()) {
                case Type::LIVE:
                    $pickData = HtmlParser::getLivePickFromHtml($htmlBody);
                    $message = Telegram::getMessageForLivePick($pickData, $tipster);
                    break;
                case Type::COMBO:
                    $pickData = HtmlParser::getComboPickFromHtml($htmlBody);
                    $message = Telegram::getMessageForComboPick($pickData, $tipster);
                    break;
                case Type::ASIAN:
                    $pickData = HtmlParser::getPickFromHtml($htmlBody);
                    $message = Telegram::getMessageForAsianPick($pickData, $tipster);
                    break;
                case Type::ASIAN_LIVE:
                    $pickData = HtmlParser::getLivePickFromHtml($htmlBody);
                    $message = Telegram::getMessageForAsianLivePick($pickData, $tipster);
                    break;
                case Type::NORMAL:
                    $pickData = HtmlParser::getPickFromHtml($htmlBody);
                    $message = Telegram::getMessageForPick($pickData, $tipster);
                    break;
                default:
                    return ['error' => 'Undefined type'];
            }

            $this->bot->sendMessage($this->chatId, $message);

            return ['ok' => 'Pick send correctly'];
        } catch (Throwable $e) {
            \Log::error($e->getMessage());
            return ['false' => 'Error sending message'];
        }
    }

    private function getPickType($subject)
    {
        $lowercaseSubject = strtolower($subject);

        $containsLive = Str::contains($lowercaseSubject, 'live');
        $containsAsian = Str::contains($lowercaseSubject, 'asian');
        $containsCombo = Str::contains($lowercaseSubject, 'combo');

        switch (true) {
            case $containsLive && $containsAsian:
                return new Type(Type::ASIAN_LIVE);
            case $containsLive:
                return new Type(Type::LIVE);
            case $containsCombo:
                return new Type(Type::COMBO);
            case $containsAsian:
                return new Type(Type::ASIAN);
            default:
                return new Type(Type::NORMAL);
        }
    }

}
