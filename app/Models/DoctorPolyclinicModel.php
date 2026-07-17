<?php

namespace App\Models;

use CodeIgniter\Model;

class DoctorPolyclinicModel extends Model
{
    protected $table = 'doctor_polyclinics';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'doctor_id',
        'polyclinic_id',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'doctor_id'     => 'required|integer',
        'polyclinic_id' => 'required|integer',
    ];
}