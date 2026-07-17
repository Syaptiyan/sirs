<?php

namespace App\Models;

use CodeIgniter\Model;

class DoctorModel extends Model
{
    protected $table = 'doctors';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'user_id',
        'employee_id',
        'name',
        'sip',
        'specialization_id',
        'phone',
        'email',
        'photo',
        'bio',
        'consultation_fee',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'employee_id'       => 'required|max_length[20]|is_unique[doctors.employee_id,id,{id}]',
        'name'              => 'required|max_length[150]',
        'sip'               => 'required|max_length[50]|is_unique[doctors.sip,id,{id}]',
        'specialization_id' => 'required',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
