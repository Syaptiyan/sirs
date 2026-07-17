<?php
// Usage: Pass $modalId, $modalTitle, $modalContent, $modalActions
?>
<dialog id="<?= $modalId ?? 'modal' ?>" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg"><?= $modalTitle ?? 'Modal' ?></h3>
        <div class="py-4">
            <?= $modalContent ?? '' ?>
        </div>
        <div class="modal-action">
            <form method="dialog">
                <button class="btn">Tutup</button>
            </form>
            <?= $modalActions ?? '' ?>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
