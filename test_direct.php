<?php

require_once 'vendor/autoload.php';

// Teste direto com include
require_once 'app/Http/Controllers/TextToSpeechController.php';

echo "=== Teste com Include Direto ===\n";

try {
    $controller = new App\Http\Controllers\TextToSpeechController();
    echo "✅ Controller carregado com sucesso!\n";
    
    echo "Métodos disponíveis:\n";
    $methods = get_class_methods($controller);
    foreach ($methods as $method) {
        echo "- $method\n";
    }
    
    if (method_exists($controller, 'index')) {
        echo "✅ Método index() encontrado!\n";
    } else {
        echo "❌ Método index() NÃO encontrado!\n";
    }
    
    if (method_exists($controller, 'convert')) {
        echo "✅ Método convert() encontrado!\n";
    } else {
        echo "❌ Método convert() NÃO encontrado!\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
