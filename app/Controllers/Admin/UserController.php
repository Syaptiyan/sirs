<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\User\UserService;
use App\Models\RoleModel;

class UserController extends BaseController
{
    private UserService $userService;
    private RoleModel $roleModel;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->roleModel = new RoleModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $roleId = $this->request->getGet('role_id');
        $isActive = $this->request->getGet('is_active');
        $page = (int) $this->request->getGet('page') ?: 1;
        $perPage = (int) $this->request->getGet('per_page') ?: 20;

        $activeFilter = null;
        if ($isActive !== null && $isActive !== '') {
            $activeFilter = $isActive === '1';
        }

        $result = $this->userService->getAll($search, $roleId ? (int) $roleId : null, $activeFilter, $page, $perPage);
        $roles = $this->roleModel->orderBy('name', 'ASC')->findAll();

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data user berhasil diambil',
                'data' => $result['data'],
                'meta' => $result['meta'],
            ]);
        }

        return view('users/index', [
            'users' => $result['data'],
            'meta' => $result['meta'],
            'roles' => $roles,
            'filters' => [
                'search' => $search,
                'role_id' => $roleId,
                'is_active' => $isActive,
            ],
        ]);
    }

    public function create()
    {
        $roles = $this->roleModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();

        return view('users/create', [
            'roles' => $roles,
        ]);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
            'phone' => 'max_length[20]',
        ];

        if (!$this->validate($rules)) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $this->validator->getErrors(),
                ])->setStatusCode(422);
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'phone' => $this->request->getPost('phone'),
            'is_active' => 1,
        ];

        $user = $this->userService->create($data);

        if ($user === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan data user',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data user');
        }

        $roleId = $this->request->getPost('role_id');
        if ($roleId) {
            $this->userService->assignRole($user->uuid, (int) $roleId);
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data user berhasil disimpan',
                'data' => $user,
            ])->setStatusCode(201);
        }

        return redirect()->to('/users')->with('success', 'Data user berhasil disimpan');
    }

    public function show(string $uuid)
    {
        $user = $this->userService->getByUuid($uuid);

        if ($user === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User tidak ditemukan',
                ])->setStatusCode(404);
            }

            return redirect()->to('/users')->with('error', 'User tidak ditemukan');
        }

        $page = (int) $this->request->getGet('page') ?: 1;
        $perPage = (int) $this->request->getGet('per_page') ?: 20;
        $activity = $this->userService->getActivity($uuid, $page, $perPage);

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data user berhasil diambil',
                'data' => $user,
                'activity' => $activity,
            ]);
        }

        return view('users/show', [
            'user' => $user,
            'activity' => $activity['data'],
            'activityMeta' => $activity['meta'],
        ]);
    }

    public function edit(string $uuid)
    {
        $user = $this->userService->getByUuid($uuid);

        if ($user === null) {
            return redirect()->to('/users')->with('error', 'User tidak ditemukan');
        }

        $roles = $this->roleModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
        $userRoleIds = array_map(fn($r) => $r->role_id, $user->roles);

        return view('users/edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoleIds' => $userRoleIds,
        ]);
    }

    public function update(string $uuid)
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => "required|valid_email|is_unique[users.email,id,{$uuid}]",
            'phone' => 'max_length[20]',
        ];

        $user = $this->userService->getByUuid($uuid);
        if ($user) {
            $rules['email'] = "required|valid_email|is_unique[users.email,id,{$user->id}]";
        }

        if (!$this->validate($rules)) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $this->validator->getErrors(),
                ])->setStatusCode(422);
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
        ];

        $password = $this->request->getPost('password');
        if ($password !== null && $password !== '') {
            $data['password'] = $password;
        }

        $result = $this->userService->update($uuid, $data);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mengupdate data user',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data user');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data user berhasil diupdate',
            ]);
        }

        return redirect()->to('/users/' . $uuid)->with('success', 'Data user berhasil diupdate');
    }

    public function delete(string $uuid)
    {
        $result = $this->userService->delete($uuid);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus data user',
                ])->setStatusCode(500);
            }

            return redirect()->to('/users')->with('error', 'Gagal menghapus data user');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data user berhasil dihapus',
            ]);
        }

        return redirect()->to('/users')->with('success', 'Data user berhasil dihapus');
    }

    public function activate(string $uuid)
    {
        $result = $this->userService->activate($uuid);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mengaktifkan user',
                ])->setStatusCode(500);
            }

            return redirect()->back()->with('error', 'Gagal mengaktifkan user');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User berhasil diaktifkan',
            ]);
        }

        return redirect()->back()->with('success', 'User berhasil diaktifkan');
    }

    public function deactivate(string $uuid)
    {
        $result = $this->userService->deactivate($uuid);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menonaktifkan user',
                ])->setStatusCode(500);
            }

            return redirect()->back()->with('error', 'Gagal menonaktifkan user');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User berhasil dinonaktifkan',
            ]);
        }

        return redirect()->back()->with('success', 'User berhasil dinonaktifkan');
    }

    public function assignRole(string $uuid)
    {
        $rules = [
            'role_id' => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $this->validator->getErrors(),
                ])->setStatusCode(422);
            }

            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $roleId = (int) $this->request->getPost('role_id');
        $result = $this->userService->assignRole($uuid, $roleId);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal assign role',
                ])->setStatusCode(500);
            }

            return redirect()->back()->with('error', 'Gagal assign role');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Role berhasil diassign',
            ]);
        }

        return redirect()->back()->with('success', 'Role berhasil diassign');
    }

    public function activity(string $uuid)
    {
        $user = $this->userService->getByUuid($uuid);

        if ($user === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User tidak ditemukan',
                ])->setStatusCode(404);
            }

            return redirect()->to('/users')->with('error', 'User tidak ditemukan');
        }

        $page = (int) $this->request->getGet('page') ?: 1;
        $perPage = (int) $this->request->getGet('per_page') ?: 20;

        $result = $this->userService->getActivity($uuid, $page, $perPage);

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Riwayat aktivitas berhasil diambil',
                'data' => $result['data'],
                'meta' => $result['meta'],
            ]);
        }

        return view('users/activity', [
            'user' => $user,
            'activities' => $result['data'],
            'meta' => $result['meta'],
        ]);
    }

    public function export()
    {
        $format = $this->request->getGet('format') ?? 'csv';
        $csv = $this->userService->export($format);

        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="users_' . date('YmdHis') . '.csv"')
            ->setBody($csv);
    }

    private function isApiRequest(): bool
    {
        $accept = $this->request->getHeaderLine('Accept');
        $contentType = $this->request->getHeaderLine('Content-Type');

        return str_contains($accept, 'application/json') || str_contains($contentType, 'application/json');
    }
}
