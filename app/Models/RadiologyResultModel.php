<?php

namespace App\Models;

use CodeIgniter\Model;

class RadiologyResultModel extends Model
{
    protected $table = 'radiology_results';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'order_id',
        'result_text',
        'impression',
        'result_date',
        'result_by',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'order_id'    => 'required|integer',
        'result_text' => 'required',
        'result_date' => 'required|valid_date',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
