<?php

require_once __DIR__ . '/../conexao.php';

use MongoDB\BSON\ObjectId;

$banco = conectarBanco();

try {
    $id = new ObjectId($_GET['id'] ?? '');
} catch (Exception $e) {
    header('Location: listar.php');
    exit;
}

$matriculados = $banco->matriculas->countDocuments(['curso_id' => $id]);

if ($matriculados > 0) {
    header('Location: listar.php?msg=bloqueado');
    exit;
}

$banco->cursos->deleteOne(['_id' => $id]);

header('Location: listar.php?msg=excluido');
exit;