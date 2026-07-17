<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $allowedFields = [
        'uuid',
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'is_read',
    ];
    protected $validationRules = [
        'user_id' => 'required|is_natural_no_zero',
        'type'    => 'required|max_length[50]',
        'title'   => 'required|max_length[255]',
        'message' => 'required',
    ];
    protected $beforeInsert = ['generateUUID'];

    protected function generateUUID(array $data): array
    {
        $data['data']['uuid'] = bin2hex(random_bytes(16));
        return $data;
    }
}
