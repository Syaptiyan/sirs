<?php

namespace App\Services\Polyclinic;

use App\Models\PolyclinicModel;
use App\Models\DoctorPolyclinicModel;
use App\Models\DoctorModel;

class PolyclinicService
{
    private PolyclinicModel $polyclinicModel;
    private DoctorPolyclinicModel $doctorPolyclinicModel;
    private DoctorModel $doctorModel;

    public function __construct()
    {
        $this->polyclinicModel = new PolyclinicModel();
        $this->doctorPolyclinicModel = new DoctorPolyclinicModel();
        $this->doctorModel = new DoctorModel();
    }

    public function getAll(?string $search = null, ?bool $isActive = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->polyclinicModel->builder();

        if ($search !== null && $search !== '') {
            $builder = $builder->groupStart()
                ->like('name', $search)
                ->orLike('code', $search)
                ->orLike('location', $search)
                ->groupEnd();
        }

        if ($isActive !== null) {
            $builder = $builder->where('is_active', $isActive);
        }

        $total = $builder->countAllResults(false);

        $offset = ($page - 1) * $perPage;
        $data = $builder->orderBy('name', 'ASC')
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
        return $this->polyclinicModel->where('uuid', $uuid)->first();
    }

    public function create(array $data): ?object
    {
        $data['uuid'] = $this->generateUuid();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $id = $this->polyclinicModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->polyclinicModel->find($id);
    }

    public function update(string $uuid, array $data): bool
    {
        $polyclinic = $this->polyclinicModel->where('uuid', $uuid)->first();

        if ($polyclinic === null) {
            return false;
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->polyclinicModel->update($polyclinic->id, $data);
    }

    public function delete(string $uuid): bool
    {
        $polyclinic = $this->polyclinicModel->where('uuid', $uuid)->first();

        if ($polyclinic === null) {
            return false;
        }

        return $this->polyclinicModel->delete($polyclinic->id);
    }

    public function getDoctors(string $polyclinicUuid): array
    {
        $polyclinic = $this->polyclinicModel->where('uuid', $polyclinicUuid)->first();

        if ($polyclinic === null) {
            return [];
        }

        return $this->doctorPolyclinicModel
            ->select('doctor_polyclinics.*, doctors.name as doctor_name, doctors.employee_id, doctors.sip')
            ->join('doctors', 'doctors.id = doctor_polyclinics.doctor_id')
            ->where('doctor_polyclinics.polyclinic_id', $polyclinic->id)
            ->orderBy('doctors.name', 'ASC')
            ->findAll();
    }

    public function assignDoctor(string $polyclinicUuid, int $doctorId): bool
    {
        $polyclinic = $this->polyclinicModel->where('uuid', $polyclinicUuid)->first();

        if ($polyclinic === null) {
            return false;
        }

        $exists = $this->doctorPolyclinicModel
            ->where('doctor_id', $doctorId)
            ->where('polyclinic_id', $polyclinic->id)
            ->first();

        if ($exists !== null) {
            return true;
        }

        return $this->doctorPolyclinicModel->insert([
            'doctor_id'     => $doctorId,
            'polyclinic_id' => $polyclinic->id,
        ]) !== false;
    }

    public function removeDoctor(string $polyclinicUuid, int $doctorId): bool
    {
        $polyclinic = $this->polyclinicModel->where('uuid', $polyclinicUuid)->first();

        if ($polyclinic === null) {
            return false;
        }

        return $this->doctorPolyclinicModel
            ->where('doctor_id', $doctorId)
            ->where('polyclinic_id', $polyclinic->id)
            ->delete();
    }

    public function getAvailableDoctors(): array
    {
        return $this->doctorModel->where('is_active', true)->orderBy('name', 'ASC')->findAll();
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