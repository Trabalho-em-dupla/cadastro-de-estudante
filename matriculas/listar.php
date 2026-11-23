<?php

require_once __DIR__ . '/../conexao.php';

$titulo = 'Matrículas';
$banco = conectarBanco();

$msg = $_GET['msg'] ?? '';
$mensagens = [
    'matriculado' => 'Matrícula realizada com sucesso!',
    'cancelada'   => 'Matrícula cancelada com sucesso!',
];

$estudantes = [];
foreach ($banco->estudantes->find([], ['sort' => ['nome' => 1]]) as $e) {
    $estudantes[(string) $e['_id']] = $e;
}

$cursos = [];
foreach ($banco->cursos->find([], ['sort' => ['nome' => 1]]) as $c) {
    $cursos[(string) $c['_id']] = $c;
}

$porCurso = [];
$porEstudante = [];
foreach ($banco->matriculas->find() as $m) {
    $idCurso = (string) $m['curso_id'];
    $idEstudante = (string) $m['estudante_id'];
    $porCurso[$idCurso][] = $m;
    $porEstudante[$idEstudante][] = $m;
}

require_once __DIR__ . '/../includes/cabecalho.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Matrículas</h1>
    <a href="matricular.php" class="btn btn-primary">Nova matrícula</a>
</div>

<?php if (isset($mensagens[$msg])): ?>
    <div class="alert alert-success"><?= $mensagens[$msg] ?></div>
<?php endif; ?>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#aba-curso" type="button">Por curso</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#aba-estudante" type="button">Por estudante</button>
    </li>
</ul>

<div class="tab-content">

    <div class="tab-pane fade show active" id="aba-curso">
        <?php if (count($cursos) === 0): ?>
            <p class="text-muted">Nenhum curso cadastrado.</p>
        <?php endif; ?>

        <?php foreach ($cursos as $idCurso => $curso): ?>
            <?php $lista = $porCurso[$idCurso] ?? []; ?>
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex justify-content-between">
                    <strong><?= htmlspecialchars($curso['codigo']) ?> - <?= htmlspecialchars($curso['nome']) ?></strong>
                    <span class="badge text-bg-primary"><?= count($lista) ?> / <?= (int) $curso['vagas'] ?> vagas</span>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($lista as $m): ?>
                        <?php $est = $estudantes[(string) $m['estudante_id']] ?? null; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <?= $est ? htmlspecialchars($est['nome']) . ' (RA ' . htmlspecialchars($est['ra']) . ')' : 'Estudante removido' ?>
                                <small class="text-muted">- desde <?= htmlspecialchars($m['data_matricula']) ?></small>
                            </span>
                            <a href="cancelar.php?id=<?= $m['_id'] ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Cancelar esta matrícula?');">Cancelar</a>
                        </li>
                    <?php endforeach; ?>
                    <?php if (count($lista) === 0): ?>
                        <li class="list-group-item text-muted">Nenhum estudante matriculado.</li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="tab-pane fade" id="aba-estudante">
        <?php if (count($estudantes) === 0): ?>
            <p class="text-muted">Nenhum estudante cadastrado.</p>
        <?php endif; ?>

        <?php foreach ($estudantes as $idEstudante => $estudante): ?>
            <?php $lista = $porEstudante[$idEstudante] ?? []; ?>
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex justify-content-between">
                    <strong><?= htmlspecialchars($estudante['nome']) ?> (RA <?= htmlspecialchars($estudante['ra']) ?>)</strong>
                    <span class="badge text-bg-secondary"><?= count($lista) ?> curso(s)</span>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($lista as $m): ?>
                        <?php $cur = $cursos[(string) $m['curso_id']] ?? null; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <?= $cur ? htmlspecialchars($cur['codigo']) . ' - ' . htmlspecialchars($cur['nome']) : 'Curso removido' ?>
                                <small class="text-muted">- desde <?= htmlspecialchars($m['data_matricula']) ?></small>
                            </span>
                            <a href="cancelar.php?id=<?= $m['_id'] ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Cancelar esta matrícula?');">Cancelar</a>
                        </li>
                    <?php endforeach; ?>
                    <?php if (count($lista) === 0): ?>
                        <li class="list-group-item text-muted">Não está matriculado em nenhum curso.</li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>