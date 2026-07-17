<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        // Insert admin user
        $userData = [
            'uuid'       => bin2hex(random_bytes(16)),
            'name'       => 'Admin SIRS',
            'email'     => 'admin@sirs.local',
            'password'  => password_hash('password', PASSWORD_BCRYPT),
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $this->db->table('users')->insert($userData);
        $userId = $this->db->insertID();

        // Assign admin role
        $role = $this->db->table('roles')->where('slug', 'admin')->get()->getRowArray();

        if ($role) {
            $this->db->table('user_roles')->insert([
                'user_id'    => $userId,
                'role_id'    => $role['id'],
                'created_at' => $now,
            ]);
        }
    }
}
