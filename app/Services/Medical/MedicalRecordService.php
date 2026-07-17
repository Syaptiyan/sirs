<?php

namespace App\Services\Medical;

use App\Models\MedicalRecordModel;
use App\Models\MedicalRecordTemplateModel;
use App\Models\DiagnosisModel;
use App\Models\ICD10Model;
use App\Models\ActionTypeModel;
use App\Models\ActionModel;
use App\Models\VitalSignModel;

class MedicalRecordService
{
    private MedicalRecordModel $recordModel;
    private MedicalRecordTemplateModel $templateModel;
    private DiagnosisModel $diagnosisModel;
    private ICD10Model $icd10Model;
    private ActionTypeModel $actionTypeModel;
    private ActionModel $actionModel;
    private VitalSignModel $vitalSignModel;

    public function __construct()
    {
        $this->recordModel = new MedicalRecordModel();
        $this->templateModel = new MedicalRecordTemplateModel();
        $this->diagnosisModel = new DiagnosisModel();
        $this->icd10Model = new ICD10Model();
        $this->actionTypeModel = new ActionTypeModel();
        $this->actionModel = new ActionModel();
        $this->vitalSignModel = new VitalSignModel();
    }

    public function create(array $data): ?object
    {
        $data['record_number'] = $this->generateRecordNumber();

        $id = $this->recordModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->recordModel->find($id);
    }

    public function getByUuid(string $uuid): ?object
    {
        $record = $this->recordModel
            ->select('medical_records.*, patients.name as patient_name, patients.mrn, doctors.name as doctor_name')
            ->join('patients', 'patients.id = medical_records.patient_id', 'left')
            ->join('doctors', 'doctors.id = medical_records.doctor_id', 'left')
            ->where('medical_records.uuid', $uuid)
            ->first();

        if ($record !== null) {
            $record->diagnoses = $this->diagnosisModel
                ->select('diagnoses.*, icd10_codes.code as icd10_code, icd10_codes.description as icd10_description')
                ->join('icd10_codes', 'icd10_codes.id = diagnoses.icd10_code_id', 'left')
                ->where('diagnoses.medical_record_id', $record->id)
                ->findAll();

            $record->actions = $this->actionModel
                ->select('actions.*, action_types.name as action_type_name, action_types.code as action_type_code')
                ->join('action_types', 'action_types.id = actions.action_type_id', 'left')
                ->where('actions.medical_record_id', $record->id)
                ->findAll();
        }

        return $record;
    }

    public function getByVisitId(int $visitId): array
    {
        return $this->recordModel
            ->select('medical_records.*, doctors.name as doctor_name')
            ->join('doctors', 'doctors.id = medical_records.doctor_id', 'left')
            ->where('medical_records.visit_id', $visitId)
            ->orderBy('medical_records.created_at', 'DESC')
            ->findAll();
    }

    public function update(string $uuid, array $data): bool
    {
        $record = $this->recordModel->where('uuid', $uuid)->first();

        if ($record === null) {
            return false;
        }

        return $this->recordModel->update($record->id, $data);
    }

    public function addDiagnosis(string $recordUuid, int $icd10Id, string $type, ?string $notes = null): ?object
    {
        $record = $this->recordModel->where('uuid', $recordUuid)->first();

        if ($record === null) {
            return null;
        }

        $icd10 = $this->icd10Model->find($icd10Id);

        if ($icd10 === null) {
            return null;
        }

        $data = [
            'medical_record_id' => $record->id,
            'icd10_code_id'     => $icd10Id,
            'diagnosis_type'    => $type,
            'notes'             => $notes,
        ];

        $id = $this->diagnosisModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->diagnosisModel
            ->select('diagnoses.*, icd10_codes.code as icd10_code, icd10_codes.description as icd10_description')
            ->join('icd10_codes', 'icd10_codes.id = diagnoses.icd10_code_id', 'left')
            ->where('diagnoses.id', $id)
            ->first();
    }

    public function addAction(string $recordUuid, int $actionTypeId, int $quantity, float $unitPrice): ?object
    {
        $record = $this->recordModel->where('uuid', $recordUuid)->first();

        if ($record === null) {
            return null;
        }

        $actionType = $this->actionTypeModel->find($actionTypeId);

        if ($actionType === null) {
            return null;
        }

        $data = [
            'medical_record_id' => $record->id,
            'action_type_id'    => $actionTypeId,
            'quantity'          => $quantity,
            'unit_price'        => $unitPrice,
            'total_price'       => $quantity * $unitPrice,
        ];

        $id = $this->actionModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->actionModel
            ->select('actions.*, action_types.name as action_type_name, action_types.code as action_type_code')
            ->join('action_types', 'action_types.id = actions.action_type_id', 'left')
            ->where('actions.id', $id)
            ->first();
    }

    public function searchICD10(string $query): array
    {
        return $this->icd10Model
            ->groupStart()
                ->like('code', $query)
                ->orLike('description', $query)
            ->groupEnd()
            ->where('is_active', true)
            ->orderBy('code', 'ASC')
            ->limit(20)
            ->findAll();
    }

    public function getTemplates(?string $category = null): array
    {
        $builder = $this->templateModel->where('is_active', true);

        if ($category !== null) {
            $builder = $builder->where('category', $category);
        }

        return $builder->orderBy('name', 'ASC')->findAll();
    }

    public function addVitalSign(int $visitId, array $data): ?object
    {
        $data['visit_id'] = $visitId;
        $data['recorded_at'] = $data['recorded_at'] ?? date('Y-m-d H:i:s');

        $id = $this->vitalSignModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->vitalSignModel->find($id);
    }

    public function generateRecordNumber(): string
    {
        $date = date('Ymd');
        $prefix = 'RM-' . $date . '-';

        $lastRecord = $this->recordModel
            ->like('record_number', $prefix, 'after')
            ->orderBy('record_number', 'DESC')
            ->first();

        if ($lastRecord === null) {
            return $prefix . '0001';
        }

        $lastNumber = (int) substr($lastRecord->record_number, -4);
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $newNumber;
    }
}
