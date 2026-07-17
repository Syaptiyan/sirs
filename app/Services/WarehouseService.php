<?php

namespace App\Services;

use App\Models\WarehouseModel;
use App\Models\SupplierModel;
use App\Models\ItemCategoryModel;
use App\Models\ItemModel;
use App\Models\ItemStockModel;
use App\Models\ItemReceiptModel;
use App\Models\ItemDistributionModel;

class WarehouseService
{
    private WarehouseModel $warehouseModel;
    private SupplierModel $supplierModel;
    private ItemCategoryModel $categoryModel;
    private ItemModel $itemModel;
    private ItemStockModel $stockModel;
    private ItemReceiptModel $receiptModel;
    private ItemDistributionModel $distributionModel;

    public function __construct()
    {
        $this->warehouseModel = new WarehouseModel();
        $this->supplierModel = new SupplierModel();
        $this->categoryModel = new ItemCategoryModel();
        $this->itemModel = new ItemModel();
        $this->stockModel = new ItemStockModel();
        $this->receiptModel = new ItemReceiptModel();
        $this->distributionModel = new ItemDistributionModel();
    }

    public function getAll(?string $search = null, ?int $categoryId = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->itemModel
            ->select('items.*')
            ->select('item_categories.name as category_name')
            ->join('item_categories', 'item_categories.id = items.category_id', 'left')
            ->orderBy('items.name', 'ASC');

        if ($search !== null && $search !== '') {
            $builder->groupStart()
                ->like('items.name', $search)
                ->orLike('items.code', $search)
                ->groupEnd();
        }

        if ($categoryId !== null) {
            $builder->where('items.category_id', $categoryId);
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
        $item = $this->itemModel
            ->select('items.*')
            ->select('item_categories.name as category_name')
            ->join('item_categories', 'item_categories.id = items.category_id', 'left')
            ->where('items.uuid', $uuid)
            ->first();

        if ($item === null) {
            return null;
        }

        $item->stocks = $this->stockModel
            ->select('item_stocks.*')
            ->select('warehouses.name as warehouse_name')
            ->join('warehouses', 'warehouses.id = item_stocks.warehouse_id', 'left')
            ->where('item_stocks.item_id', $item->id)
            ->findAll();

        $item->total_stock = array_sum(array_column($item->stocks, 'quantity'));

        return $item;
    }

    public function create(array $data): ?object
    {
        $itemId = $this->itemModel->insert($data);

        if ($itemId === false) {
            return null;
        }

        return $this->itemModel->find($itemId);
    }

    public function update(string $uuid, array $data): bool
    {
        $item = $this->itemModel->where('uuid', $uuid)->first();

        if ($item === null) {
            return false;
        }

        return $this->itemModel->update($item->id, $data);
    }

    public function delete(string $uuid): bool
    {
        $item = $this->itemModel->where('uuid', $uuid)->first();

        if ($item === null) {
            return false;
        }

        return $this->itemModel->delete($item->id);
    }

    public function getStocks(?int $itemId = null, ?int $warehouseId = null): array
    {
        $builder = $this->stockModel
            ->select('item_stocks.*')
            ->select('items.name as item_name, items.code as item_code, items.unit')
            ->select('warehouses.name as warehouse_name')
            ->join('items', 'items.id = item_stocks.item_id', 'left')
            ->join('warehouses', 'warehouses.id = item_stocks.warehouse_id', 'left')
            ->orderBy('items.name', 'ASC');

        if ($itemId !== null) {
            $builder->where('item_stocks.item_id', $itemId);
        }

        if ($warehouseId !== null) {
            $builder->where('item_stocks.warehouse_id', $warehouseId);
        }

        return $builder->findAll();
    }

    public function receiveStock(int $itemId, int $warehouseId, int $quantity, string $receiptDate, ?int $supplierId = null, ?string $notes = null): bool
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $this->receiptModel->insert([
            'item_id'      => $itemId,
            'warehouse_id' => $warehouseId,
            'supplier_id'  => $supplierId,
            'quantity'     => $quantity,
            'receipt_date' => $receiptDate,
            'notes'        => $notes,
            'created_by'   => user()->id,
        ]);

        $stock = $this->stockModel
            ->where('item_id', $itemId)
            ->where('warehouse_id', $warehouseId)
            ->first();

        if ($stock) {
            $this->stockModel->update($stock->id, [
                'quantity' => $stock->quantity + $quantity,
            ]);
        } else {
            $this->stockModel->insert([
                'item_id'      => $itemId,
                'warehouse_id' => $warehouseId,
                'quantity'     => $quantity,
            ]);
        }

        $db->transComplete();
        return $db->transStatus() !== false;
    }

    public function distributeStock(int $itemId, int $warehouseId, int $quantity, ?string $destination = null, ?string $notes = null): bool
    {
        $stock = $this->stockModel
            ->where('item_id', $itemId)
            ->where('warehouse_id', $warehouseId)
            ->first();

        if ($stock === null || $stock->quantity < $quantity) {
            return false;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->distributionModel->insert([
            'item_id'           => $itemId,
            'warehouse_id'      => $warehouseId,
            'quantity'          => $quantity,
            'distribution_date' => date('Y-m-d'),
            'destination'       => $destination,
            'notes'             => $notes,
            'created_by'        => user()->id,
        ]);

        $this->stockModel->update($stock->id, [
            'quantity' => $stock->quantity - $quantity,
        ]);

        $db->transComplete();
        return $db->transStatus() !== false;
    }

    public function getSuppliers(): array
    {
        return $this->supplierModel->orderBy('name', 'ASC')->findAll();
    }

    public function createSupplier(array $data): ?object
    {
        $supplierId = $this->supplierModel->insert($data);

        if ($supplierId === false) {
            return null;
        }

        return $this->supplierModel->find($supplierId);
    }

    public function updateSupplier(string $uuid, array $data): bool
    {
        $supplier = $this->supplierModel->where('uuid', $uuid)->first();

        if ($supplier === null) {
            return false;
        }

        return $this->supplierModel->update($supplier->id, $data);
    }

    public function getCategories(): array
    {
        return $this->categoryModel->orderBy('name', 'ASC')->findAll();
    }

    public function getWarehouses(): array
    {
        return $this->warehouseModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
    }

    public function getSupplierByUuid(string $uuid): ?object
    {
        return $this->supplierModel->where('uuid', $uuid)->first();
    }

    public function deleteSupplier(string $uuid): bool
    {
        $supplier = $this->supplierModel->where('uuid', $uuid)->first();

        if ($supplier === null) {
            return false;
        }

        return $this->supplierModel->delete($supplier->id);
    }
}
