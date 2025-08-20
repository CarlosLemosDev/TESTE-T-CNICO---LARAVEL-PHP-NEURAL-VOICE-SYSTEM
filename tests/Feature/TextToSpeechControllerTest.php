<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TextToSpeechControllerTest extends TestCase
{
    use WithFaker;

    /**
     * Test que a página principal do Text-to-Speech carrega corretamente
     */
    public function test_text_to_speech_page_loads_successfully(): void
    {
        $response = $this->get('/text-to-speech');

        $response->assertStatus(200);
        $response->assertViewIs('text-to-speech');
        $response->assertSee('TESTE TÉCNICO');
        $response->assertSee('INITIATE SYNTHESIS');
    }

    /**
     * Test que a rota principal da aplicação funciona
     */
    public function test_home_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Laravel');
    }

    /**
     * Test de conversão de texto válido via POST
     */
    public function test_convert_text_with_valid_input(): void
    {
        $testText = 'Olá, este é um teste de conversão de texto para fala.';

        $response = $this->postJson('/text-to-speech/convert', [
            'text' => $testText
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'originalText' => $testText,
            'translatedText' => $testText,
            'language' => 'pt-BR',
            'translationStatus' => 'original',
            'message' => 'Texto processado com sucesso!'
        ]);
    }

    /**
     * Test de validação com texto vazio
     */
    public function test_convert_text_fails_with_empty_input(): void
    {
        $response = $this->postJson('/text-to-speech/convert', [
            'text' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['text']);
    }

    /**
     * Test de validação com texto muito longo (mais de 1000 caracteres)
     */
    public function test_convert_text_fails_with_too_long_input(): void
    {
        $longText = str_repeat('a', 1001); // 1001 caracteres

        $response = $this->postJson('/text-to-speech/convert', [
            'text' => $longText
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['text']);
    }

    /**
     * Test de validação com exatamente 1000 caracteres (limite válido)
     */
    public function test_convert_text_succeeds_with_max_length(): void
    {
        $maxText = str_repeat('a', 1000); // Exatamente 1000 caracteres

        $response = $this->postJson('/text-to-speech/convert', [
            'text' => $maxText
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'originalText' => $maxText,
            'translatedText' => $maxText
        ]);
    }

    /**
     * Test com texto contendo caracteres especiais
     */
    public function test_convert_text_with_special_characters(): void
    {
        $specialText = 'Olá! Como vai? Preço: R$ 10,50. Email: test@example.com';

        $response = $this->postJson('/text-to-speech/convert', [
            'text' => $specialText
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'originalText' => $specialText,
            'translatedText' => $specialText
        ]);
    }

    /**
     * Test com texto contendo números e símbolos
     */
    public function test_convert_text_with_numbers_and_symbols(): void
    {
        $numberText = '123 + 456 = 579. 50% de 100 é 50.';

        $response = $this->postJson('/text-to-speech/convert', [
            'text' => $numberText
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'originalText' => $numberText,
            'translatedText' => $numberText
        ]);
    }

    /**
     * Test que verifica se os headers JSON estão corretos
     */
    public function test_convert_text_returns_correct_json_headers(): void
    {
        $response = $this->postJson('/text-to-speech/convert', [
            'text' => 'Teste de headers'
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    /**
     * Test de requisição sem CSRF token (modo API ainda passa)
     */
    public function test_convert_text_with_api_mode(): void
    {
        // Em modo de teste, o CSRF está desabilitado para API calls
        $response = $this->post('/text-to-speech/convert', [
            'text' => 'Teste sem CSRF'
        ]);

        // Como estamos usando postJson nos outros testes, este deve passar também
        $response->assertStatus(200);
    }

    /**
     * Test com método HTTP inválido (GET ao invés de POST)
     */
    public function test_convert_endpoint_requires_post_method(): void
    {
        $response = $this->get('/text-to-speech/convert');

        $response->assertStatus(405); // Method Not Allowed
    }

    /**
     * Test que verifica se a validação funciona com tipo de dados incorreto
     */
    public function test_convert_text_fails_with_non_string_input(): void
    {
        $response = $this->postJson('/text-to-speech/convert', [
            'text' => 123456 // Número ao invés de string
        ]);

        // Laravel valida que o campo deve ser string, então deve falhar
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['text']);
    }

    /**
     * Test que verifica se a aplicação lida com texto em português corretamente
     */
    public function test_convert_portuguese_text(): void
    {
        $portugueseText = 'Bom dia! Este é um teste em português com acentuação: ção, ã, é, ó.';

        $response = $this->postJson('/text-to-speech/convert', [
            'text' => $portugueseText
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'originalText' => $portugueseText,
            'translatedText' => $portugueseText
        ]);
    }

    /**
     * Test de performance - verificar se a resposta é rápida
     */
    public function test_convert_text_response_time(): void
    {
        $startTime = microtime(true);

        $response = $this->postJson('/text-to-speech/convert', [
            'text' => 'Teste de performance'
        ]);

        $endTime = microtime(true);
        $responseTime = $endTime - $startTime;

        $response->assertStatus(200);
        
        // Verifica se a resposta foi em menos de 1 segundo
        $this->assertLessThan(1.0, $responseTime, 'A resposta demorou mais de 1 segundo');
    }

    /**
     * Test de múltiplas requisições sequenciais
     */
    public function test_multiple_convert_requests(): void
    {
        $texts = [
            'Primeiro teste',
            'Segundo teste',
            'Terceiro teste'
        ];

        foreach ($texts as $text) {
            $response = $this->postJson('/text-to-speech/convert', [
                'text' => $text
            ]);

            $response->assertStatus(200);
            $response->assertJson([
                'success' => true,
                'originalText' => $text,
                'translatedText' => $text
            ]);
        }
    }

    /**
     * Test de conversão com idioma português
     */
    public function test_convert_text_with_portuguese_language(): void
    {
        $response = $this->postJson('/text-to-speech/convert', [
            'text' => 'Olá, como você está?',
            'language' => 'pt-BR'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'language' => 'pt-BR',
            'message' => 'Texto processado com sucesso!'
        ]);
    }

    /**
     * Test de conversão com idioma inglês falha (não suportado)
     */
    public function test_convert_text_with_english_language(): void
    {
        $response = $this->postJson('/text-to-speech/convert', [
            'text' => 'Hello, how are you?',
            'language' => 'en-US'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['language']);
    }

    /**
     * Test validação - idioma espanhol não suportado
     */
    public function test_convert_text_with_spanish_language_fails(): void
    {
        $response = $this->postJson('/text-to-speech/convert', [
            'text' => 'Hola, ¿cómo estás?',
            'language' => 'es-ES'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['language']);
    }

    /**
     * Test validação - idioma chinês não suportado
     */
    public function test_convert_text_with_chinese_language_fails(): void
    {
        $response = $this->postJson('/text-to-speech/convert', [
            'text' => '你好吗？',
            'language' => 'zh-CN'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['language']);
    }

    /**
     * Test validação - idioma japonês não suportado
     */
    public function test_convert_text_with_japanese_language_fails(): void
    {
        $response = $this->postJson('/text-to-speech/convert', [
            'text' => 'こんにちは、元気ですか？',
            'language' => 'ja-JP'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['language']);
    }

    /**
     * Test de conversão com idioma inválido
     */
    public function test_convert_text_with_invalid_language(): void
    {
        $response = $this->postJson('/text-to-speech/convert', [
            'text' => 'Teste com idioma inválido',
            'language' => 'fr-FR'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['language']);
    }

    /**
     * Test de conversão sem especificar idioma (deve usar padrão)
     */
    public function test_convert_text_without_language_uses_default(): void
    {
        $response = $this->postJson('/text-to-speech/convert', [
            'text' => 'Teste sem idioma especificado'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'language' => 'pt-BR',
            'translationStatus' => 'original',
            'message' => 'Texto processado com sucesso!'
        ]);
    }

    /**
     * Test que verifica se o processamento está funcionando apenas para português
     */
    public function test_translation_functionality(): void
    {
        $response = $this->postJson('/text-to-speech/convert', [
            'text' => 'Olá, como você está?',
            'language' => 'pt-BR'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'originalText',
            'translatedText',
            'language',
            'translationStatus',
            'message'
        ]);
        
        $data = $response->json();
        $this->assertEquals('Olá, como você está?', $data['originalText']);
        $this->assertEquals('pt-BR', $data['language']);
        $this->assertNotEmpty($data['translatedText']);
    }

    /**
     * Test que verifica o status de tradução para português
     */
    public function test_portuguese_text_status(): void
    {
        $response = $this->postJson('/text-to-speech/convert', [
            'text' => 'Este texto está em português',
            'language' => 'pt-BR'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'translationStatus' => 'original'
        ]);
    }

    /**
     * Test de resposta com todas as chaves necessárias
     */
    public function test_response_contains_all_translation_keys(): void
    {
        $response = $this->postJson('/text-to-speech/convert', [
            'text' => 'Teste completo de processamento',
            'language' => 'pt-BR'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'originalText',
            'translatedText', 
            'language',
            'translationStatus',
            'message'
        ]);
    }
}
