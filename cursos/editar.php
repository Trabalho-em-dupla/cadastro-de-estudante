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

<h1 class="mb-4">Editar Curso</h1>

<?php if ($erro !== ''): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form method="post" class="card card-body shadow-sm">
    <div class="row g-3">
        <div class="col-md-8">
            <label class="form-label">Nome do curso</label>
            <input type="text" name="nome" class="form-control" required
                   value="<?= htmlspecialchars($curso['nome']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Código</label>
            <input type="text" name="codigo" class="form-control" required
                   value="<?= htmlspecialchars($curso['codigo']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Professor</label>
            <input type="text" name="professor" class="form-control" required
                   value="<?= htmlspecialchars($curso['professor']) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Carga horária (horas)</label>
            <input type="number" name="carga_horaria" min="1" class="form-control" required
                   value="<?= (int) $curso['carga_horaria'] ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Vagas</label>
            <input type="number" name="vagas" min="1" class="form-control" required
                   value="<?= (int) $curso['vagas'] ?>">
        </div>
        <div class="col-12">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" rows="3" class="form-control"><?= htmlspecialchars($curso['descricao'] ?? '') ?></textarea>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Salvar alterações</button>
        <a href="listar.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>