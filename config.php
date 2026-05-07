<?php

// Configurar a chave a partir do .env (ou do ambiente do servidor)
$catApiKey = getenv('CAT_API_KEY');
$env = parse_ini_file(__DIR__ . '/.env');
if (isset($env['CAT_API_KEY'])) {
	$catApiKey = $env['CAT_API_KEY'];
}


$catApiBaseUrl = 'https://api.thecatapi.com/v1';

