<?php

declare(strict_types=1);

define('OPENCODE_BOOTSTRAP_STARTED', microtime(true));

require __DIR__ . '/../vendor/codeigniter4/framework/system/Test/bootstrap.php';

// Run all migrations once on the 'tests' database
use CodeIgniter\Database\MigrationRunner;
use Config\Database;
use Config\Migrations;

$db = Database::connect('tests');
$db->initialize();

$config = new Migrations();
$config->enabled = true;

$migrations = new MigrationRunner($config, $db);
$migrations->setNamespace(null);
$migrations->setSilent(false);
$result = $migrations->latest('tests');

if ($result !== true) {
    throw new \RuntimeException('Bootstrap migration failed!');
}

define('OPENCODE_BOOTSTRAP_DONE', microtime(true));
