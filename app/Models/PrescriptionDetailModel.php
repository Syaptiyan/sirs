<?php

namespace App\Models;

use CodeIgniter\Model;

class PrescriptionDetailModel extends Model
{
    protected $table = 'prescription_details';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'prescription_id',
        'drug_id',
        'quantity',
        'unit',
        'dosage',
        'frequency',
        'duration',
        'instructions',
        'dispensed_quantity',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'prescription_id' => 'required|integer',
        'drug_id'         => 'required|integer',
        'quantity'        => 'required|decimal',
        'unit'            => 'required|max_length[20]',
        'dosage'          => 'required|max_length[100]',
        'frequency'       => 'required|max_length[100]',
    ];
}
