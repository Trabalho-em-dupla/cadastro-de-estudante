Documentação Técnica do Projeto

Cadastro de Estudantes
Sistema web desenvolvido em PHP e MongoDB para gerenciamento acadêmico de estudantes, cursos e matrículas.

1. Visão Geral
O sistema Cadastro de Estudantes é uma aplicação web projetada para centralizar o registro de alunos, a organização de cursos ofertados (com seus respectivos professores e limites de vagas) e a conferência de matrículas realizadas.

Cadastrar, editar, listar e excluir estudantes com dados pessoais e endereço completo.
Cadastrar, editar, listar e excluir cursos, identificando o professor responsável, a carga horária e o total de vagas.
Realizar e cancelar matrículas de estudantes em múltiplos cursos.
Consultar o panorama de matrículas agrupadas tanto por curso quanto por estudante.
2. Funcionalidades Principais e Regras de Negócio
Gestão de Estudantes: Cadastro completo contendo nome, Registro Acadêmico (RA), e-mail, CPF, data de nascimento, telefone e endereço estruturado (CEP, rua, número, bairro, cidade, UF e complemento).
Gestão de Cursos: Cadastro de cursos diversos com código identificador único, professor responsável, carga horária em horas, descrição e quantidade de vagas disponíveis.
Controle de Matrículas: Vinculação de estudantes a um ou mais cursos com registro automático da data de matrícula e status da situação.
Integridade de Dados e Bloqueios:
Impede o cadastro duplicado de CPF, RA ou e-mail para estudantes, assim como códigos repetidos para cursos.
Impede que um estudante seja matriculado duas vezes no mesmo curso ou em turmas sem vagas disponíveis.
Bloqueia a exclusão de estudantes que possuam matrículas ativas e de cursos que tenham alunos matriculados.
Impede a redução da quantidade de vagas de um curso para um número inferior ao total de estudantes já matriculados.
Cadastro de Estudantes — Documentação do Sistema (PHP & MongoDB)

3. Tecnologias Utilizadas
Camada / Categoria	Tecnologia	Papel no Sistema
Back-end	PHP	Processamento das requisições (GET e POST), validações no servidor, regras de negócio e controle de redirecionamentos.
Banco de Dados	MongoDB (Atlas)	Banco de dados NoSQL orientado a documentos hospedado em nuvem para armazenamento das coleções de estudantes, cursos e matrículas.
Driver e Dependências	Composer (mongodb/mongodb)	Gerenciamento de pacotes (composer.json / composer.lock) e integração do PHP com o banco via driver oficial e MongoDB\BSON\ObjectId.
Front-end (Marcação)	HTML5 Semântico	Estruturação das páginas e construção pura dos formulários utilizando <form>, <fieldset>, <legend>, <label>, <input>, <select> e <textarea>.
Front-end (Estilo e Interação)	CSS3, Bootstrap 5.3 e JavaScript	Bootstrap 5.3 (via CDN) e css/tela.css para layout das listagens e navegação; JavaScript para abas e confirmações nativas (confirm()) em exclusões.
Servidor Web Local	Apache (XAMPP)	Execução local da aplicação PHP em ambiente localhost.
Cadastro de Estudantes — Documentação do Sistema (PHP & MongoDB)

4. Estrutura do Projeto
Cadastro-Estudante/
├── css/
│   └── tela.css              # Folha de estilos customizada
├── cursos/
│   ├── cadastrar.php         # Formulário em HTML puro e lógica de cadastro de cursos
│   ├── editar.php            # Formulário em HTML puro e lógica de edição de cursos
│   ├── excluir.php           # Exclusão de curso (com verificação de matrículas)
│   └── listar.php            # Listagem de cursos e vagas ocupadas
├── estudantes/
│   ├── cadastrar.php         # Formulário em HTML puro e lógica de cadastro de estudantes
│   ├── editar.php            # Formulário em HTML puro e lógica de edição de estudantes
│   ├── excluir.php           # Exclusão de estudante (com verificação de matrículas)
│   └── listar.php            # Listagem de estudantes cadastrados
├── includes/
│   ├── cabecalho.php         # Cabeçalho e menu de navegação compartilhado
│   └── rodape.php            # Rodapé compartilhado
├── matriculas/
│   ├── cancelar.php          # Cancelamento de matrícula
│   ├── listar.php            # Visualização de matrículas por curso e por estudante
│   └── matricular.php        # Formulário em HTML puro para vincular estudante a um curso
├── composer.json             # Dependências do projeto (mongodb/mongodb)
├── conexao.php               # Função de conexão com o MongoDB Atlas
├── config.example.php        # Modelo de configuração das credenciais do banco
├── index.php                 # Página inicial do sistema
└── testar-conexao.php        # Script auxiliar para validar a conexão com o banco
5. Pré-requisitos e Execução Local
Pré-requisitos
PHP 8.0+ com a extensão ext-mongodb habilitada no arquivo php.ini.
Servidor Apache configurado localmente (XAMPP, Laragon ou WAMP).
Composer instalado para instalação das dependências do projeto.
Cluster ativo no MongoDB Atlas com o endereço IP da máquina liberado na seção Network Access.
Passo a Passo para Instalação
Clone o repositório dentro da pasta pública do seu servidor local (por exemplo, htdocs no XAMPP):
git clone <URL-DO-REPOSITORIO> Cadastro-Estudante
cd Cadastro-Estudante
Instale as dependências via Composer:
composer install
Cadastro de Estudantes — Documentação do Sistema (PHP & MongoDB)

Configure a conexão com o banco de dados: copie o arquivo config.example.php para config.php (ou ajuste conforme o padrão definido em conexao.php) e preencha a Connection String (mongodb+srv://...) do seu cluster no MongoDB Atlas.
Inicie o servidor e valide a aplicação: ative o módulo Apache no XAMPP, acesse http://localhost/Cadastro-Estudante/testar-conexao.php para testar a comunicação com o banco e, em seguida, abra http://localhost/Cadastro-Estudante/index.php.
Nota de manutenção: Caso a aplicação seja executada em uma nova rede Wi-Fi ou computador com endereço IP diferente, adicione o novo IP na IP Access List do MongoDB Atlas para evitar bloqueios de conexão TLS.