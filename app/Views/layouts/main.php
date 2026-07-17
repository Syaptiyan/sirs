<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SIRS' ?> - Sistem Informasi Rumah Sakit</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.24/dist/full.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-base-200">
    <div class="flex h-screen" x-data="{ sidebarOpen: true }">
        <?= $this->include('components/sidebar') ?>
        <div class="flex-1 flex flex-col overflow-hidden">
            <?= $this->include('components/header') ?>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-base-200 p-6">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>
</body>
</html>