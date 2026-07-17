<?php

namespace App\Models;

use CodeIgniter\Model;

class QueueModel extends Model
{
    protected $table = 'queues';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'queue_number',
        'patient_id',
        'doctor_id',
        'polyclinic_id',
        'visit_date',
        'queue_date',
        'status',
        'priority',
        'called_at',
        'completed_at',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'queue_number'  => 'required|max_length[20]|is_unique[queues.queue_number,id,{id}]',
        'patient_id'    => 'required|integer',
        'doctor_id'     => 'required|integer',
        'polyclinic_id' => 'required|integer',
        'visit_date'    => 'required|valid_date',
        'queue_date'    => 'required|valid_date',
        'status'        => 'required|in_list[waiting,called,in_progress,completed,skipped]',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
