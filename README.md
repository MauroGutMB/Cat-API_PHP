Cat API

Website em PHP que consome TheCatAPI e mostra imagens com descricoes de racas.
Renderizacao 100% no servidor

Como rodar localmente:

1. Inicie o servidor embutido do PHP apontando para a pasta public

```bash
composer install
php -S localhost:8000
```

2. Abra http://localhost:8000

A chave padrao ja esta em config.php, mas voce pode sobrescrever via variavel de ambiente.

Arquivos principais:
- config.php
- public/index.php
- public/style.css
