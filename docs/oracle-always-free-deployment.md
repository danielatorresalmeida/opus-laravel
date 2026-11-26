# OPUS na Oracle Cloud Always Free — preparação, sem ativar serviços
_Atualizado em 26/11/2026. Ainda não existe uma implantação pública confirmada._

## Porque esta opção
Esta proposta permite executar o OPUS (Laravel 13, PHP 8.3+, MySQL) e, se a capacidade for suficiente, outros projetos Docker (como DevFlow Hub) **num único servidor gratuito**. O modo de demonstração não altera a versão original do trabalho.

**Fontes oficiais:** 
- <https://docs.oracle.com/en-us/iaas/Content/FreeTier/freetier_topic-Always_Free_Resources.htm>
- <https://www.oracle.com/cloud/free/>
- <https://coolify.io/docs/start-with-self-hosted>
- <https://coolify.io/docs/core/infrastructure/servers/add-server>

## Limites gratuitos verificados

- `VM.Standard.A1.Flex`, ARM64, **até 2 OCPU / 12 GB RAM**, na home region e dentro da quota de horas mensal.
- **200 GB** no total em volumes de arranque/dados Always Free, e até cinco backups de volumes.
- As ofertas gratuitas dependem de elegibilidade, disponibilidade e da localização da conta.
- É normalmente exigido cartão para verificação de identidade. Podem existir retenções temporárias; isto não é uma subscrição paga.
- A Oracle pode recuperar instâncias consideradas inativas. A ausência de capacidade é um impedimento; **nunca avançar para máquinas pagas como alternativa automática**.
- O crédito promocional inicial é temporário e não autoriza despesa continuada. Não fazer upgrade para **Pay As You Go** sem aprovação.

## Preparação da conta e do servidor (a executar apenas pela titular)

1. Criar/usar conta Oracle Cloud Free Tier; verificar explicitamente a elegibilidade Always Free e a região escolhida.
2. Quando houver capacidade, criar **uma única instância Ubuntu ARM64** com o shape `VM.Standard.A1.Flex`, **2 OCPU / 12 GB no máximo**, volume inicial elegível (por exemplo 50 GB) e endereço IP adequado à opção gratuita.
3. Confirmar que a consola identifica recursos como **Always Free Eligible**. Verificar que nenhum serviço faturável foi provisionado.
4. Configurar chave SSH, firewall VCN e firewall do sistema; permitir apenas tráfego público 80/443 e SSH restrito ao administrador. Não publicar interfaces de administração, MySQL ou PostgreSQL na Internet.
5. Atualizar o sistema, instalar Docker e o **Coolify autoalojado**, seguindo apenas os scripts oficiais. Em Coolify, confirmar o funcionamento e HTTPS do painel.
6. Escolher uma implantação ARM64. O `Dockerfile` nesta branch usa PHP/Apache e Composer em imagens oficiais; validar `linux/arm64`, build e extensões antes de publicar.

## Serviços e dados

- **OPUS web**: fonte GitHub `danielatorresalmeida/opus-laravel`, branch de deployment revista, Dockerfile da raiz. `PORT=8080` e raiz Apache `public/`.
- **MariaDB ou MySQL**: container privado, com volume persistente. Escolher uma imagem que suporte ARM64; verificar a compatibilidade com Laravel e migrations. Não expor a porta 3306 publicamente.
- **Ficheiros OPUS**: volume persistente em `/var/www/html/storage/app/public` para imagens e PDFs. Garantir permissões do processo PHP (`www-data`) e ligação simbólica `public/storage`.
- **DevFlow Hub (futuro)**: containers independentes para Spring Boot, React e PostgreSQL. Confirmar consumo de RAM e CPU antes de ativar todos em simultâneo.
- Guardar backup **fora da VM**, de forma encriptada, e praticar uma restauração. Os backups gratuitos de volume da Oracle não substituem uma cópia independente.

### Variáveis de ambiente do OPUS

Guardar no gestor de secrets/variables, **nunca no repositório**:

```dotenv
APP_NAME=OPUS
APP_ENV=production
APP_DEBUG=false
APP_URL=https://opus.torresalmeida.com
APP_KEY=base64:GERAR_EM_SEGREDO
OPUS_READ_ONLY=true

DB_CONNECTION=mysql
DB_HOST=SERVICO_PRIVADO_MYSQL
DB_PORT=3306
DB_DATABASE=opus
DB_USERNAME=UTILIZADOR_DA_APLICACAO
DB_PASSWORD=GERAR_EM_SEGREDO

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=stderr
PORT=8080
```

Antes de apontar o domínio, usar no `APP_URL` o endereço **HTTPS real de testes**. As variáveis acima são ilustrativas, não credenciais válidas.

### Inicialização

Num contexto seguro dentro do serviço Laravel:

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan optimize:clear
```

Os seeders desta aplicação criam compositores e obras de demonstração, não as contas do utilizador; por defeito não carregam imagens/PDF. O seed deve ser executado uma vez por base de dados nova. Não usar `migrate:fresh` nem importar passwords ou dados pessoais da base local.

### Verificações antes da publicação

- A página `/up` responde corretamente e `/catalogue` apresenta os dados de demonstração.
- URL inexistente devolve HTTP 404 com a View personalizada.
- `OPUS_READ_ONLY=true` bloqueia `POST`, `PUT`, `PATCH` e `DELETE`. Não é possível registar contas nem editar dados.
- `/admin/openopus` permanece protegido por autenticação e pela Gate administrativa.
- O frontend mostra o aviso de demonstração e oculta links de login/registo.
- As imagens e documentos são servidos apenas quando são públicos e os direitos de partilha estão confirmados.
- O domínio só é divulgado depois de validar HTTPS, firewall, logs, health-check, backups, quotas e custos.
- Nunca supor que a existência de um commit ou um deploy iniciado prova que a aplicação está operacional.

## Importante: ainda por testar

O Dockerfile, middleware de modo consulta e estas instruções são uma **proposta de deployment em PR de rascunho**. As alterações não foram executadas no servidor Oracle, nem foi feito um build ARM64 local neste fluxo. Não fazer merge nem anunciar uma demo interativa sem testar, rever e verificar a implantação.
