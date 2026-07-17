<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleExceptionModel extends Model
{
    protected $table = 'schedule_exceptions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'doctor_id',
        'exception_date',
        'is_holiday',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'doctor_id'      => 'required|integer',
        'exception_date' => 'required|valid_date',
    ];
}
