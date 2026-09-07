<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$currentPage = 'contact';
$pageTitle = 'VIOLA | Contact';
$bodyClass = 'page-inner';

$errors = array();
$old = array(
    'name' => '',
    'email' => '',
    'topic' => 'showing',
    'message' => '',
);

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $errors[] = 'Your session expired. Please try again.';
    } else {
        $old['name'] = isset($_POST['name']) ? trim((string) $_POST['name']) : '';
        $old['email'] = isset($_POST['email']) ? trim((string) $_POST['email']) : '';
        $old['topic'] = isset($_POST['topic']) ? trim((string) $_POST['topic']) : '';
        $old['message'] = isset($_POST['message']) ? trim((string) $_POST['message']) : '';
        $topics = array('showing', 'introduction', 'listing', 'other');

        if ($old['name'] === '' || strlen($old['name']) > 120) {
            $errors[] = 'Please enter your name.';
        }
        if (filter_var($old['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors[] = 'Please enter a valid email address.';
        }
        if (!in_array($old['topic'], $topics, true)) {
            $errors[] = 'Please choose a topic.';
        }
        if (strlen($old['message']) < 12 || strlen($old['message']) > 2000) {
            $errors[] = 'Your message should be between 12 and 2,000 characters.';
        }

        if (count($errors) === 0) {
            try {
                $stmt = db()->prepare(
                    'INSERT INTO inquiries (name, email, topic, message)
                     VALUES (:name, :email, :topic, :message)'
                );
                $stmt->execute($old);
                flash_set('success', 'Message received. A specialist will follow up shortly.');
                header('Location: contact.php');
                exit;
            } catch (PDOException $exception) {
                $errors[] = 'Unable to send your message right now. Please try again later.';
            }
        }
    }
}

require __DIR__ . '/includes/header.php';
render_page_hero('Contact the desk', 'Showings, introductions, and private inventory. We reply during business hours.');
?>

<main class="section section--narrow">
  <?php render_flash(); ?>
  <?php foreach ($errors as $error): ?>
    <p class="alert alert--error"><?= e($error) ?></p>
  <?php endforeach; ?>

  <form class="stack-form" method="post" action="contact.php" novalidate>
    <?= csrf_field() ?>
    <label>
      <span>Name</span>
      <input type="text" name="name" maxlength="120" required value="<?= e($old['name']) ?>">
    </label>
    <label>
      <span>Email</span>
      <input type="email" name="email" maxlength="190" required value="<?= e($old['email']) ?>">
    </label>
    <label>
      <span>Topic</span>
      <select name="topic">
        <option value="showing"<?= selected_attr($old['topic'], 'showing') ?>>Schedule a showing</option>
        <option value="introduction"<?= selected_attr($old['topic'], 'introduction') ?>>Meet an agent</option>
        <option value="listing"<?= selected_attr($old['topic'], 'listing') ?>>Discuss a listing</option>
        <option value="other"<?= selected_attr($old['topic'], 'other') ?>>Something else</option>
      </select>
    </label>
    <label>
      <span>Message</span>
      <textarea name="message" rows="6" required><?= e($old['message']) ?></textarea>
    </label>
    <button class="btn-search form-submit" type="submit">Send message</button>
  </form>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
