<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GitHubAI;

class AIController extends Controller
{
    public function ask(Request $request)
    {
        $q = $request->input('q');
        
        if (!$q) {
            return response()->json(["error" => "Missing 'q' parameter"], 400);
        }
        
        $response = GitHubAI::ask($q);
        
        // Extract just the answer text
        if (isset($response['choices'][0]['message']['content'])) {
            return response()->json([
                "answer" => $response['choices'][0]['message']['content']
            ]);
        }
        
        // If something went wrong, return full response
        return response()->json($response);
    }
}