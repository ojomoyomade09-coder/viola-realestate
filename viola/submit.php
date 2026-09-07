<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

require_login('submit.php');

$currentPage = 'submit';
$pageTitle = 'VIOLA | Submit Property';
$bodyClass = 'page-inner';

$errors = array();
$old = array(
    'title' => '',
    'price' => '',
    'location' => '',
    'beds' => '3',
    'baths' => '2',
    'status' => 'sale',
    'type' => 'house',
    'image_url' => '',
);

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $errors[] = 'Your session expired. Please try again.';
    } else {
        foreach ($old as $key => $default) {
            $old[$key] = isset($_POST[$key]) ? trim((string) $_POST[$key]) : $default;
        }

        $titleLen = strlen($old['title']);
        if ($titleLen < 4 || $titleLen > 180) {
            $errors[] = 'Title should be between 4 and 180 characters.';
        }

        $price = filter_var($old['price'], FILTER_VALIDATE_FLOAT);
        if ($price === false || $price < 1 || $price > 99999999) {
            $errors[] = 'Enter a realistic price.';
        }

        if (!in_array($old['location'], viola_locations(), true)) {
            $errors[] = 'Choose a supported location.';
        }

        $beds = ctype_digit($old['beds']) ? (int) $old['beds'] : 0;
        $baths = ctype_digit($old['baths']) ? (int) $old['baths'] : 0;
        if ($beds < 1 || $beds > 12 || $baths < 1 || $baths > 12) {
            $errors[] = 'Beds and baths must be between 1 and 12.';
        }

        if (!in_array($old['status'], viola_statuses(), true) || !in_array($old['type'], viola_types(), true)) {
            $errors[] = 'Choose a valid status and property type.';
        }

        if (!is_http_url($old['image_url']) || strlen($old['image_url']) > 500) {
            $errors[] = 'Provide a valid http(s) image URL.';
        }

        if (count($errors) === 0) {
            $user = current_user();
            if ($user === null) {
                $errors[] = 'Please sign in again before submitting.';
            } else {
                try {
                    $stmt = db()->prepare(
                        'INSERT INTO listings
                            (title, price, location, beds, baths, status, type, image_url, visibility, user_id)
                         VALUES
                            (:title, :price, :location, :beds, :baths, :status, :type, :image_url, :visibility, :user_id)'
                    );
                    $stmt->execute(array(
                        'title' => $old['title'],
                        'price' => $price,
                        'location' => $old['location'],
                        'beds' => $beds,
                        'baths' => $baths,
                        'status' => $old['status'],
                        'type' => $old['type'],
                        'image_url' => $old['image_url'],
                        'visibility' => 'pending',
                        'user_id' => $user['id'],
                    ));
                    flash_set('success', 'Listing submitted for review. It will appear on Properties after approval.');
                    header('Location: account.php');
                    exit;
                } catch (PDOException $exception) {
                    $errors[] = 'Unable to save your listing right now.';
                }
            }
        }
    }
}

require __DIR__ . '/includes/header.php';
render_page_hero(
    'Submit a property',
    'Share accurate facts and a public photo URL. Listings stay pending until VIOLA reviews them.'
);
?>

<main class="section section--narrow">
  <?php foreach ($errors as $error): ?>
    <p class="alert alert--error"><?= e($error) ?></p>
  <?php endforeach; ?>

  <form class="stack-form" method="post" action="submit.php" novalidate>
    <?= csrf_field() ?>
    <label>
      <span>Title</span>
      <input type="text" name="title" maxlength="180" required value="<?= e($old['title']) ?>">
    </label>
    <div class="form-row">
      <label>
        <span>Price (USD)</span>
        <input type="number" name="price" min="1" step="1" required value="<?= e($old['price']) ?>">
      </label>
      <label>
        <span>Status</span>
        <select name="status">
          <option value="sale"<?= selected_attr($old['status'], 'sale') ?>>For Sale</option>
          <option value="rent"<?= selected_attr($old['status'], 'rent') ?>>For Rent</option>
        </select>
      </label>
    </div>
    <div class="form-row">
      <label>
        <span>Type</span>
        <select name="type">
          <?php foreach (viola_types() as $type): ?>
            <option value="<?= e($type) ?>"<?= selected_attr($old['type'], $type) ?>><?= e(ucfirst($type)) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>
        <span>Location</span>
        <select name="location" required>
          <option value="">Select</option>
          <?php foreach (viola_locations() as $city): ?>
            <option value="<?= e($city) ?>"<?= selected_attr($old['location'], $city) ?>><?= e($city) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
    </div>
    <div class="form-row">
      <label>
        <span>Bedrooms</span>
        <input type="number" name="beds" min="1" max="12" required value="<?= e($old['beds']) ?>">
      </label>
      <label>
        <span>Bathrooms</span>
        <input type="number" name="baths" min="1" max="12" required value="<?= e($old['baths']) ?>">
      </label>
    </div>
    <label>
      <span>Photo URL</span>
      <input type="url" name="image_url" maxlength="500" required placeholder="https://" value="<?= e($old['image_url']) ?>">
    </label>
    <button class="btn-search form-submit" type="submit">Submit for review</button>
  </form>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
