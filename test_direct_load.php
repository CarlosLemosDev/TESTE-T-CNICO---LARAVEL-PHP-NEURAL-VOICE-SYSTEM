<?php

// Teste direto de carregamento do controller
require_once 'vendor/autoload.php';

echo "=== TESTE DE CARREGAMENTO DIRETO ===\n";

// Incluir o arquivo diretamente
require_once 'app/Http/Controllers/TextToSpeechController.php';

echo "Arquivo incluído diretamente\n";

// Testar reflexão
$reflection = new ReflectionClass('App\Http\Controllers\TextToSpeechController');
echo "Métodos encontrados:\n";
foreach ($reflection->getMethods() as $method) {
    if ($method->getDeclaringClass()->getName() === 'App\Http\Controllers\TextToSpeechController') {
        echo "  - " . $method->getName() . "()\n";
    }
}

// Testar instanciação
$controller = new App\Http\Controllers\TextToSpeechController();
echo "Controller instanciado: " . get_class($controller) . "\n";

// Verificar se métodos existem
echo "method_exists index: " . (method_exists($controller, 'index') ? 'SIM' : 'NÃO') . "\n";
echo "method_exists convert: " . (method_exists($controller, 'convert') ? 'SIM' : 'NÃO') . "\n";
