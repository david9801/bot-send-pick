<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\UseCases\SendPickUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SendPickController extends Controller
{
    private $sendPickUseCase;

    public function __construct(SendPickUseCase $sendPickUseCase)
    {
        $this->sendPickUseCase = $sendPickUseCase;
    }
    public function __invoke(Request $request)
    {
        $subject = $request->subject;
        $htmlBody = $request->html_body;

        $result = $this->sendPickUseCase->execute($subject, $htmlBody);

        if (isset($result['error'])) {
            return response()->json($result, 500);
        }

        return response()->json($result);
    }
}
