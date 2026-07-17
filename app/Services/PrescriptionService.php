<?php

namespace App\Services;

use App\Models\PrescriptionModel;
use App\Models\PrescriptionDetailModel;

class PrescriptionService
{
    private PrescriptionModel $prescriptionModel;
    private PrescriptionDetailModel $detailModel;

    public function __construct()
    {
        $this->prescriptionModel = new PrescriptionModel();
        $this->detailModel = new PrescriptionDetailModel();
    }

    public function create(int $visitId, int $patientId, int $doctorId, array $items): ?object
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $prescriptionNumber = $this->generatePrescriptionNumber();

        $prescriptionId = $this->prescriptionModel->insert([
            'prescription_number' => $prescriptionNumber,
            'visit_id'            => $visitId,
            'patient_id'          => $patientId,
            'doctor_id'           => $doctorId,
            'prescription_date'   => date('Y-m-d'),
            'status'              => 'pending',
            'notes'               => $items['notes'] ?? null,
        ]);

        if ($prescriptionId === false) {
            $db->transRollback();
            return null;
        }

        foreach ($items['details'] as $item) {
            $detailId = $this->detailModel->insert([
                'prescription_id' => $prescriptionId,
                'drug_id'         => $item['drug_id'],
                'quantity'        => $item['quantity'],
                'unit'            => $item['unit'],
                'dosage'          => $item['dosage'],
                'frequency'       => $item['frequency'],
                'duration'        => $item['duration'] ?? null,
                'instructions'    => $item['instructions'] ?? null,
            ]);

            if ($detailId === false) {
                $db->transRollback();
                return null;
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return null;
        }

        return $this->prescriptionModel->find($prescriptionId);
    }

    public function getByUuid(string $uuid): ?object
    {
        $prescription = $this->prescriptionModel
            ->select('prescriptions.*')
            ->select('patients.name as patient_name, patients.mrn')
            ->select('doctors.name as doctor_name')
            ->select('visits.visit_number')
            ->join('patients', 'patients.id = prescriptions.patient_id', 'left')
            ->join('doctors', 'doctors.id = prescriptions.doctor_id', 'left')
            ->join('visits', 'visits.id = prescriptions.visit_id', 'left')
            ->where('prescriptions.uuid', $uuid)
            ->first();

        if ($prescription === null) {
            return null;
        }

        $prescription->details = $this->detailModel
            ->select('prescription_details.*')
            ->select('drugs.name as drug_name, drugs.code as drug_code')
            ->join('drugs', 'drugs.id = prescription_details.drug_id', 'left')
            ->where('prescription_id', $prescription->id)
            ->findAll();

        return $prescription;
    }

    public function getByVisitId(int $visitId): array
    {
        return $this->prescriptionModel
            ->where('visit_id', $visitId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function dispense(string $uuid): bool
    {
        $prescription = $this->prescriptionModel->where('uuid', $uuid)->first();
        if ($prescription === null || $prescription->status !== 'pending') {
            return false;
        }

        $details = $this->detailModel->where('prescription_id', $prescription->id)->findAll();
        $allDispensed = true;

        foreach ($details as $detail) {
            if ($detail->dispensed_quantity < $detail->quantity) {
                $allDispensed = false;
                break;
            }
        }

        $newStatus = $allDispensed ? 'dispensed' : 'partial';

        return $this->prescriptionModel->update($prescription->id, [
            'status' => $newStatus,
        ]);
    }

    public function cancel(string $uuid): bool
    {
        $prescription = $this->prescriptionModel->where('uuid', $uuid)->first();
        if ($prescription === null || in_array($prescription->status, ['dispensed', 'cancelled'])) {
            return false;
        }

        return $this->prescriptionModel->update($prescription->id, [
            'status' => 'cancelled',
        ]);
    }

    public function generatePrescriptionNumber(): string
    {
        $date = date('Ymd');
        $prefix = 'RSP-' . $date . '-';

        $lastPrescription = $this->prescriptionModel
            ->like('prescription_number', $prefix, 'after')
            ->orderBy('prescription_number', 'DESC')
            ->first();

        if ($lastPrescription === null) {
            $number = 1;
        } else {
            $lastNumber = (int) substr($lastPrescription->prescription_number, -4);
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getPending(): array
    {
        return $this->prescriptionModel
            ->select('prescriptions.*')
            ->select('patients.name as patient_name, patients.mrn')
            ->select('doctors.name as doctor_name')
            ->join('patients', 'patients.id = prescriptions.patient_id', 'left')
            ->join('doctors', 'doctors.id = prescriptions.doctor_id', 'left')
            ->where('prescriptions.status', 'pending')
            ->orderBy('prescriptions.created_at', 'ASC')
            ->findAll();
    }
}
