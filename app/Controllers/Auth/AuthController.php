<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Services\Auth\AuthService;

class AuthController extends BaseController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function loginForm()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('pages/auth/login');
    }

    public function login()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ];

        $messages = [
            'email' => [
                'required' => 'Email wajib diisi',
                'valid_email' => 'Format email tidak valid',
            ],
            'password' => [
                'required' => 'Password wajib diisi',
                'min_length' => 'Password minimal 8 karakter',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $result = $this->authService->login($email, $password);

        if (!$result) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau password salah');
        }

        $session = session();
        $session->set([
            'logged_in' => true,
            'user_id' => $result['user']->id,
            'user_name' => $result['user']->name,
            'user_email' => $result['user']->email,
            'roles' => array_column($result['roles'], 'slug'),
            'permissions' => array_column($result['permissions'], 'slug'),
            'last_activity' => time(),
        ]);

        return redirect()->to('/dashboard');
    }

    public function registerForm()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('pages/auth/register');
    }

    public function register()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        $messages = [
            'name' => [
                'required' => 'Nama wajib diisi',
                'min_length' => 'Nama minimal 3 karakter',
            ],
            'email' => [
                'required' => 'Email wajib diisi',
                'valid_email' => 'Format email tidak valid',
                'is_unique' => 'Email sudah terdaftar',
            ],
            'password' => [
                'required' => 'Password wajib diisi',
                'min_length' => 'Password minimal 8 karakter',
            ],
            'password_confirm' => [
                'required' => 'Konfirmasi password wajib diisi',
                'matches' => 'Password tidak cocok',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $user = $this->authService->register([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ]);

        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mendaftar. Silakan coba lagi.');
        }

        return redirect()->to('/login')
            ->with('success', 'Registrasi berhasil. Silakan login.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')
            ->with('success', 'Anda telah logout.');
    }

    public function forgotPasswordForm()
    {
        return view('pages/auth/forgot-password');
    }

    public function forgotPassword()
    {
        $rules = [
            'email' => 'required|valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $token = $this->authService->forgotPassword($email);

        if (!$token) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email tidak ditemukan');
        }

        // TODO: Send email with reset link
        // For now, just show success message

        return redirect()->back()
            ->with('success', 'Link reset password telah dikirim ke email Anda');
    }

    public function resetPasswordForm(string $token)
    {
        return view('pages/auth/reset-password', ['token' => $token]);
    }

    public function resetPassword()
    {
        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');

        $result = $this->authService->resetPassword($token, $password);

        if (!$result) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Token tidak valid atau expired');
        }

        return redirect()->to('/login')
            ->with('success', 'Password berhasil direset. Silakan login.');
    }

    public function verifyEmail(string $token)
    {
        $result = $this->authService->verifyEmail($token);

        if (!$result) {
            return redirect()->to('/login')
                ->with('error', 'Token verifikasi tidak valid');
        }

        return redirect()->to('/login')
            ->with('success', 'Email berhasil diverifikasi. Silakan login.');
    }
}
