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

        User::insert([
            [
                'role_id' => 1,
                'login' => 'user',
                'manager_id' => 1,
                'bitrix_link' => 'https://www.bitrix24.ru/',
                'password' => '$2y$10$MmtjAFh2/PXiIrFdGpWuiee1QMEq.TQPgrWUw7mT8TEN6he5XpINK', // 12345
                'name' => 'Иванов Иван Иванович',
                'subscribe_end' => null
            ],
            [
                'role_id' => 1,
                'login' => 'user-1',
                'manager_id' => 2,
                'bitrix_link' => 'https://www.bitrix24.ru/',
                'password' => '$2y$10$MmtjAFh2/PXiIrFdGpWuiee1QMEq.TQPgrWUw7mT8TEN6he5XpINK', // 12345
                'name' => 'Петров Петр Петрович',
                'subscribe_end' => '2023-05-29'
            ]
        ]);

        User::factory()->count(50)->create();
    }
}
