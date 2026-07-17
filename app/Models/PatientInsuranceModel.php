<?php

namespace App\Models;

use CodeIgniter\Model;

class PatientInsuranceModel extends Model
{
    protected $table = 'patient_insurance';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'patient_id',
        'insurance_name',
        'policy_number',
        'expiry_date',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'patient_id' => 'required',
        'insurance_name' => 'required',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
