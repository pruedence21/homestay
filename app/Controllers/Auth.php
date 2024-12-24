<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
    }

    public function index()
    {
        return redirect()->to(base_url('auth/login'));
    }

    public function login()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            $role = session()->get('role');
            return redirect()->to($role . '/dashboard');
        }
        return view('auth/login');
    }

    public function authenticate()
    {
        try {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $user = $this->userModel->where('username', $username)->first();

            if ($user && password_verify($password, $user['password'])) {
                $sessionData = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'nama' => $user['nama'],
                    'role' => $user['role'],
                    'logged_in' => TRUE
                ];

                session()->set($sessionData);
                return redirect()->to($user['role'] . '/dashboard');
            }

            return redirect()->back()
                ->with('error', 'Username atau password salah');

        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem');
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('auth/login'));
    }
}
