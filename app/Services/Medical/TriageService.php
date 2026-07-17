<?php

namespace App\Services\Medical;

use App\Models\TriageModel;

class TriageService
{
    private TriageModel $triageModel;

    public function __construct()
    {
        $this->triageModel = new TriageModel();
    }

    public function create(array $data): ?object
    {
        $data['vital_signs'] = json_encode($data['vital_signs'] ?? []);
        $data['triaged_at'] = $data['triaged_at'] ?? date('Y-m-d H:i:s');

        $id = $this->triageModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->triageModel->find($id);
    }

    public function getByVisitId(int $visitId): ?object
    {
        return $this->triageModel
            ->where('visit_id', $visitId)
            ->orderBy('triaged_at', 'DESC')
            ->first();
    }

    public function update(int $id, array $data): bool
    {
        $triage = $this->triageModel->find($id);

        if ($triage === null) {
            return false;
        }

        if (isset($data['vital_signs']) && is_array($data['vital_signs'])) {
            $data['vital_signs'] = json_encode($data['vital_signs']);
        }

        return $this->triageModel->update($id, $data);
    }

    public function getTriageHistory(int $patientId): array
    {
        return $this->triageModel
            ->select('triages.*, users.name as triaged_by_name')
            ->join('users', 'users.id = triages.triaged_by', 'left')
            ->where('triages.patient_id', $patientId)
            ->orderBy('triages.triaged_at', 'DESC')
            ->findAll();
    }

    public function getById(int $id): ?object
    {
        $triage = $this->triageModel
            ->select('triages.*, users.name as triaged_by_name, patients.name as patient_name, patients.mrn')
            ->join('users', 'users.id = triages.triaged_by', 'left')
            ->join('patients', 'patients.id = triages.patient_id', 'left')
            ->where('triages.id', $id)
            ->first();

        if ($triage !== null && is_string($triage->vital_signs)) {
            $triage->vital_signs = json_decode($triage->vital_signs, true);
        }

        return $triage;
    }
}
