<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = [
        'group',
        'key',
        'value',
        'type',
        'description',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'key'   => 'required|max_length[100]|is_unique[settings.key,id,{id}]',
        'group' => 'required|max_length[50]',
        'type'  => 'required|max_length[20]|in_list[string,integer,boolean,json]',
    ];
}
