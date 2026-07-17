<?php

namespace App\Models;

use CodeIgniter\Model;

class DrugReturnModel extends Model
{
    protected $table = 'drug_returns';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'drug_id',
        'batch_id',
        'quantity',
        'return_date',
        'reason',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'drug_id'     => 'required|integer',
        'batch_id'    => 'required|integer',
        'quantity'    => 'required|integer|greater_than[0]',
        'return_date' => 'required|valid_date',
        'created_by'  => 'required|integer',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
