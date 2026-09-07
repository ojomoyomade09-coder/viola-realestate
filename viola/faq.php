<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$currentPage = 'faq';
$pageTitle = 'VIOLA | FAQ';
$bodyClass = 'page-inner';

$faqs = array(
    array(
        'How does property search work?',
        'Use the homepage or Properties filters. Status, type, and location are matched exactly. Bedrooms and bathrooms are minimums, so 3+ beds includes larger homes.',
    ),
    array(
        'Why is my submitted home not on Properties yet?',
        'Owner submissions are saved as pending. They appear in My Account immediately and only move to public search after a VIOLA review.',
    ),
    array(
        'Do I need an account to submit a listing?',
        'Yes. Sign in or register under My Account, then open Submit Property. This keeps your listings attached to you.',
    ),
    array(
        'Can I contact an agent directly?',
        'Each agent profile lists email and phone. You can also send a note through Contact and request an introduction.',
    ),
    array(
        'Is the demo login safe to use locally?',
        'The seeded user is demo@viola.test with password “password”. Change that hash before any public deployment.',
    ),
);

require __DIR__ . '/includes/header.php';
render_page_hero('Questions, answered', 'A short guide to search, agents, accounts, and owner submissions.');
?>

<main class="section section--narrow">
  <div class="faq-list">
    <?php foreach ($faqs as $index => $faq): ?>
      <details class="faq-item"<?= $index === 0 ? ' open' : '' ?>>
        <summary><?= e($faq[0]) ?></summary>
        <p><?= e($faq[1]) ?></p>
      </details>
    <?php endforeach; ?>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
