<?php

require_once 'vendor/autoload.php';

try {
    echo "Verificando se a classe existe...\n";
    if (class_exists('App\Http\Controllers\TextToSpeechController')) {
        echo "✓ Classe existe!\n";
        
        $reflection = new ReflectionClass('App\Http\Controllers\TextToSpeechController');
        echo "Métodos públicos encontrados:\n";
        
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            echo "- " . $method->getName() . "()\n";
        }
        
        echo "\nTentando instanciar a classe...\n";
        $controller = new App\Http\Controllers\TextToSpeechController();
        echo "✓ Classe instanciada com sucesso!\n";
        
    } else {
        echo "✗ Classe NÃO existe!\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
