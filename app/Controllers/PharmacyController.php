<?php

namespace App\Controllers;

use App\Services\PharmacyService;
use App\Models\DrugModel;
use App\Models\DrugCategoryModel;

class PharmacyController extends BaseController
{
    private PharmacyService $pharmacyService;
    private DrugModel $drugModel;
    private DrugCategoryModel $categoryModel;

    public function __construct()
    {
        $this->pharmacyService = new PharmacyService();
        $this->drugModel = new DrugModel();
        $this->categoryModel = new DrugCategoryModel();
    }

    public function index(): string
    {
        $search = $this->request->getGet('search');
        $categoryId = $this->request->getGet('category_id');
        $page = (int) $this->request->getGet('page', 1);

        $result = $this->pharmacyService->getAll($search, $categoryId ? (int) $categoryId : null, $page);
        $categories = $this->pharmacyService->getCategories();

        $data = [
            'title'      => 'Obat',
            'drugs'      => $result['drugs'],
            'total'      => $result['total'],
            'page'       => $result['page'],
            'perPage'    => $result['perPage'],
            'totalPages' => $result['totalPages'],
            'categories' => $categories,
            'search'     => $search,
            'categoryId' => $categoryId,
        ];

        return view('pages/pharmacy/drugs/index', $data);
    }

    public function create(): string
    {
        $categories = $this->pharmacyService->getCategories();

        $data = [
            'title'      => 'Tambah Obat',
            'categories' => $categories,
        ];

        return view('pages/pharmacy/drugs/create', $data);
    }

    public function store()
    {
        $rules = [
            'code'         => 'required|max_length[20]|is_unique[drugs.code]',
            'name'         => 'required|max_length[200]',
            'generic_name' => 'permit_empty|max_length[200]',
            'category_id'  => 'required|integer',
            'form'         => 'required|in_list[tablet,kapsul,sirup,injeksi,salep,tetes]',
            'strength'     => 'permit_empty|max_length[100]',
            'unit'         => 'required|max_length[20]',
            'manufacturer' => 'permit_empty|max_length[200]',
            'buy_price'    => 'permit_empty|decimal',
            'sell_price'   => 'permit_empty|decimal',
            'min_stock'    => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'code'         => $this->request->getPost('code'),
            'name'         => $this->request->getPost('name'),
            'generic_name' => $this->request->getPost('generic_name') ?: null,
            'category_id'  => (int) $this->request->getPost('category_id'),
            'form'         => $this->request->getPost('form'),
            'strength'     => $this->request->getPost('strength') ?: null,
            'unit'         => $this->request->getPost('unit'),
            'manufacturer' => $this->request->getPost('manufacturer') ?: null,
            'buy_price'    => (float) $this->request->getPost('buy_price'),
            'sell_price'   => (float) $this->request->getPost('sell_price'),
            'min_stock'    => (int) $this->request->getPost('min_stock'),
            'is_active'    => 1,
        ];

        $drug = $this->pharmacyService->create($data);

        if ($drug === null) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan obat.');
        }

        return redirect()->to('/pharmacy/drugs/' . $drug->uuid)->with('success', 'Obat berhasil ditambahkan.');
    }

    public function show(string $uuid): string
    {
        $drug = $this->pharmacyService->getByUuid($uuid);

        if ($drug === null) {
            return redirect()->to('/pharmacy/drugs')->with('error', 'Obat tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Obat',
            'drug'  => $drug,
        ];

        return view('pages/pharmacy/drugs/show', $data);
    }

    public function edit(string $uuid): string
    {
        $drug = $this->pharmacyService->getByUuid($uuid);

        if ($drug === null) {
            return redirect()->to('/pharmacy/drugs')->with('error', 'Obat tidak ditemukan.');
        }

        $categories = $this->pharmacyService->getCategories();

        $data = [
            'title'      => 'Edit Obat',
            'drug'       => $drug,
            'categories' => $categories,
        ];

        return view('pages/pharmacy/drugs/edit', $data);
    }

    public function update(string $uuid)
    {
        $rules = [
            'code'         => 'required|max_length[20]|is_unique[drugs.code,id,{id}]',
            'name'         => 'required|max_length[200]',
            'generic_name' => 'permit_empty|max_length[200]',
            'category_id'  => 'required|integer',
            'form'         => 'required|in_list[tablet,kapsul,sirup,injeksi,salep,tetes]',
            'strength'     => 'permit_empty|max_length[100]',
            'unit'         => 'required|max_length[20]',
            'manufacturer' => 'permit_empty|max_length[200]',
            'buy_price'    => 'permit_empty|decimal',
            'sell_price'   => 'permit_empty|decimal',
            'min_stock'    => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'code'         => $this->request->getPost('code'),
            'name'         => $this->request->getPost('name'),
            'generic_name' => $this->request->getPost('generic_name') ?: null,
            'category_id'  => (int) $this->request->getPost('category_id'),
            'form'         => $this->request->getPost('form'),
            'strength'     => $this->request->getPost('strength') ?: null,
            'unit'         => $this->request->getPost('unit'),
            'manufacturer' => $this->request->getPost('manufacturer') ?: null,
            'buy_price'    => (float) $this->request->getPost('buy_price'),
            'sell_price'   => (float) $this->request->getPost('sell_price'),
            'min_stock'    => (int) $this->request->getPost('min_stock'),
        ];

        if (!$this->pharmacyService->update($uuid, $data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui obat.');
        }

        return redirect()->to('/pharmacy/drugs/' . $uuid)->with('success', 'Obat berhasil diperbarui.');
    }

    public function delete(string $uuid)
    {
        if (!$this->pharmacyService->delete($uuid)) {
            return redirect()->back()->with('error', 'Gagal menghapus obat.');
        }

        return redirect()->to('/pharmacy/drugs')->with('success', 'Obat berhasil dihapus.');
    }

    public function stocks(): string
    {
        $drugId = $this->request->getGet('drug_id');
        $stocks = $this->pharmacyService->getStocks($drugId ? (int) $drugId : null);
        $drugs = $this->pharmacyService->getAll(null, null, 1, 1000)['drugs'];

        $data = [
            'title'  => 'Stok Obat',
            'stocks' => $stocks,
            'drugs'  => $drugs,
            'drugId' => $drugId,
        ];

        return view('pages/pharmacy/stocks/index', $data);
    }

    public function receive()
    {
        $rules = [
            'drug_id'      => 'required|integer',
            'batch_id'     => 'required|integer',
            'quantity'     => 'required|integer|greater_than[0]',
            'receipt_date' => 'required|valid_date',
            'supplier_id'  => 'permit_empty|integer',
            'notes'        => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $result = $this->pharmacyService->receiveStock(
            (int) $this->request->getPost('drug_id'),
            (int) $this->request->getPost('batch_id'),
            (int) $this->request->getPost('quantity'),
            $this->request->getPost('receipt_date'),
            $this->request->getPost('supplier_id') ? (int) $this->request->getPost('supplier_id') : null,
            $this->request->getPost('notes')
        );

        if (!$result) {
            return redirect()->back()->withInput()->with('error', 'Gagal menerima stok.');
        }

        return redirect()->to('/pharmacy/stocks')->with('success', 'Stok berhasil diterima.');
    }

    public function distribute()
    {
        $rules = [
            'drug_id'         => 'required|integer',
            'batch_id'        => 'required|integer',
            'quantity'        => 'required|integer|greater_than[0]',
            'prescription_id' => 'permit_empty|integer',
            'notes'           => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $result = $this->pharmacyService->distributeStock(
            (int) $this->request->getPost('drug_id'),
            (int) $this->request->getPost('batch_id'),
            (int) $this->request->getPost('quantity'),
            $this->request->getPost('prescription_id') ? (int) $this->request->getPost('prescription_id') : null,
            $this->request->getPost('notes')
        );

        if (!$result) {
            return redirect()->back()->withInput()->with('error', 'Gagal mendistribusikan stok. Pastikan stok mencukupi.');
        }

        return redirect()->to('/pharmacy/stocks')->with('success', 'Stok berhasil didistribusikan.');
    }

    public function returnStock()
    {
        $rules = [
            'drug_id'     => 'required|integer',
            'batch_id'    => 'required|integer',
            'quantity'    => 'required|integer|greater_than[0]',
            'reason'      => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $result = $this->pharmacyService->returnStock(
            (int) $this->request->getPost('drug_id'),
            (int) $this->request->getPost('batch_id'),
            (int) $this->request->getPost('quantity'),
            $this->request->getPost('reason')
        );

        if (!$result) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengembalikan stok.');
        }

        return redirect()->to('/pharmacy/stocks')->with('success', 'Stok berhasil dikembalikan.');
    }

    public function opname()
    {
        $rules = [
            'drug_id'         => 'required|integer',
            'actual_quantity' => 'required|integer|greater_than_equal_to[0]',
            'notes'           => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $result = $this->pharmacyService->stockOpname(
            (int) $this->request->getPost('drug_id'),
            (int) $this->request->getPost('actual_quantity'),
            $this->request->getPost('notes')
        );

        if (!$result) {
            return redirect()->back()->withInput()->with('error', 'Gagal melakukan stok opname.');
        }

        return redirect()->to('/pharmacy/stocks')->with('success', 'Stok opname berhasil dilakukan.');
    }

    public function alerts(): string
    {
        $lowStock = $this->pharmacyService->getLowStock();
        $expiringSoon = $this->pharmacyService->getExpiringSoon(90);

        $data = [
            'title'        => 'Peringatan Stok',
            'lowStock'     => $lowStock,
            'expiringSoon' => $expiringSoon,
        ];

        return view('pages/pharmacy/alerts/index', $data);
    }
}
