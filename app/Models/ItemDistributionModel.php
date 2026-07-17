<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemDistributionModel extends Model
{
    protected $table = 'item_distributions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'item_id',
        'warehouse_id',
        'quantity',
        'distribution_date',
        'destination',
        'notes',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'item_id'           => 'required|integer',
        'warehouse_id'      => 'required|integer',
        'quantity'          => 'required|integer|greater_than[0]',
        'distribution_date' => 'required|valid_date',
        'created_by'        => 'required|integer',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
