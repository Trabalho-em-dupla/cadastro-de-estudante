<?php

try {
    require __DIR__ . '/conexao.php';
    $banco->command(['ping' => 1])->toArray();

    $titulo = 'Conexão realizada com sucesso!';
    $detalhe = 'O MongoDB respondeu ao teste.';
    $codigo = 200;
} catch (Throwable $erro) {
    $titulo = 'Falha na conexão.';
    $detalhe = preg_replace(
        '/(mongodb(?:\+srv)?:\/\/)[^@\s]+@/i',
        '$1[credenciais]@',
        $erro->getMessage()
    ) ?? 'Não foi possível obter detalhes do erro.';
    $codigo = 500;
}

http_response_code($codigo);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de conexão MongoDB</title>
</head>
<body>
    <h1><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></h1>
    <p><?= htmlspecialchars($detalhe, ENT_QUOTES, 'UTF-8') ?></p>
</body>
</html>
