<?php

namespace App\Models;

use CodeIgniter\Model;

class NurseModel extends Model
{
    protected $table = 'nurses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'user_id',
        'employee_id',
        'name',
        'sip',
        'phone',
        'email',
        'photo',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'employee_id' => 'required|max_length[20]|is_unique[nurses.employee_id,id,{id}]',
        'name'        => 'required|max_length[150]',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
