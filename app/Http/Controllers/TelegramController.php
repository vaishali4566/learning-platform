<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            $botToken = env('TELEGRAM_BOT_TOKEN');
            $chatId = env('TELEGRAM_CHAT_ID');

            if (!$botToken || !$chatId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing Telegram credentials in .env'
                ], 500);
            }

            $message = "📩 *New Website Message!*\n\n"
                     . "👤 Name: {$request->name}\n"
                     . "📧 Email: {$request->email}\n"
                     . "💬 Message: {$request->message}";

            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown'
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Message sent to Telegram']);
            }

            return response()->json([
                'success' => false,
                'message' => 'Telegram API error',
                'details' => $response->body()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
