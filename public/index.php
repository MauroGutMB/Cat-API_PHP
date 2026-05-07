<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';

$limit = 10;
if (isset($_GET['limit'])) {
  $limit = (int) $_GET['limit'];
}
if ($limit < 1 || $limit > 100) {
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

$translator = null;
$translateCache = [];

try {
  $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('pt-BR', 'en');
} catch (Throwable $e) {
  $translator = null;
}

function translateText($text, $translator, &$cache) {
  if (!is_string($text) || $text === '' || $translator === null) {
    return $text;
  }

  if (isset($cache[$text])) {
    return $cache[$text];
  }

  try {
    $translated = $translator->translate($text);
    $cache[$text] = $translated;
    return $translated;
  } catch (Throwable $e) {
    $cache[$text] = $text;
    return $text;
  }
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
          <input
            id="limit"
            name="limit"
            type="number"
            min="1"
            max="100"
            value="<?php echo (int) $limit; ?>"
            required
          />
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

            if ($breed !== null && $translator !== null) {
              $breedName = translateText($breedName, $translator, $translateCache);
              $breedDesc = translateText($breedDesc, $translator, $translateCache);
            }
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
