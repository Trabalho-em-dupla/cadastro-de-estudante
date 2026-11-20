<?php
// cursos/cadastrar.php
// Mostra o formulário de cadastro de curso e salva no MongoDB.

require_once __DIR__ . '/../conexao.php';

$titulo = 'Cadastrar Curso';
$erro = '';

// Só entra aqui quando o formulário foi enviado (botão Salvar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // trim() remove espaços sobrando no começo e no fim
    $nome          = trim($_POST['nome'] ?? '');
    $codigo        = trim($_POST['codigo'] ?? '');
    $descricao     = trim($_POST['descricao'] ?? '');
    $professor     = trim($_POST['professor'] ?? '');
    $cargaHoraria  = (int) ($_POST['carga_horaria'] ?? 0);
    $vagas         = (int) ($_POST['vagas'] ?? 0);

    // Validação simples: campos obrigatórios
    if ($nome === '' || $codigo === '' || $professor === '' || $cargaHoraria <= 0 || $vagas <= 0) {
        $erro = 'Preencha nome, código, professor, carga horária e vagas (números maiores que zero).';
    } else {
        $banco = conectarBanco();

        // Não permite dois cursos com o mesmo código
        $existe = $banco->cursos->findOne(['codigo' => $codigo]);

        if ($existe) {
            $erro = 'Já existe um curso com esse código.';
        } else {
            // insertOne grava um documento na coleção "cursos"
            // (o MongoDB cria a coleção sozinho na primeira vez)
            $banco->cursos->insertOne([
                'nome'          => $nome,
                'codigo'        => $codigo,
                'descricao'     => $descricao,
                'carga_horaria' => $cargaHoraria,
                'professor'     => $professor,
                'vagas'         => $vagas,
            ]);

            // Volta para a lista (que vamos criar no próximo passo)
            header('Location: listar.php?msg=cadastrado');
            exit;
        }
    }
}

require_once __DIR__ . '/../includes/cabecalho.php';
?>

<h1 class="mb-4">Cadastrar Curso</h1>

<?php if ($erro !== ''): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form method="post" class="card card-body shadow-sm">
    <div class="row g-3">
        <div class="col-md-8">
            <label class="form-label">Nome do curso</label>
            <input type="text" name="nome" class="form-control" required
                   value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Código</label>
            <input type="text" name="codigo" class="form-control" required
                   value="<?= htmlspecialchars($_POST['codigo'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Professor</label>
            <input type="text" name="professor" class="form-control" required
                   value="<?= htmlspecialchars($_POST['professor'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Carga horária (horas)</label>
            <input type="number" name="carga_horaria" min="1" class="form-control" required
                   value="<?= htmlspecialchars($_POST['carga_horaria'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Vagas</label>
            <input type="number" name="vagas" min="1" class="form-control" required
                   value="<?= htmlspecialchars($_POST['vagas'] ?? '') ?>">
        </div>
        <div class="col-12">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" rows="3" class="form-control"><?= htmlspecialchars($_POST['descricao'] ?? '') ?></textarea>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="listar.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>