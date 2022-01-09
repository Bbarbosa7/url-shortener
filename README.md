# Encurtador de URL's 
Powered by Lumen a Laravel micro-framework 💡 <br/>
<img align="center" alt="Laravel" height="100" width="100" src="https://raw.githubusercontent.com/devicons/devicon/master/icons/laravel/laravel-plain.svg">

Um projeto simples com o propósito de encurtar URL's.

## Utilização

- O "core" principal está em:
    - Criar uma URL encurtada através de uma requisição POST.
    - Conseguir acessar a URL encurtada através de uma requisição GET.
- Fazer a verificação da validade de uma URL.


## Como usar (passo a passo para instalação)

**Requisitos:**
- Docker.
- Docker Compose.

Toda a aplicação depende do container docker, então é indispensável tê-lo devidamente instalado em sua máquina.

**Mão na massa:**

Dentro da pasta do projeto que você baixou em sua máquina, vamos fazer a **build** da imagem:

```bash
$ docker-compose build
```

Quando concluído você terá seu docker pronto para uso. Vamos levantar o ambiente e vamos fazer isso usando o comando:

```bash
$ docker-compose up -d
```
A flag `-d` permite a execução do **Docker** em segundo plano, deixando o terminal livre para uso.

O comando abaixo irá rodar um **Shell Script** com alguns outros comandos que vão finalizar a instalação do nosso projeto para uso. Neles estão incluídos a instalação das dependências do composer, ajustes de permissão, a criação do arquivo **.env** e irá rodar a primeira migration.
```bash
$ .docker/init.sh
```

Agora já estamos com nosso ambiente funcionando e você pode conferir a [mensagem de boas vindas aqui](http://localhost:8080/).

### Atenção: caso receba mensagens de erro

Elas podem acontecer pelo fato das portas `8080` e `3306` já estarem sendo usadas por outro serviço. Nesse caso é importante alterar essas portas para uma que não esteja sendo utilizada. Para "matar" o nosso container **Docker** é só executar o comando abaixo:

```bash
$ docker-compose down
```

## Usando o encurtador

O endereço (*endpoint*) desse projeto é o [http://localhost:8080/](http://localhost:8080/). Ter ou o [**Postman**](https://www.postman.com/), [**Insomnia**](https://insomnia.rest/) instalado na sua máquina irá facilitar nas chamadas de requisição *POST* e *GET*. Fique à vontade para escolher outro método de sua preferência.

Abaixo um exemplo de uso ilustrativo:

Método | URI           | Ação                 | Exemplo
-------|---------------|----------------------|-------------------
POST   | /{url}        | URL a ser encurtada  | POST https://arcoeducacao.com.br/
GET    | /{slug}       | Acessa URL encurtada | GET http://localhost:8080/abc123ab


O código `HTTP` do retorno pode conter um dos seguintes valores:
- `201` (created) caso a URL esteja sendo encurtada pela primeira vez ou a opção `URL_ALLOW_MULTIPLE` esteja ativada
- `200` (OK) caso a URL já exista e a opção `URL_ALLOW_MULTIPLE` esteja desativada

### Acessando uma URL a partir da sua versão encurtada

Você pode realizar uma requisição GET pelo **Postman** ou **Insomnia** mas como o objetivo é acessar e renderizar o site corretamente a melhor forma de fazer isso é usando o campo de endereço do seu navegador de preferência.

### Testes

Este projeto inclui teste para garantir, dentro do possível, seu funcionamento. Para executá-lo rode o comando abaixo no diretório do projeto:

```bash
$ ./test.sh
```

## Tecnologias que envolvem esse projeto

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- [Lumen](https://lumen.laravel.com/)
- [MySQL](https://www.mysql.com/)
- [Postman](https://www.postman.com/)
- [PHPStorm](https://www.jetbrains.com/pt-br/phpstorm/)
