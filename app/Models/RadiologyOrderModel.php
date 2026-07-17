<?php

namespace App\Models;

use CodeIgniter\Model;

class RadiologyOrderModel extends Model
{
    protected $table = 'radiology_orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'order_number',
        'visit_id',
        'patient_id',
        'doctor_id',
        'template_id',
        'order_date',
        'status',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'order_number' => 'required|max_length[30]|is_unique[radiology_orders.order_number,id,{id}]',
        'visit_id'     => 'required|integer',
        'patient_id'   => 'required|integer',
        'doctor_id'    => 'required|integer',
        'order_date'   => 'required|valid_date',
        'status'       => 'required|in_list[pending,in_progress,completed,cancelled]',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
