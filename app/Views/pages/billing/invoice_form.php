<?php
/**
 * Form Buat Tagihan Manual
 * 
 * Variables:
 * - $visits (array) - list of visits with id, patient_name, visit_date
 * - $itemTypes (array) - ['consultation', 'action', 'medicine', 'lab', 'room', 'other']
 */
?>
<div class="card bg-base-100 shadow-sm">
    <div class="card-body">
        <h3 class="card-title text-lg">Buat Tagihan Baru</h3>
        
        <form id="invoiceForm" class="space-y-6">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Kunjungan / Pasien</span>
                    </label>
                    <select name="visit_id" class="select select-bordered w-full" required>
                        <option value="">Pilih Kunjungan</option>
                        <?php if (!empty($visits)): ?>
                            <?php foreach ($visits as $visit): ?>
                                <option value="<?= $visit['id'] ?>">
                                    <?= $visit['patient_name'] ?> - <?= date('d/m/Y', strtotime($visit['visit_date'])) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Jatuh Tempo</span>
                    </label>
                    <input type="date" name="due_date" class="input input-bordered" required>
                </div>
            </div>
            
            <div class="divider">Item Tagihan</div>
            
            <div id="invoiceItems" class="space-y-3">
                <div class="invoice-item grid grid-cols-12 gap-2 items-end">
                    <div class="col-span-3">
                        <label class="label"><span class="label-text text-xs">Tipe</span></label>
                        <select name="items[0][type]" class="select select-bordered select-sm w-full" required>
                            <option value="">Pilih</option>
                            <option value="consultation">Konsultasi</option>
                            <option value="action">Tindakan</option>
                            <option value="medicine">Obat</option>
                            <option value="lab">Lab</option>
                            <option value="room">Kamar</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-span-4">
                        <label class="label"><span class="label-text text-xs">Nama Item</span></label>
                        <input type="text" name="items[0][name]" class="input input-bordered input-sm w-full" placeholder="Nama item" required>
                    </div>
                    <div class="col-span-1">
                        <label class="label"><span class="label-text text-xs">Qty</span></label>
                        <input type="number" name="items[0][quantity]" class="input input-bordered input-sm w-full" value="1" min="1" required>
                    </div>
                    <div class="col-span-3">
                        <label class="label"><span class="label-text text-xs">Harga (Rp)</span></label>
                        <input type="number" name="items[0][price]" class="input input-bordered input-sm w-full" placeholder="0" required>
                    </div>
                    <div class="col-span-1">
                        <button type="button" class="btn btn-ghost btn-sm btn-square text-error remove-item" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <button type="button" id="addItemRow" class="btn btn-ghost btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Item
            </button>
            
            <div class="divider"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Diskon (Rp)</span>
                    </label>
                    <input type="number" name="discount" class="input input-bordered" value="0" min="0" id="discount">
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span id="subtotal" class="font-semibold">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Diskon:</span>
                        <span id="discountDisplay" class="text-error">- Rp 0</span>
                    </div>
                    <div class="divider my-0"></div>
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total:</span>
                        <span id="total">Rp 0</span>
                    </div>
                </div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Catatan</span>
                </label>
                <textarea name="notes" class="textarea textarea-bordered h-24" placeholder="Catatan tagihan (opsional)"></textarea>
            </div>
            
            <div class="flex justify-end gap-2">
                <a href="/billing" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Buat Tagihan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    
    document.getElementById('addItemRow').addEventListener('click', function() {
        const container = document.getElementById('invoiceItems');
        const template = `
            <div class="invoice-item grid grid-cols-12 gap-2 items-end">
                <div class="col-span-3">
                    <select name="items[${itemIndex}][type]" class="select select-bordered select-sm w-full" required>
                        <option value="">Pilih</option>
                        <option value="consultation">Konsultasi</option>
                        <option value="action">Tindakan</option>
                        <option value="medicine">Obat</option>
                        <option value="lab">Lab</option>
                        <option value="room">Kamar</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>
                <div class="col-span-4">
                    <input type="text" name="items[${itemIndex}][name]" class="input input-bordered input-sm w-full" placeholder="Nama item" required>
                </div>
                <div class="col-span-1">
                    <input type="number" name="items[${itemIndex}][quantity]" class="input input-bordered input-sm w-full" value="1" min="1" required>
                </div>
                <div class="col-span-3">
                    <input type="number" name="items[${itemIndex}][price]" class="input input-bordered input-sm w-full" placeholder="0" required>
                </div>
                <div class="col-span-1">
                    <button type="button" class="btn btn-ghost btn-sm btn-square text-error remove-item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
        itemIndex++;
        updateRemoveButtons();
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            e.target.closest('.invoice-item').remove();
            calculateTotal();
            updateRemoveButtons();
        }
    });
    
    function updateRemoveButtons() {
        const items = document.querySelectorAll('.invoice-item');
        items.forEach((item, index) => {
            const btn = item.querySelector('.remove-item');
            if (btn) btn.disabled = items.length <= 1;
        });
    }
    
    document.addEventListener('input', function(e) {
        if (e.target.matches('[name$="[quantity]"]') || e.target.matches('[name$="[price]"]') || e.target.id === 'discount') {
            calculateTotal();
        }
    });
    
    function calculateTotal() {
        let subtotal = 0;
        document.querySelectorAll('.invoice-item').forEach(item => {
            const qty = parseInt(item.querySelector('[name$="[quantity]"]')?.value) || 0;
            const price = parseInt(item.querySelector('[name$="[price]"]')?.value) || 0;
            subtotal += qty * price;
        });
        
        const discount = parseInt(document.getElementById('discount').value) || 0;
        const total = Math.max(0, subtotal - discount);
        
        document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('discountDisplay').textContent = '- Rp ' + discount.toLocaleString('id-ID');
        document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
    
    document.getElementById('invoiceForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        try {
            const response = await fetch('/billing/invoices', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                window.location.href = '/billing/' + result.invoice_id;
            } else {
                alert(result.message || 'Gagal membuat tagihan');
            }
        } catch (error) {
            console.error('Submit error:', error);
            alert('Terjadi kesalahan saat menyimpan data');
        }
    });
});
</script>
