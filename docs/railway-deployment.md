# Railway: publicação segura do OPUS

Este guia prepara uma demonstração pública do OPUS. A configuração **não está ativada** por adicionar estes ficheiros ao GitHub: só funciona após criar serviços, configurar variáveis, executar migrations e publicar o código.

## Antes de começar

- O Railway pode cobrar pela utilização dos serviços. Rever o plano, os custos de aplicação, MySQL, volume e eventuais backups **antes** da ativação.
- Não publicar credenciais, `.env`, cópias de bases de dados com dados pessoais ou ficheiros sem direitos de utilização.
- O OPUS original permite editar o catálogo a qualquer utilizador autenticado. A demonstração pública deve começar com `OPUS_READ_ONLY=true`, que bloqueia pedidos de escrita, incluindo registo, login, edição, favoritos e importação.
- A configuração local mantém `OPUS_READ_ONLY=false` por predefinição.

## Infraestrutura sugerida

1. Criar um projeto privado no Railway (por exemplo, `OPUS`).
2. Adicionar **MySQL** com armazenamento persistente. Usar uma instância nova para a demo, sem ligar a bases de dados pessoais existentes.
3. Adicionar o serviço web a partir do repositório `danielatorresalmeida/opus-laravel`, na branch escolhida.
4. Usar o `Dockerfile` na raiz, com Apache na porta **8080**. O document root é `/var/www/html/public`.
5. Se forem permitidas imagens/PDFs na aplicação após desligar o modo de consulta, montar um volume persistente na pasta exata `/var/www/html/storage/app/public`. Sem o volume, uploads perdem-se após a substituição do contentor. O symlink `public/storage` é criado na imagem. O volume deve ter permissões de escrita para `www-data`; se não tiver, corrigir antes de permitir uploads.
6. Associar primeiro um domínio temporário Railway e testar; só depois associar `opus.torresalmeida.com` e os registos DNS pedidos pelo Railway. Não alterar MX, SPF ou DKIM do email.

## Variáveis do serviço Laravel

Nunca colocar chaves secretas ou palavras-passe no código.

| Nome | Valor recomendado |
| --- | --- |
| `APP_NAME` | `OPUS` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | URL HTTPS real da aplicação |
| `APP_KEY` | Chave aleatória Laravel gerada de forma segura (`base64:...`) |
| `OPUS_READ_ONLY` | `true` para a demonstração pública |
| `PORT` | `8080` |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `${{MySQL.MYSQLHOST}}` |
| `DB_PORT` | `${{MySQL.MYSQLPORT}}` |
| `DB_DATABASE` | `${{MySQL.MYSQLDATABASE}}` |
| `DB_USERNAME` | `${{MySQL.MYSQLUSER}}` |
| `DB_PASSWORD` | `${{MySQL.MYSQLPASSWORD}}` |
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `LOG_CHANNEL` | `stderr` |

Os nomes de referência são os do serviço MySQL e podem variar. Confirmar os nomes das variáveis disponíveis na interface do Railway antes de guardar.

## Inicialização e teste

- Na configuração de deploy do serviço Laravel, definir **pre-deploy command:** `php artisan migrate --force`. Nunca executar `migrate:fresh` em produção.
- Para a instalação inicial, executar `php artisan db:seed --force` **uma vez** depois das migrations, num contexto autorizado do serviço. Os seeders criam apenas o catálogo de exemplo, não copiam a tua base de dados local.
- A página de saúde Laravel existe em `/up`. Testar `/catalogue`, `/sources` e uma URL inexistente (deve devolver HTTP 404).
- Com `OPUS_READ_ONLY=true`, confirmar que `POST /register` e outros pedidos de escrita são rejeitados. Não usar contas reais para testar o modo de consulta.
- Confirmar primeiro que o deployment está em estado **SUCCESS** e que o catálogo carrega; só depois divulgar o endereço.
- Antes de ativar inscrições e edições reais, rever autorização de utilizadores, controlo de uploads, emails de recuperação, limites de tráfego e política de privacidade.

## Limitações

Uma nova base MySQL não contém automaticamente os compositores e obras acrescentados no computador local. Para migrá-los, exportar apenas o catálogo não sensível, com um processo de migração revisto, sem expor utilizadores, favoritos pessoais ou passwords. Imagens e PDF locais também precisam de migração separada e de direitos de partilha.
