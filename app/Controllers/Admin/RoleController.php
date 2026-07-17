<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\Role\RoleService;

class RoleController extends BaseController
{
    private RoleService $roleService;

    public function __construct()
    {
        $this->roleService = new RoleService();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $page = (int) $this->request->getGet('page') ?: 1;
        $perPage = (int) $this->request->getGet('per_page') ?: 20;

        $result = $this->roleService->getAll($search, $page, $perPage);

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data role berhasil diambil',
                'data' => $result['data'],
                'meta' => $result['meta'],
            ]);
        }

        return view('roles/index', [
            'roles' => $result['data'],
            'meta' => $result['meta'],
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create()
    {
        return view('roles/create');
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[50]|is_unique[roles.name]',
            'description' => 'max_length[255]',
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
            'slug' => $this->request->getPost('slug'),
            'description' => $this->request->getPost('description'),
            'is_active' => 1,
        ];

        $role = $this->roleService->create($data);

        if ($role === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan data role',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data role');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data role berhasil disimpan',
                'data' => $role,
            ])->setStatusCode(201);
        }

        return redirect()->to('/roles')->with('success', 'Data role berhasil disimpan');
    }

    public function show(string $uuid)
    {
        $role = $this->roleService->getByUuid($uuid);

        if ($role === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Role tidak ditemukan',
                ])->setStatusCode(404);
            }

            return redirect()->to('/roles')->with('error', 'Role tidak ditemukan');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data role berhasil diambil',
                'data' => $role,
            ]);
        }

        return view('roles/show', [
            'role' => $role,
        ]);
    }

    public function edit(string $uuid)
    {
        $role = $this->roleService->getByUuid($uuid);

        if ($role === null) {
            return redirect()->to('/roles')->with('error', 'Role tidak ditemukan');
        }

        return view('roles/edit', [
            'role' => $role,
        ]);
    }

    public function update(string $uuid)
    {
        $role = $this->roleService->getByUuid($uuid);

        $rules = [
            'name' => 'required|min_length[3]|max_length[50]',
            'description' => 'max_length[255]',
        ];

        if ($role) {
            $rules['name'] = "required|min_length[3]|max_length[50]|is_unique[roles.name,id,{$role->id}]";
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
            'slug' => $this->request->getPost('slug'),
            'description' => $this->request->getPost('description'),
        ];

        $result = $this->roleService->update($uuid, $data);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mengupdate data role',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data role');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data role berhasil diupdate',
            ]);
        }

        return redirect()->to('/roles/' . $uuid)->with('success', 'Data role berhasil diupdate');
    }

    public function delete(string $uuid)
    {
        $result = $this->roleService->delete($uuid);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus data role',
                ])->setStatusCode(500);
            }

            return redirect()->to('/roles')->with('error', 'Gagal menghapus data role');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data role berhasil dihapus',
            ]);
        }

        return redirect()->to('/roles')->with('success', 'Data role berhasil dihapus');
    }

    public function permissions(string $uuid)
    {
        $role = $this->roleService->getByUuid($uuid);

        if ($role === null) {
            return redirect()->to('/roles')->with('error', 'Role tidak ditemukan');
        }

        $permissionsByModule = $this->roleService->getPermissionsByModule();
        $rolePermissionIds = array_map(fn($p) => $p->permission_id, $role->permissions);

        return view('roles/permissions', [
            'role' => $role,
            'permissionsByModule' => $permissionsByModule,
            'rolePermissionIds' => $rolePermissionIds,
        ]);
    }

    public function updatePermissions(string $uuid)
    {
        $role = $this->roleService->getByUuid($uuid);

        if ($role === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Role tidak ditemukan',
                ])->setStatusCode(404);
            }

            return redirect()->to('/roles')->with('error', 'Role tidak ditemukan');
        }

        $permissionIds = $this->request->getPost('permissions') ?? [];

        $result = $this->roleService->updatePermissions($uuid, array_map('intval', $permissionIds));

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mengupdate permission',
                ])->setStatusCode(500);
            }

            return redirect()->back()->with('error', 'Gagal mengupdate permission');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Permission berhasil diupdate',
            ]);
        }

        return redirect()->to('/roles/' . $uuid . '/permissions')->with('success', 'Permission berhasil diupdate');
    }

    private function isApiRequest(): bool
    {
        $accept = $this->request->getHeaderLine('Accept');
        $contentType = $this->request->getHeaderLine('Content-Type');

        return str_contains($accept, 'application/json') || str_contains($contentType, 'application/json');
    }
}
