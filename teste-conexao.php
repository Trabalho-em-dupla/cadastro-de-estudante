<?php
// Teste de conexão entre PHP e MongoDB Atlas.

// Carrega as bibliotecas instaladas pelo Composer
require __DIR__ . '/vendor/autoload.php';

// Carrega as configurações (URI e nome do banco)
$config = require __DIR__ . '/config.local.php';

try {
    // Cria o cliente de conexão com o Atlas
    $cliente = new MongoDB\Client($config['uri']);

    // Envia um comando "ping" ao servidor para confirmar a conexão
    $cliente->selectDatabase('admin')->command(['ping' => 1]);

    echo '<h2>Conexão realizada com sucesso!</h2>';
    echo '<p>Banco configurado: ' . htmlspecialchars($config['banco']) . '</p>';
} catch (Exception $e) {
    // Em caso de erro, mostra a mensagem para diagnóstico
    echo '<h2>Falha na conexão</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
}