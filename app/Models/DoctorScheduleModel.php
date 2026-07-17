<?php

namespace App\Models;

use CodeIgniter\Model;

class DoctorScheduleModel extends Model
{
    protected $table = 'doctor_schedules';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'doctor_id',
        'polyclinic_id',
        'day_of_week',
        'start_time',
        'end_time',
        'quota',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'doctor_id'     => 'required|integer',
        'polyclinic_id' => 'required|integer',
        'day_of_week'   => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[6]',
        'start_time'    => 'required',
        'end_time'      => 'required',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
