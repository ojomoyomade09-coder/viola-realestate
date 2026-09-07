<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$currentPage = 'index';
$pageTitle = 'VIOLA | Find Your Amazing Home';
$bodyClass = 'page-home';

require __DIR__ . '/includes/header.php';
?>

<main>
  <section class="hero" aria-labelledby="hero-title">
    <div class="hero-media" role="img" aria-label="Luxury modern home at dusk"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <h1 id="hero-title">Find Your Amazing Home</h1>
      <p class="hero-subtitle">Discover luxury residences curated for modern living.</p>
    </div>
    <div class="search-wrap">
      <?php require __DIR__ . '/includes/search-form.php'; ?>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
