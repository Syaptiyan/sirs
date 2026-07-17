<?php

namespace App\Services;

use App\Models\DrugModel;
use App\Models\DrugCategoryModel;
use App\Models\DrugBatchModel;
use App\Models\DrugStockModel;
use App\Models\DrugReceiptModel;
use App\Models\DrugDistributionModel;
use App\Models\DrugReturnModel;
use App\Models\DrugStockOpnameModel;

class PharmacyService
{
    private DrugModel $drugModel;
    private DrugCategoryModel $categoryModel;
    private DrugBatchModel $batchModel;
    private DrugStockModel $stockModel;
    private DrugReceiptModel $receiptModel;
    private DrugDistributionModel $distributionModel;
    private DrugReturnModel $returnModel;
    private DrugStockOpnameModel $opnameModel;

    public function __construct()
    {
        $this->drugModel = new DrugModel();
        $this->categoryModel = new DrugCategoryModel();
        $this->batchModel = new DrugBatchModel();
        $this->stockModel = new DrugStockModel();
        $this->receiptModel = new DrugReceiptModel();
        $this->distributionModel = new DrugDistributionModel();
        $this->returnModel = new DrugReturnModel();
        $this->opnameModel = new DrugStockOpnameModel();
    }

    public function getAll(?string $search = null, ?int $categoryId = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->drugModel
            ->select('drugs.*')
            ->select('drug_categories.name as category_name')
            ->join('drug_categories', 'drug_categories.id = drugs.category_id', 'left')
            ->orderBy('drugs.name', 'ASC');

        if ($search !== null && $search !== '') {
            $builder->groupStart()
                ->like('drugs.name', $search)
                ->orLike('drugs.code', $search)
                ->orLike('drugs.generic_name', $search)
                ->groupEnd();
        }

        if ($categoryId !== null) {
            $builder->where('drugs.category_id', $categoryId);
        }

        $total = $builder->countAllResults(false);

        $drugs = $builder
            ->findAll($perPage, ($page - 1) * $perPage);

        return [
            'drugs'     => $drugs,
            'total'     => $total,
            'page'      => $page,
            'perPage'   => $perPage,
            'totalPages' => ceil($total / $perPage),
        ];
    }

    public function getByUuid(string $uuid): ?object
    {
        $drug = $this->drugModel
            ->select('drugs.*')
            ->select('drug_categories.name as category_name')
            ->join('drug_categories', 'drug_categories.id = drugs.category_id', 'left')
            ->where('drugs.uuid', $uuid)
            ->first();

        if ($drug === null) {
            return null;
        }

        $drug->batches = $this->batchModel
            ->where('drug_id', $drug->id)
            ->orderBy('expiry_date', 'ASC')
            ->findAll();

        $drug->stocks = $this->stockModel
            ->select('drug_stocks.*')
            ->select('drug_batches.batch_number, drug_batches.expiry_date')
            ->join('drug_batches', 'drug_batches.id = drug_stocks.batch_id', 'left')
            ->where('drug_stocks.drug_id', $drug->id)
            ->findAll();

        $drug->total_stock = array_sum(array_column($drug->stocks, 'quantity'));

        return $drug;
    }

    public function create(array $data): ?object
    {
        $drugId = $this->drugModel->insert($data);

        if ($drugId === false) {
            return null;
        }

        return $this->drugModel->find($drugId);
    }

    public function update(string $uuid, array $data): bool
    {
        $drug = $this->drugModel->where('uuid', $uuid)->first();

        if ($drug === null) {
            return false;
        }

        return $this->drugModel->update($drug->id, $data);
    }

    public function delete(string $uuid): bool
    {
        $drug = $this->drugModel->where('uuid', $uuid)->first();

        if ($drug === null) {
            return false;
        }

        return $this->drugModel->delete($drug->id);
    }

    public function getStocks(?int $drugId = null): array
    {
        $builder = $this->stockModel
            ->select('drug_stocks.*')
            ->select('drugs.name as drug_name, drugs.code as drug_code, drugs.unit')
            ->select('drug_batches.batch_number, drug_batches.expiry_date')
            ->join('drugs', 'drugs.id = drug_stocks.drug_id', 'left')
            ->join('drug_batches', 'drug_batches.id = drug_stocks.batch_id', 'left')
            ->orderBy('drugs.name', 'ASC');

        if ($drugId !== null) {
            $builder->where('drug_stocks.drug_id', $drugId);
        }

        return $builder->findAll();
    }

    public function receiveStock(int $drugId, int $batchId, int $quantity, string $receiptDate, ?int $supplierId = null, ?string $notes = null): bool
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $this->receiptModel->insert([
            'drug_id'      => $drugId,
            'batch_id'     => $batchId,
            'quantity'     => $quantity,
            'receipt_date' => $receiptDate,
            'supplier_id'  => $supplierId,
            'notes'        => $notes,
            'created_by'   => user()->id,
        ]);

        $batch = $this->batchModel->find($batchId);
        if ($batch) {
            $this->batchModel->update($batchId, [
                'quantity' => $batch->quantity + $quantity,
            ]);
        }

        $stock = $this->stockModel
            ->where('drug_id', $drugId)
            ->where('batch_id', $batchId)
            ->first();

        if ($stock) {
            $this->stockModel->update($stock->id, [
                'quantity' => $stock->quantity + $quantity,
            ]);
        } else {
            $this->stockModel->insert([
                'drug_id'  => $drugId,
                'batch_id' => $batchId,
                'quantity' => $quantity,
            ]);
        }

        $db->transComplete();
        return $db->transStatus() !== false;
    }

    public function distributeStock(int $drugId, int $batchId, int $quantity, ?int $prescriptionId = null, ?string $notes = null): bool
    {
        $stock = $this->stockModel
            ->where('drug_id', $drugId)
            ->where('batch_id', $batchId)
            ->first();

        if ($stock === null || $stock->quantity < $quantity) {
            return false;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->distributionModel->insert([
            'drug_id'           => $drugId,
            'batch_id'          => $batchId,
            'prescription_id'   => $prescriptionId,
            'quantity'          => $quantity,
            'distribution_date' => date('Y-m-d'),
            'notes'             => $notes,
            'created_by'        => user()->id,
        ]);

        $this->stockModel->update($stock->id, [
            'quantity' => $stock->quantity - $quantity,
        ]);

        $batch = $this->batchModel->find($batchId);
        if ($batch) {
            $this->batchModel->update($batchId, [
                'quantity' => $batch->quantity - $quantity,
            ]);
        }

        $db->transComplete();
        return $db->transStatus() !== false;
    }

    public function returnStock(int $drugId, int $batchId, int $quantity, string $reason): bool
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $this->returnModel->insert([
            'drug_id'     => $drugId,
            'batch_id'    => $batchId,
            'quantity'    => $quantity,
            'return_date' => date('Y-m-d'),
            'reason'      => $reason,
            'created_by'  => user()->id,
        ]);

        $stock = $this->stockModel
            ->where('drug_id', $drugId)
            ->where('batch_id', $batchId)
            ->first();

        if ($stock) {
            $this->stockModel->update($stock->id, [
                'quantity' => $stock->quantity + $quantity,
            ]);
        } else {
            $this->stockModel->insert([
                'drug_id'  => $drugId,
                'batch_id' => $batchId,
                'quantity' => $quantity,
            ]);
        }

        $batch = $this->batchModel->find($batchId);
        if ($batch) {
            $this->batchModel->update($batchId, [
                'quantity' => $batch->quantity + $quantity,
            ]);
        }

        $db->transComplete();
        return $db->transStatus() !== false;
    }

    public function getLowStock(): array
    {
        return $this->drugModel
            ->select('drugs.*')
            ->select('drug_categories.name as category_name')
            ->select('COALESCE(SUM(drug_stocks.quantity), 0) as total_stock')
            ->join('drug_categories', 'drug_categories.id = drugs.category_id', 'left')
            ->join('drug_stocks', 'drug_stocks.drug_id = drugs.id', 'left')
            ->where('drugs.is_active', 1)
            ->groupBy('drugs.id')
            ->having('total_stock <= drugs.min_stock')
            ->orderBy('total_stock', 'ASC')
            ->findAll();
    }

    public function getExpiringSoon(int $days = 90): array
    {
        $expiryThreshold = date('Y-m-d', strtotime("+{$days} days"));

        return $this->batchModel
            ->select('drug_batches.*')
            ->select('drugs.name as drug_name, drugs.code as drug_code')
            ->join('drugs', 'drugs.id = drug_batches.drug_id', 'left')
            ->where('drug_batches.expiry_date <=', $expiryThreshold)
            ->where('drug_batches.quantity >', 0)
            ->orderBy('drug_batches.expiry_date', 'ASC')
            ->findAll();
    }

    public function stockOpname(int $drugId, int $actualQuantity, ?string $notes = null): bool
    {
        $systemQuantity = 0;
        $stocks = $this->stockModel->where('drug_id', $drugId)->findAll();

        foreach ($stocks as $stock) {
            $systemQuantity += $stock->quantity;
        }

        $difference = $actualQuantity - $systemQuantity;

        $db = \Config\Database::connect();
        $db->transStart();

        $this->opnameModel->insert([
            'drug_id'         => $drugId,
            'system_quantity' => $systemQuantity,
            'actual_quantity' => $actualQuantity,
            'difference'      => $difference,
            'opname_date'     => date('Y-m-d'),
            'notes'           => $notes,
            'created_by'      => user()->id,
        ]);

        if ($difference !== 0) {
            $firstStock = $this->stockModel->where('drug_id', $drugId)->first();
            if ($firstStock) {
                $this->stockModel->update($firstStock->id, [
                    'quantity' => $actualQuantity,
                ]);
            }
        }

        $db->transComplete();
        return $db->transStatus() !== false;
    }

    public function getCategories(): array
    {
        return $this->categoryModel->orderBy('name', 'ASC')->findAll();
    }

    public function getBatchesByDrugId(int $drugId): array
    {
        return $this->batchModel
            ->where('drug_id', $drugId)
            ->where('quantity >', 0)
            ->orderBy('expiry_date', 'ASC')
            ->findAll();
    }
}
