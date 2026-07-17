<?php

require __DIR__ . '/../../vendor/codeigniter4/framework/system/Test/bootstrap.php';

$db = \Config\Database::connect('tests');
$migrateConfig = new \Config\Migrations();
$migrateConfig->enabled = true;
$migrate = new \CodeIgniter\Database\MigrationRunner($migrateConfig, $db);
$migrate->setNamespace(null);

try {
    $migrate->latest('tests');
} catch (\Throwable $e) {
    echo 'Migration error: ' . $e->getMessage() . "\n";
}
