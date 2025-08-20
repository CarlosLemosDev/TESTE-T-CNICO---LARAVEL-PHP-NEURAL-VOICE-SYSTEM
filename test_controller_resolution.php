<?php

require_once 'vendor/autoload.php';

echo "1. Testando autoload direto...\n";
try {
    if (class_exists('App\Http\Controllers\TextToSpeechController')) {
        echo "   ✓ Classe encontrada no autoload\n";
    } else {
        echo "   ✗ Classe NÃO encontrada no autoload\n";
    }
} catch (Exception $e) {
    echo "   ✗ Erro: " . $e->getMessage() . "\n";
}

echo "\n2. Testando resolução pelo Laravel...\n";
try {
    $app = require_once 'bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    $controller = app('App\Http\Controllers\TextToSpeechController');
    echo "   ✓ Controller instanciado com sucesso!\n";
    echo "   Classe: " . get_class($controller) . "\n";
    
    if (method_exists($controller, 'index')) {
        echo "   ✓ Método index() encontrado\n";
    }
    
    if (method_exists($controller, 'convert')) {
        echo "   ✓ Método convert() encontrado\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ Erro na resolução: " . $e->getMessage() . "\n";
}

echo "\n3. Testando namespace...\n";
$reflection = new ReflectionClass('App\Http\Controllers\TextToSpeechController');
echo "   Arquivo: " . $reflection->getFileName() . "\n";
echo "   Namespace: " . $reflection->getNamespaceName() . "\n";
