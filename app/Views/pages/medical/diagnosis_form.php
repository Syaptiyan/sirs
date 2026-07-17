<?php
/**
 * Form Tambah Diagnosa
 * 
 * Variables:
 * - $visitId (int)
 * - $icd10List (array) - optional, for initial load
 */
?>
<div class="card bg-base-100 shadow-sm">
    <div class="card-body">
        <h3 class="card-title text-lg">Tambah Diagnosa</h3>
        
        <form id="diagnosisForm" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="visit_id" value="<?= $visitId ?? '' ?>">
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Kode ICD-10</span>
                </label>
                <div class="relative" x-data="icdSearch()">
                    <input 
                        type="text" 
                        class="input input-bordered w-full" 
                        placeholder="Ketik kode atau nama diagnosa..."
                        x-model="query"
                        @input.debounce.300ms="search()"
                        @focus="showDropdown = true"
                        @click.outside="showDropdown = false"
                    >
                    <input type="hidden" name="icd_code" :value="selectedCode">
                    
                    <div 
                        x-show="showDropdown && results.length > 0" 
                        class="absolute z-50 w-full mt-1 bg-base-100 shadow-lg rounded-box max-h-60 overflow-y-auto"
                    >
                        <template x-for="item in results" :key="item.code">
                            <div 
                                class="p-3 hover:bg-base-200 cursor-pointer border-b border-base-200 last:border-0"
                                @click="selectItem(item)"
                            >
                                <div class="font-semibold text-sm" x-text="item.code"></div>
                                <div class="text-xs text-base-content/70" x-text="item.description"></div>
                            </div>
                        </template>
                    </div>
                    
                    <div x-show="selectedCode" class="mt-2">
                        <div class="badge badge-primary gap-1">
                            <span x-text="selectedCode"></span>
                            <span x-text="selectedDescription" class="max-w-[200px] truncate"></span>
                            <button type="button" @click="clearSelection()" class="ml-1">&times;</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Jenis Diagnosa</span>
                </label>
                <div class="flex gap-4">
                    <label class="label cursor-pointer gap-2">
                        <input type="radio" name="diagnosis_type" value="primary" class="radio radio-primary" checked>
                        <span class="label-text">Primer</span>
                    </label>
                    <label class="label cursor-pointer gap-2">
                        <input type="radio" name="diagnosis_type" value="secondary" class="radio radio-primary">
                        <span class="label-text">Sekunder</span>
                    </label>
                </div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Catatan</span>
                </label>
                <textarea name="notes" class="textarea textarea-bordered h-24" placeholder="Catatan diagnosa (opsional)"></textarea>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="reset" class="btn btn-ghost">Reset</button>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Simpan Diagnosa
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function icdSearch() {
    return {
        query: '',
        results: [],
        showDropdown: false,
        selectedCode: '',
        selectedDescription: '',
        
        async search() {
            if (this.query.length < 2) {
                this.results = [];
                return;
            }
            try {
                const response = await fetch(`/api/icd10/search?q=${encodeURIComponent(this.query)}`);
                const data = await response.json();
                this.results = data.data || [];
                this.showDropdown = true;
            } catch (error) {
                console.error('Search error:', error);
                this.results = [];
            }
        },
        
        selectItem(item) {
            this.selectedCode = item.code;
            this.selectedDescription = item.description;
            this.query = '';
            this.showDropdown = false;
        },
        
        clearSelection() {
            this.selectedCode = '';
            this.selectedDescription = '';
        }
    };
}

document.getElementById('diagnosisForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch('/medical/diagnoses', {
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
            alert(result.message || 'Gagal menyimpan diagnosa');
        }
    } catch (error) {
        console.error('Submit error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    }
});
</script>
