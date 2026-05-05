<?php
require_once __DIR__ . '/../config.php';

$limit = 10;
if (isset($_GET['limit'])) {
  $limit = (int) $_GET['limit'];
}
if ($limit < 1 || $limit > 30) {
  $limit = 10;
}

$errorMessage = '';
$items = [];
$url = $catApiBaseUrl . '/images/search?limit=' . $limit . '&has_breeds=1';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'x-api-key: ' . $catApiKey,
  'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($response === false || $httpCode >= 400) {
  $errorMessage = 'Erro ao consultar TheCatAPI.';
  if ($curlError !== '') {
    $errorMessage .= ' ' . $curlError;
  }
} else {
  $decoded = json_decode($response, true);
  if (is_array($decoded)) {
    $items = $decoded;
  } else {
    $errorMessage = 'Resposta invalida da API.';
  }
}

function selected($current, $value) {
  return $current === $value ? ' selected' : '';
}
?>
<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Cat API Consumer</title>
    <link rel="stylesheet" href="/style.css" />
  </head>
  <body>
    <header class="hero">
      <div class="hero__content">
        <p class="eyebrow">TheCatAPI</p>
        <h1>Galeria felina com imagens e descricoes</h1>
        <p class="lede">Consumidor simples da API com foco em imagens e informacoes de racas.</p>
        <form class="controls" method="get" action="/">
          <label for="limit">Quantidade</label>
          <select id="limit" name="limit">
            <option value="5"<?php echo selected($limit, 5); ?>>5</option>
            <option value="10"<?php echo selected($limit, 10); ?>>10</option>
            <option value="15"<?php echo selected($limit, 15); ?>>15</option>
            <option value="20"<?php echo selected($limit, 20); ?>>20</option>
          </select>
          <button type="submit">Carregar</button>
        </form>
      </div>
    </header>

    <main class="content">
      <?php if ($errorMessage !== ''): ?>
        <div class="error"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
      <?php endif; ?>

      <div class="grid">
        <?php foreach ($items as $index => $item): ?>
          <?php
            $breed = isset($item['breeds'][0]) ? $item['breeds'][0] : null;
            $breedName = $breed['name'] ?? 'Raca desconhecida';
            $breedOrigin = $breed['origin'] ?? '';
            $breedDesc = $breed['description'] ?? 'Sem descricao disponivel.';
            $imgUrl = $item['url'] ?? '';
          ?>
          <article class="card" style="animation-delay: <?php echo (int) $index * 60; ?>ms">
            <?php if ($imgUrl !== ''): ?>
              <img src="<?php echo htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="Cat image" />
            <?php endif; ?>
            <div class="meta">
              <h3>
                <?php echo htmlspecialchars($breedName, ENT_QUOTES, 'UTF-8'); ?>
                <?php if ($breedOrigin !== ''): ?>
                  (<?php echo htmlspecialchars($breedOrigin, ENT_QUOTES, 'UTF-8'); ?>)
                <?php endif; ?>
              </h3>
              <p><?php echo htmlspecialchars($breedDesc, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </main>

    <footer class="footer">
      <span>Dados fornecidos por TheCatAPI.</span>
    </footer>

  </body>
</html>
