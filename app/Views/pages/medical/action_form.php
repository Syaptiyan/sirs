<?php
/**
 * Form Tambah Tindakan
 * 
 * Variables:
 * - $visitId (int)
 * - $actionTypes (array) - list of action types with id, name, price
 */
?>
<div class="card bg-base-100 shadow-sm">
    <div class="card-body">
        <h3 class="card-title text-lg">Tambah Tindakan</h3>
        
        <form id="actionForm" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="visit_id" value="<?= $visitId ?? '' ?>">
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Jenis Tindakan</span>
                </label>
                <select name="action_type_id" class="select select-bordered w-full" required x-data @change="$dispatch('action-type-changed', $el.value)">
                    <option value="">Pilih Tindakan</option>
                    <?php if (!empty($actionTypes)): ?>
                        <?php foreach ($actionTypes as $type): ?>
                            <option value="<?= $type['id'] ?>" data-price="<?= $type['price'] ?? 0 ?>">
                                <?= $type['name'] ?> - Rp <?= number_format($type['price'] ?? 0, 0, ',', '.') ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Jumlah</span>
                    </label>
                    <input 
                        type="number" 
                        name="quantity" 
                        class="input input-bordered" 
                        value="1" 
                        min="1" 
                        required
                        x-data
                        @input="$dispatch('quantity-changed', $el.value)"
                    >
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Harga Satuan (Rp)</span>
                    </label>
                    <input 
                        type="number" 
                        name="unit_price" 
                        class="input input-bordered" 
                        placeholder="0"
                        required
                        x-data
                        @input="$dispatch('price-changed', $el.value)"
                    >
                </div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Total Harga (Rp)</span>
                </label>
                <input 
                    type="text" 
                    class="input input-bordered bg-base-200 font-semibold" 
                    readonly
                    x-data="actionTotal()"
                    @action-type-changed.window="updatePrice($event.detail)"
                    @quantity-changed.window="quantity = $event.detail; calculate()"
                    @price-changed.window="unitPrice = $event.detail; calculate()"
                    :value="formattedTotal"
                >
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Catatan</span>
                </label>
                <textarea name="notes" class="textarea textarea-bordered h-24" placeholder="Catatan tindakan (opsional)"></textarea>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="reset" class="btn btn-ghost">Reset</button>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Simpan Tindakan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function actionTotal() {
    return {
        quantity: 1,
        unitPrice: 0,
        
        get total() {
            return this.quantity * this.unitPrice;
        },
        
        get formattedTotal() {
            return 'Rp ' + this.total.toLocaleString('id-ID');
        },
        
        updatePrice(actionTypeId) {
            const select = document.querySelector('select[name="action_type_id"]');
            const option = select.options[select.selectedIndex];
            const price = parseInt(option.dataset.price) || 0;
            
            this.unitPrice = price;
            document.querySelector('input[name="unit_price"]').value = price;
            this.calculate();
        },
        
        calculate() {
            this.quantity = parseInt(document.querySelector('input[name="quantity"]').value) || 0;
            this.unitPrice = parseInt(document.querySelector('input[name="unit_price"]').value) || 0;
        }
    };
}

document.getElementById('actionForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch('/medical/actions', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            window.location.reload();
        } else {
            alert(result.message || 'Gagal menyimpan tindakan');
        }
    } catch (error) {
        console.error('Submit error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    }
});
</script>
