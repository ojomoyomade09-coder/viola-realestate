<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$currentPage = 'agents';
$bodyClass = 'page-inner';
$agentIdRaw = isset($_GET['id']) ? trim((string) $_GET['id']) : '';
$agentId = ctype_digit($agentIdRaw) ? (int) $agentIdRaw : 0;

$agent = null;
$agentListings = array();
$agents = array();
$dbError = null;

try {
    if ($agentId > 0) {
        $stmt = db()->prepare('SELECT id, name, role, phone, email, location, bio, photo_url FROM agents WHERE id = :id LIMIT 1');
        $stmt->execute(array('id' => $agentId));
        $row = $stmt->fetch();
        $agent = ($row === false) ? null : $row;

        if ($agent !== null) {
            $listingStmt = db()->prepare(
                'SELECT id, title, price, location, beds, baths, status, type, image_url
                 FROM listings
                 WHERE agent_id = :agent_id AND visibility = :visibility
                 ORDER BY created_at DESC'
            );
            $listingStmt->execute(array(
                'agent_id' => $agentId,
                'visibility' => 'public',
            ));
            $agentListings = $listingStmt->fetchAll();
        }
    } else {
        $agents = db()->query(
            'SELECT id, name, role, phone, email, location, bio, photo_url FROM agents ORDER BY name'
        )->fetchAll();
    }
} catch (PDOException $exception) {
    $dbError = 'Unable to load agents right now. Import sql/schema.sql and check config/db.php.';
}

$pageTitle = ($agent !== null) ? 'VIOLA | ' . $agent['name'] : 'VIOLA | Agents';
$heroTitle = ($agent !== null) ? (string) $agent['name'] : 'Our Agents';
$heroSubtitle = ($agent !== null)
    ? $agent['role'] . ' · ' . $agent['location']
    : 'Licensed advisors for architectural sales, seasonal rentals, and private listings.';

require __DIR__ . '/includes/header.php';
render_page_hero($heroTitle, $heroSubtitle);
?>

<main class="section">
  <?php if ($dbError !== null): ?>
    <p class="empty-state"><?= e($dbError) ?></p>
  <?php elseif ($agentId > 0 && $agent === null): ?>
    <p class="empty-state">That agent could not be found. <a href="agents.php">View the team</a>.</p>
  <?php elseif ($agent !== null): ?>
    <article class="agent-profile">
      <img class="agent-profile__photo" src="<?= e($agent['photo_url']) ?>" alt="<?= e($agent['name']) ?>" width="420" height="520">
      <div>
        <p class="eyebrow"><?= e($agent['role']) ?></p>
        <h2><?= e($agent['name']) ?></h2>
        <p><?= e($agent['bio']) ?></p>
        <ul class="contact-list">
          <li><?= e($agent['location']) ?></li>
          <li><a href="mailto:<?= e($agent['email']) ?>"><?= e($agent['email']) ?></a></li>
          <li><?= e($agent['phone']) ?></li>
        </ul>
        <a class="text-link" href="contact.php">Request an introduction</a>
      </div>
    </article>

    <h3 class="section-title">Listings with <?= e(first_name($agent['name'])) ?></h3>
    <?php if (count($agentListings) === 0): ?>
      <p class="empty-state">No public listings assigned yet.</p>
    <?php else: ?>
      <div class="listings-grid">
        <?php foreach ($agentListings as $listing): ?>
          <?php render_property_card($listing); ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  <?php else: ?>
    <div class="card-grid">
      <?php foreach ($agents as $member): ?>
        <article class="person-card">
          <a href="agents.php?id=<?= (int) $member['id'] ?>">
            <img src="<?= e($member['photo_url']) ?>" alt="<?= e($member['name']) ?>" width="640" height="800" loading="lazy">
          </a>
          <div class="person-card__body">
            <p class="eyebrow"><?= e($member['role']) ?></p>
            <h2><a href="agents.php?id=<?= (int) $member['id'] ?>"><?= e($member['name']) ?></a></h2>
            <p><?= e($member['location']) ?></p>
            <p class="muted"><?= e($member['bio']) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
