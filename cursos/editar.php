<?php

require_once __DIR__ . '/../conexao.php';

use MongoDB\BSON\ObjectId;

$titulo = 'Editar Curso';
$erro = '';
$banco = conectarBanco();

try {
    $id = new ObjectId($_GET['id'] ?? '');
} catch (Exception $e) {
    header('Location: listar.php');
    exit;
}

$curso = $banco->cursos->findOne(['_id' => $id]);
if (!$curso) {
    header('Location: listar.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome         = trim($_POST['nome'] ?? '');
    $codigo       = trim($_POST['codigo'] ?? '');
    $descricao    = trim($_POST['descricao'] ?? '');
    $professor    = trim($_POST['professor'] ?? '');
    $cargaHoraria = (int) ($_POST['carga_horaria'] ?? 0);
    $vagas        = (int) ($_POST['vagas'] ?? 0);

    $ocupadas = $banco->matriculas->countDocuments(['curso_id' => $id]);

    if ($nome === '' || $codigo === '' || $professor === '' || $cargaHoraria <= 0 || $vagas <= 0) {
        $erro = 'Preencha nome, código, professor, carga horária e vagas (números maiores que zero).';
    } elseif ($vagas < $ocupadas) {
        $erro = "Este curso já tem $ocupadas matriculados. As vagas não podem ser menores que isso.";
    } else {
        $outro = $banco->cursos->findOne(['codigo' => $codigo, '_id' => ['$ne' => $id]]);

        if ($outro) {
            $erro = 'Já existe outro curso com esse código.';
        } else {
            $banco->cursos->updateOne(
                ['_id' => $id],
                ['$set' => [
                    'nome'          => $nome,
                    'codigo'        => $codigo,
                    'descricao'     => $descricao,
                    'carga_horaria' => $cargaHoraria,
                    'professor'     => $professor,
                    'vagas'         => $vagas,
                ]]
            );

            header('Location: listar.php?msg=editado');
            exit;
        }
    }

    $curso = [
        'nome'          => $nome,
        'codigo'        => $codigo,
        'descricao'     => $descricao,
        'carga_horaria' => $cargaHoraria,
        'professor'     => $professor,
        'vagas'         => $vagas,
    ];
}

require_once __DIR__ . '/../includes/cabecalho.php';
?>

<h1>Editar Curso</h1>

<?php if ($erro !== ''): ?>
    <p role="alert"><strong>Erro:</strong> <?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<form method="post" action="">
    <fieldset>
        <legend>Dados do Curso</legend>

        <p>
            <label for="nome">Nome do curso:</label><br>
            <input type="text" id="nome" name="nome" required
                   value="<?= htmlspecialchars($curso['nome']) ?>">
        </p>

        <p>
            <label for="codigo">Código:</label><br>
            <input type="text" id="codigo" name="codigo" required
                   value="<?= htmlspecialchars($curso['codigo']) ?>">
        </p>

        <p>
            <label for="professor">Professor:</label><br>
            <input type="text" id="professor" name="professor" required
                   value="<?= htmlspecialchars($curso['professor']) ?>">
        </p>

        <p>
            <label for="carga_horaria">Carga horária (horas):</label><br>
            <input type="number" id="carga_horaria" name="carga_horaria" min="1" required
                   value="<?= (int) $curso['carga_horaria'] ?>">
        </p>

        <p>
            <label for="vagas">Vagas:</label><br>
            <input type="number" id="vagas" name="vagas" min="1" required
                   value="<?= (int) $curso['vagas'] ?>">
        </p>

        <p>
            <label for="descricao">Descrição:</label><br>
            <textarea id="descricao" name="descricao" rows="4" cols="50"><?= htmlspecialchars($curso['descricao'] ?? '') ?></textarea>
        </p>
    </fieldset>

    <p>
        <button type="submit">Salvar alterações</button>
        <a href="listar.php">Cancelar</a>
    </p>
</form>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>