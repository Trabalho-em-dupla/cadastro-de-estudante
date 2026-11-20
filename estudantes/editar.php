<?php
// estudantes/editar.php
// Mostra o formulário preenchido com os dados do estudante e salva as alterações.

require_once __DIR__ . '/../conexao.php';

use MongoDB\BSON\ObjectId;

$titulo = 'Editar Estudante';
$erro = '';
$banco = conectarBanco();

// Pega o id da URL e converte para ObjectId
try {
    $id = new ObjectId($_GET['id'] ?? '');
} catch (Exception $e) {
    header('Location: listar.php');
    exit;
}

// Busca o estudante; se não existir, volta para a lista
$estudante = $banco->estudantes->findOne(['_id' => $id]);
if (!$estudante) {
    header('Location: listar.php');
    exit;
}

// Monta um array simples com os dados para preencher o formulário
$dados = [
    'nome'            => $estudante['nome'],
    'email'           => $estudante['email'],
    'cpf'             => $estudante['cpf'],
    'data_nascimento' => $estudante['data_nascimento'],
    'telefone'        => $estudante['telefone'],
    'ra'              => $estudante['ra'],
    'cep'             => $estudante['endereco']['cep'] ?? '',
    'rua'             => $estudante['endereco']['rua'] ?? '',
    'numero'          => $estudante['endereco']['numero'] ?? '',
    'bairro'          => $estudante['endereco']['bairro'] ?? '',
    'cidade'          => $estudante['endereco']['cidade'] ?? '',
    'estado'          => $estudante['endereco']['estado'] ?? '',
    'complemento'     => $estudante['endereco']['complemento'] ?? '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome           = trim($_POST['nome'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $cpf            = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
    $dataNascimento = trim($_POST['data_nascimento'] ?? '');
    $telefone       = trim($_POST['telefone'] ?? '');
    $ra             = trim($_POST['ra'] ?? '');

    $cep         = preg_replace('/\D/', '', $_POST['cep'] ?? '');
    $rua         = trim($_POST['rua'] ?? '');
    $numero      = trim($_POST['numero'] ?? '');
    $bairro      = trim($_POST['bairro'] ?? '');
    $cidade      = trim($_POST['cidade'] ?? '');
    $estado      = strtoupper(trim($_POST['estado'] ?? ''));
    $complemento = trim($_POST['complemento'] ?? '');

    // Se der erro, o formulário continua mostrando o que a pessoa digitou
    $dados = [
        'nome' => $nome, 'email' => $email, 'cpf' => $cpf,
        'data_nascimento' => $dataNascimento, 'telefone' => $telefone, 'ra' => $ra,
        'cep' => $cep, 'rua' => $rua, 'numero' => $numero, 'bairro' => $bairro,
        'cidade' => $cidade, 'estado' => $estado, 'complemento' => $complemento,
    ];

    if ($nome === '' || $email === '' || $cpf === '' || $dataNascimento === '' || $telefone === '' || $ra === '') {
        $erro = 'Preencha todos os dados do estudante.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif (strlen($cpf) !== 11) {
        $erro = 'O CPF deve ter 11 números.';
    } elseif ($cep === '' || $rua === '' || $numero === '' || $bairro === '' || $cidade === '' || $estado === '') {
        $erro = 'Preencha o endereço (complemento é opcional).';
    } else {
        // CPF, RA e e-mail não podem repetir em OUTRO estudante ($ne = "diferente de")
        $outro = $banco->estudantes->findOne([
            '_id' => ['$ne' => $id],
            '$or' => [['cpf' => $cpf], ['ra' => $ra], ['email' => $email]],
        ]);

        if ($outro) {
            $erro = 'Já existe outro estudante com esse CPF, RA ou e-mail.';
        } else {
            $banco->estudantes->updateOne(
                ['_id' => $id],
                ['$set' => [
                    'nome'            => $nome,
                    'email'           => $email,
                    'cpf'             => $cpf,
                    'data_nascimento' => $dataNascimento,
                    'telefone'        => $telefone,
                    'ra'              => $ra,
                    'endereco'        => [
                        'cep'         => $cep,
                        'rua'         => $rua,
                        'numero'      => $numero,
                        'bairro'      => $bairro,
                        'cidade'      => $cidade,
                        'estado'      => $estado,
                        'complemento' => $complemento,
                    ],
                ]]
            );

            header('Location: listar.php?msg=editado');
            exit;
        }
    }
}

require_once __DIR__ . '/../includes/cabecalho.php';
?>

<h1 class="mb-4">Editar Estudante</h1>

<?php if ($erro !== ''): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form method="post" class="card card-body shadow-sm">

    <h5 class="mb-3">Dados do estudante</h5>
    <div class="row g-3">
        <div class="col-md-8">
            <label class="form-label">Nome completo</label>
            <input type="text" name="nome" class="form-control" required
                   value="<?= htmlspecialchars($dados['nome']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">RA (registro acadêmico)</label>
            <input type="text" name="ra" class="form-control" required
                   value="<?= htmlspecialchars($dados['ra']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" required
                   value="<?= htmlspecialchars($dados['email']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">CPF (11 números)</label>
            <input type="text" name="cpf" maxlength="14" class="form-control" required
                   value="<?= htmlspecialchars($dados['cpf']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Data de nascimento</label>
            <input type="date" name="data_nascimento" class="form-control" required
                   value="<?= htmlspecialchars($dados['data_nascimento']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control" required
                   value="<?= htmlspecialchars($dados['telefone']) ?>">
        </div>
    </div>

    <h5 class="mt-4 mb-3">Endereço</h5>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">CEP</label>
            <input type="text" name="cep" maxlength="9" class="form-control" required
                   value="<?= htmlspecialchars($dados['cep']) ?>">
        </div>
        <div class="col-md-7">
            <label class="form-label">Rua</label>
            <input type="text" name="rua" class="form-control" required
                   value="<?= htmlspecialchars($dados['rua']) ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Número</label>
            <input type="text" name="numero" class="form-control" required
                   value="<?= htmlspecialchars($dados['numero']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Bairro</label>
            <input type="text" name="bairro" class="form-control" required
                   value="<?= htmlspecialchars($dados['bairro']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Cidade</label>
            <input type="text" name="cidade" class="form-control" required
                   value="<?= htmlspecialchars($dados['cidade']) ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Estado (UF)</label>
            <input type="text" name="estado" maxlength="2" class="form-control" required
                   value="<?= htmlspecialchars($dados['estado']) ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Complemento</label>
            <input type="text" name="complemento" class="form-control"
                   value="<?= htmlspecialchars($dados['complemento']) ?>">
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Salvar alterações</button>
        <a href="listar.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>