<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify()) {
    header('Location: account.php');
    exit;
}

logout_user();
header('Location: index.php');
exit;
