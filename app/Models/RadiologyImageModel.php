<?php

namespace App\Models;

use CodeIgniter\Model;

class RadiologyImageModel extends Model
{
    protected $table = 'radiology_images';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'order_id',
        'file_name',
        'file_path',
        'file_size',
        'description',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'order_id'  => 'required|integer',
        'file_name' => 'required|max_length[255]',
        'file_path' => 'required|max_length[500]',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
