<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use App\Services\InventarisService;

class InventarisController extends BaseController
{
    private InventarisService $inventarisService;

    public function __construct()
    {
        $this->inventarisService = new InventarisService();
    }

    public function index(): string
    {
        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $status = $this->request->getGet('status');
        $page = (int) $this->request->getGet('page', 1);

        $result = $this->inventarisService->getAll($search, $category, $status, $page);
        $categories = $this->inventarisService->getCategories();

        $data = [
            'title'      => 'Inventaris',
            'items'      => $result['items'],
            'total'      => $result['total'],
            'page'       => $result['page'],
            'perPage'    => $result['perPage'],
            'totalPages' => $result['totalPages'],
            'categories' => $categories,
            'search'     => $search,
            'category'   => $category,
            'status'     => $status,
        ];

        return view('pages/inventory/index', $data);
    }

    public function create(): string
    {
        $data = [
            'title' => 'Tambah Inventaris',
        ];

        return view('pages/inventory/create', $data);
    }

    public function store()
    {
        $rules = [
            'code'           => 'required|max_length[20]|is_unique[inventaris.code]',
            'name'           => 'required|max_length[200]',
            'category'       => 'required|max_length[100]',
            'purchase_date'  => 'permit_empty|valid_date',
            'purchase_price' => 'permit_empty|decimal',
            'current_value'  => 'permit_empty|decimal',
            'location'       => 'permit_empty|max_length[200]',
            'condition'      => 'required|in_list[good,fair,poor,disposed]',
            'status'         => 'required|in_list[active,maintenance,disposed]',
            'notes'          => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'code'           => $this->request->getPost('code'),
            'name'           => $this->request->getPost('name'),
            'category'       => $this->request->getPost('category'),
            'purchase_date'  => $this->request->getPost('purchase_date') ?: null,
            'purchase_price' => (float) $this->request->getPost('purchase_price'),
            'current_value'  => (float) $this->request->getPost('current_value'),
            'location'       => $this->request->getPost('location') ?: null,
            'condition'      => $this->request->getPost('condition'),
            'status'         => $this->request->getPost('status'),
            'notes'          => $this->request->getPost('notes') ?: null,
        ];

        $item = $this->inventarisService->create($data);

        if ($item === null) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan inventaris.');
        }

        return redirect()->to('/inventory/' . $item->uuid)->with('success', 'Inventaris berhasil ditambahkan.');
    }

    public function show(string $uuid): string
    {
        $item = $this->inventarisService->getByUuid($uuid);

        if ($item === null) {
            return redirect()->to('/inventory')->with('error', 'Inventaris tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Inventaris',
            'item'  => $item,
        ];

        return view('pages/inventory/show', $data);
    }

    public function edit(string $uuid): string
    {
        $item = $this->inventarisService->getByUuid($uuid);

        if ($item === null) {
            return redirect()->to('/inventory')->with('error', 'Inventaris tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Inventaris',
            'item'  => $item,
        ];

        return view('pages/inventory/edit', $data);
    }

    public function update(string $uuid)
    {
        $item = $this->inventarisService->getByUuid($uuid);

        if ($item === null) {
            return redirect()->to('/inventory')->with('error', 'Inventaris tidak ditemukan.');
        }

        $rules = [
            'code'           => 'required|max_length[20]|is_unique[inventaris.code,id,' . $item->id . ']',
            'name'           => 'required|max_length[200]',
            'category'       => 'required|max_length[100]',
            'purchase_date'  => 'permit_empty|valid_date',
            'purchase_price' => 'permit_empty|decimal',
            'current_value'  => 'permit_empty|decimal',
            'location'       => 'permit_empty|max_length[200]',
            'condition'      => 'required|in_list[good,fair,poor,disposed]',
            'status'         => 'required|in_list[active,maintenance,disposed]',
            'notes'          => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'code'           => $this->request->getPost('code'),
            'name'           => $this->request->getPost('name'),
            'category'       => $this->request->getPost('category'),
            'purchase_date'  => $this->request->getPost('purchase_date') ?: null,
            'purchase_price' => (float) $this->request->getPost('purchase_price'),
            'current_value'  => (float) $this->request->getPost('current_value'),
            'location'       => $this->request->getPost('location') ?: null,
            'condition'      => $this->request->getPost('condition'),
            'status'         => $this->request->getPost('status'),
            'notes'          => $this->request->getPost('notes') ?: null,
        ];

        if (!$this->inventarisService->update($uuid, $data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui inventaris.');
        }

        return redirect()->to('/inventory/' . $uuid)->with('success', 'Inventaris berhasil diperbarui.');
    }

    public function delete(string $uuid)
    {
        if (!$this->inventarisService->delete($uuid)) {
            return redirect()->back()->with('error', 'Gagal menghapus inventaris.');
        }

        return redirect()->to('/inventory')->with('success', 'Inventaris berhasil dihapus.');
    }
}
