<?php

declare(strict_types=1);

$selected = array(
    'status' => isset($_GET['status']) ? (string) $_GET['status'] : '',
    'type' => isset($_GET['type']) ? (string) $_GET['type'] : '',
    'beds' => isset($_GET['beds']) ? (string) $_GET['beds'] : '',
    'baths' => isset($_GET['baths']) ? (string) $_GET['baths'] : '',
    'location' => isset($_GET['location']) ? (string) $_GET['location'] : '',
);
?>
<form class="search-bar" action="listings.php" method="get" role="search">
  <label class="search-field">
    <span>Any Status</span>
    <select name="status">
      <option value="">Any Status</option>
      <option value="sale"<?= selected_attr($selected['status'], 'sale') ?>>For Sale</option>
      <option value="rent"<?= selected_attr($selected['status'], 'rent') ?>>For Rent</option>
    </select>
  </label>

  <label class="search-field">
    <span>All Type</span>
    <select name="type">
      <option value="">All Type</option>
      <option value="house"<?= selected_attr($selected['type'], 'house') ?>>House</option>
      <option value="apartment"<?= selected_attr($selected['type'], 'apartment') ?>>Apartment</option>
      <option value="villa"<?= selected_attr($selected['type'], 'villa') ?>>Villa</option>
      <option value="condo"<?= selected_attr($selected['type'], 'condo') ?>>Condo</option>
    </select>
  </label>

  <label class="search-field">
    <span>Bedrooms</span>
    <select name="beds">
      <option value="">Bedrooms</option>
      <?php for ($i = 1; $i <= 6; $i++): ?>
        <option value="<?= $i ?>"<?= selected_attr($selected['beds'], $i) ?>><?= $i ?>+ Beds</option>
      <?php endfor; ?>
    </select>
  </label>

  <label class="search-field">
    <span>Bathrooms</span>
    <select name="baths">
      <option value="">Bathrooms</option>
      <?php for ($i = 1; $i <= 5; $i++): ?>
        <option value="<?= $i ?>"<?= selected_attr($selected['baths'], $i) ?>><?= $i ?>+ Baths</option>
      <?php endfor; ?>
    </select>
  </label>

  <label class="search-field">
    <span>Location</span>
    <select name="location">
      <option value="">Location</option>
      <?php foreach (viola_locations() as $city): ?>
        <option value="<?= e($city) ?>"<?= selected_attr($selected['location'], $city) ?>><?= e($city) ?></option>
      <?php endforeach; ?>
    </select>
  </label>

  <button class="btn-search" type="submit">Search</button>
</form>
