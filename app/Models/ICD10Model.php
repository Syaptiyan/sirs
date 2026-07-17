<?php

namespace App\Models;

use CodeIgniter\Model;

class ICD10Model extends Model
{
    protected $table = 'icd10_codes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'code',
        'description',
        'category',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'code'        => 'required|max_length[20]|is_unique[icd10_codes.code,id,{id}]',
        'description' => 'required',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
