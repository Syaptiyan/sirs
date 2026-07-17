<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentReceiptModel extends Model
{
    protected $table = 'payment_receipts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'payment_id',
        'receipt_number',
        'file_path',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'payment_id'     => 'required|integer',
        'receipt_number' => 'required|max_length[30]|is_unique[payment_receipts.receipt_number,id,{id}]',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
