# 🐳 De Olho no Deputado — Ambiente Docker
Projeto Laravel com front-end integrado via Vite e Tailwind CSS. Este guia mostra como configurar e rodar o ambiente **exclusivamente com Docker** utilizando o Laravel Sail.

---

## ⚙️ Requisitos
- Docker  
- Docker Compose  
- Make (opcional, mas recomendado)  
- Git

---

## 🚀 Passo a passo

### 1. Clonar o repositório e configurar o ambiente
```bash
git clone https://github.com/felipeatari/de-olho-no-deputado
cd de-olho-no-deputado
cp .env.example .env
```
> O arquivo `.env` é essencial para configurar o ambiente local. O `.env.example` serve como modelo base e deve ser mantido versionado.

---

### 2. Instalar dependências PHP
```bash
docker run --rm -v $(pwd):/app composer install
```

---

### 3. Subir o ambiente com Laravel Sail (sem o `-d` o container sobe com os logs)
```bash
./vendor/bin/sail up -d
```
> Esse comando sobe os containers em segundo plano.

---

### 4. Gerar a chave da aplicação
```bash
./vendor/bin/sail artisan key:generate
```
A variável `APP_KEY` será preenchida automaticamente no `.env`.

---

### 5. Rodar as migrations
```bash
./vendor/bin/sail artisan migrate
```

---

### 6. Popular o banco com dados fake (opcional)
```bash
./vendor/bin/sail artisan db:seed
```

---

### 7. Instalar dependências do front-end
```bash
./vendor/bin/sail npm install
```

---

### 8. Rodar Tailwind CSS em modo desenvolvimento
```bash
./vendor/bin/sail npm run dev
```
> Isso irá compilar os assets com Vite + Tailwind CSS e manter a aplicação atualizada em tempo real, recarregando a página automaticamente sempre que você alterar os arquivos (hot reload).

---

### 9. Gerar build para produção
```bash
./vendor/bin/sail npm run build
```
> Este comando gera os arquivos otimizados para produção, compilando e minificando CSS e JavaScript. Ao usar este build, o container sobe sem atualizar a página automaticamente — ideal para ambientes de produção.

---

### 10. Rodar os jobs (filas) no Laravel

Após compilar os assets (passo 8 ou 9), você pode iniciar o processamento dos jobs em fila com:

```bash
./vendor/bin/sail artisan queue:work --sleep=3 --tries=3
```

> Esse comando inicia o worker responsável por processar os jobs pendentes da fila padrão. Em ambiente de desenvolvimento, você pode deixar esse processo rodando em segundo plano durante os testes.  
> Para produção, considere usar `queue:work --daemon` junto com um gerenciador como Supervisor.

---

## 🔍 Estrutura esperada no `.env`
Ajuste as variáveis conforme necessário. Alguns exemplos:
```env
APP_NAME=DeOlhoNoDeputado
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

---

## 📂 Acessando a aplicação

### Ambiente de desenvolvimento  
Acesse no navegador:  
📍 [`http://localhost`](http://localhost)  
ou conforme configurado na variável `APP_URL` do `.env`.

### Ambiente de produção  
Para acessar o projeto em produção, abra o link:  
📍 [`http://31.97.23.210`](http://31.97.23.210)

---

## 🧰 Comandos úteis com Sail
```bash
# Subir os containers
./vendor/bin/sail up -d
# Acessar o bash do container
./vendor/bin/sail shell
# Parar os containers
./vendor/bin/sail down
# Rodar migrations e seed
./vendor/bin/sail artisan migrate:fresh --seed
# Ver logs da aplicação
./vendor/bin/sail logs -f
# Sobe o container e mantém o Tailwind CSS rodando em modo dev (com atualização automática da página)
./vendor/bin/sail up -d && ./vendor/bin/sail npm run dev
# Sobe o container sem atualizar a página (modo produção)
./vendor/bin/sail up -d && ./vendor/bin/sail npm run build
```

---

## ✅ Pronto!  
Seu ambiente Docker com Laravel está configurado. Agora é só começar a codar 🚀
