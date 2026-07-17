<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'payment_number',
        'invoice_id',
        'patient_id',
        'payment_method_id',
        'amount',
        'payment_date',
        'reference_number',
        'notes',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'payment_number'    => 'required|max_length[30]|is_unique[payments.payment_number,id,{id}]',
        'invoice_id'        => 'required|integer',
        'patient_id'        => 'required|integer',
        'payment_method_id' => 'required|integer',
        'amount'            => 'required|decimal',
        'payment_date'      => 'required|valid_date',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
