<?php

require_once __DIR__ . '/../conexao.php';

$titulo = 'Estudantes';
$banco = conectarBanco();

$estudantes = $banco->estudantes->find([], ['sort' => ['nome' => 1]]);

$msg = $_GET['msg'] ?? '';
$mensagens = [
    'cadastrado' => 'Estudante cadastrado com sucesso!',
    'editado'    => 'Estudante atualizado com sucesso!',
    'excluido'   => 'Estudante excluído com sucesso!',
    'bloqueado'  => 'Não é possível excluir: este estudante está matriculado em algum curso.',
];

require_once __DIR__ . '/../includes/cabecalho.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Estudantes</h1>
    <a href="cadastrar.php" class="btn btn-primary">Novo estudante</a>
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
                <th>RA</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Cidade / UF</th>
                <th>Cursos</th>
                <th class="text-end">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php foreach ($estudantes as $estudante): ?>
                <?php
                $total++;
                $qtdCursos = $banco->matriculas->countDocuments(['estudante_id' => $estudante['_id']]);
                ?>
                <tr>
                    <td><?= htmlspecialchars($estudante['ra']) ?></td>
                    <td><?= htmlspecialchars($estudante['nome']) ?></td>
                    <td><?= htmlspecialchars($estudante['email']) ?></td>
                    <td><?= htmlspecialchars($estudante['endereco']['cidade']) ?> / <?= htmlspecialchars($estudante['endereco']['estado']) ?></td>
                    <td><?= $qtdCursos ?></td>
                    <td class="text-end">
                        <a href="editar.php?id=<?= $estudante['_id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                        <a href="excluir.php?id=<?= $estudante['_id'] ?>" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Excluir este estudante?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if ($total === 0): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Nenhum estudante cadastrado ainda.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>