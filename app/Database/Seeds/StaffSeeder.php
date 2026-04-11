<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $users = auth()->getProvider();

        $staffData = [
            [
                'username' => 'staff1',
                'email'    => 'staff1@example.com',
                'password' => 'staff123',
            ],
            [
                'username' => 'staff2',
                'email'    => 'staff2@example.com',
                'password' => 'staff234',
            ],
        ];

        foreach ($staffData as $data) {
            $user = new User($data);
            $users->save($user);

            // Ambil user yang baru saja disimpan
            $user = $users->findById($users->getInsertID());
            
            // Masukkan ke grup staff
            $user->addGroup('staff');
            $user->activate();
        }

        echo "Berhasil mendaftarkan 2 akun Staff.\n";
    }
}
