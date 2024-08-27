# ☄ Proposta do projeto
>Desenvolver um sistema de gerenciamento de biblioteca utilizando PHP com o framework Laravel.

>Este sistema deve permitir o cadastro de livros, autores e usuários, além de gerenciar empréstimos e devoluções de livros.

# 💻 Stack Utilizada

- Este projeto utiliza o [framework Laravel](https://laravel.com).
- Como banco de dados, o projeto utiliza o [MySQL](https://www.mysql.com).
- Para os testes, é utilizado o [PHPUnit](https://phpunit.de).
- Para o versionamento, foi utilizado o [Git](https://git-scm.com).
- Para o set-up no ambiente de desenvolvimento, foi utilizado o [Docker](https://www.docker.com).
- Para o servidor de e-mail, foram utilizadas as Notifications e as Queues do próprio laravel para gerar a fila de envio.

# ⚡️ Como Instalar

- Acesse algum diretório de sua preferência e baixe o projeto, usando:
```
git clone https://github.com/CmoratoJ/sistema-biblioteca.git
```
- Acesse o diretório sistema-biblioteca:
```
cd sistema-biblioteca/  
```
- Agora que os arquivos foram devidamente baixados para o seu diretório, configure o seu arquivo .env com base no arquivo .env.example:
```
cp .env.example .env
```
- Dentro do arquivo .env informe as credenciais para envio de email:
```
MAIL_MAILER=smtp
MAIL_HOST=seu host
MAIL_PORT=sua porta
MAIL_USERNAME=seu email
MAIL_PASSWORD=sua senha
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="seu endereço de email"
MAIL_FROM_NAME="${APP_NAME}"
```
- O projeto roda utilizando o docker portanto certifique-se de tê-lo instalado:

- Com o docker instalado rode o comando
```
docker-compose up -d
```
- Após a criação do container é necessário acessá-lo, para isso utilize o comando
```
docker exec -it myapp_php_fpm bash
```
- Rode o comando para gerar a pasta vendor e criar o autoload
```
composer install
```
- Dentro do container precisamos realizar algumas configurações, a primeira é gerar a chave para o laravel rodando o comando
```
php artisan key:generate
```
- Rode o comando abaixo para gerar o jwt secret:
```
php artisan jwt:secret
```
- Ainda dentro do conteiner crie as tabelas usando as migrations:
```
php artisan migrate
```
- Para executar os testes automatizados basta executar o comando:
```
php artisan test
```
- Lembre-se de deixar um "[worker](https://laravel.com/docs/11.x/queues#running-the-queue-worker)" rodando para o envio de e-mail assíncrono 🚨
```
php artisan queue:work
```
✅ Pronto! Agora você está pronto para usar o projeto na sua máquina com essas etapas simples.

# 📃 Documentação e API

- Com o container rodando acesse a documentação [clicando Aqui](http://localhost:8000/api/documentation)
