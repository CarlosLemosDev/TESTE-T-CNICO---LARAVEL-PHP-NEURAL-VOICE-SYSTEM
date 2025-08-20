<?php

require_once 'vendor/autoload.php';

echo "Testando autoload...\n";

// Verificar se a classe existe no autoload
$autoloadFiles = require 'vendor/composer/autoload_classmap.php';
echo "Total de classes no autoload: " . count($autoloadFiles) . "\n";

// Procurar nossa classe
$found = false;
foreach ($autoloadFiles as $className => $filePath) {
    if (strpos($className, 'TextToSpeechController') !== false) {
        echo "Encontrada: $className => $filePath\n";
        $found = true;
    }
}

if (!$found) {
    echo "Classe TextToSpeechController NÃO encontrada no autoload!\n";
}

// Verificar se o arquivo existe fisicamente
$expectedPath = __DIR__ . '/app/Http/Controllers/TextToSpeechController.php';
echo "Arquivo existe em $expectedPath: " . (file_exists($expectedPath) ? 'SIM' : 'NÃO') . "\n";

if (file_exists($expectedPath)) {
    echo "Conteúdo do arquivo (primeiras linhas):\n";
    $content = file_get_contents($expectedPath);
    echo substr($content, 0, 200) . "...\n";
}
