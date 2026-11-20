<?php
// matriculas/matricular.php
// Formulário para matricular um estudante em um curso.
// A matrícula guarda só os ObjectIds (referências), não copia os dados.

require_once __DIR__ . '/../conexao.php';

use MongoDB\BSON\ObjectId;

$titulo = 'Nova Matrícula';
$erro = '';
$banco = conectarBanco();

// Busca estudantes e cursos para preencher as listas de escolha
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
            // 1) Já está matriculado neste curso?
            $jaExiste = $banco->matriculas->findOne([
                'estudante_id' => $estudanteId,
                'curso_id'     => $cursoId,
            ]);

            // 2) Quantas vagas já foram ocupadas?
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

<h1 class="mb-4">Nova Matrícula</h1>

<?php if ($erro !== ''): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<?php if (count($estudantes) === 0 || count($cursos) === 0): ?>
    <div class="alert alert-warning">
        Para matricular, é preciso ter pelo menos um estudante e um curso cadastrados.
    </div>
<?php else: ?>
    <form method="post" class="card card-body shadow-sm">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Estudante</label>
                <select name="estudante_id" class="form-select" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($estudantes as $e): ?>
                        <option value="<?= $e['_id'] ?>">
                            <?= htmlspecialchars($e['nome']) ?> (RA <?= htmlspecialchars($e['ra']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Curso</label>
                <select name="curso_id" class="form-select" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($cursos as $c): ?>
                        <option value="<?= $c['_id'] ?>">
                            <?= htmlspecialchars($c['codigo']) ?> - <?= htmlspecialchars($c['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Matricular</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>