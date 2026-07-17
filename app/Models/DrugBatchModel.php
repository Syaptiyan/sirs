<?php

namespace App\Models;

use CodeIgniter\Model;

class DrugBatchModel extends Model
{
    protected $table = 'drug_batches';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'drug_id',
        'batch_number',
        'expiry_date',
        'quantity',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'drug_id'      => 'required|integer',
        'batch_number' => 'required|max_length[50]',
        'expiry_date'  => 'required|valid_date',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
