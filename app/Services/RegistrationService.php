<?php

namespace App\Services;

use App\Models\VisitModel;
use App\Models\VisitTypeModel;
use App\Models\QueueModel;
use App\Models\BedModel;
use App\Models\RoomModel;

class RegistrationService
{
    private VisitModel $visitModel;
    private VisitTypeModel $visitTypeModel;
    private QueueModel $queueModel;
    private BedModel $bedModel;
    private RoomModel $roomModel;

    public function __construct()
    {
        $this->visitModel = new VisitModel();
        $this->visitTypeModel = new VisitTypeModel();
        $this->queueModel = new QueueModel();
        $this->bedModel = new BedModel();
        $this->roomModel = new RoomModel();
    }

    public function registerOutpatient(int $patientId, int $doctorId, int $polyclinicId, ?string $complaint = null): ?object
    {
        $visitType = $this->visitTypeModel->where('code', 'RJ')->first();
        if ($visitType === null) {
            return null;
        }

        $visitNumber = $this->generateVisitNumber();
        $now = date('Y-m-d H:i:s');

        $data = [
            'visit_number'  => $visitNumber,
            'patient_id'    => $patientId,
            'doctor_id'     => $doctorId,
            'polyclinic_id' => $polyclinicId,
            'visit_type_id' => $visitType->id,
            'visit_date'    => date('Y-m-d'),
            'visit_time'    => date('H:i:s'),
            'complaint'     => $complaint,
            'status'        => 'waiting',
        ];

        $id = $this->visitModel->insert($data);
        if ($id === false) {
            return null;
        }

        return $this->visitModel->find($id);
    }

    public function registerInpatient(int $patientId, int $doctorId, int $polyclinicId, int $roomId, ?int $bedId = null, ?string $complaint = null): ?object
    {
        $visitType = $this->visitTypeModel->where('code', 'RI')->first();
        if ($visitType === null) {
            return null;
        }

        if ($bedId !== null) {
            $bed = $this->bedModel->find($bedId);
            if ($bed === null || $bed->status !== 'available') {
                return null;
            }
        }

        $visitNumber = $this->generateVisitNumber();
        $now = date('Y-m-d H:i:s');

        $data = [
            'visit_number'   => $visitNumber,
            'patient_id'     => $patientId,
            'doctor_id'      => $doctorId,
            'polyclinic_id'  => $polyclinicId,
            'visit_type_id'  => $visitType->id,
            'room_id'        => $roomId,
            'bed_id'         => $bedId,
            'visit_date'     => date('Y-m-d'),
            'visit_time'     => date('H:i:s'),
            'complaint'      => $complaint,
            'status'         => 'in_progress',
            'admission_date' => $now,
        ];

        $id = $this->visitModel->insert($data);
        if ($id === false) {
            return null;
        }

        if ($bedId !== null) {
            $this->bedModel->update($bedId, ['status' => 'occupied']);
        }

        $room = $this->roomModel->find($roomId);
        if ($room !== null) {
            $this->roomModel->update($roomId, [
                'current_occupancy' => $room->current_occupancy + 1,
            ]);
        }

        return $this->visitModel->find($id);
    }

    public function registerEmergency(int $patientId, ?int $doctorId = null, ?string $complaint = null): ?object
    {
        $visitType = $this->visitTypeModel->where('code', 'IGD')->first();
        if ($visitType === null) {
            return null;
        }

        $visitNumber = $this->generateVisitNumber();
        $now = date('Y-m-d H:i:s');

        $data = [
            'visit_number'   => $visitNumber,
            'patient_id'     => $patientId,
            'doctor_id'      => $doctorId ?? 0,
            'polyclinic_id'  => 0,
            'visit_type_id'  => $visitType->id,
            'visit_date'     => date('Y-m-d'),
            'visit_time'     => date('H:i:s'),
            'complaint'      => $complaint,
            'status'         => 'in_progress',
            'admission_date' => $now,
        ];

        $id = $this->visitModel->insert($data);
        if ($id === false) {
            return null;
        }

        return $this->visitModel->find($id);
    }

    public function getVisits(?string $status = null, ?string $dateFrom = null, ?string $dateTo = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->visitModel->builder();
        $builder->join('patients', 'patients.id = visits.patient_id', 'left');
        $builder->join('doctors', 'doctors.id = visits.doctor_id', 'left');
        $builder->join('polyclinics', 'polyclinics.id = visits.polyclinic_id', 'left');
        $builder->join('visit_types', 'visit_types.id = visits.visit_type_id', 'left');
        $builder->select('visits.*, patients.name as patient_name, patients.mrn, doctors.name as doctor_name, polyclinics.name as polyclinic_name, visit_types.name as visit_type_name, visit_types.code as visit_type_code');

        if ($status !== null) {
            $builder->where('visits.status', $status);
        }

        if ($dateFrom !== null) {
            $builder->where('visits.visit_date >=', $dateFrom);
        }

        if ($dateTo !== null) {
            $builder->where('visits.visit_date <=', $dateTo);
        }

        $total = $builder->countAllResults(false);

        $offset = ($page - 1) * $perPage;
        $visits = $builder
            ->orderBy('visits.created_at', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();

        return [
            'visits'     => $visits,
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => (int) ceil($total / $perPage),
        ];
    }

    public function getVisitByUuid(string $uuid): ?object
    {
        $builder = $this->visitModel->builder();
        $builder->join('patients', 'patients.id = visits.patient_id', 'left');
        $builder->join('doctors', 'doctors.id = visits.doctor_id', 'left');
        $builder->join('polyclinics', 'polyclinics.id = visits.polyclinic_id', 'left');
        $builder->join('visit_types', 'visit_types.id = visits.visit_type_id', 'left');
        $builder->join('rooms', 'rooms.id = visits.room_id', 'left');
        $builder->join('beds', 'beds.id = visits.bed_id', 'left');
        $builder->select('visits.*, patients.name as patient_name, patients.mrn, patients.nik as patient_nik, patients.phone as patient_phone, patients.gender as patient_gender, patients.birth_date as patient_birth_date, doctors.name as doctor_name, polyclinics.name as polyclinic_name, visit_types.name as visit_type_name, visit_types.code as visit_type_code, rooms.room_number, beds.bed_number');

        $builder->where('visits.uuid', $uuid);

        return $builder->get()->getRow() ?: null;
    }

    public function updateStatus(string $uuid, string $status): bool
    {
        $visit = $this->visitModel->where('uuid', $uuid)->first();
        if ($visit === null) {
            return false;
        }

        $validTransitions = [
            'waiting'     => ['in_progress', 'cancelled', 'no_show'],
            'in_progress' => ['completed', 'cancelled'],
            'completed'   => [],
            'cancelled'   => [],
            'no_show'     => [],
        ];

        if (!isset($validTransitions[$visit->status]) || !in_array($status, $validTransitions[$visit->status])) {
            return false;
        }

        $updateData = ['status' => $status];

        if ($status === 'completed') {
            $updateData['discharge_date'] = date('Y-m-d H:i:s');

            if ($visit->bed_id !== null) {
                $this->bedModel->update($visit->bed_id, ['status' => 'available']);
                $room = $this->roomModel->find($visit->room_id);
                if ($room !== null && $room->current_occupancy > 0) {
                    $this->roomModel->update($visit->room_id, [
                        'current_occupancy' => $room->current_occupancy - 1,
                    ]);
                }
            }
        }

        return $this->visitModel->update($visit->id, $updateData);
    }

    public function discharge(string $uuid): bool
    {
        return $this->updateStatus($uuid, 'completed');
    }

    public function generateVisitNumber(): string
    {
        $prefix = 'VIS-' . date('Ymd') . '-';
        $lastVisit = $this->visitModel
            ->like('visit_number', $prefix, 'after')
            ->orderBy('visit_number', 'DESC')
            ->first();

        if ($lastVisit === null) {
            $number = 1;
        } else {
            $lastNumber = (int) substr($lastVisit->visit_number, -4);
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
