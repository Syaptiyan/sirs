<?php

namespace App\Services;

use App\Models\QueueModel;

class QueueService
{
    private QueueModel $queueModel;

    public function __construct()
    {
        $this->queueModel = new QueueModel();
    }

    public function generateQueue(int $patientId, int $doctorId, int $polyclinicId, string $date): ?object
    {
        $queueNumber = $this->getNextQueueNumber($polyclinicId, $date);

        $data = [
            'queue_number'  => $queueNumber,
            'patient_id'    => $patientId,
            'doctor_id'     => $doctorId,
            'polyclinic_id' => $polyclinicId,
            'visit_date'    => $date,
            'queue_date'    => $date,
            'status'        => 'waiting',
            'priority'      => 0,
        ];

        $id = $this->queueModel->insert($data);
        if ($id === false) {
            return null;
        }

        return $this->queueModel->find($id);
    }

    public function getTodayQueues(?int $polyclinicId = null, ?string $status = null): array
    {
        $builder = $this->queueModel->builder();
        $builder->join('patients', 'patients.id = queues.patient_id', 'left');
        $builder->join('doctors', 'doctors.id = queues.doctor_id', 'left');
        $builder->join('polyclinics', 'polyclinics.id = queues.polyclinic_id', 'left');
        $builder->select('queues.*, patients.name as patient_name, patients.mrn, doctors.name as doctor_name, polyclinics.name as polyclinic_name');

        $builder->where('queues.queue_date', date('Y-m-d'));

        if ($polyclinicId !== null) {
            $builder->where('queues.polyclinic_id', $polyclinicId);
        }

        if ($status !== null) {
            $builder->where('queues.status', $status);
        }

        return $builder
            ->orderBy('queues.priority', 'DESC')
            ->orderBy('queues.created_at', 'ASC')
            ->get()
            ->getResult();
    }

    public function callQueue(string $uuid): bool
    {
        $queue = $this->queueModel->where('uuid', $uuid)->first();
        if ($queue === null || $queue->status !== 'waiting') {
            return false;
        }

        return $this->queueModel->update($queue->id, [
            'status'    => 'called',
            'called_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function skipQueue(string $uuid): bool
    {
        $queue = $this->queueModel->where('uuid', $uuid)->first();
        if ($queue === null || !in_array($queue->status, ['waiting', 'called'])) {
            return false;
        }

        return $this->queueModel->update($queue->id, [
            'status' => 'skipped',
        ]);
    }

    public function recallQueue(string $uuid): bool
    {
        $queue = $this->queueModel->where('uuid', $uuid)->first();
        if ($queue === null || $queue->status !== 'skipped') {
            return false;
        }

        return $this->queueModel->update($queue->id, [
            'status'    => 'called',
            'called_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function completeQueue(string $uuid): bool
    {
        $queue = $this->queueModel->where('uuid', $uuid)->first();
        if ($queue === null || !in_array($queue->status, ['called', 'in_progress'])) {
            return false;
        }

        return $this->queueModel->update($queue->id, [
            'status'       => 'completed',
            'completed_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getDisplayData(?int $polyclinicId = null): array
    {
        $builder = $this->queueModel->builder();
        $builder->join('patients', 'patients.id = queues.patient_id', 'left');
        $builder->join('doctors', 'doctors.id = queues.doctor_id', 'left');
        $builder->join('polyclinics', 'polyclinics.id = queues.polyclinic_id', 'left');
        $builder->select('queues.*, patients.name as patient_name, doctors.name as doctor_name, polyclinics.name as polyclinic_name');

        $builder->where('queues.queue_date', date('Y-m-d'));
        $builder->whereIn('queues.status', ['called', 'in_progress']);

        if ($polyclinicId !== null) {
            $builder->where('queues.polyclinic_id', $polyclinicId);
        }

        $current = $builder
            ->orderBy('queues.called_at', 'DESC')
            ->get()
            ->getResult();

        $nextBuilder = $this->queueModel->builder();
        $nextBuilder->join('patients', 'patients.id = queues.patient_id', 'left');
        $nextBuilder->join('doctors', 'doctors.id = queues.doctor_id', 'left');
        $nextBuilder->join('polyclinics', 'polyclinics.id = queues.polyclinic_id', 'left');
        $nextBuilder->select('queues.*, patients.name as patient_name, doctors.name as doctor_name, polyclinics.name as polyclinic_name');

        $nextBuilder->where('queues.queue_date', date('Y-m-d'));
        $nextBuilder->where('queues.status', 'waiting');

        if ($polyclinicId !== null) {
            $nextBuilder->where('queues.polyclinic_id', $polyclinicId);
        }

        $next = $nextBuilder
            ->orderBy('queues.priority', 'DESC')
            ->orderBy('queues.created_at', 'ASC')
            ->limit(5)
            ->get()
            ->getResult();

        return [
            'current' => $current,
            'next'    => $next,
        ];
    }

    public function getNextQueueNumber(int $polyclinicId, string $date): string
    {
        $polyclinicModel = new \App\Models\PolyclinicModel();
        $polyclinic = $polyclinicModel->find($polyclinicId);
        $prefix = $polyclinic ? strtoupper(substr($polyclinic->code, 0, 3)) : 'A';

        $lastQueue = $this->queueModel
            ->where('polyclinic_id', $polyclinicId)
            ->where('queue_date', $date)
            ->orderBy('queue_number', 'DESC')
            ->first();

        if ($lastQueue === null) {
            $number = 1;
        } else {
            $lastNumber = (int) substr($lastQueue->queue_number, -3);
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
