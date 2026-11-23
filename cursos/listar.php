<?php

require_once __DIR__ . '/../conexao.php';

$titulo = 'Cursos';
$banco = conectarBanco();

$cursos = $banco->cursos->find([], ['sort' => ['nome' => 1]]);

$msg = $_GET['msg'] ?? '';
$mensagens = [
    'cadastrado' => 'Curso cadastrado com sucesso!',
    'editado'    => 'Curso atualizado com sucesso!',
    'excluido'   => 'Curso excluído com sucesso!',
    'bloqueado'  => 'Não é possível excluir: existem estudantes matriculados neste curso.',
];

require_once __DIR__ . '/../includes/cabecalho.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Cursos</h1>
    <a href="cadastrar.php" class="btn btn-primary">Novo curso</a>
</div>

<?php if (isset($mensagens[$msg])): ?>
    <div class="alert <?= $msg === 'bloqueado' ? 'alert-warning' : 'alert-success' ?>">
        <?= $mensagens[$msg] ?>
    </div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-hover bg-white shadow-sm align-middle">
        <thead class="table-primary">
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Professor</th>
                <th>Carga horária</th>
                <th>Vagas (ocupadas / total)</th>
                <th class="text-end">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php foreach ($cursos as $curso): ?>
                <?php
                $total++;
                $ocupadas = $banco->matriculas->countDocuments(['curso_id' => $curso['_id']]);
                ?>
                <tr>
                    <td><?= htmlspecialchars($curso['codigo']) ?></td>
                    <td><?= htmlspecialchars($curso['nome']) ?></td>
                    <td><?= htmlspecialchars($curso['professor']) ?></td>
                    <td><?= (int) $curso['carga_horaria'] ?> h</td>
                    <td><?= $ocupadas ?> / <?= (int) $curso['vagas'] ?></td>
                    <td class="text-end">
                        <a href="editar.php?id=<?= $curso['_id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                        <a href="excluir.php?id=<?= $curso['_id'] ?>" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Excluir este curso?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if ($total === 0): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Nenhum curso cadastrado ainda.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>