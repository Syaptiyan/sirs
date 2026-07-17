<?php

namespace App\Controllers;

use App\Services\ScheduleService;
use App\Models\DoctorModel;
use App\Models\PolyclinicModel;

class ScheduleController extends BaseController
{
    private ScheduleService $scheduleService;
    private DoctorModel $doctorModel;
    private PolyclinicModel $polyclinicModel;

    public function __construct()
    {
        $this->scheduleService = new ScheduleService();
        $this->doctorModel = new DoctorModel();
        $this->polyclinicModel = new PolyclinicModel();
    }

    public function index(): string
    {
        $doctorId = $this->request->getGet('doctor_id') ? (int) $this->request->getGet('doctor_id') : null;
        $polyclinicId = $this->request->getGet('polyclinic_id') ? (int) $this->request->getGet('polyclinic_id') : null;
        $page = (int) $this->request->getGet('page', 1);

        $data = [
            'title'        => 'Jadwal Dokter',
            'schedules'    => $this->scheduleService->getAll($doctorId, $polyclinicId, $page),
            'doctors'      => $this->doctorModel->where('is_active', true)->findAll(),
            'polyclinics'  => $this->polyclinicModel->where('is_active', true)->findAll(),
            'doctorId'     => $doctorId,
            'polyclinicId' => $polyclinicId,
        ];

        return view('pages/schedules/index', $data);
    }

    public function create(): string
    {
        $data = [
            'title'       => 'Tambah Jadwal Dokter',
            'doctors'     => $this->doctorModel->where('is_active', true)->findAll(),
            'polyclinics' => $this->polyclinicModel->where('is_active', true)->findAll(),
            'days'        => [
                0 => 'Minggu',
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
            ],
        ];

        return view('pages/schedules/create', $data);
    }

    public function store()
    {
        $rules = [
            'doctor_id'     => 'required|integer',
            'polyclinic_id' => 'required|integer',
            'day_of_week'   => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[6]',
            'start_time'    => 'required',
            'end_time'      => 'required',
            'quota'         => 'permit_empty|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'doctor_id'     => $this->request->getPost('doctor_id'),
            'polyclinic_id' => $this->request->getPost('polyclinic_id'),
            'day_of_week'   => $this->request->getPost('day_of_week'),
            'start_time'    => $this->request->getPost('start_time'),
            'end_time'      => $this->request->getPost('end_time'),
            'quota'         => $this->request->getPost('quota') ?: 20,
            'is_active'     => true,
        ];

        $schedule = $this->scheduleService->create($data);

        if ($schedule === null) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan jadwal. Kemungkinan terjadi konflik jadwal.');
        }

        return redirect()->to('/schedules')->with('success', 'Jadwal dokter berhasil ditambahkan.');
    }

    public function edit(string $uuid): string
    {
        $schedule = $this->scheduleService->getByUuid($uuid);

        if ($schedule === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Jadwal tidak ditemukan.');
        }

        $data = [
            'title'       => 'Edit Jadwal Dokter',
            'schedule'    => $schedule,
            'doctors'     => $this->doctorModel->where('is_active', true)->findAll(),
            'polyclinics' => $this->polyclinicModel->where('is_active', true)->findAll(),
            'days'        => [
                0 => 'Minggu',
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
            ],
        ];

        return view('pages/schedules/edit', $data);
    }

    public function update(string $uuid)
    {
        $rules = [
            'doctor_id'     => 'required|integer',
            'polyclinic_id' => 'required|integer',
            'day_of_week'   => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[6]',
            'start_time'    => 'required',
            'end_time'      => 'required',
            'quota'         => 'permit_empty|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'doctor_id'     => $this->request->getPost('doctor_id'),
            'polyclinic_id' => $this->request->getPost('polyclinic_id'),
            'day_of_week'   => $this->request->getPost('day_of_week'),
            'start_time'    => $this->request->getPost('start_time'),
            'end_time'      => $this->request->getPost('end_time'),
            'quota'         => $this->request->getPost('quota') ?: 20,
            'is_active'     => $this->request->getPost('is_active') ? true : false,
        ];

        if (!$this->scheduleService->update($uuid, $data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui jadwal. Kemungkinan terjadi konflik jadwal.');
        }

        return redirect()->to('/schedules')->with('success', 'Jadwal dokter berhasil diperbarui.');
    }

    public function delete(string $uuid)
    {
        if (!$this->scheduleService->delete($uuid)) {
            return redirect()->back()->with('error', 'Gagal menghapus jadwal.');
        }

        return redirect()->to('/schedules')->with('success', 'Jadwal dokter berhasil dihapus.');
    }
}
