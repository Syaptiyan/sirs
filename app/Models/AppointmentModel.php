<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'patient_id',
        'doctor_id',
        'polyclinic_id',
        'appointment_date',
        'appointment_time',
        'status',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'patient_id'       => 'required|integer',
        'doctor_id'        => 'required|integer',
        'polyclinic_id'    => 'required|integer',
        'appointment_date' => 'required|valid_date',
        'appointment_time' => 'required',
        'status'           => 'required|in_list[scheduled,confirmed,completed,cancelled]',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
