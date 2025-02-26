<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class SatriaAI extends Controller
{
    public function chat(Request $request)
    {
        $userInput = $request->input('message');

        if (!$userInput) {
            return response()->json(['response' => 'Pesan tidak boleh kosong'], 400);
        }

        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=$apiKey";

        $client = new Client();
        $response = $client->post($url, [
            'json' => [
                'contents' => [
                    ['parts' => [['text' => $userInput]]]
                ]
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $botReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak dapat memahami pertanyaan Anda.';
        $botReply = str_replace("Gemini", "Satria", $botReply);

        return response()->json(['response' => $botReply]);
    }
}
