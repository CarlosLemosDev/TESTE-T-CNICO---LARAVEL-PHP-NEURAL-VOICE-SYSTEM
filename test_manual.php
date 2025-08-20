<?php

require_once 'vendor/autoload.php';

try {
    echo "=== Teste Manual do Controller ===\n\n";
    
    // Verificar se a classe existe
    if (!class_exists('App\Http\Controllers\TextToSpeechController')) {
        echo "❌ Classe não encontrada!\n";
        exit(1);
    }
    
    echo "✅ Classe encontrada\n";
    
    // Criar reflexão da classe
    $reflection = new ReflectionClass('App\Http\Controllers\TextToSpeechController');
    
    echo "📁 Arquivo: " . $reflection->getFileName() . "\n";
    echo "📊 Métodos públicos:\n";
    
    $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
    foreach ($methods as $method) {
        if ($method->getDeclaringClass()->getName() === 'App\Http\Controllers\TextToSpeechController') {
            echo "   - " . $method->getName() . "\n";
        }
    }
    
    // Tentar instanciar
    echo "\n🔨 Tentando instanciar...\n";
    $controller = new App\Http\Controllers\TextToSpeechController();
    echo "✅ Instanciação bem-sucedida\n";
    
    // Verificar métodos específicos
    echo "\n🔍 Verificando métodos específicos:\n";
    echo "   index(): " . (method_exists($controller, 'index') ? "✅ EXISTE" : "❌ NÃO EXISTE") . "\n";
    echo "   convert(): " . (method_exists($controller, 'convert') ? "✅ EXISTE" : "❌ NÃO EXISTE") . "\n";
    
} catch (Exception $e) {
    echo "💥 Erro: " . $e->getMessage() . "\n";
    echo "📍 Arquivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
