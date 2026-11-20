<?php
// estudantes/excluir.php
// Exclui um estudante, mas só se ele não estiver matriculado em nenhum curso.

require_once __DIR__ . '/../conexao.php';

use MongoDB\BSON\ObjectId;

$banco = conectarBanco();

// Pega o id da URL e converte para ObjectId; se for inválido, volta para a lista
try {
    $id = new ObjectId($_GET['id'] ?? '');
} catch (Exception $e) {
    header('Location: listar.php');
    exit;
}

// Confere se o estudante tem matrículas (relacionamento por referência)
$qtdMatriculas = $banco->matriculas->countDocuments(['estudante_id' => $id]);

if ($qtdMatriculas > 0) {
    // Bloqueia: apagar o estudante deixaria matrículas "órfãs"
    header('Location: listar.php?msg=bloqueado');
    exit;
}

// deleteOne remove o documento com esse _id
$banco->estudantes->deleteOne(['_id' => $id]);

header('Location: listar.php?msg=excluido');
exit;