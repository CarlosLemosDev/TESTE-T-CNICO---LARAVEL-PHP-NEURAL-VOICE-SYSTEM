<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $controller = new App\Http\Controllers\TextToSpeechController();
    echo "Controller loaded successfully!\n";
    echo "Methods: " . implode(', ', get_class_methods($controller)) . "\n";
    echo "Has index method: " . (method_exists($controller, 'index') ? 'YES' : 'NO') . "\n";
    echo "Has convert method: " . (method_exists($controller, 'convert') ? 'YES' : 'NO') . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
