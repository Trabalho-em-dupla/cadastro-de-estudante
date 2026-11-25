<?php
$base = '/Cadastro-Estudante';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($titulo ?? 'Cadastro de Estudantes') ?></title>
    <link rel="stylesheet" href="<?= $base ?>/css/tela.css">
</head>
<body>

<header>
    <nav>
        <a href="<?= $base ?>/index.php"><strong>Cadastro de Estudantes</strong></a> |
        <a href="<?= $base ?>/index.php">Início</a> |
        <a href="<?= $base ?>/cursos/listar.php">Cursos</a> |
        <a href="<?= $base ?>/estudantes/listar.php">Estudantes</a> |
        <a href="<?= $base ?>/matriculas/listar.php">Matrículas</a>
    </nav>
    <hr>
</header>

<main>