<?php

namespace App\Models;

use CodeIgniter\Model;

class LabOrderItemModel extends Model
{
    protected $table = 'lab_order_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'order_id',
        'template_id',
        'parameter_name',
        'result_value',
        'unit',
        'normal_range',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'order_id'       => 'required|integer',
        'parameter_name' => 'required|max_length[200]',
    ];
}
