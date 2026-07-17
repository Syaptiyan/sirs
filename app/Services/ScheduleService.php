<?php

namespace App\Services;

use App\Models\DoctorScheduleModel;
use App\Models\ScheduleExceptionModel;

class ScheduleService
{
    private DoctorScheduleModel $scheduleModel;
    private ScheduleExceptionModel $exceptionModel;

    public function __construct()
    {
        $this->scheduleModel = new DoctorScheduleModel();
        $this->exceptionModel = new ScheduleExceptionModel();
    }

    public function getAll(?int $doctorId = null, ?int $polyclinicId = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->scheduleModel->builder();
        $builder->join('doctors', 'doctors.id = doctor_schedules.doctor_id', 'left');
        $builder->join('polyclinics', 'polyclinics.id = doctor_schedules.polyclinic_id', 'left');
        $builder->select('doctor_schedules.*, doctors.name as doctor_name, polyclinics.name as polyclinic_name');

        if ($doctorId !== null) {
            $builder->where('doctor_schedules.doctor_id', $doctorId);
        }

        if ($polyclinicId !== null) {
            $builder->where('doctor_schedules.polyclinic_id', $polyclinicId);
        }

        $total = $builder->countAllResults(false);

        $offset = ($page - 1) * $perPage;
        $schedules = $builder
            ->orderBy('doctor_schedules.day_of_week', 'ASC')
            ->orderBy('doctor_schedules.start_time', 'ASC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();

        return [
            'data'         => $schedules,
            'total'        => $total,
            'current_page' => $page,
            'per_page'     => $perPage,
            'last_page'    => (int) ceil($total / $perPage),
        ];
    }

    public function getByUuid(string $uuid): ?object
    {
        return $this->scheduleModel->where('uuid', $uuid)->first();
    }

    public function create(array $data): ?object
    {
        if ($this->checkConflict($data['doctor_id'], $data['day_of_week'], $data['start_time'], $data['end_time'])) {
            return null;
        }

        $id = $this->scheduleModel->insert($data);
        if ($id === false) {
            return null;
        }

        return $this->scheduleModel->find($id);
    }

    public function update(string $uuid, array $data): bool
    {
        $schedule = $this->getByUuid($uuid);
        if ($schedule === null) {
            return false;
        }

        $doctorId = $data['doctor_id'] ?? $schedule->doctor_id;
        $dayOfWeek = $data['day_of_week'] ?? $schedule->day_of_week;
        $startTime = $data['start_time'] ?? $schedule->start_time;
        $endTime = $data['end_time'] ?? $schedule->end_time;

        if ($this->checkConflict($doctorId, $dayOfWeek, $startTime, $endTime, $schedule->id)) {
            return false;
        }

        return $this->scheduleModel->update($schedule->id, $data);
    }

    public function delete(string $uuid): bool
    {
        $schedule = $this->getByUuid($uuid);
        if ($schedule === null) {
            return false;
        }

        return $this->scheduleModel->delete($schedule->id);
    }

    public function getAvailableSlots(int $doctorId, int $polyclinicId, string $date): array
    {
        $dayOfWeek = date('w', strtotime($date));

        $schedule = $this->scheduleModel
            ->where('doctor_id', $doctorId)
            ->where('polyclinic_id', $polyclinicId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if ($schedule === null) {
            return [];
        }

        $exception = $this->exceptionModel
            ->where('doctor_id', $doctorId)
            ->where('exception_date', $date)
            ->where('is_holiday', true)
            ->first();

        if ($exception !== null) {
            return [];
        }

        $queueModel = new \App\Models\QueueModel();
        $usedSlots = $queueModel
            ->where('doctor_id', $doctorId)
            ->where('polyclinic_id', $polyclinicId)
            ->where('queue_date', $date)
            ->whereIn('status', ['waiting', 'called', 'in_progress'])
            ->countAllResults();

        $available = max(0, $schedule->quota - $usedSlots);

        return [
            'schedule'       => $schedule,
            'total_quota'    => $schedule->quota,
            'used_slots'     => $usedSlots,
            'available_slots' => $available,
            'start_time'     => $schedule->start_time,
            'end_time'       => $schedule->end_time,
        ];
    }

    public function checkConflict(int $doctorId, string $dayOfWeek, string $startTime, string $endTime, ?int $excludeId = null): bool
    {
        $builder = $this->scheduleModel
            ->where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->groupStart()
            ->where('start_time <', $endTime)
            ->where('end_time >', $startTime)
            ->groupEnd();

        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    public function addException(int $doctorId, string $date, bool $isHoliday, ?string $notes = null): bool
    {
        $existing = $this->exceptionModel
            ->where('doctor_id', $doctorId)
            ->where('exception_date', $date)
            ->first();

        if ($existing !== null) {
            return $this->exceptionModel->update($existing->id, [
                'is_holiday' => $isHoliday,
                'notes'      => $notes,
            ]);
        }

        return $this->exceptionModel->insert([
            'doctor_id'      => $doctorId,
            'exception_date' => $date,
            'is_holiday'     => $isHoliday,
            'notes'          => $notes,
        ]) !== false;
    }
}
