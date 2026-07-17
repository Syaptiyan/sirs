<?php

namespace App\Controllers;

use App\Services\AuditService;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Controller untuk mengelola audit log.
 */
class AuditController extends BaseController
{
    private AuditService $auditService;

    public function __construct()
    {
        $this->auditService = new AuditService();
    }

    /**
     * Tampilkan daftar audit log dengan filter.
     */
    public function index(): string
    {
        $module   = $this->request->getGet('module');
        $action   = $this->request->getGet('action');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo   = $this->request->getGet('date_to');
        $page     = (int) $this->request->getGet('page', 1);

        $data = [
            'title'     => 'Audit Logs',
            'logs'      => $this->auditService->getLogs($module, $action, $dateFrom, $dateTo, $page),
            'module'    => $module,
            'action'    => $action,
            'dateFrom'  => $dateFrom,
            'dateTo'    => $dateTo,
        ];

        return view('pages/audit/index', $data);
    }

    /**
     * Tampilkan detail audit log.
     */
    public function show(int $id): string
    {
        $log = $this->auditService->getLogById($id);

        if ($log === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Audit log tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Audit Log',
            'log'   => $log,
        ];

        return view('pages/audit/show', $data);
    }

    /**
     * Export audit log.
     */
    public function export(): ResponseInterface
    {
        $format = $this->request->getGet('format') ?? 'csv';

        return $this->auditService->exportLogs($format);
    }
}
