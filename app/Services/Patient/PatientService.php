<?php

namespace App\Services\Patient;

use App\Models\PatientModel;
use App\Models\PatientAllergyModel;
use App\Models\PatientChronicDiseaseModel;
use App\Models\PatientDocumentModel;
use App\Models\VisitModel;

class PatientService
{
    private PatientModel $patientModel;
    private PatientAllergyModel $allergyModel;
    private PatientChronicDiseaseModel $chronicDiseaseModel;
    private PatientDocumentModel $documentModel;
    private VisitModel $visitModel;

    public function __construct()
    {
        $this->patientModel = new PatientModel();
        $this->allergyModel = new PatientAllergyModel();
        $this->chronicDiseaseModel = new PatientChronicDiseaseModel();
        $this->documentModel = new PatientDocumentModel();
        $this->visitModel = new VisitModel();
    }

    public function getAll(?string $search = null, ?string $gender = null, ?bool $isActive = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->patientModel->builder();

        if ($search !== null && $search !== '') {
            $builder = $builder->groupStart()
                ->like('name', $search)
                ->orLike('mr_number', $search)
                ->orLike('nik', $search)
                ->orLike('phone', $search)
                ->groupEnd();
        }

        if ($gender !== null && $gender !== '') {
            $builder = $builder->where('gender', $gender);
        }

        if ($isActive !== null) {
            $builder = $builder->where('is_active', $isActive ? 1 : 0);
        }

        $total = $builder->countAllResults(false);

        $offset = ($page - 1) * $perPage;
        $data = $builder->orderBy('created_at', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();

        return [
            'data' => $data,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => (int) ceil($total / $perPage),
            ],
        ];
    }

    public function getByUuid(string $uuid): ?object
    {
        return $this->patientModel->where('uuid', $uuid)->first();
    }

    public function create(array $data): ?object
    {
        $data['uuid'] = $this->generateUuid();
        $data['mr_number'] = $this->generateMRN();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $id = $this->patientModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->patientModel->find($id);
    }

    public function update(string $uuid, array $data): bool
    {
        $patient = $this->patientModel->where('uuid', $uuid)->first();

        if ($patient === null) {
            return false;
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->patientModel->update($patient->id, $data);
    }

    public function delete(string $uuid): bool
    {
        $patient = $this->patientModel->where('uuid', $uuid)->first();

        if ($patient === null) {
            return false;
        }

        return $this->patientModel->delete($patient->id);
    }

    public function generateMRN(): string
    {
        $prefix = 'RM-' . date('Ymd') . '-';
        $lastPatient = $this->patientModel
            ->like('mr_number', $prefix, 'after')
            ->orderBy('mr_number', 'DESC')
            ->first();

        if ($lastPatient) {
            $lastNumber = (int) substr($lastPatient->mr_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }

    public function getVisits(string $patientUuid, int $page = 1, int $perPage = 20): array
    {
        $patient = $this->patientModel->where('uuid', $patientUuid)->first();

        if ($patient === null) {
            return ['data' => [], 'meta' => ['total' => 0, 'page' => 1, 'per_page' => $perPage, 'total_pages' => 0]];
        }

        $total = $this->visitModel->where('patient_id', $patient->id)->countAllResults(false);
        $offset = ($page - 1) * $perPage;

        $data = $this->visitModel->where('patient_id', $patient->id)
            ->orderBy('visit_date', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();

        return [
            'data' => $data,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => (int) ceil($total / $perPage),
            ],
        ];
    }

    public function getAllergies(string $patientUuid): array
    {
        $patient = $this->patientModel->where('uuid', $patientUuid)->first();

        if ($patient === null) {
            return [];
        }

        return $this->allergyModel->where('patient_id', $patient->id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function addAllergy(string $patientUuid, array $data): ?object
    {
        $patient = $this->patientModel->where('uuid', $patientUuid)->first();

        if ($patient === null) {
            return null;
        }

        $data['uuid'] = $this->generateUuid();
        $data['patient_id'] = $patient->id;
        $data['created_at'] = date('Y-m-d H:i:s');

        $id = $this->allergyModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->allergyModel->find($id);
    }

    public function removeAllergy(string $allergyUuid): bool
    {
        $allergy = $this->allergyModel->where('uuid', $allergyUuid)->first();

        if ($allergy === null) {
            return false;
        }

        return $this->allergyModel->delete($allergy->id);
    }

    public function getChronicDiseases(string $patientUuid): array
    {
        $patient = $this->patientModel->where('uuid', $patientUuid)->first();

        if ($patient === null) {
            return [];
        }

        return $this->chronicDiseaseModel->where('patient_id', $patient->id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function addChronicDisease(string $patientUuid, array $data): ?object
    {
        $patient = $this->patientModel->where('uuid', $patientUuid)->first();

        if ($patient === null) {
            return null;
        }

        $data['uuid'] = $this->generateUuid();
        $data['patient_id'] = $patient->id;
        $data['created_at'] = date('Y-m-d H:i:s');

        $id = $this->chronicDiseaseModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->chronicDiseaseModel->find($id);
    }

    public function getDocuments(string $patientUuid): array
    {
        $patient = $this->patientModel->where('uuid', $patientUuid)->first();

        if ($patient === null) {
            return [];
        }

        return $this->documentModel->where('patient_id', $patient->id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function addDocument(string $patientUuid, array $data): ?object
    {
        $patient = $this->patientModel->where('uuid', $patientUuid)->first();

        if ($patient === null) {
            return null;
        }

        $data['uuid'] = $this->generateUuid();
        $data['patient_id'] = $patient->id;
        $data['created_at'] = date('Y-m-d H:i:s');

        $id = $this->documentModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->documentModel->find($id);
    }

    public function export(string $format = 'csv'): string
    {
        $patients = $this->patientModel->orderBy('created_at', 'DESC')->findAll();

        if ($format === 'csv') {
            return $this->exportCsv($patients);
        }

        return $this->exportCsv($patients);
    }

    private function exportCsv(array $patients): string
    {
        $output = fopen('php://temp', 'r+');

        fputcsv($output, [
            'No. RM',
            'Nama',
            'NIK',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Telepon',
            'Email',
            'Alamat',
            'Status',
        ]);

        foreach ($patients as $patient) {
            fputcsv($output, [
                $patient->mr_number,
                $patient->name,
                $patient->nik ?? '-',
                $patient->gender === 'M' ? 'Laki-laki' : 'Perempuan',
                $patient->birth_place ?? '-',
                $patient->birth_date ?? '-',
                $patient->phone ?? '-',
                $patient->email ?? '-',
                $patient->address ?? '-',
                $patient->is_active ? 'Aktif' : 'Nonaktif',
            ]);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

    private function generateUuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
