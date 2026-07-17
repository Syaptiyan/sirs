<?php

namespace App\Controllers\Warehouse;

use App\Controllers\BaseController;
use App\Services\WarehouseService;
use App\Models\ItemCategoryModel;
use App\Models\WarehouseModel;

class WarehouseController extends BaseController
{
    private WarehouseService $warehouseService;
    private ItemCategoryModel $categoryModel;
    private WarehouseModel $warehouseModel;

    public function __construct()
    {
        $this->warehouseService = new WarehouseService();
        $this->categoryModel = new ItemCategoryModel();
        $this->warehouseModel = new WarehouseModel();
    }

    // ==================== ITEMS ====================

    public function index(): string
    {
        $search = $this->request->getGet('search');
        $categoryId = $this->request->getGet('category_id');
        $page = (int) $this->request->getGet('page', 1);

        $result = $this->warehouseService->getAll($search, $categoryId ? (int) $categoryId : null, $page);
        $categories = $this->warehouseService->getCategories();

        $data = [
            'title'      => 'Barang',
            'items'      => $result['items'],
            'total'      => $result['total'],
            'page'       => $result['page'],
            'perPage'    => $result['perPage'],
            'totalPages' => $result['totalPages'],
            'categories' => $categories,
            'search'     => $search,
            'categoryId' => $categoryId,
        ];

        return view('pages/warehouse/items/index', $data);
    }

    public function create(): string
    {
        $categories = $this->warehouseService->getCategories();

        $data = [
            'title'      => 'Tambah Barang',
            'categories' => $categories,
        ];

        return view('pages/warehouse/items/create', $data);
    }

    public function store()
    {
        $rules = [
            'code'        => 'required|max_length[20]|is_unique[items.code]',
            'name'        => 'required|max_length[200]',
            'category_id' => 'required|integer',
            'unit'        => 'required|max_length[20]',
            'buy_price'   => 'permit_empty|decimal',
            'min_stock'   => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'code'        => $this->request->getPost('code'),
            'name'        => $this->request->getPost('name'),
            'category_id' => (int) $this->request->getPost('category_id'),
            'unit'        => $this->request->getPost('unit'),
            'buy_price'   => (float) $this->request->getPost('buy_price'),
            'min_stock'   => (int) $this->request->getPost('min_stock'),
            'is_active'   => 1,
        ];

        $item = $this->warehouseService->create($data);

        if ($item === null) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan barang.');
        }

        return redirect()->to('/warehouse/items/' . $item->uuid)->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(string $uuid): string
    {
        $item = $this->warehouseService->getByUuid($uuid);

        if ($item === null) {
            return redirect()->to('/warehouse/items')->with('error', 'Barang tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Barang',
            'item'  => $item,
        ];

        return view('pages/warehouse/items/show', $data);
    }

    public function edit(string $uuid): string
    {
        $item = $this->warehouseService->getByUuid($uuid);

        if ($item === null) {
            return redirect()->to('/warehouse/items')->with('error', 'Barang tidak ditemukan.');
        }

        $categories = $this->warehouseService->getCategories();

        $data = [
            'title'      => 'Edit Barang',
            'item'       => $item,
            'categories' => $categories,
        ];

        return view('pages/warehouse/items/edit', $data);
    }

    public function update(string $uuid)
    {
        $item = $this->warehouseService->getByUuid($uuid);

        if ($item === null) {
            return redirect()->to('/warehouse/items')->with('error', 'Barang tidak ditemukan.');
        }

        $rules = [
            'code'        => 'required|max_length[20]|is_unique[items.code,id,' . $item->id . ']',
            'name'        => 'required|max_length[200]',
            'category_id' => 'required|integer',
            'unit'        => 'required|max_length[20]',
            'buy_price'   => 'permit_empty|decimal',
            'min_stock'   => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'code'        => $this->request->getPost('code'),
            'name'        => $this->request->getPost('name'),
            'category_id' => (int) $this->request->getPost('category_id'),
            'unit'        => $this->request->getPost('unit'),
            'buy_price'   => (float) $this->request->getPost('buy_price'),
            'min_stock'   => (int) $this->request->getPost('min_stock'),
        ];

        if (!$this->warehouseService->update($uuid, $data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui barang.');
        }

        return redirect()->to('/warehouse/items/' . $uuid)->with('success', 'Barang berhasil diperbarui.');
    }

    public function delete(string $uuid)
    {
        if (!$this->warehouseService->delete($uuid)) {
            return redirect()->back()->with('error', 'Gagal menghapus barang.');
        }

        return redirect()->to('/warehouse/items')->with('success', 'Barang berhasil dihapus.');
    }

    // ==================== STOCKS ====================

    public function stocks(): string
    {
        $itemId = $this->request->getGet('item_id');
        $warehouseId = $this->request->getGet('warehouse_id');
        $stocks = $this->warehouseService->getStocks($itemId ? (int) $itemId : null, $warehouseId ? (int) $warehouseId : null);
        $items = $this->warehouseService->getAll(null, null, 1, 1000)['items'];
        $warehouses = $this->warehouseService->getWarehouses();

        $data = [
            'title'       => 'Stok Barang',
            'stocks'      => $stocks,
            'items'       => $items,
            'warehouses'  => $warehouses,
            'itemId'      => $itemId,
            'warehouseId' => $warehouseId,
        ];

        return view('pages/warehouse/stocks/index', $data);
    }

    public function receive()
    {
        $rules = [
            'item_id'      => 'required|integer',
            'warehouse_id' => 'required|integer',
            'quantity'     => 'required|integer|greater_than[0]',
            'receipt_date' => 'required|valid_date',
            'supplier_id'  => 'permit_empty|integer',
            'notes'        => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $result = $this->warehouseService->receiveStock(
            (int) $this->request->getPost('item_id'),
            (int) $this->request->getPost('warehouse_id'),
            (int) $this->request->getPost('quantity'),
            $this->request->getPost('receipt_date'),
            $this->request->getPost('supplier_id') ? (int) $this->request->getPost('supplier_id') : null,
            $this->request->getPost('notes')
        );

        if (!$result) {
            return redirect()->back()->withInput()->with('error', 'Gagal menerima stok.');
        }

        return redirect()->to('/warehouse/stocks')->with('success', 'Stok berhasil diterima.');
    }

    public function distribute()
    {
        $rules = [
            'item_id'      => 'required|integer',
            'warehouse_id' => 'required|integer',
            'quantity'     => 'required|integer|greater_than[0]',
            'destination'  => 'permit_empty|max_length[200]',
            'notes'        => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $result = $this->warehouseService->distributeStock(
            (int) $this->request->getPost('item_id'),
            (int) $this->request->getPost('warehouse_id'),
            (int) $this->request->getPost('quantity'),
            $this->request->getPost('destination'),
            $this->request->getPost('notes')
        );

        if (!$result) {
            return redirect()->back()->withInput()->with('error', 'Gagal mendistribusikan stok. Pastikan stok mencukupi.');
        }

        return redirect()->to('/warehouse/stocks')->with('success', 'Stok berhasil didistribusikan.');
    }

    // ==================== SUPPLIERS ====================

    public function suppliers(): string
    {
        $suppliers = $this->warehouseService->getSuppliers();

        $data = [
            'title'     => 'Supplier',
            'suppliers' => $suppliers,
        ];

        return view('pages/warehouse/suppliers/index', $data);
    }

    public function createSupplier(): string
    {
        $data = [
            'title' => 'Tambah Supplier',
        ];

        return view('pages/warehouse/suppliers/create', $data);
    }

    public function storeSupplier()
    {
        $rules = [
            'name'    => 'required|max_length[200]',
            'company' => 'permit_empty|max_length[200]',
            'phone'   => 'permit_empty|max_length[20]',
            'email'   => 'permit_empty|max_length[150]|valid_email',
            'address' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'      => $this->request->getPost('name'),
            'company'   => $this->request->getPost('company') ?: null,
            'phone'     => $this->request->getPost('phone') ?: null,
            'email'     => $this->request->getPost('email') ?: null,
            'address'   => $this->request->getPost('address') ?: null,
            'is_active' => 1,
        ];

        $supplier = $this->warehouseService->createSupplier($data);

        if ($supplier === null) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan supplier.');
        }

        return redirect()->to('/warehouse/suppliers')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function editSupplier(string $uuid): string
    {
        $supplier = $this->warehouseService->getSupplierByUuid($uuid);

        if ($supplier === null) {
            return redirect()->to('/warehouse/suppliers')->with('error', 'Supplier tidak ditemukan.');
        }

        $data = [
            'title'    => 'Edit Supplier',
            'supplier' => $supplier,
        ];

        return view('pages/warehouse/suppliers/edit', $data);
    }

    public function updateSupplier(string $uuid)
    {
        $rules = [
            'name'    => 'required|max_length[200]',
            'company' => 'permit_empty|max_length[200]',
            'phone'   => 'permit_empty|max_length[20]',
            'email'   => 'permit_empty|max_length[150]|valid_email',
            'address' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'    => $this->request->getPost('name'),
            'company' => $this->request->getPost('company') ?: null,
            'phone'   => $this->request->getPost('phone') ?: null,
            'email'   => $this->request->getPost('email') ?: null,
            'address' => $this->request->getPost('address') ?: null,
        ];

        if (!$this->warehouseService->updateSupplier($uuid, $data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui supplier.');
        }

        return redirect()->to('/warehouse/suppliers')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function deleteSupplier(string $uuid)
    {
        if (!$this->warehouseService->deleteSupplier($uuid)) {
            return redirect()->back()->with('error', 'Gagal menghapus supplier.');
        }

        return redirect()->to('/warehouse/suppliers')->with('success', 'Supplier berhasil dihapus.');
    }
}
