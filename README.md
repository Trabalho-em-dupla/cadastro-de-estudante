# Cadastro de Estudantes

Sistema web para gerenciamento de estudantes, cursos e matrículas, desenvolvido em PHP e MongoDB.

## Visão geral

O projeto permite centralizar os dados acadêmicos em um ambiente simples e organizado:

- cadastrar, editar, listar e excluir estudantes;
- cadastrar, editar, listar e excluir cursos;
- matricular estudantes em cursos e cancelar matrículas;
- consultar matrículas por estudante ou por curso.

## Funcionalidades

### Estudantes

- Nome, Registro Acadêmico (RA), CPF e data de nascimento;
- E-mail e telefone;
- Endereço completo com CEP, logradouro, número, bairro, cidade, UF e complemento.

### Cursos

- Código, nome, professor responsável e descrição;
- Carga horária e quantidade de vagas.

### Matrículas

- Registro da data da matrícula;
- Consulta das matrículas por curso ou estudante;
- Cancelamento de matrícula;
- Controle de vagas disponíveis.

## Regras de negócio

- Não permite CPF, RA ou e-mail duplicados entre estudantes.
- Não permite código de curso duplicado.
- Impede duas matrículas do mesmo estudante no mesmo curso.
- Impede matrículas em cursos sem vagas disponíveis.
- Impede a exclusão de estudantes com matrículas ativas.
- Impede a exclusão de cursos que possuam estudantes matriculados.
- Impede reduzir a quantidade de vagas abaixo do número de matrículas já realizadas.

## Tecnologias

| Tecnologia | Utilização |
| --- | --- |
| PHP | Regras de negócio e processamento das requisições |
| MongoDB / MongoDB Atlas | Armazenamento das informações |
| MongoDB PHP Library | Integração entre PHP e MongoDB |
| Composer | Gerenciamento das dependências |
| HTML5 e CSS3 | Estrutura e apresentação das páginas |
| Bootstrap 5.3 | Layout responsivo e componentes da interface |
| JavaScript | Interações e confirmações no navegador |
| Apache / XAMPP | Execução local da aplicação |

## Estrutura do projeto

```text
Cadastro-Estudante/
|-- css/
|   `-- tela.css
|-- cursos/
|   |-- cadastrar.php
|   |-- editar.php
|   |-- excluir.php
|   `-- listar.php
|-- estudantes/
|   |-- cadastrar.php
|   |-- editar.php
|   |-- excluir.php
|   `-- listar.php
|-- includes/
|   |-- cabecalho.php
|   `-- rodape.php
|-- matriculas/
|   |-- cancelar.php
|   |-- listar.php
|   `-- matricular.php
|-- composer.json
|-- composer.lock
|-- config.example.php
|-- config.local.php
|-- conexao.php
|-- index.php
`-- testar-conexao.php
```

## Pré-requisitos

- PHP 8.1 ou superior;
- Extensão `mongodb` habilitada no PHP;
- Composer;
- Servidor Apache, como o fornecido pelo XAMPP;
- Conta no MongoDB Atlas ou uma instância local do MongoDB.

## Como executar

### 1. Clonar o projeto

Coloque o projeto na pasta pública do servidor local. No XAMPP, por exemplo:

```powershell
cd C:\xampp\htdocs
git clone <URL_DO_REPOSITORIO> Cadastro-Estudante
cd Cadastro-Estudante
```

### 2. Instalar as dependências

```powershell
composer install
```

### 3. Configurar a conexão

Crie o arquivo de configuração local a partir do modelo:

```powershell
Copy-Item config.example.php config.local.php
```

Edite `config.local.php` e informe a URI de conexão do MongoDB e o nome do banco:

```php
return [
    'uri'   => 'mongodb+srv://USUARIO:SENHA@SEU_CLUSTER.mongodb.net/',
    'banco' => 'cadastro_estudantes',
];
```

> O arquivo `config.local.php` contém dados sensíveis e já está incluído no `.gitignore`. Não faça upload dele para o repositório.

Para usar MongoDB local, a URI pode ser:

```text
mongodb://localhost:27017
```

### 4. Iniciar o sistema

1. Inicie o Apache pelo painel do XAMPP.
2. Acesse `http://localhost/Cadastro-Estudante/testar-conexao.php`.
3. Confirme que a conexão foi estabelecida.
4. Abra `http://localhost/Cadastro-Estudante/index.php`.

## MongoDB Atlas

No cluster do MongoDB Atlas:

1. crie um usuário de banco de dados;
2. adicione o endereço IP atual em **Network Access**;
3. obtenha a connection string em **Connect**;
4. substitua `USUARIO` e `SENHA` na configuração local.
