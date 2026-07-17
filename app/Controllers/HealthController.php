<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class HealthController extends BaseController
{
    use ResponseTrait;

    public function check()
    {
        $checks = [
            'status' => 'ok',
            'timestamp' => date('c'),
            'version' => '1.0.0',
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
        ];

        $healthy = true;
        foreach ($checks as $key => $value) {
            if (is_array($value) && isset($value['status']) && $value['status'] !== 'ok') {
                $healthy = false;
            }
        }

        return $this->respond($checks, $healthy ? 200 : 503);
    }

    private function checkDatabase(): array
    {
        try {
            $db = \Config\Database::connect();
            $db->query('SELECT 1');
            return ['status' => 'ok'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function checkCache(): array
    {
        try {
            cache()->save('health_check', 'ok', 60);
            $value = cache()->get('health_check');
            return ['status' => $value === 'ok' ? 'ok' : 'error'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
