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
        User::factory()->count(7)->sequence(
            [
                'role_id' => 2,
                'login' => 'manager',
                'password' => '$2y$10$MmtjAFh2/PXiIrFdGpWuiee1QMEq.TQPgrWUw7mT8TEN6he5XpINK', // 12345
                'name' => 'Федор'
            ],
            [
                'manager_id' => 1,
                'role_id' => 1,
                'login' => 'test-1',
                'password' => '$2y$10$MmtjAFh2/PXiIrFdGpWuiee1QMEq.TQPgrWUw7mT8TEN6he5XpINK', // 12345
                'name' => 'Тест 1',
                'bitrix_link' => 'https://www.bitrix24.ru/',
            ],
            [
                'manager_id' => 1,
                'role_id' => 1,
                'login' => 'test-2',
                'password' => '$2y$10$MmtjAFh2/PXiIrFdGpWuiee1QMEq.TQPgrWUw7mT8TEN6he5XpINK', // 12345
                'name' => 'Тест 2',
                'bitrix_link' => 'https://www.bitrix24.ru/',
            ],
            [
                'manager_id' => 1,
                'role_id' => 1,
                'login' => 'test-3',
                'password' => '$2y$10$MmtjAFh2/PXiIrFdGpWuiee1QMEq.TQPgrWUw7mT8TEN6he5XpINK', // 12345
                'name' => 'Тест 3',
                'bitrix_link' => 'https://www.bitrix24.ru/',
            ],
            [
                'manager_id' => 1,
                'role_id' => 1,
                'login' => 'test-4',
                'password' => '$2y$10$MmtjAFh2/PXiIrFdGpWuiee1QMEq.TQPgrWUw7mT8TEN6he5XpINK', // 12345
                'name' => 'Тест 4',
                'bitrix_link' => 'https://www.bitrix24.ru/',
                'subscribe_end' => '2023-05-24'
            ],
            [
                'manager_id' => 1,
                'role_id' => 1,
                'login' => 'test-5',
                'password' => '$2y$10$MmtjAFh2/PXiIrFdGpWuiee1QMEq.TQPgrWUw7mT8TEN6he5XpINK', // 12345
                'name' => 'Тест 5',
                'bitrix_link' => 'https://www.bitrix24.ru/',
                'subscribe_end' => '2023-05-24'
            ],
            [
                'manager_id' => 1,
                'role_id' => 1,
                'login' => 'test-6',
                'password' => '$2y$10$MmtjAFh2/PXiIrFdGpWuiee1QMEq.TQPgrWUw7mT8TEN6he5XpINK', // 12345
                'name' => 'Тест 6',
                'bitrix_link' => 'https://www.bitrix24.ru/',
                'subscribe_end' => '2023-05-24'
            ],
        )->create();
    }
}
