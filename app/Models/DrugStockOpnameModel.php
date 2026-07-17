<?php

namespace App\Models;

use CodeIgniter\Model;

class DrugStockOpnameModel extends Model
{
    protected $table = 'drug_stock_opnames';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'drug_id',
        'system_quantity',
        'actual_quantity',
        'difference',
        'opname_date',
        'notes',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'drug_id'         => 'required|integer',
        'system_quantity' => 'required|integer',
        'actual_quantity' => 'required|integer',
        'difference'      => 'required|integer',
        'opname_date'     => 'required|valid_date',
        'created_by'      => 'required|integer',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
