<?php

namespace App\Models;

use CodeIgniter\Model;

class BedModel extends Model
{
    protected $table = 'beds';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'room_id',
        'bed_number',
        'status',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'room_id'    => 'required',
        'bed_number' => 'required|max_length[20]',
        'status'     => 'required|in_list[available,occupied,maintenance]',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
