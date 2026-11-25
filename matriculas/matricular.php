<?php

require_once __DIR__ . '/../conexao.php';

use MongoDB\BSON\ObjectId;

$titulo = 'Nova Matrícula';
$erro = '';
$banco = conectarBanco();

$estudantes = $banco->estudantes->find([], ['sort' => ['nome' => 1]])->toArray();
$cursos     = $banco->cursos->find([], ['sort' => ['nome' => 1]])->toArray();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $estudanteId = new ObjectId($_POST['estudante_id'] ?? '');
        $cursoId     = new ObjectId($_POST['curso_id'] ?? '');
    } catch (Exception $e) {
        $estudanteId = null;
        $cursoId = null;
    }

    if ($estudanteId === null || $cursoId === null) {
        $erro = 'Escolha um estudante e um curso.';
    } else {
        $estudante = $banco->estudantes->findOne(['_id' => $estudanteId]);
        $curso     = $banco->cursos->findOne(['_id' => $cursoId]);

        if (!$estudante || !$curso) {
            $erro = 'Estudante ou curso não encontrado.';
        } else {
            $jaExiste = $banco->matriculas->findOne([
                'estudante_id' => $estudanteId,
                'curso_id'     => $cursoId,
            ]);

            $ocupadas = $banco->matriculas->countDocuments(['curso_id' => $cursoId]);

            if ($jaExiste) {
                $erro = 'Este estudante já está matriculado neste curso.';
            } elseif ($ocupadas >= (int) $curso['vagas']) {
                $erro = 'Não há vagas disponíveis neste curso.';
            } else {
                $banco->matriculas->insertOne([
                    'estudante_id'   => $estudanteId,
                    'curso_id'       => $cursoId,
                    'data_matricula' => date('Y-m-d'),
                    'situacao'       => 'ativa',
                ]);

                header('Location: listar.php?msg=matriculado');
                exit;
            }
        }
    }
}

require_once __DIR__ . '/../includes/cabecalho.php';
?>

<h1>Nova Matrícula</h1>

<?php if ($erro !== ''): ?>
    <p role="alert"><strong>Erro:</strong> <?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<?php if (count($estudantes) === 0 || count($cursos) === 0): ?>
    <p>Para matricular, é preciso ter pelo menos um estudante e um curso cadastrados.</p>
<?php else: ?>
    <form method="post" action="">
        <fieldset>
            <legend>Dados da Matrícula</legend>

            <p>
                <label for="estudante_id">Estudante:</label><br>
                <select id="estudante_id" name="estudante_id" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($estudantes as $e): ?>
                        <option value="<?= $e['_id'] ?>">
                            <?= htmlspecialchars($e['nome']) ?> (RA <?= htmlspecialchars($e['ra']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>

            <p>
                <label for="curso_id">Curso:</label><br>
                <select id="curso_id" name="curso_id" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($cursos as $c): ?>
                        <option value="<?= $c['_id'] ?>">
                            <?= htmlspecialchars($c['codigo']) ?> - <?= htmlspecialchars($c['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>
        </fieldset>

        <p>
            <button type="submit">Matricular</button>
            <a href="listar.php">Cancelar</a>
        </p>
    </form>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>