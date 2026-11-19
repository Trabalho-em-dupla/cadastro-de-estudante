<?php

declare(strict_types=1);

$configPath = __DIR__ . '/config.local.php';
$autoloadPath = __DIR__ . '/vendor/autoload.php';

if (!is_file($configPath)) {
    throw new RuntimeException(
        'Arquivo config.local.php não encontrado. Copie config.example.php para config.local.php e configure a conexão.'
    );
}

if (!is_file($autoloadPath)) {
    throw new RuntimeException('Dependências ausentes. Execute composer install na raiz do projeto.');
}

if (!extension_loaded('mongodb')) {
    throw new RuntimeException('A extensão mongodb do PHP não está habilitada.');
}

require_once $autoloadPath;

$config = require $configPath;

if (!is_array($config)) {
    throw new RuntimeException('A configuração local deve retornar um array.');
}

foreach (['uri', 'banco'] as $campo) {
    if (!isset($config[$campo]) || !is_string($config[$campo]) || trim($config[$campo]) === '') {
        throw new RuntimeException("O campo '{$campo}' deve ser preenchido em config.local.php.");
    }
}

$cliente = new MongoDB\Client(
    $config['uri'],
    [],
    ['serverSelectionTimeoutMS' => 5000]
);

$banco = $cliente->selectDatabase($config['banco']);
