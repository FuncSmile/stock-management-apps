<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = auth()->getProvider();

        $user = new \CodeIgniter\Shield\Entities\User([
            'username' => 'owner',
            'email'    => 'owner@example.com',
            'password' => 'owner123',
        ]);
        
        $users->save($user);

        // Add to owner group
        $user = $users->findById($users->getInsertID());
        $user->addGroup('owner');
        $user->activate();
    }
}
