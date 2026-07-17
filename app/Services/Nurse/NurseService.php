<?php

namespace App\Services\Nurse;

use App\Models\NurseModel;

class NurseService
{
    private NurseModel $nurseModel;

    public function __construct()
    {
        $this->nurseModel = new NurseModel();
    }

    public function getAll(?string $search = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->nurseModel->builder();

        if ($search !== null && $search !== '') {
            $builder = $builder->groupStart()
                ->like('name', $search)
                ->orLike('employee_id', $search)
                ->orLike('sip', $search)
                ->orLike('phone', $search)
                ->orLike('email', $search)
                ->groupEnd();
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
        return $this->nurseModel->where('uuid', $uuid)->first();
    }

    public function create(array $data): ?object
    {
        $data['uuid'] = $this->generateUuid();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $id = $this->nurseModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->nurseModel->find($id);
    }

    public function update(string $uuid, array $data): bool
    {
        $nurse = $this->nurseModel->where('uuid', $uuid)->first();

        if ($nurse === null) {
            return false;
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->nurseModel->update($nurse->id, $data);
    }

    public function delete(string $uuid): bool
    {
        $nurse = $this->nurseModel->where('uuid', $uuid)->first();

        if ($nurse === null) {
            return false;
        }

        return $this->nurseModel->delete($nurse->id);
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
