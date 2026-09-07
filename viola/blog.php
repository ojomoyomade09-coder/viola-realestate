<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$currentPage = 'blog';
$bodyClass = 'page-inner';
$slug = isset($_GET['slug']) ? trim((string) $_GET['slug']) : '';

$post = null;
$posts = array();
$dbError = null;

try {
    if ($slug !== '' && preg_match('/^[a-z0-9-]{3,180}$/', $slug) === 1) {
        $stmt = db()->prepare(
            'SELECT id, title, slug, excerpt, body, image_url, published_at FROM posts WHERE slug = :slug LIMIT 1'
        );
        $stmt->execute(array('slug' => $slug));
        $row = $stmt->fetch();
        $post = ($row === false) ? null : $row;
    } else {
        $posts = db()->query(
            'SELECT id, title, slug, excerpt, image_url, published_at FROM posts ORDER BY published_at DESC'
        )->fetchAll();
    }
} catch (PDOException $exception) {
    $dbError = 'Unable to load articles right now. Import sql/schema.sql and check config/db.php.';
}

$pageTitle = ($post !== null) ? 'VIOLA | ' . $post['title'] : 'VIOLA | Blog';
$heroTitle = ($post !== null) ? (string) $post['title'] : 'Market notes';
$heroSubtitle = ($post !== null)
    ? date('F j, Y', strtotime((string) $post['published_at']))
    : 'Neighborhood reads, listing strategy, and how VIOLA reviews homes.';

require __DIR__ . '/includes/header.php';
render_page_hero($heroTitle, $heroSubtitle);
?>

<main class="section">
  <?php if ($dbError !== null): ?>
    <p class="empty-state"><?= e($dbError) ?></p>
  <?php elseif ($slug !== '' && $post === null): ?>
    <p class="empty-state">That article could not be found. <a href="blog.php">Back to the journal</a>.</p>
  <?php elseif ($post !== null): ?>
    <article class="article">
      <img src="<?= e($post['image_url']) ?>" alt="" width="1400" height="800">
      <?php foreach (preg_split("/\n\n/", (string) $post['body']) as $paragraph): ?>
        <?php if (trim($paragraph) !== ''): ?>
          <p><?= e($paragraph) ?></p>
        <?php endif; ?>
      <?php endforeach; ?>
      <p><a class="text-link" href="blog.php">All articles</a></p>
    </article>
  <?php else: ?>
    <div class="card-grid card-grid--two">
      <?php foreach ($posts as $item): ?>
        <article class="story-card">
          <a href="blog.php?slug=<?= e($item['slug']) ?>">
            <img src="<?= e($item['image_url']) ?>" alt="" width="900" height="560" loading="lazy">
          </a>
          <div class="story-card__body">
            <p class="eyebrow"><?= e(date('F j, Y', strtotime((string) $item['published_at']))) ?></p>
            <h2><a href="blog.php?slug=<?= e($item['slug']) ?>"><?= e($item['title']) ?></a></h2>
            <p><?= e($item['excerpt']) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
