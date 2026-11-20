<?php
// matriculas/cancelar.php
// Cancela (remove) uma matrícula, liberando a vaga no curso.

require_once __DIR__ . '/../conexao.php';

use MongoDB\BSON\ObjectId;

$banco = conectarBanco();

// Pega o id da matrícula na URL e converte para ObjectId
try {
    $id = new ObjectId($_GET['id'] ?? '');
} catch (Exception $e) {
    header('Location: listar.php');
    exit;
}

// deleteOne remove só a matrícula; o estudante e o curso continuam existindo
$banco->matriculas->deleteOne(['_id' => $id]);

header('Location: listar.php?msg=cancelada');
exit;