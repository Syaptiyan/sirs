<?php
// Usage: <?= $this->include('components/breadcrumb') ?>
// Set $breadcrumbs = [['label' => 'Home', 'url' => '/'], ['label' => 'Current Page']]
?>
<?php if (!empty($breadcrumbs)): ?>
<div class="text-sm breadcrumbs">
    <ul>
        <?php foreach ($breadcrumbs as $i => $crumb): ?>
            <?php if ($i === count($breadcrumbs) - 1): ?>
                <li><span class="font-semibold"><?= $crumb['label'] ?></span></li>
            <?php else: ?>
                <li><a href="<?= $crumb['url'] ?>" class="link link-hover"><?= $crumb['label'] ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
