<?php

namespace App\Models;

use CodeIgniter\Model;

class VitalSignModel extends Model
{
    protected $table = 'vital_signs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'visit_id',
        'patient_id',
        'blood_pressure',
        'heart_rate',
        'temperature',
        'respiratory_rate',
        'spo2',
        'weight',
        'height',
        'recorded_by',
        'recorded_at',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'visit_id'   => 'required|integer',
        'patient_id' => 'required|integer',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
