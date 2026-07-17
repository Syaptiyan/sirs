<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemStockModel extends Model
{
    protected $table = 'item_stocks';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'item_id',
        'warehouse_id',
        'quantity',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'item_id'      => 'required|integer',
        'warehouse_id' => 'required|integer',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
