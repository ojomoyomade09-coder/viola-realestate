<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$currentPage = 'index';
$pageTitle = 'VIOLA | Find Your Amazing Home';
$bodyClass = 'page-home';
// 1. Fetch the 3 most recent public properties for the homepage grid
$sql = 'SELECT id, title, price, location, beds, baths, status, type, image_url
        FROM listings
        WHERE visibility = :visibility
        ORDER BY created_at DESC, id DESC
        LIMIT 3';

$featuredListings = array();

try {
    $stmt = db()->prepare($sql);
    $stmt->execute(array('visibility' => 'public'));
    $featuredListings = $stmt->fetchAll();
} catch (PDOException $exception) {
    // If the database fails, it will gracefully default to an empty array
    $featuredListings = array();
}


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

   <section class="listings-results" style="padding: 60px 20px; max-width: 1200px; margin: 0 auto;" aria-live="polite">
    <?php if (count($featuredListings) > 0): ?>
      <h2 style="font-family: 'Playfair Display', serif; font-size: 2.2rem; text-align: center; margin-bottom: 40px; color: #111;">Featured Properties</h2>
      
      <!-- Reusing your project's built-in responsive styling grid class -->
      <div class="listings-grid">
        <?php foreach ($featuredListings as $listing): ?>
          <?php render_property_card($listing); ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>

