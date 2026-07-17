<?php

namespace App\Models;

use CodeIgniter\Model;

class PatientDocumentModel extends Model
{
    protected $table = 'patient_documents';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'patient_id',
        'document_type',
        'file_name',
        'file_path',
        'file_size',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'patient_id' => 'required',
        'document_type' => 'required',
        'file_name' => 'required',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
