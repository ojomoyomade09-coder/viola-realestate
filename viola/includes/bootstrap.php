<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/db.php';

function viola_locations()
{
    return array(
        'Los Angeles, CA',
        'Miami, FL',
        'Austin, TX',
        'Chicago, IL',
        'San Diego, CA',
        'Seattle, WA',
        'New York, NY',
        'Portland, OR',
        'Malibu, CA',
        'Atlanta, GA',
    );
}

function viola_types()
{
    return array('house', 'apartment', 'villa', 'condo');
}

function viola_statuses()
{
    return array('sale', 'rent');
}

function viola_safe_next_pages()
{
    return array(
        'account.php',
        'submit.php',
        'listings.php',
        'index.php',
        'agents.php',
        'blog.php',
        'pages.php',
        'faq.php',
        'contact.php',
    );
}

function start_app_session()
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params(array(
            'lifetime' => 0,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ));
    } else {
        session_set_cookie_params(0, '/', '', false, true);
    }

    session_start();
}

start_app_session();

function csrf_token()
{
    if (empty($_SESSION['csrf']) || !is_string($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf'];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_verify()
{
    $token = isset($_POST['csrf']) ? $_POST['csrf'] : '';

    return is_string($token)
        && isset($_SESSION['csrf'])
        && is_string($_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $token);
}

function flash_set($type, $message)
{
    $_SESSION['flash'] = array(
        'type' => (string) $type,
        'message' => (string) $message,
    );
}

function flash_get()
{
    if (empty($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function safe_next($next)
{
    $next = trim((string) $next);

    return in_array($next, viola_safe_next_pages(), true) ? $next : 'account.php';
}

function first_name($name)
{
    $parts = preg_split('/\s+/', trim((string) $name));

    return (isset($parts[0]) && $parts[0] !== '') ? $parts[0] : 'there';
}

function current_user()
{
    static $user = false;

    if ($user !== false) {
        return $user;
    }

    $id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $validId = is_int($id) || (is_string($id) && ctype_digit($id));

    if (!$validId) {
        $user = null;
        return null;
    }

    try {
        $stmt = db()->prepare('SELECT id, name, email, created_at FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(array('id' => (int) $id));
        $row = $stmt->fetch();
        $user = ($row === false) ? null : $row;
    } catch (PDOException $exception) {
        $user = null;
    }

    return $user;
}

function login_user($userId)
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $userId;
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function logout_user()
{
    $_SESSION = array();

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        $domain = isset($params['domain']) ? $params['domain'] : '';
        $secure = !empty($params['secure']);
        $httponly = !empty($params['httponly']);
        setcookie(session_name(), '', time() - 42000, $params['path'], $domain, $secure, $httponly);
    }

    session_destroy();
}

function require_login($next = 'account.php')
{
    if (current_user() !== null) {
        return;
    }

    header('Location: account.php?next=' . rawurlencode(safe_next($next)));
    exit;
}

function is_http_url($url)
{
    if (filter_var($url, FILTER_VALIDATE_URL) === false) {
        return false;
    }

    $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

    return $scheme === 'http' || $scheme === 'https';
}

function render_flash()
{
    $flash = flash_get();
    if ($flash === null) {
        return;
    }

    $type = (isset($flash['type']) && $flash['type'] === 'error') ? 'error' : 'success';
    $message = isset($flash['message']) ? $flash['message'] : '';
    echo '<p class="alert alert--' . e($type) . '" role="status">' . e($message) . '</p>';
}

function render_page_hero($title, $subtitle)
{
    echo '<section class="page-hero"><div class="page-hero-inner">';
    echo '<h1>' . e($title) . '</h1>';
    if ((string) $subtitle !== '') {
        echo '<p>' . e($subtitle) . '</p>';
    }
    echo '</div></section>';
}

function render_property_card(array $listing)
{
    $status = isset($listing['status']) ? (string) $listing['status'] : '';
    $visibility = isset($listing['visibility']) ? (string) $listing['visibility'] : 'public';
    $type = isset($listing['type']) ? (string) $listing['type'] : '';
    $title = isset($listing['title']) ? (string) $listing['title'] : '';
    $location = isset($listing['location']) ? (string) $listing['location'] : '';
    $image = isset($listing['image_url']) ? (string) $listing['image_url'] : '';
    $price = isset($listing['price']) ? (float) $listing['price'] : 0;
    $beds = isset($listing['beds']) ? (int) $listing['beds'] : 0;
    $baths = isset($listing['baths']) ? (int) $listing['baths'] : 0;
    $badge = ($status === 'sale') ? 'For Sale' : 'For Rent';
    ?>
<article class="property-card">
  <div class="property-media">
    <img src="<?= e($image) ?>" alt="<?= e($title) ?>" loading="lazy" width="640" height="420">
    <span class="badge"><?= e($badge) ?></span>
    <?php if ($visibility === 'pending'): ?>
      <span class="badge badge--pending">Pending review</span>
    <?php endif; ?>
  </div>
  <div class="property-body">
    <p class="property-type"><?= e(ucfirst($type)) ?></p>
    <h2><?= e($title) ?></h2>
    <p class="property-location"><?= e($location) ?></p>
    <p class="property-price">
      $<?= number_format($price, 0) ?>
      <?= $status === 'rent' ? '<span>/mo</span>' : '' ?>
    </p>
    <ul class="property-meta">
      <li><?= $beds ?> Beds</li>
      <li><?= $baths ?> Baths</li>
    </ul>
  </div>
</article>
    <?php
}

function selected_attr($current, $expected)
{
    return ((string) $current === (string) $expected) ? ' selected' : '';
}
