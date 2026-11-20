<?php
// testar-conexao.php
// Testa se o PHP consegue falar com o MongoDB Atlas.

require_once __DIR__ . '/conexao.php';

try {
    $banco = conectarBanco();
    $banco->command(['ping' => 1]);
    echo '<h1>Conexão realizada com sucesso!</h1>';
    echo '<p>O MongoDB respondeu ao teste.</p>';
} catch (Exception $e) {
    echo '<h1>Falha na conexão.</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
}