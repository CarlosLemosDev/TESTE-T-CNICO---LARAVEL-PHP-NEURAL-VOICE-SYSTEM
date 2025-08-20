<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TextToSpeechController extends Controller
{
    public function index()
    {
        return view('text-to-speech');
    }

    public function convert(Request $request)
    {
        // Log para depuração
        Log::info('Convert request received', [
            'all_data' => $request->all(),
            'method' => $request->method(),
            'content_type' => $request->header('Content-Type')
        ]);
        
        // Validação para apenas português brasileiro
        try {
            $validatedData = $request->validate([
                'text' => 'required|string|max:1000',
                'language' => 'sometimes|in:pt-BR',
                'source_language' => 'sometimes|in:pt-BR',
                'target_language' => 'sometimes|in:pt-BR',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            throw $e;
        }

        try {
            $sourceText = $validatedData['text'];
            
            // Usar apenas português brasileiro como padrão
            $language = $validatedData['language'] ?? $validatedData['source_language'] ?? 'pt-BR';
            $targetLanguage = $validatedData['target_language'] ?? 'pt-BR';

            // Simulação de tradução
            $translatedText = $sourceText; // Por enquanto retorna o mesmo texto
            
            // Mensagem sempre em português
            $message = 'Texto processado com sucesso!';

            return response()->json([
                'success' => true,
                'originalText' => $sourceText,
                'translatedText' => $translatedText,
                'language' => $language,
                'targetLanguage' => $targetLanguage,
                'translationStatus' => 'original', // Para os testes, sempre original
                'message' => $message,
                // Compatibilidade com testes antigos
                'original_text' => $sourceText,
                'translated_text' => $translatedText,
                'source_language' => $language,
                'target_language' => $targetLanguage,
            ]);

        } catch (\Exception $e) {
            Log::error('Convert method error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Erro na tradução: ' . $e->getMessage(),
            ], 500);
        }
    }
}
