<?php

namespace App\Models;

use CodeIgniter\Model;

class PatientModel extends Model
{
    protected $table = 'patients';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'mrn',
        'name',
        'nik',
        'birth_place',
        'birth_date',
        'gender',
        'blood_type',
        'religion',
        'education',
        'occupation',
        'marital_status',
        'phone',
        'email',
        'address',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'postal_code',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'photo',
        'notes',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'name' => 'required',
        'birth_date' => 'required',
        'gender' => 'required',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
