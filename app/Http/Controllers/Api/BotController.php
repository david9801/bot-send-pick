<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Utils\HtmlParser;
use App\Models\Type;
use App\Utils\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class BotController extends Controller
{
    public function sendPick(Request $request)
    {
        try {
            // TODO: Validar petición, leer HTML, etc.
            $chatId = '-870620251';
            $chatIdClandestino = '-935732130';
            if (!Str::contains($request->subject, ['pick', 'PICK', 'Pick'])) {
                return response()->json(['exito' => 'Mensaje recibido pero el asunto no contiene "pick"']);
            }

            $type = $this->getPickType($request->subject);
            $bot = new \TelegramBot\Api\BotApi(config('services.telegram.token'));
            $tipster = Str::between($request->subject, 'pick from ', ' for ');

            switch ($type->getType()) {
                case Type::LIVE:
                    $pickData = HtmlParser::getLivePickFromHtml($request->html_body);
                    $message = Telegram::getMessageForLivePick($pickData, $tipster);
                    break;
                case Type::COMBO:
                    $pickData = HtmlParser::getComboPickFromHtml($request->html_body);
                    $message = Telegram::getMessageForComboPick($pickData, $tipster);
                    break;
                case Type::ASIAN:
                    $pickData = HtmlParser::getPickFromHtml($request->html_body);
                    $message = Telegram::getMessageForAsianPick($pickData, $tipster);
                    break;
                case Type::ASIAN_LIVE:
                    $pickData = HtmlParser::getLivePickFromHtml($request->html_body);
                    $message = Telegram::getMessageForAsianLivePick($pickData, $tipster);
                    break;
                case Type::NORMAL:
                    $pickData = HtmlParser::getPickFromHtml($request->html_body);
                    $message = Telegram::getMessageForPick($pickData, $tipster);
                    break;
                default:
                    return response()->json(['error' => 'Tipo de pick no encontrado'], 404);
            }

            $bot->sendMessage($chatId, $message);
            sleep(2);
            $bot->sendMessage($chatIdClandestino, $message);

            return response()->json(['exito' => 'Pick enviado correctamente']);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Error al enviar pick'], 500);
        }
    }

    private function getPickType($subject)
    {
        $lowercaseSubject = strtolower($subject);

        if (Str::contains($lowercaseSubject, ['live'])) {
            return Str::contains($lowercaseSubject, ['asian'])
            ? new Type(Type::ASIAN_LIVE)
            : new Type(Type::LIVE);
        } elseif (Str::contains($lowercaseSubject, ['combo'])) {
            return new Type(Type::COMBO);
        } elseif (Str::contains($lowercaseSubject, ['asian'])) {
            return Str::contains($lowercaseSubject, ['live'])
            ? new Type(Type::ASIAN_LIVE)
            : new Type(Type::ASIAN);
        } else {
            return new Type(Type::NORMAL);
        }
    }
}
