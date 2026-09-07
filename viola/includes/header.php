<?php

declare(strict_types=1);

$currentPage = isset($currentPage) ? $currentPage : 'index';
$pageTitle = isset($pageTitle) ? $pageTitle : 'VIOLA | Find Your Amazing Home';
$bodyClass = isset($bodyClass) ? $bodyClass : '';
$user = current_user();
$accountLabel = ($user !== null) ? first_name($user['name']) : 'My Account';
$pagesActive = in_array($currentPage, array('pages', 'faq', 'contact'), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
      <link rel="icon" type="image/x-icon" href="/viola/favicon.ico.ico"> 

</head>
<body class="<?= e($bodyClass) ?>">
  <header class="site-header" id="site-header">
    <div class="header-inner">
      <a class="logo" href="index.php">VIOLA</a>

      <button class="nav-toggle" id="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav">
        <span></span>
        <span></span>
        <span></span>
        <span class="sr-only">Toggle menu</span>
      </button>

      <nav class="site-nav" id="site-nav" aria-label="Primary">
        <a href="index.php" class="<?= $currentPage === 'index' ? 'is-active' : '' ?>">Index</a>
        <a href="listings.php" class="<?= $currentPage === 'listings' ? 'is-active' : '' ?>">Properties</a>
        <a href="agents.php" class="<?= $currentPage === 'agents' ? 'is-active' : '' ?>">Agents</a>
        <a href="pages.php" class="<?= $pagesActive ? 'is-active' : '' ?>">Pages</a>
        <a href="blog.php" class="<?= $currentPage === 'blog' ? 'is-active' : '' ?>">Blog</a>
        <a href="account.php" class="<?= $currentPage === 'account' ? 'is-active' : '' ?>"><?= e($accountLabel) ?></a>
        <a class="btn-submit" href="submit.php">+ Submit Property</a>
      </nav>
    </div>
  </header>
