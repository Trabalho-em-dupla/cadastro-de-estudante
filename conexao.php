<?php
// conexao.php
// Função que conecta ao MongoDB e devolve o banco de dados.
// Todas as páginas do sistema usam: $banco = conectarBanco();

require_once __DIR__ . '/vendor/autoload.php';

function conectarBanco()
{
    // "static" guarda a conexão, para não reconectar a cada chamada
    static $banco = null;

    if ($banco !== null) {
        return $banco;
    }

    $caminhoConfig = __DIR__ . '/config.local.php';

    // Verificações com mensagens claras, úteis para quem for rodar o projeto
    if (!is_file($caminhoConfig)) {
        throw new RuntimeException(
            'Arquivo config.local.php não encontrado. Copie config.example.php para config.local.php e configure a conexão.'
        );
    }

    if (!extension_loaded('mongodb')) {
        throw new RuntimeException('A extensão mongodb do PHP não está habilitada.');
    }

    // Lê as configurações (uri e banco) do config.local.php
    $config = require $caminhoConfig;

    // O terceiro parâmetro limita a espera a 5 segundos se o Atlas não responder
    $cliente = new MongoDB\Client(
        $config['uri'],
        [],
        ['serverSelectionTimeoutMS' => 5000]
    );

    $banco = $cliente->selectDatabase($config['banco']);

    return $banco;
}