<?php

require_once __DIR__ . '/../conexao.php';

$titulo = 'Cadastrar Curso';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome          = trim($_POST['nome'] ?? '');
    $codigo        = trim($_POST['codigo'] ?? '');
    $descricao     = trim($_POST['descricao'] ?? '');
    $professor     = trim($_POST['professor'] ?? '');
    $cargaHoraria  = (int) ($_POST['carga_horaria'] ?? 0);
    $vagas         = (int) ($_POST['vagas'] ?? 0);

    if ($nome === '' || $codigo === '' || $professor === '' || $cargaHoraria <= 0 || $vagas <= 0) {
        $erro = 'Preencha nome, código, professor, carga horária e vagas (números maiores que zero).';
    } else {
        $banco = conectarBanco();

        $existe = $banco->cursos->findOne(['codigo' => $codigo]);

        if ($existe) {
            $erro = 'Já existe um curso com esse código.';
        } else {
            $banco->cursos->insertOne([
                'nome'          => $nome,
                'codigo'        => $codigo,
                'descricao'     => $descricao,
                'carga_horaria' => $cargaHoraria,
                'professor'     => $professor,
                'vagas'         => $vagas,
            ]);

            header('Location: listar.php?msg=cadastrado');
            exit;
        }
    }
}

require_once __DIR__ . '/../includes/cabecalho.php';
?>

<h1>Cadastrar Curso</h1>

<?php if ($erro !== ''): ?>
    <p role="alert"><strong>Erro:</strong> <?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<form method="post" action="">
    <fieldset>
        <legend>Dados do Curso</legend>

        <p>
            <label for="nome">Nome do curso:</label><br>
            <input type="text" id="nome" name="nome" required
                   value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
        </p>

        <p>
            <label for="codigo">Código:</label><br>
            <input type="text" id="codigo" name="codigo" required
                   value="<?= htmlspecialchars($_POST['codigo'] ?? '') ?>">
        </p>

        <p>
            <label for="professor">Professor:</label><br>
            <input type="text" id="professor" name="professor" required
                   value="<?= htmlspecialchars($_POST['professor'] ?? '') ?>">
        </p>

        <p>
            <label for="carga_horaria">Carga horária (horas):</label><br>
            <input type="number" id="carga_horaria" name="carga_horaria" min="1" required
                   value="<?= htmlspecialchars($_POST['carga_horaria'] ?? '') ?>">
        </p>

        <p>
            <label for="vagas">Vagas:</label><br>
            <input type="number" id="vagas" name="vagas" min="1" required
                   value="<?= htmlspecialchars($_POST['vagas'] ?? '') ?>">
        </p>

        <p>
            <label for="descricao">Descrição:</label><br>
            <textarea id="descricao" name="descricao" rows="4" cols="50"><?= htmlspecialchars($_POST['descricao'] ?? '') ?></textarea>
        </p>
    </fieldset>

    <p>
        <button type="submit">Salvar</button>
        <a href="listar.php">Cancelar</a>
    </p>
</form>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>