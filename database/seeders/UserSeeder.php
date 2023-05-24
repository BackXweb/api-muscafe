<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'role_id' => 2,
                'login' => 'manager',
                'password' => '$2y$10$MmtjAFh2/PXiIrFdGpWuiee1QMEq.TQPgrWUw7mT8TEN6he5XpINK', // 12345
                'name' => 'Федор'
            ],
            [
                'role_id' => 2,
                'login' => 'natalyia',
                'password' => '$2y$10$MmtjAFh2/PXiIrFdGpWuiee1QMEq.TQPgrWUw7mT8TEN6he5XpINK', // 12345
                'name' => 'Наталья'
            ],
            [
                'role_id' => 2,
                'login' => 'malika',
                'password' => '$2y$10$MmtjAFh2/PXiIrFdGpWuiee1QMEq.TQPgrWUw7mT8TEN6he5XpINK', // 12345
                'name' => 'Малика'
            ],
            [
                'role_id' => 2,
                'login' => 'olga',
                'password' => '$2y$10$MmtjAFh2/PXiIrFdGpWuiee1QMEq.TQPgrWUw7mT8TEN6he5XpINK', // 12345
                'name' => 'Ольга'
            ]
        ]);

        User::factory()->count(100)->create();
    }
}
