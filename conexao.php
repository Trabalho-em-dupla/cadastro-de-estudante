<?php

require_once __DIR__ . '/vendor/autoload.php';

function conectarBanco()
{
    static $banco = null;

    if ($banco !== null) {
        return $banco;
    }

    $caminhoConfig = __DIR__ . '/config.local.php';

    if (!is_file($caminhoConfig)) {
        throw new RuntimeException(
            'Arquivo config.local.php não encontrado. Copie config.example.php para config.local.php e configure a conexão.'
        );
    }

    if (!extension_loaded('mongodb')) {
        throw new RuntimeException('A extensão mongodb do PHP não está habilitada.');
    }

    $config = require $caminhoConfig;

    $cliente = new MongoDB\Client(
        $config['uri'],
        [],
        ['serverSelectionTimeoutMS' => 5000]
    );

    $banco = $cliente->selectDatabase($config['banco']);

    return $banco;
}