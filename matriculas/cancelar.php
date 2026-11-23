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

$banco->matriculas->deleteOne(['_id' => $id]);

header('Location: listar.php?msg=cancelada');
exit;