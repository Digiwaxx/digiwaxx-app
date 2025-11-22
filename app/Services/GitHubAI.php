<?php
namespace App\Services;

class GitHubAI
{
    public static function ask($prompt)
    {
        $token = env('GITHUB_AI_KEY');
        $payload = [
            "model" => "gpt-4o-mini",
            "messages" => [
                ["role" => "user", "content" => $prompt]
            ]
        ];
        
        $ch = curl_init("https://models.inference.ai.azure.com/chat/completions");
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer $token"
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60
        ]);
        
        $raw = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return ["error" => $error];
        }
        
        return json_decode($raw, true);
    }
}