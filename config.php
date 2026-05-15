<?php

// Configurar a chave a partir do .env (ou do ambiente do servidor)
$catApiKey = getenv('CAT_API_KEY');
$env = parse_ini_file(__DIR__ . '/.env');
if (isset($env['CAT_API_KEY'])) {
	$catApiKey = $env['CAT_API_KEY'];
}

// Caso não haja .env, usar chave hardcoded. (jamais use isso em produção)
$catApiKey = "live_WTJvA8KE6SYgLR0USUe9kPdHM3KcstAcaXGchkMz7u9c9BSKh6pDhCbvwGHO0Ok0";


$catApiBaseUrl = 'https://api.thecatapi.com/v1';

