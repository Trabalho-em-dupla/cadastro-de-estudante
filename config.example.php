<?php
// MODELO de configuração (este arquivo vai para o GitHub, sem senha).
//
// Como usar:
// 1. Copie este arquivo e renomeie a cópia para config.local.php
// 2. Troque USUARIO e SENHA pelos dados do seu usuário no MongoDB Atlas
//    (ou use 'mongodb://localhost:27017' se for usar um MongoDB local)
// 3. O config.local.php é ignorado pelo Git (.gitignore), então a senha não sobe.
return [
    'uri'   => 'mongodb+srv://USUARIO:SENHA@cadastrodealunos.haajezs.mongodb.net/',
    'banco' => 'cadastro_estudantes',
];