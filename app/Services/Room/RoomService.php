<?php

namespace App\Services\Room;

use App\Models\RoomModel;
use App\Models\RoomTypeModel;
use App\Models\BedModel;
use App\Models\WardModel;
use App\Models\BedAssignmentModel;

class RoomService
{
    private RoomModel $roomModel;
    private RoomTypeModel $roomTypeModel;
    private BedModel $bedModel;
    private WardModel $wardModel;
    private BedAssignmentModel $bedAssignmentModel;

    public function __construct()
    {
        $this->roomModel = new RoomModel();
        $this->roomTypeModel = new RoomTypeModel();
        $this->bedModel = new BedModel();
        $this->wardModel = new WardModel();
        $this->bedAssignmentModel = new BedAssignmentModel();
    }

    public function getAll(?string $search = null, ?bool $isActive = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->roomModel->builder();

        $builder->join('room_types', 'room_types.id = rooms.room_type_id', 'left');

        if ($search !== null && $search !== '') {
            $builder = $builder->groupStart()
                ->like('rooms.room_number', $search)
                ->orLike('room_types.name', $search)
                ->groupEnd();
        }

        if ($isActive !== null) {
            $builder = $builder->where('rooms.is_active', $isActive);
        }

        $total = $builder->countAllResults(false);

        $offset = ($page - 1) * $perPage;
        $data = $builder->select('rooms.*, room_types.name as room_type_name, room_types.base_price')
            ->orderBy('rooms.room_number', 'ASC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();

        return [
            'data' => $data,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => (int) ceil($total / $perPage),
            ],
        ];
    }

    public function getByUuid(string $uuid): ?object
    {
        return $this->roomModel
            ->select('rooms.*, room_types.name as room_type_name, room_types.base_price')
            ->join('room_types', 'room_types.id = rooms.room_type_id', 'left')
            ->where('rooms.uuid', $uuid)
            ->first();
    }

    public function create(array $data): ?object
    {
        $data['uuid'] = $this->generateUuid();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $id = $this->roomModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->roomModel->find($id);
    }

    public function update(string $uuid, array $data): bool
    {
        $room = $this->roomModel->where('uuid', $uuid)->first();

        if ($room === null) {
            return false;
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->roomModel->update($room->id, $data);
    }

    public function delete(string $uuid): bool
    {
        $room = $this->roomModel->where('uuid', $uuid)->first();

        if ($room === null) {
            return false;
        }

        return $this->roomModel->delete($room->id);
    }

    public function getBeds(string $roomUuid): array
    {
        $room = $this->roomModel->where('uuid', $roomUuid)->first();

        if ($room === null) {
            return [];
        }

        return $this->bedModel
            ->where('room_id', $room->id)
            ->orderBy('bed_number', 'ASC')
            ->findAll();
    }

    public function addBed(string $roomUuid, array $data): ?object
    {
        $room = $this->roomModel->where('uuid', $roomUuid)->first();

        if ($room === null) {
            return null;
        }

        $data['room_id'] = $room->id;
        $data['uuid'] = $this->generateUuid();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $id = $this->bedModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->bedModel->find($id);
    }

    public function updateBed(string $bedUuid, array $data): bool
    {
        $bed = $this->bedModel->where('uuid', $bedUuid)->first();

        if ($bed === null) {
            return false;
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->bedModel->update($bed->id, $data);
    }

    public function deleteBed(string $bedUuid): bool
    {
        $bed = $this->bedModel->where('uuid', $bedUuid)->first();

        if ($bed === null) {
            return false;
        }

        return $this->bedModel->delete($bed->id);
    }

    public function getRoomTypes(): array
    {
        return $this->roomTypeModel->orderBy('name', 'ASC')->findAll();
    }

    public function getWards(?string $search = null, ?bool $isActive = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->wardModel->builder();

        if ($search !== null && $search !== '') {
            $builder = $builder->groupStart()
                ->like('name', $search)
                ->orLike('code', $search)
                ->orLike('building', $search)
                ->groupEnd();
        }

        if ($isActive !== null) {
            $builder = $builder->where('is_active', $isActive);
        }

        $total = $builder->countAllResults(false);

        $offset = ($page - 1) * $perPage;
        $data = $builder->orderBy('name', 'ASC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();

        return [
            'data' => $data,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => (int) ceil($total / $perPage),
            ],
        ];
    }

    public function getWardByUuid(string $uuid): ?object
    {
        return $this->wardModel->where('uuid', $uuid)->first();
    }

    public function createWard(array $data): ?object
    {
        $data['uuid'] = $this->generateUuid();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $id = $this->wardModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->wardModel->find($id);
    }

    public function updateWard(string $uuid, array $data): bool
    {
        $ward = $this->wardModel->where('uuid', $uuid)->first();

        if ($ward === null) {
            return false;
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->wardModel->update($ward->id, $data);
    }

    public function deleteWard(string $uuid): bool
    {
        $ward = $this->wardModel->where('uuid', $uuid)->first();

        if ($ward === null) {
            return false;
        }

        return $this->wardModel->delete($ward->id);
    }

    public function getBedAssignments(?string $roomUuid = null, ?string $status = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->bedAssignmentModel->builder();

        $builder->join('beds', 'beds.id = bed_assignments.bed_id', 'left')
            ->join('rooms', 'rooms.id = beds.room_id', 'left')
            ->join('patients', 'patients.id = bed_assignments.patient_id', 'left');

        if ($roomUuid !== null && $roomUuid !== '') {
            $room = $this->roomModel->where('uuid', $roomUuid)->first();
            if ($room !== null) {
                $builder = $builder->where('rooms.id', $room->id);
            }
        }

        if ($status !== null && $status !== '') {
            $builder = $builder->where('bed_assignments.status', $status);
        }

        $total = $builder->countAllResults(false);

        $offset = ($page - 1) * $perPage;
        $data = $builder->select('bed_assignments.*, beds.bed_number, rooms.room_number, patients.name as patient_name')
            ->orderBy('bed_assignments.assigned_at', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();

        return [
            'data' => $data,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => (int) ceil($total / $perPage),
            ],
        ];
    }

    public function assignBed(array $data): ?object
    {
        $bed = $this->bedModel->find($data['bed_id']);

        if ($bed === null || $bed->status !== 'available') {
            return null;
        }

        $data['uuid'] = $this->generateUuid();
        $data['assigned_at'] = date('Y-m-d H:i:s');
        $data['status'] = 'active';
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $id = $this->bedAssignmentModel->insert($data);

        if ($id === false) {
            return null;
        }

        $this->bedModel->update($bed->id, ['status' => 'occupied']);

        $room = $this->roomModel->find($bed->room_id);
        if ($room !== null) {
            $this->roomModel->update($room->id, [
                'current_occupancy' => $room->current_occupancy + 1,
            ]);
        }

        return $this->bedAssignmentModel->find($id);
    }

    public function releaseBed(string $assignmentUuid): bool
    {
        $assignment = $this->bedAssignmentModel->where('uuid', $assignmentUuid)->first();

        if ($assignment === null || $assignment->status !== 'active') {
            return false;
        }

        $this->bedAssignmentModel->update($assignment->id, [
            'status' => 'completed',
            'released_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->bedModel->update($assignment->bed_id, ['status' => 'available']);

        $bed = $this->bedModel->find($assignment->bed_id);
        if ($bed !== null) {
            $room = $this->roomModel->find($bed->room_id);
            if ($room !== null && $room->current_occupancy > 0) {
                $this->roomModel->update($room->id, [
                    'current_occupancy' => $room->current_occupancy - 1,
                ]);
            }
        }

        return true;
    }

    public function getAvailability(string $roomUuid): ?array
    {
        $room = $this->roomModel
            ->select('rooms.*, room_types.name as room_type_name, room_types.base_price')
            ->join('room_types', 'room_types.id = rooms.room_type_id', 'left')
            ->where('rooms.uuid', $roomUuid)
            ->first();

        if ($room === null) {
            return null;
        }

        $beds = $this->bedModel
            ->where('room_id', $room->id)
            ->orderBy('bed_number', 'ASC')
            ->findAll();

        $totalBeds = count($beds);
        $availableBeds = 0;
        $occupiedBeds = 0;
        $maintenanceBeds = 0;
        $bedDetails = [];

        foreach ($beds as $bed) {
            if ($bed->status === 'available') {
                $availableBeds++;
            } elseif ($bed->status === 'occupied') {
                $occupiedBeds++;
            } elseif ($bed->status === 'maintenance') {
                $maintenanceBeds++;
            }

            $bedDetails[] = [
                'id'         => $bed->id,
                'uuid'       => $bed->uuid,
                'bed_number' => $bed->bed_number,
                'status'     => $bed->status,
            ];
        }

        return [
            'room' => [
                'id'                => $room->id,
                'uuid'              => $room->uuid,
                'room_number'       => $room->room_number,
                'room_type_name'    => $room->room_type_name,
                'floor'             => $room->floor,
                'capacity'          => $room->capacity,
                'current_occupancy' => $room->current_occupancy,
                'is_active'         => $room->is_active,
                'base_price'        => $room->base_price,
            ],
            'summary' => [
                'total_beds'       => $totalBeds,
                'available_beds'   => $availableBeds,
                'occupied_beds'    => $occupiedBeds,
                'maintenance_beds' => $maintenanceBeds,
                'occupancy_rate'   => $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100, 1) : 0,
            ],
            'beds' => $bedDetails,
        ];
    }

    public function getStats(): array
    {
        $totalRooms = $this->roomModel->countAll();
        $activeRooms = $this->roomModel->where('is_active', true)->countAllResults();
        $totalBeds = $this->bedModel->countAll();
        $availableBeds = $this->bedModel->where('status', 'available')->countAllResults();
        $occupiedBeds = $this->bedModel->where('status', 'occupied')->countAllResults();
        $maintenanceBeds = $this->bedModel->where('status', 'maintenance')->countAllResults();
        $activeAssignments = $this->bedAssignmentModel->where('status', 'active')->countAllResults();

        return [
            'total_rooms' => $totalRooms,
            'active_rooms' => $activeRooms,
            'total_beds' => $totalBeds,
            'available_beds' => $availableBeds,
            'occupied_beds' => $occupiedBeds,
            'maintenance_beds' => $maintenanceBeds,
            'active_assignments' => $activeAssignments,
            'occupancy_rate' => $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100, 1) : 0,
        ];
    }

    private function generateUuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
