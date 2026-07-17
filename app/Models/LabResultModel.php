<?php

namespace App\Models;

use CodeIgniter\Model;

class LabResultModel extends Model
{
    protected $table = 'lab_results';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'order_id',
        'result_date',
        'result_by',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'order_id'    => 'required|integer',
        'result_date' => 'required|valid_date',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
