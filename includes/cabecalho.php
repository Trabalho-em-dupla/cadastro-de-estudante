<?php

$base = '/Cadastro-Estudante';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($titulo ?? 'Cadastro de Estudantes') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= $base ?>/index.php">Cadastro de Estudantes</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?= $base ?>/index.php">Início</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $base ?>/cursos/listar.php">Cursos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $base ?>/estudantes/listar.php">Estudantes</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $base ?>/matriculas/listar.php">Matrículas</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="container">