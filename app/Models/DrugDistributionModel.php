<?php

namespace App\Models;

use CodeIgniter\Model;

class DrugDistributionModel extends Model
{
    protected $table = 'drug_distributions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'drug_id',
        'batch_id',
        'prescription_id',
        'quantity',
        'distribution_date',
        'notes',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'drug_id'           => 'required|integer',
        'batch_id'          => 'required|integer',
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
