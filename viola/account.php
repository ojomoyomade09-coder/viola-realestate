<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$currentPage = 'account';
$pageTitle = 'VIOLA | My Account';
$bodyClass = 'page-inner';

$nextParam = isset($_GET['next']) ? $_GET['next'] : (isset($_POST['next']) ? $_POST['next'] : 'account.php');
$next = safe_next((string) $nextParam);
$mode = (isset($_GET['mode']) && $_GET['mode'] === 'register') ? 'register' : 'login';
$errors = array();
$old = array('name' => '', 'email' => '');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? (string) $_POST['action'] : '';

    if (!csrf_verify()) {
        $errors[] = 'Your session expired. Please try again.';
    } elseif ($action === 'login') {
        $mode = 'login';
        $email = isset($_POST['email']) ? strtolower(trim((string) $_POST['email'])) : '';
        $password = isset($_POST['password']) ? (string) $_POST['password'] : '';
        $old['email'] = $email;

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false || $password === '') {
            $errors[] = 'Enter a valid email and password.';
        } else {
            try {
                $stmt = db()->prepare('SELECT id, password_hash FROM users WHERE email = :email LIMIT 1');
                $stmt->execute(array('email' => $email));
                $row = $stmt->fetch();
                if ($row === false || !password_verify($password, (string) $row['password_hash'])) {
                    $errors[] = 'Those credentials do not match our records.';
                } else {
                    login_user((int) $row['id']);
                    header('Location: ' . $next);
                    exit;
                }
            } catch (PDOException $exception) {
                $errors[] = 'Unable to sign in right now. Import sql/schema.sql and check config/db.php.';
            }
        }
    } elseif ($action === 'register') {
        $mode = 'register';
        $name = isset($_POST['name']) ? trim((string) $_POST['name']) : '';
        $email = isset($_POST['email']) ? strtolower(trim((string) $_POST['email'])) : '';
        $password = isset($_POST['password']) ? (string) $_POST['password'] : '';
        $old['name'] = $name;
        $old['email'] = $email;

        if ($name === '' || strlen($name) > 120) {
            $errors[] = 'Please enter your name.';
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors[] = 'Please enter a valid email address.';
        }
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }

        if (count($errors) === 0) {
            try {
                $stmt = db()->prepare(
                    'INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)'
                );
                $stmt->execute(array(
                    'name' => $name,
                    'email' => $email,
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                ));
                login_user((int) db()->lastInsertId());
                flash_set('success', 'Welcome to VIOLA. You can submit a property whenever you are ready.');
                header('Location: ' . $next);
                exit;
            } catch (PDOException $exception) {
                if ((string) $exception->getCode() === '23000') {
                    $errors[] = 'An account with that email already exists. Try signing in.';
                    $mode = 'login';
                } else {
                    $errors[] = 'Unable to create your account right now.';
                }
            }
        }
    }
}

$user = current_user();
$listings = array();
$dbError = null;

if ($user !== null) {
    $heroTitle = 'Hello, ' . first_name($user['name']);
    $heroSubtitle = 'Track submissions, return to search, or add another home.';
    try {
        $stmt = db()->prepare(
            'SELECT id, title, price, location, beds, baths, status, type, image_url, visibility
             FROM listings
             WHERE user_id = :user_id
             ORDER BY created_at DESC'
        );
        $stmt->execute(array('user_id' => $user['id']));
        $listings = $stmt->fetchAll();
    } catch (PDOException $exception) {
        $dbError = 'Unable to load your listings right now.';
    }
} else {
    $heroTitle = ($mode === 'register') ? 'Create an account' : 'My Account';
    $heroSubtitle = ($next === 'submit.php')
        ? 'Sign in to submit a property.'
        : 'Sign in to manage listings, or register to get started.';
}

require __DIR__ . '/includes/header.php';
render_page_hero($heroTitle, $heroSubtitle);
?>

<main class="section">
  <?php render_flash(); ?>
  <?php foreach ($errors as $error): ?>
    <p class="alert alert--error"><?= e($error) ?></p>
  <?php endforeach; ?>

  <?php if ($user !== null): ?>
    <div class="account-toolbar">
      <p class="muted"><?= e($user['email']) ?></p>
      <div class="account-toolbar__actions">
        <a class="btn-search form-submit" href="submit.php">Submit property</a>
        <form method="post" action="logout.php">
          <?= csrf_field() ?>
          <button class="btn-ghost" type="submit">Log out</button>
        </form>
      </div>
    </div>

    <?php if ($dbError !== null): ?>
      <p class="empty-state"><?= e($dbError) ?></p>
    <?php elseif (count($listings) === 0): ?>
      <p class="empty-state">You have not submitted a home yet. <a href="submit.php">Add a property</a>.</p>
    <?php else: ?>
      <h2 class="section-title">Your listings</h2>
      <div class="listings-grid">
        <?php foreach ($listings as $listing): ?>
          <?php render_property_card($listing); ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  <?php else: ?>
    <div class="auth-panel">
      <nav class="auth-tabs" aria-label="Account">
        <a href="account.php?mode=login&amp;next=<?= e($next) ?>" class="<?= $mode === 'login' ? 'is-active' : '' ?>">Sign in</a>
        <a href="account.php?mode=register&amp;next=<?= e($next) ?>" class="<?= $mode === 'register' ? 'is-active' : '' ?>">Register</a>
      </nav>

      <?php if ($mode === 'login'): ?>
        <form class="stack-form" method="post" action="account.php">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="login">
          <input type="hidden" name="next" value="<?= e($next) ?>">
          <label>
            <span>Email</span>
            <input type="email" name="email" required value="<?= e($old['email']) ?>">
          </label>
          <label>
            <span>Password</span>
            <input type="password" name="password" required>
          </label>
          <button class="btn-search form-submit" type="submit">Sign in</button>
          <p class="muted">Demo: demo@viola.test / password</p>
        </form>
      <?php else: ?>
        <form class="stack-form" method="post" action="account.php">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="register">
          <input type="hidden" name="next" value="<?= e($next) ?>">
          <label>
            <span>Name</span>
            <input type="text" name="name" maxlength="120" required value="<?= e($old['name']) ?>">
          </label>
          <label>
            <span>Email</span>
            <input type="email" name="email" required value="<?= e($old['email']) ?>">
          </label>
          <label>
            <span>Password</span>
            <input type="password" name="password" minlength="8" required>
          </label>
          <button class="btn-search form-submit" type="submit">Create account</button>
        </form>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
