<?php
/**
 * Form Vital Signs
 * 
 * Variables:
 * - $visitId (int)
 * - $patientId (int)
 */
?>
<div class="card bg-base-100 shadow-sm">
    <div class="card-body">
        <h3 class="card-title text-lg">Catat Tanda Vital</h3>
        
        <form id="vitalSignsForm" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="visit_id" value="<?= $visitId ?? '' ?>">
            <input type="hidden" name="patient_id" value="<?= $patientId ?? '' ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Tekanan Darah (mmHg)</span>
                    </label>
                    <div class="join">
                        <input 
                            type="text" 
                            name="blood_pressure_systolic" 
                            class="input input-bordered join-item w-1/2" 
                            placeholder="Sistol"
                            pattern="\d*"
                            maxlength="3"
                        >
                        <span class="btn btn-disabled join-item">/</span>
                        <input 
                            type="text" 
                            name="blood_pressure_diastolic" 
                            class="input input-bordered join-item w-1/2" 
                            placeholder="Diastol"
                            pattern="\d*"
                            maxlength="3"
                        >
                    </div>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Denyut Jantung (bpm)</span>
                    </label>
                    <input 
                        type="number" 
                        name="heart_rate" 
                        class="input input-bordered" 
                        placeholder="0"
                        min="0"
                        max="300"
                    >
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Suhu Tubuh (&deg;C)</span>
                    </label>
                    <input 
                        type="number" 
                        name="temperature" 
                        class="input input-bordered" 
                        placeholder="36.5"
                        min="30"
                        max="45"
                        step="0.1"
                    >
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Frekuensi Napas (x/menit)</span>
                    </label>
                    <input 
                        type="number" 
                        name="respiratory_rate" 
                        class="input input-bordered" 
                        placeholder="0"
                        min="0"
                        max="100"
                    >
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">SpO2 (%)</span>
                    </label>
                    <input 
                        type="number" 
                        name="spo2" 
                        class="input input-bordered" 
                        placeholder="0"
                        min="0"
                        max="100"
                    >
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Berat Badan (kg)</span>
                    </label>
                    <input 
                        type="number" 
                        name="weight" 
                        class="input input-bordered" 
                        placeholder="0"
                        min="0"
                        max="500"
                        step="0.1"
                    >
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Tinggi Badan (cm)</span>
                    </label>
                    <input 
                        type="number" 
                        name="height" 
                        class="input input-bordered" 
                        placeholder="0"
                        min="0"
                        max="300"
                        step="0.1"
                    >
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">BMI (kg/m&sup2;)</span>
                    </label>
                    <input 
                        type="text" 
                        class="input input-bordered bg-base-200" 
                        readonly
                        x-data="bmiCalculator()"
                        @input.window="calculateBMI()"
                        :value="bmi"
                    >
                </div>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="reset" class="btn btn-ghost">Reset</button>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Simpan Tanda Vital
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function bmiCalculator() {
    return {
        bmi: '-',
        
        calculateBMI() {
            const weight = parseFloat(document.querySelector('input[name="weight"]').value) || 0;
            const height = parseFloat(document.querySelector('input[name="height"]').value) || 0;
            
            if (weight > 0 && height > 0) {
                const heightInMeters = height / 100;
                const bmiValue = weight / (heightInMeters * heightInMeters);
                this.bmi = bmiValue.toFixed(1);
            } else {
                this.bmi = '-';
            }
        }
    };
}

document.getElementById('vitalSignsForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    const systolic = formData.get('blood_pressure_systolic');
    const diastolic = formData.get('blood_pressure_diastolic');
    if (systolic && diastolic) {
        formData.set('blood_pressure', `${systolic}/${diastolic}`);
    }
    formData.delete('blood_pressure_systolic');
    formData.delete('blood_pressure_diastolic');
    
    try {
        const response = await fetch('/medical/vital-signs', {
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
            alert(result.message || 'Gagal menyimpan tanda vital');
        }
    } catch (error) {
        console.error('Submit error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    }
});
</script>
