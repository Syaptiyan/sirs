<?php

namespace App\Models;

use CodeIgniter\Model;

class ActionTypeModel extends Model
{
    protected $table = 'action_types';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'name',
        'code',
        'base_price',
        'description',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'name'       => 'required|max_length[200]',
        'code'       => 'required|max_length[20]|is_unique[action_types.code,id,{id}]',
        'base_price' => 'required|decimal',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
