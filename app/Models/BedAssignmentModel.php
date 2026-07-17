<?php

namespace App\Models;

use CodeIgniter\Model;

class BedAssignmentModel extends Model
{
    protected $table = 'bed_assignments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'bed_id',
        'patient_id',
        'visit_id',
        'assigned_at',
        'released_at',
        'status',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'bed_id'     => 'required',
        'patient_id' => 'required',
        'assigned_at' => 'required',
        'status'     => 'required|in_list[active,completed,cancelled]',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
