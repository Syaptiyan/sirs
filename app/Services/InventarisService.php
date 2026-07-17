<?php

namespace App\Services;

use App\Models\InventarisModel;

class InventarisService
{
    private InventarisModel $inventarisModel;

    public function __construct()
    {
        $this->inventarisModel = new InventarisModel();
    }

    public function getAll(?string $search = null, ?string $category = null, ?string $status = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->inventarisModel->orderBy('name', 'ASC');

        if ($search !== null && $search !== '') {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('code', $search)
                ->groupEnd();
        }

        if ($category !== null && $category !== '') {
            $builder->where('category', $category);
        }

        if ($status !== null && $status !== '') {
            $builder->where('status', $status);
        }

        $total = $builder->countAllResults(false);

        $items = $builder->findAll($perPage, ($page - 1) * $perPage);

        return [
            'items'      => $items,
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => ceil($total / $perPage),
        ];
    }

    public function getByUuid(string $uuid): ?object
    {
        return $this->inventarisModel->where('uuid', $uuid)->first();
    }

    public function create(array $data): ?object
    {
        $id = $this->inventarisModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->inventarisModel->find($id);
    }

    public function update(string $uuid, array $data): bool
    {
        $item = $this->inventarisModel->where('uuid', $uuid)->first();

        if ($item === null) {
            return false;
        }

        return $this->inventarisModel->update($item->id, $data);
    }

    public function delete(string $uuid): bool
    {
        $item = $this->inventarisModel->where('uuid', $uuid)->first();

        if ($item === null) {
            return false;
        }

        return $this->inventarisModel->delete($item->id);
    }

    public function getCategories(): array
    {
        $rows = $this->inventarisModel
            ->selectDistinct('category')
            ->orderBy('category', 'ASC')
            ->findAll();

        return $rows ? array_column($rows, 'category') : [];
    }

    public function getStats(): array
    {
        $total = $this->inventarisModel->countAllResults(false);

        $active = (clone $this->inventarisModel)->where('status', 'active')->countAllResults(false);
        $maintenance = (clone $this->inventarisModel)->where('status', 'maintenance')->countAllResults(false);
        $disposed = (clone $this->inventarisModel)->where('status', 'disposed')->countAllResults(false);

        return [
            'total'       => $total,
            'active'      => $active,
            'maintenance' => $maintenance,
            'disposed'    => $disposed,
        ];
    }
}
