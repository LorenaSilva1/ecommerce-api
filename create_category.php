<?php

use App\Models\Category;

require __DIR__ . '/vendor/autoload.php';

// Carrega o aplicativo Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';

// Inicializa o kernel (para ter acesso ao Eloquent e DB)
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Agora podemos usar os models normalmente
$category = Category::create([
    'name' => 'Roupas',
    'description' => 'Vestuário em geral'
]);

echo "✅ Categoria criada: {$category->name}\n";
