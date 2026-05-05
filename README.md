Cat API

Website em PHP que consome TheCatAPI e mostra imagens com descricoes de racas.
Renderizacao 100% no servidor (sem JavaScript).

Como rodar localmente:

1. Inicie o servidor embutido do PHP apontando para a pasta public

```bash
php -S localhost:8000 -t public
```

2. Abra http://localhost:8000

Para mudar a quantidade, use a selecao na pagina (envia a requisicao via GET).

Configurar chave da API (opcional):

```bash
export CAT_API_KEY=live_xxx
```

A chave padrao ja esta em config.php, mas voce pode sobrescrever via variavel de ambiente.

Arquivos principais:
- config.php
- public/index.php
- public/style.css
