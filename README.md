Cat API

Website em PHP que consome TheCatAPI e mostra imagens com descricoes de racas.

Como rodar localmente:

1. Instale o composer através do seu gerenciador de pacotes (Ubuntu)
```bash
curl -sS https://getcomposer.org/installer -o composer-setup.php   
```

3. Inicie o servidor embutido do PHP apontando para a pasta public

```bash
composer install
php -S localhost:8000
```

3. Abra http://localhost:8000

A chave padrao ja esta em config.php, mas voce pode sobrescrever via variavel de ambiente.

Arquivos principais:
- config.php
- public/index.php
- public/style.css
