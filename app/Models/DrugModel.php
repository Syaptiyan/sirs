<?php

namespace App\Models;

use CodeIgniter\Model;

class DrugModel extends Model
{
    protected $table = 'drugs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'uuid',
        'code',
        'name',
        'generic_name',
        'category_id',
        'form',
        'strength',
        'unit',
        'manufacturer',
        'buy_price',
        'sell_price',
        'min_stock',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'code'   => 'required|max_length[20]|is_unique[drugs.code,id,{id}]',
        'name'   => 'required|max_length[200]',
        'form'   => 'required|max_length[50]|in_list[tablet,kapsul,sirup,injeksi,salep,tetes]',
        'unit'   => 'required|max_length[20]',
    ];

    protected $beforeInsert = ['generateUuid'];

    protected function generateUuid(array $data): array
    {
        $data['data']['uuid'] = service('uuid')->uuid4()->toString();
        return $data;
    }
}
