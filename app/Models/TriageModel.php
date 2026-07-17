<?php

namespace App\Models;

use CodeIgniter\Model;

class TriageModel extends Model
{
    protected $table = 'triages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'visit_id',
        'patient_id',
        'triage_level',
        'chief_complaint',
        'vital_signs',
        'consciousness_level',
        'pain_scale',
        'notes',
        'triaged_by',
        'triaged_at',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'visit_id'           => 'required|integer',
        'patient_id'         => 'required|integer',
        'triage_level'       => 'required|in_list[emergency,urgent,non_urgent]',
        'chief_complaint'    => 'required',
        'consciousness_level' => 'required|in_list[alert,confused,drowsy,unresponsive]',
        'pain_scale'         => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[10]',
        'triaged_by'         => 'required|integer',
        'triaged_at'         => 'required|valid_date',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
