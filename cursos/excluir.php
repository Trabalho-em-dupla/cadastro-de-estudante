<?php
// cursos/excluir.php
// Exclui um curso, mas só se não houver estudantes matriculados nele.

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

// Confere se existem matrículas ligadas a este curso (relacionamento por referência)
$matriculados = $banco->matriculas->countDocuments(['curso_id' => $id]);

if ($matriculados > 0) {
    // Bloqueia: apagar o curso deixaria matrículas "órfãs"
    header('Location: listar.php?msg=bloqueado');
    exit;
}

// deleteOne remove o documento com esse _id
$banco->cursos->deleteOne(['_id' => $id]);

header('Location: listar.php?msg=excluido');
exit;