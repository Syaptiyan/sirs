<?php

namespace App\Models;

use CodeIgniter\Model;

class PrescriptionModel extends Model
{
    protected $table = 'prescriptions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'prescription_number',
        'visit_id',
        'patient_id',
        'doctor_id',
        'prescription_date',
        'status',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'prescription_number' => 'required|max_length[30]|is_unique[prescriptions.prescription_number,id,{id}]',
        'visit_id'            => 'required|integer',
        'patient_id'          => 'required|integer',
        'doctor_id'           => 'required|integer',
        'prescription_date'   => 'required|valid_date',
        'status'              => 'required|in_list[pending,dispensed,partial,cancelled]',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
