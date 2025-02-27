<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class SatriaAI extends Controller
{
    public function chat(Request $request)
    {
        $userInput = strtolower(trim($request->input('message')));

        if (!$userInput) {
            return response()->json(['response' => 'Pesan tidak boleh kosong'], 400);
        }

        if ($userInput === "info") {
            return response()->json([
                'response' => '**Satria AI** Integrated from Gemini AI by Google (v1 Beta)'
            ]);
        }

        $identityQuestions = [
            "siapa kamu", "kamu siapa", "apa itu satria", "apa itu gemini", "satria ai",
            "kamu dibuat oleh siapa", "siapa yang membuat kamu", "apakah kamu gemini",
            "siapa kamu?", "kamu siapa?", "apa itu satria?", "apa itu gemini?", "satria ai?",
            "kamu dibuat oleh siapa?", "siapa yang membuat kamu?", "apakah kamu gemini?"
        ];

        foreach ($identityQuestions as $question) {
            if (strpos($userInput, $question) !== false) {
                return response()->json([
                    'response' => 'Saya adalah **Satria**, AI yang terintegrasi oleh Gemini AI yang membantu kegiatan pembelajaran di SMK Negeri 1 Sumberasih, lihat versi saya dengan prompt **"info"**. salam kenal ya 😎'
                ]);
            }
        }

        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$apiKey";

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
