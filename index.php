<?php
// index.php - Página inicial com atalhos para os três cadastros.
$titulo = 'Início';
require_once __DIR__ . '/includes/cabecalho.php';
?>

<h1 class="mb-4">Sistema de Cadastro</h1>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Cursos</h5>
                <p class="card-text">Cadastre e gerencie os cursos.</p>
                <a href="cursos/listar.php" class="btn btn-primary">Abrir</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Estudantes</h5>
                <p class="card-text">Cadastre estudantes com endereço.</p>
                <a href="estudantes/listar.php" class="btn btn-primary">Abrir</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Matrículas</h5>
                <p class="card-text">Matricule estudantes nos cursos.</p>
                <a href="matriculas/listar.php" class="btn btn-primary">Abrir</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/rodape.php'; ?>