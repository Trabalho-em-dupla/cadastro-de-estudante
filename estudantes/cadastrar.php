<?php

require_once __DIR__ . '/../conexao.php';

$titulo = 'Cadastrar Estudante';
$erro = '';

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

<h1>Cadastrar Estudante</h1>

<?php if ($erro !== ''): ?>
    <p role="alert"><strong>Erro:</strong> <?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<form method="post" action="">
    <fieldset>
        <legend>Dados do estudante</legend>

        <p>
            <label for="nome">Nome completo:</label><br>
            <input type="text" id="nome" name="nome" required
                   value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
        </p>

        <p>
            <label for="ra">RA (registro acadêmico):</label><br>
            <input type="text" id="ra" name="ra" required
                   value="<?= htmlspecialchars($_POST['ra'] ?? '') ?>">
        </p>

        <p>
            <label for="email">E-mail:</label><br>
            <input type="email" id="email" name="email" required
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </p>

        <p>
            <label for="cpf">CPF (11 números):</label><br>
            <input type="text" id="cpf" name="cpf" maxlength="14" required
                   value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>">
        </p>

        <p>
            <label for="data_nascimento">Data de nascimento:</label><br>
            <input type="date" id="data_nascimento" name="data_nascimento" required
                   value="<?= htmlspecialchars($_POST['data_nascimento'] ?? '') ?>">
        </p>

        <p>
            <label for="telefone">Telefone:</label><br>
            <input type="tel" id="telefone" name="telefone" required
                   value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>">
        </p>
    </fieldset>

    <fieldset>
        <legend>Endereço</legend>

        <p>
            <label for="cep">CEP:</label><br>
            <input type="text" id="cep" name="cep" maxlength="9" required
                   value="<?= htmlspecialchars($_POST['cep'] ?? '') ?>">
        </p>

        <p>
            <label for="rua">Rua:</label><br>
            <input type="text" id="rua" name="rua" required
                   value="<?= htmlspecialchars($_POST['rua'] ?? '') ?>">
        </p>

        <p>
            <label for="numero">Número:</label><br>
            <input type="text" id="numero" name="numero" required
                   value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>">
        </p>

        <p>
            <label for="bairro">Bairro:</label><br>
            <input type="text" id="bairro" name="bairro" required
                   value="<?= htmlspecialchars($_POST['bairro'] ?? '') ?>">
        </p>

        <p>
            <label for="cidade">Cidade:</label><br>
            <input type="text" id="cidade" name="cidade" required
                   value="<?= htmlspecialchars($_POST['cidade'] ?? '') ?>">
        </p>

        <p>
            <label for="estado">Estado (UF):</label><br>
            <input type="text" id="estado" name="estado" maxlength="2" required
                   value="<?= htmlspecialchars($_POST['estado'] ?? '') ?>">
        </p>

        <p>
            <label for="complemento">Complemento:</label><br>
            <input type="text" id="complemento" name="complemento"
                   value="<?= htmlspecialchars($_POST['complemento'] ?? '') ?>">
        </p>
    </fieldset>

    <p>
        <button type="submit">Salvar</button>
        <a href="listar.php">Cancelar</a>
    </p>
</form>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>