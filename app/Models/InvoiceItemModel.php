<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceItemModel extends Model
{
    protected $table = 'invoice_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'invoice_id',
        'item_type',
        'item_id',
        'item_name',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'invoice_id' => 'required|integer',
        'item_type'  => 'required|in_list[consultation,action,drug,lab,radiology,room]',
        'item_id'    => 'required|integer',
        'item_name'  => 'required|max_length[200]',
        'quantity'   => 'required|integer',
        'unit_price' => 'required|decimal',
    ];
}
