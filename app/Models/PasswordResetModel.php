<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetModel extends Model
{
    protected $table = 'password_resets';
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
        'email',
        'token',
        'ip_address',
        'user_agent',
        'expires_at',
    ];
    protected $validationRules = [
        'email'      => 'required|valid_email',
        'token'      => 'required',
        'expires_at' => 'required|valid_date',
    ];
    protected $beforeInsert = ['generateUUID'];

    protected function generateUUID(array $data): array
    {
        $data['data']['uuid'] = bin2hex(random_bytes(16));
        return $data;
    }
}
