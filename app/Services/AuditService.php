<?php

namespace App\Services;

use App\Models\AuditLogModel;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Service untuk mengelola audit log.
 */
class AuditService
{
    private AuditLogModel $auditLogModel;

    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
    }

    /**
     * Simpan audit log baru.
     */
    public function log(
        string $action,
        string $module,
        ?int $recordId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): bool {
        $data = [
            'user_id'     => session()->get('user_id'),
            'action'      => $action,
            'model'       => $module,
            'model_id'    => $recordId,
            'old_values'  => $oldValues ? json_encode($oldValues) : null,
            'new_values'  => $newValues ? json_encode($newValues) : null,
            'ip_address'  => service('request')->getIPAddress(),
            'user_agent'  => service('request')->getUserAgent()->getAgentString(),
            'description' => $description,
        ];

        return $this->auditLogModel->insert($data) !== false;
    }

    /**
     * Ambil daftar audit log dengan filter dan pagination.
     */
    public function getLogs(
        ?string $module = null,
        ?string $action = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        int $page = 1,
        int $perPage = 20
    ): array {
        $builder = $this->auditLogModel->builder();

        if ($module !== null) {
            $builder->where('model', $module);
        }

        if ($action !== null) {
            $builder->where('action', $action);
        }

        if ($dateFrom !== null) {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo !== null) {
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }

        $total = $builder->countAllResults(false);

        $offset = ($page - 1) * $perPage;
        $logs = $builder
            ->orderBy('created_at', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();

        return [
            'data'         => $logs,
            'total'        => $total,
            'current_page' => $page,
            'per_page'     => $perPage,
            'last_page'    => (int) ceil($total / $perPage),
        ];
    }

    /**
     * Ambil detail audit log berdasarkan ID.
     */
    public function getLogById(int $id): ?object
    {
        return $this->auditLogModel->find($id);
    }

    /**
     * Export audit log ke format CSV.
     */
    public function exportLogs(?string $format = 'csv'): ResponseInterface
    {
        $logs = $this->auditLogModel
            ->orderBy('created_at', 'DESC')
            ->findAll();

        if ($format === 'csv') {
            return $this->exportCsv($logs);
        }

        return $this->exportCsv($logs);
    }

    /**
     * Generate file CSV dari data log.
     */
    private function exportCsv(array $logs): ResponseInterface
    {
        $response = service('response');
        $filename = 'audit_logs_' . date('YmdHis') . '.csv';

        $header = [
            'ID',
            'User ID',
            'Action',
            'Module',
            'Record ID',
            'Old Values',
            'New Values',
            'IP Address',
            'Description',
            'Created At',
        ];

        $csvData = fopen('php://temp', 'r+');
        fputcsv($csvData, $header);

        foreach ($logs as $log) {
            fputcsv($csvData, [
                $log->id,
                $log->user_id,
                $log->action,
                $log->model,
                $log->model_id,
                $log->old_values,
                $log->new_values,
                $log->ip_address,
                $log->description,
                $log->created_at,
            ]);
        }

        rewind($csvData);
        $content = stream_get_contents($csvData);
        fclose($csvData);

        return $response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($content);
    }
}
