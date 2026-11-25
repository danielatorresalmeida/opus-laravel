# OPUS — Biblioteca de Música Erudita

**Projeto final de Programação Web (Laravel) | CESAE Digital, 2026**

O OPUS é uma aplicação web desenvolvida em Laravel para consultar e gerir **compositores** e **obras de música erudita**.

## Funcionalidades

- **Base de dados:** MySQL, migrations, seeders e relações Eloquent entre compositores e obras.
- **Gestão do catálogo:** criar, consultar, editar e eliminar compositores e obras, com validação de formulários.
- **Imagens e partituras:** carregamento de retratos e ficheiros PDF, com os respetivos caminhos guardados na base de dados.
- **Autenticação:** registo, login, logout e recuperação de palavra-passe com Laravel Fortify.
- **Autorização:** visitante consulta, utilizador autenticado edita e guarda favoritos, administrador cria e elimina.

## Catálogo e importação

- **Favoritos pessoais** de compositores e obras (relações muitos-para-muitos e tabelas pivot).
- **Pesquisa, filtros, ordenação e paginação** no catálogo.
- **Open Opus:** o administrador pode consultar compositores e obras de uma API externa e importar apenas os que selecionar.

### Open Opus — como funciona

1. O `OpenOpusController` faz um pedido HTTPS com o HTTP Client do Laravel e recebe JSON.
2. O Laravel apresenta os dados em páginas de **50**. Este é um limite de apresentação/importação definido no OPUS, não um limite global da Open Opus.
3. O administrador assinala os compositores ou obras a importar. Existe «Selecionar todos» para as obras disponíveis **apenas na página atual**.
4. O controlador verifica novamente os identificadores junto da API, evita duplicados com `openopus_id` e preserva os dados locais já preenchidos.
5. A gravação é feita com Eloquent, dentro de uma transação por pedido. Depois de importar obras, regressa-se à página em que se estava.
6. **Pesquisa sem resultados:** quando a API devolve «No composers found» ou «No works found», a página mostra um aviso informativo. Um erro de ligação ou um pedido inválido apresenta um aviso de erro.
7. **Imagens e PDFs não são importados pela API.** Estes ficheiros são adicionados através dos formulários da aplicação.

A API pode estar indisponível e, nesse caso, o catálogo que já está guardado no MySQL continua acessível. A importação é reservada ao administrador.

## Permissões

| Operação | Visitante | Utilizador autenticado | Administrador |
| --- | :---: | :---: | :---: |
| Consultar compositores e obras | Sim | Sim | Sim |
| Consultar imagens e PDFs públicos | Sim | Sim | Sim |
| Dashboard e favoritos | Não | Sim | Sim |
| Editar compositores e obras | Não | Sim | Sim |
| Criar ou eliminar compositores e obras | Não | Não | Sim |
| Importar dados da Open Opus | Não | Não | Sim |

## Tecnologias

PHP 8.3+ · Laravel 13 · MySQL · Eloquent · Blade · HTML/CSS · JavaScript simples · Fortify.

## Estrutura da base de dados

As migrations definem as tabelas `users`, `composers`, `compositions` e as tabelas intermédias `composer_user` e `composition_user` para os favoritos. A migration `add_openopus_ids_to_music_tables` acrescenta os identificadores externos para evitar duplicados nas importações.

## Melhorias futuras

- **Interface multilingue:** disponibilizar o OPUS em português e inglês, utilizando o sistema de traduções do Laravel para menus, formulários e mensagens.
- **Importação de imagens e partituras PDF:** estudar a obtenção automática destes ficheiros a partir de fontes externas adequadas, com verificação da disponibilidade, dos direitos de utilização e da atribuição das fontes. Atualmente, os ficheiros são adicionados manualmente através dos formulários.

## Instalação

1. Instalar PHP, Composer e MySQL e criar uma base de dados `opus`.
2. Na pasta do projeto, executar:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
```

3. Configurar a ligação à base de dados no `.env`.
4. Executar:

```powershell
php artisan migrate --seed
php artisan storage:link
php artisan serve --port=8001
```

5. Abrir `http://127.0.0.1:8001/catalogue` (o endereço `/` redireciona para o catálogo).

**Instalações existentes:** preservar `.env`, `storage` e a base de dados antes de atualizar o código. Não executar `migrate:fresh` sobre uma base com dados a preservar.

Os seeders inserem um catálogo inicial de compositores e obras, mas não incluem imagens nem partituras PDF. Para iniciar sessão, é necessário criar uma conta na aplicação.

## Segurança e direitos de utilização

O ficheiro `.env`, as credenciais e os dados pessoais não devem ser incluídos no repositório. As imagens e partituras PDF só devem ser partilhadas quando os direitos de utilização o permitirem. A API Open Opus não disponibiliza as partituras PDF.

## Autora

Daniela Torres Almeida — CESAE Digital, 2026.
