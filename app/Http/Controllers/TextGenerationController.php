<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use Illuminate\Http\Request;

class TextGenerationController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function generateText(Request $request)
    {
        $prompt = $request->input('prompt');
        $text = $this->openAIService->generateText($prompt);

        return response()->json([
            'prompt' =>"Based on this description, we need you're suggestion, either we can select him or her or we can reject him or her, in your answer will be include accepted or rejected". $prompt,
            'generated_text' => $text,
        ]);
    }
}

