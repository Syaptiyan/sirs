<?php

namespace App\Services\Doctor;

use App\Models\DoctorModel;
use App\Models\DoctorSpecializationModel;

class DoctorService
{
    private DoctorModel $doctorModel;
    private DoctorSpecializationModel $specializationModel;

    public function __construct()
    {
        $this->doctorModel = new DoctorModel();
        $this->specializationModel = new DoctorSpecializationModel();
    }

    public function getAll(?string $search = null, ?int $specializationId = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->doctorModel->builder();

        if ($search !== null && $search !== '') {
            $builder = $builder->groupStart()
                ->like('name', $search)
                ->orLike('employee_id', $search)
                ->orLike('sip', $search)
                ->orLike('phone', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }

        if ($specializationId !== null) {
            $builder = $builder->where('specialization_id', $specializationId);
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
        return $this->doctorModel->where('uuid', $uuid)->first();
    }

    public function create(array $data): ?object
    {
        $data['uuid'] = $this->generateUuid();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $id = $this->doctorModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->doctorModel->find($id);
    }

    public function update(string $uuid, array $data): bool
    {
        $doctor = $this->doctorModel->where('uuid', $uuid)->first();

        if ($doctor === null) {
            return false;
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->doctorModel->update($doctor->id, $data);
    }

    public function delete(string $uuid): bool
    {
        $doctor = $this->doctorModel->where('uuid', $uuid)->first();

        if ($doctor === null) {
            return false;
        }

        return $this->doctorModel->delete($doctor->id);
    }

    public function getSchedules(string $doctorUuid): array
    {
        $doctor = $this->doctorModel->where('uuid', $doctorUuid)->first();

        if ($doctor === null) {
            return [];
        }

        $builder = $this->doctorModel->db->table('doctor_schedules');
        return $builder->where('doctor_id', $doctor->id)
            ->orderBy('day_of_week', 'ASC')
            ->get()
            ->getResult();
    }

    public function getSpecializations(): array
    {
        return $this->specializationModel->orderBy('name', 'ASC')->findAll();
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
