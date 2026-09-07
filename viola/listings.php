<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$currentPage = 'listings';
$pageTitle = 'VIOLA | Properties';
$bodyClass = 'page-inner page-listings';

$status = isset($_GET['status']) ? trim((string) $_GET['status']) : '';
$type = isset($_GET['type']) ? trim((string) $_GET['type']) : '';
$location = isset($_GET['location']) ? trim((string) $_GET['location']) : '';
$bedsRaw = isset($_GET['beds']) ? trim((string) $_GET['beds']) : '';
$bathsRaw = isset($_GET['baths']) ? trim((string) $_GET['baths']) : '';
$beds = ctype_digit($bedsRaw) ? (int) $bedsRaw : 0;
$baths = ctype_digit($bathsRaw) ? (int) $bathsRaw : 0;

$sql = 'SELECT id, title, price, location, beds, baths, status, type, image_url
        FROM listings
        WHERE visibility = :visibility';
$params = array('visibility' => 'public');

if (in_array($status, viola_statuses(), true)) {
    $sql .= ' AND status = :status';
    $params['status'] = $status;
}

if (in_array($type, viola_types(), true)) {
    $sql .= ' AND type = :type';
    $params['type'] = $type;
}

if ($beds >= 1 && $beds <= 6) {
    $sql .= ' AND beds >= :beds';
    $params['beds'] = $beds;
}

if ($baths >= 1 && $baths <= 5) {
    $sql .= ' AND baths >= :baths';
    $params['baths'] = $baths;
}

if (in_array($location, viola_locations(), true)) {
    $sql .= ' AND location = :location';
    $params['location'] = $location;
}

$sql .= ' ORDER BY created_at DESC, id DESC';

$listings = array();
$dbError = null;

try {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $listings = $stmt->fetchAll();
} catch (PDOException $exception) {
    $dbError = 'Unable to load listings right now. Import sql/schema.sql and check config/db.php.';
}

require __DIR__ . '/includes/header.php';
?>

<main class="listings-page">
  <section class="listings-hero">
    <div class="listings-hero-inner">
      <h1>Properties</h1>
      <p>Filter luxury homes by status, type, rooms, and location.</p>
      <div class="search-wrap search-wrap--solid">
        <?php require __DIR__ . '/includes/search-form.php'; ?>
      </div>
    </div>
  </section>

  <section class="listings-results" aria-live="polite">
    <?php if ($dbError !== null): ?>
      <p class="empty-state"><?= e($dbError) ?></p>
    <?php elseif (count($listings) === 0): ?>
      <p class="empty-state">No properties match your search. Try a broader filter.</p>
    <?php else: ?>
      <p class="results-count"><?= count($listings) ?> <?= count($listings) === 1 ? 'home' : 'homes' ?> found</p>
      <div class="listings-grid">
        <?php foreach ($listings as $listing): ?>
          <?php render_property_card($listing); ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
