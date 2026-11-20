<?php
// estudantes/cadastrar.php
// Mostra o formulário de cadastro de estudante (com endereço) e salva no MongoDB.

require_once __DIR__ . '/../conexao.php';

$titulo = 'Cadastrar Estudante';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Dados do estudante
    $nome           = trim($_POST['nome'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $cpf            = preg_replace('/\D/', '', $_POST['cpf'] ?? ''); // deixa só números
    $dataNascimento = trim($_POST['data_nascimento'] ?? '');
    $telefone       = trim($_POST['telefone'] ?? '');
    $ra             = trim($_POST['ra'] ?? '');

    // Dados do endereço
    $cep         = preg_replace('/\D/', '', $_POST['cep'] ?? '');
    $rua         = trim($_POST['rua'] ?? '');
    $numero      = trim($_POST['numero'] ?? '');
    $bairro      = trim($_POST['bairro'] ?? '');
    $cidade      = trim($_POST['cidade'] ?? '');
    $estado      = strtoupper(trim($_POST['estado'] ?? ''));
    $complemento = trim($_POST['complemento'] ?? '');

    // Validações simples
    if ($nome === '' || $email === '' || $cpf === '' || $dataNascimento === '' || $telefone === '' || $ra === '') {
        $erro = 'Preencha todos os dados do estudante.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif (strlen($cpf) !== 11) {
        $erro = 'O CPF deve ter 11 números.';
    } elseif ($cep === '' || $rua === '' || $numero === '' || $bairro === '' || $cidade === '' || $estado === '') {
        $erro = 'Preencha o endereço (complemento é opcional).';
    } else {
        $banco = conectarBanco();

        // CPF, RA e e-mail não podem repetir
        $existe = $banco->estudantes->findOne([
            '$or' => [['cpf' => $cpf], ['ra' => $ra], ['email' => $email]]
        ]);

        if ($existe) {
            $erro = 'Já existe um estudante com esse CPF, RA ou e-mail.';
        } else {
            $banco->estudantes->insertOne([
                'nome'            => $nome,
                'email'           => $email,
                'cpf'             => $cpf,
                'data_nascimento' => $dataNascimento,
                'telefone'        => $telefone,
                'ra'              => $ra,
                // Endereço embutido: pertence só a este estudante (1 para 1)
                'endereco'        => [
                    'cep'         => $cep,
                    'rua'         => $rua,
                    'numero'      => $numero,
                    'bairro'      => $bairro,
                    'cidade'      => $cidade,
                    'estado'      => $estado,
                    'complemento' => $complemento,
                ],
            ]);

            header('Location: listar.php?msg=cadastrado');
            exit;
        }
    }
}

require_once __DIR__ . '/../includes/cabecalho.php';
?>

<h1 class="mb-4">Cadastrar Estudante</h1>

<?php if ($erro !== ''): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form method="post" class="card card-body shadow-sm">

    <h5 class="mb-3">Dados do estudante</h5>
    <div class="row g-3">
        <div class="col-md-8">
            <label class="form-label">Nome completo</label>
            <input type="text" name="nome" class="form-control" required
                   value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">RA (registro acadêmico)</label>
            <input type="text" name="ra" class="form-control" required
                   value="<?= htmlspecialchars($_POST['ra'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" required
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">CPF (11 números)</label>
            <input type="text" name="cpf" maxlength="14" class="form-control" required
                   value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Data de nascimento</label>
            <input type="date" name="data_nascimento" class="form-control" required
                   value="<?= htmlspecialchars($_POST['data_nascimento'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control" required
                   value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>">
        </div>
    </div>

    <h5 class="mt-4 mb-3">Endereço</h5>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">CEP</label>
            <input type="text" name="cep" maxlength="9" class="form-control" required
                   value="<?= htmlspecialchars($_POST['cep'] ?? '') ?>">
        </div>
        <div class="col-md-7">
            <label class="form-label">Rua</label>
            <input type="text" name="rua" class="form-control" required
                   value="<?= htmlspecialchars($_POST['rua'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Número</label>
            <input type="text" name="numero" class="form-control" required
                   value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Bairro</label>
            <input type="text" name="bairro" class="form-control" required
                   value="<?= htmlspecialchars($_POST['bairro'] ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Cidade</label>
            <input type="text" name="cidade" class="form-control" required
                   value="<?= htmlspecialchars($_POST['cidade'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Estado (UF)</label>
            <input type="text" name="estado" maxlength="2" class="form-control" required
                   value="<?= htmlspecialchars($_POST['estado'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Complemento</label>
            <input type="text" name="complemento" class="form-control"
                   value="<?= htmlspecialchars($_POST['complemento'] ?? '') ?>">
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="listar.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>