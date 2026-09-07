<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$currentPage = 'pages';
$pageTitle = 'VIOLA | About';
$bodyClass = 'page-inner';

require __DIR__ . '/includes/header.php';
render_page_hero('About VIOLA', 'A boutique desk for cinematic homes, private listings, and neighborhood-level advice.');
?>

<main class="section">
  <div class="prose-split">
    <div>
      <p class="eyebrow">The studio</p>
      <h2>Quiet representation for homes that deserve a slower look.</h2>
      <p>VIOLA is built for buyers, renters, and owners who care about architecture as much as address. We keep the public catalog tight, review owner submissions before they go live, and pair every serious inquiry with a specialist who already works that market.</p>
      <p>The Pages section collects the practical side of the brand: who we are, how listings are reviewed, and how to reach the desk.</p>
    </div>
    <ul class="stat-list">
      <li><strong>10</strong> curated public listings to start</li>
      <li><strong>6</strong> market specialists</li>
      <li><strong>Pending review</strong> for every owner submission</li>
    </ul>
  </div>

  <div class="hub-grid">
    <a class="hub-card" href="faq.php">
      <p class="eyebrow">Pages</p>
      <h3>FAQ</h3>
      <p>Search, submissions, reviews, and what “pending” means for your listing.</p>
    </a>
    <a class="hub-card" href="contact.php">
      <p class="eyebrow">Pages</p>
      <h3>Contact</h3>
      <p>Write the desk about a showing, an introduction, or a private off-market home.</p>
    </a>
    <a class="hub-card" href="agents.php">
      <p class="eyebrow">Team</p>
      <h3>Agents</h3>
      <p>Meet the brokers who cover coastal estates, condos, and family streets.</p>
    </a>
    <a class="hub-card" href="submit.php">
      <p class="eyebrow">Owners</p>
      <h3>Submit a property</h3>
      <p>Create an account, send photos and facts, and track review from My Account.</p>
    </a>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
