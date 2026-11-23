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

$qtdMatriculas = $banco->matriculas->countDocuments(['estudante_id' => $id]);

if ($qtdMatriculas > 0) {
    header('Location: listar.php?msg=bloqueado');
    exit;
}

$banco->estudantes->deleteOne(['_id' => $id]);

header('Location: listar.php?msg=excluido');
exit;