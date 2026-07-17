<?php

namespace App\Models;

use CodeIgniter\Model;

class VisitModel extends Model
{
    protected $table = 'visits';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'visit_number',
        'patient_id',
        'doctor_id',
        'polyclinic_id',
        'visit_type_id',
        'room_id',
        'bed_id',
        'schedule_id',
        'queue_id',
        'visit_date',
        'visit_time',
        'complaint',
        'status',
        'admission_date',
        'discharge_date',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'visit_number'  => 'required|max_length[30]|is_unique[visits.visit_number,id,{id}]',
        'patient_id'    => 'required|integer',
        'doctor_id'     => 'required|integer',
        'polyclinic_id' => 'required|integer',
        'visit_type_id' => 'required|integer',
        'visit_date'    => 'required|valid_date',
        'visit_time'    => 'required',
        'status'        => 'required|in_list[waiting,in_progress,completed,cancelled,no_show]',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
