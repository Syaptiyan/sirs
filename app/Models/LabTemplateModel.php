<?php

namespace App\Models;

use CodeIgniter\Model;

class LabTemplateModel extends Model
{
    protected $table = 'lab_templates';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'name',
        'category',
        'items',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'name'     => 'required|max_length[200]',
        'category' => 'required|max_length[100]',
        'items'    => 'required',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }

    protected $casts = [
        'items'     => 'json',
        'is_active' => 'boolean',
    ];
}
